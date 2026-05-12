<?php

namespace Tests\Feature\Components;

use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactControllerComponentTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_form_submits_successfully(): void
    {
        Mail::fake();

        $response = $this->post(route('contact.send'), [
            'name'    => 'Islam Draidi',
            'email'   => 'islam@test.com',
            'message' => 'I love Voxura!',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('contact_success');
    }

    public function test_contact_form_saves_message_to_database(): void
    {
        Mail::fake();

        $this->post(route('contact.send'), [
            'name'    => 'Islam Draidi',
            'email'   => 'islam@test.com',
            'message' => 'I love Voxura!',
        ]);

        $this->assertDatabaseHas('contact_messages', [
            'name'    => 'Islam Draidi',
            'email'   => 'islam@test.com',
            'message' => 'I love Voxura!',
        ]);
    }

    public function test_contact_form_validates_required_fields(): void
    {
        $response = $this->post(route('contact.send'), []);

        $response->assertSessionHasErrors(['name', 'email', 'message']);
        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_contact_form_validates_email_format(): void
    {
        $response = $this->post(route('contact.send'), [
            'name'    => 'Islam',
            'email'   => 'not-an-email',
            'message' => 'Test',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_contact_form_rejects_message_over_2000_chars(): void
    {
        $response = $this->post(route('contact.send'), [
            'name'    => 'Islam',
            'email'   => 'islam@test.com',
            'message' => str_repeat('a', 2001),
        ]);

        $response->assertSessionHasErrors(['message']);
        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_contact_message_count_is_zero_before_submission(): void
    {
        $this->assertDatabaseCount('contact_messages', 0);
    }
}
