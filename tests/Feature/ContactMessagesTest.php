<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactMessagesTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(): User
    {
        return User::create([
            'name'     => 'Test User',
            'email'    => 'user-' . uniqid() . '@test.com',
            'password' => bcrypt('password'),
        ]);
    }

    // ── ContactController@send ────────────────────────────────────────────────

    public function test_guest_can_submit_contact_form_and_message_is_saved(): void
    {
        Mail::fake();

        $response = $this->post('/contact', [
            'name'    => 'Jane Doe',
            'email'   => 'jane@example.com',
            'message' => 'Hello, I have a question.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('contact_success');

        $this->assertDatabaseHas('contact_messages', [
            'name'    => 'Jane Doe',
            'email'   => 'jane@example.com',
            'message' => 'Hello, I have a question.',
            'user_id' => null,
        ]);
    }

    public function test_authenticated_user_contact_form_saves_with_user_id(): void
    {
        Mail::fake();

        $user = $this->makeUser();

        $this->actingAs($user)->post('/contact', [
            'name'    => $user->name,
            'email'   => $user->email,
            'message' => 'Logged-in user message.',
        ]);

        $this->assertDatabaseHas('contact_messages', [
            'user_id' => $user->id,
            'message' => 'Logged-in user message.',
        ]);
    }

    public function test_contact_form_requires_name(): void
    {
        $response = $this->post('/contact', [
            'name'    => '',
            'email'   => 'jane@example.com',
            'message' => 'Hello.',
        ]);

        $response->assertSessionHasErrors('name');
        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_contact_form_requires_valid_email(): void
    {
        $response = $this->post('/contact', [
            'name'    => 'Jane',
            'email'   => 'not-an-email',
            'message' => 'Hello.',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_contact_form_requires_message(): void
    {
        $response = $this->post('/contact', [
            'name'    => 'Jane',
            'email'   => 'jane@example.com',
            'message' => '',
        ]);

        $response->assertSessionHasErrors('message');
        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_contact_form_rejects_message_over_2000_chars(): void
    {
        $response = $this->post('/contact', [
            'name'    => 'Jane',
            'email'   => 'jane@example.com',
            'message' => str_repeat('a', 2001),
        ]);

        $response->assertSessionHasErrors('message');
        $this->assertDatabaseCount('contact_messages', 0);
    }

    // ── ProfileController@messages ────────────────────────────────────────────

    public function test_profile_messages_page_requires_auth(): void
    {
        $response = $this->get('/profile/messages');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_their_messages_page(): void
    {
        $user = $this->makeUser();

        $response = $this->actingAs($user)->get('/profile/messages');

        $response->assertOk();
        $response->assertViewIs('profile.messages');
    }

    public function test_messages_page_shows_empty_state_when_no_messages(): void
    {
        $user = $this->makeUser();

        $response = $this->actingAs($user)->get('/profile/messages');

        $response->assertOk();
        $response->assertViewHas('messages');
        $this->assertCount(0, $response->viewData('messages'));
    }

    public function test_messages_page_shows_only_current_users_messages(): void
    {
        $userA = $this->makeUser();
        $userB = $this->makeUser();

        ContactMessage::create([
            'user_id' => $userA->id,
            'name'    => $userA->name,
            'email'   => $userA->email,
            'message' => 'Message from user A.',
        ]);

        ContactMessage::create([
            'user_id' => $userB->id,
            'name'    => $userB->name,
            'email'   => $userB->email,
            'message' => 'Message from user B.',
        ]);

        $response = $this->actingAs($userA)->get('/profile/messages');

        $messages = $response->viewData('messages');
        $this->assertCount(1, $messages);
        $this->assertEquals('Message from user A.', $messages->first()->message);
    }

    public function test_messages_page_does_not_show_guest_messages(): void
    {
        $user = $this->makeUser();

        ContactMessage::create([
            'user_id' => null,
            'name'    => 'Ghost',
            'email'   => 'ghost@example.com',
            'message' => 'Guest message.',
        ]);

        $response = $this->actingAs($user)->get('/profile/messages');

        $this->assertCount(0, $response->viewData('messages'));
    }

    public function test_messages_appear_in_latest_first_order(): void
    {
        $user = $this->makeUser();

        ContactMessage::forceCreate([
            'user_id'    => $user->id,
            'name'       => $user->name,
            'email'      => $user->email,
            'message'    => 'First message.',
            'created_at' => now()->subMinutes(10),
            'updated_at' => now()->subMinutes(10),
        ]);

        ContactMessage::forceCreate([
            'user_id'    => $user->id,
            'name'       => $user->name,
            'email'      => $user->email,
            'message'    => 'Second message.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/profile/messages');

        $messages = $response->viewData('messages');
        $this->assertCount(2, $messages);
        $this->assertEquals('Second message.', $messages->first()->message);
    }

    public function test_messages_page_paginates_after_ten_messages(): void
    {
        $user = $this->makeUser();

        for ($i = 1; $i <= 12; $i++) {
            ContactMessage::create([
                'user_id' => $user->id,
                'name'    => $user->name,
                'email'   => $user->email,
                'message' => "Message number {$i}.",
            ]);
        }

        $response = $this->actingAs($user)->get('/profile/messages');

        $messages = $response->viewData('messages');
        $this->assertCount(10, $messages);
        $this->assertEquals(12, $messages->total());
    }

    public function test_submitting_contact_form_as_auth_user_makes_it_visible_on_messages_page(): void
    {
        Mail::fake();

        $user = $this->makeUser();

        $this->actingAs($user)->post('/contact', [
            'name'    => $user->name,
            'email'   => $user->email,
            'message' => 'Can I return an item?',
        ]);

        $response = $this->actingAs($user)->get('/profile/messages');

        $messages = $response->viewData('messages');
        $this->assertCount(1, $messages);
        $this->assertEquals('Can I return an item?', $messages->first()->message);
    }
}
