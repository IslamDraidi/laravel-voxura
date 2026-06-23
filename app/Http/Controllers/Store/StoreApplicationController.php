<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class StoreApplicationController extends Controller
{
    private function checkExistingStore()
    {
        $store = auth()->user()->store;
        if ($store) {
            if ($store->onboarding_status === 'live' || $store->status === 'approved') {
                return redirect()->route('store.dashboard')
                    ->with('info', __('general.already_have_store'));
            }
            if (in_array($store->onboarding_status, ['paid', 'ready'])) {
                return redirect()->route('store.dashboard');
            }
        }
        return null;
    }

    // ── STEP 1: Application form ──────────────────────────────────────────────

    public function applicationForm()
    {
        if ($redirect = $this->checkExistingStore()) {
            return $redirect;
        }

        return view('partner.apply');
    }

    public function submitApplication(Request $request)
    {
        $validated = $request->validate([
            'business_name'  => 'required|string|max:150',
            'business_id'    => 'nullable|string|max:100',
            'business_phone' => 'required|string|max:30',
            'store_name'     => 'required|string|max:150',
            'description'    => 'nullable|string|max:1000',
            'category_tags'  => 'nullable|array',
            'social_links'   => 'nullable|array',
            'logo'           => 'nullable|image|max:2048',
            'banner'         => 'nullable|image|max:5120',
        ]);

        session()->put('partner_application', [
            'business_name'  => $validated['business_name'],
            'business_id'    => $validated['business_id'] ?? null,
            'business_phone' => $validated['business_phone'],
            'store_name'     => $validated['store_name'],
            'description'    => $validated['description'] ?? null,
            'category_tags'  => $validated['category_tags'] ?? [],
            'social_links'   => $validated['social_links'] ?? [],
        ]);

        if ($request->hasFile('logo')) {
            $logo     = $request->file('logo');
            $filename = 'temp-logo-' . auth()->id() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('images/stores/temp'), $filename);
            session()->put('partner_logo_temp', $filename);
        }

        if ($request->hasFile('banner')) {
            $banner   = $request->file('banner');
            $filename = 'temp-banner-' . auth()->id() . '.' . $banner->getClientOriginalExtension();
            $banner->move(public_path('images/stores/temp'), $filename);
            session()->put('partner_banner_temp', $filename);
        }

        return redirect()->route('partner.plan');
    }

    // ── STEP 2: Plan selection ────────────────────────────────────────────────

    public function planSelection()
    {
        if (!session()->has('partner_application')) {
            return redirect()->route('partner.apply')
                ->with('error', __('general.complete_application_first'));
        }

        $plans = config('voxura_plans');
        $cycle = session('partner_cycle', 'monthly');

        return view('partner.plan', compact('plans', 'cycle'));
    }

    public function selectPlan(Request $request)
    {
        $request->validate([
            'plan'  => 'required|in:basic,pro,premium,custom',
            'cycle' => 'required|in:monthly,yearly',
        ]);

        if ($request->plan === 'custom') {
            return redirect()->route('partner.contact')
                ->with('info', __('general.contact_for_custom'));
        }

        session()->put('partner_plan', $request->plan);
        session()->put('partner_cycle', $request->cycle);

        return redirect()->route('partner.payment');
    }

    // ── STEP 3: Payment page ──────────────────────────────────────────────────

    public function paymentPage()
    {
        if (!session()->has('partner_application') || !session()->has('partner_plan')) {
            return redirect()->route('partner.apply');
        }

        $plan     = session('partner_plan');
        $cycle    = session('partner_cycle', 'monthly');
        $planData = config("voxura_plans.{$plan}");

        $price = $cycle === 'yearly'
            ? $planData['yearly_price']
            : $planData['monthly_price'];

        $bankDetails = [
            'bank_name'      => 'Bank of Palestine',
            'account_name'   => 'Voxura Ltd.',
            'account_number' => '1234-5678-9012',
            'iban'           => 'PS92 BKPA 0000 0000 1234 5678 9012',
            'swift'          => 'BKPAPSJE',
            'reference'      => 'VOXURA-' . strtoupper(auth()->id()) . '-' . strtoupper($plan),
        ];

        return view('partner.payment', compact('plan', 'cycle', 'planData', 'price', 'bankDetails'));
    }

    // ── Pay with Tap ──────────────────────────────────────────────────────────

    public function payWithTap(Request $request)
    {
        if (!session()->has('partner_application') || !session()->has('partner_plan')) {
            return redirect()->route('partner.apply');
        }

        $plan     = session('partner_plan');
        $cycle    = session('partner_cycle', 'monthly');
        $planData = config("voxura_plans.{$plan}");

        $price = $cycle === 'yearly'
            ? $planData['yearly_price']
            : $planData['monthly_price'];

        $store = $this->createDraftStore($plan, $cycle, $price);
        session()->put('partner_store_id', $store->id);

        $tapConfig = config('payment.gateways.tap');
        $secretKey = $tapConfig['secret_key'] ?? '';
        $baseUrl   = $tapConfig['base_url'];
        $user      = auth()->user();

        $response = Http::withToken($secretKey)
            ->post("{$baseUrl}/charges", [
                'amount'            => (float) $price,
                'currency'          => 'USD',
                'customer_initiated' => true,
                'threeDSecure'      => true,
                'save_card'         => false,
                'description'       => "Voxura {$planData['name']} Plan — " . ucfirst($cycle),
                'metadata'          => ['store_id' => $store->id, 'plan' => $plan],
                'reference'         => ['order' => 'PARTNER-' . $store->id],
                'receipt'           => ['email' => true, 'sms' => false],
                'customer'          => [
                    'first_name' => $user->name,
                    'email'      => $user->email,
                ],
                'source'    => ['id' => 'src_all'],
                'redirect'  => ['url' => route('partner.payment.callback')],
                'post'      => ['url' => route('partner.payment.callback')],
            ]);

        if ($response->failed()) {
            Log::error('Partner Tap payment failed', [
                'store_id' => $store->id,
                'body'     => $response->json(),
            ]);
            $store->delete();
            session()->forget('partner_store_id');
            return back()->with('error', __('general.payment_initiation_failed'));
        }

        $data = $response->json();
        $approvalUrl = $data['transaction']['url'] ?? $data['url'] ?? null;

        if (!$approvalUrl) {
            $store->delete();
            session()->forget('partner_store_id');
            return back()->with('error', __('general.payment_initiation_failed'));
        }

        return redirect()->away($approvalUrl);
    }

    // ── Pay with bank transfer ────────────────────────────────────────────────

    public function payWithBank(Request $request)
    {
        if (!session()->has('partner_application') || !session()->has('partner_plan')) {
            return redirect()->route('partner.apply');
        }

        $plan     = session('partner_plan');
        $cycle    = session('partner_cycle', 'monthly');
        $planData = config("voxura_plans.{$plan}");

        $price = $cycle === 'yearly'
            ? $planData['yearly_price']
            : $planData['monthly_price'];

        $store = $this->createDraftStore($plan, $cycle, $price);

        $reference = 'BANK-' . strtoupper(Str::random(8));
        $store->update([
            'bank_transfer_pending' => true,
            'payment_method'        => 'bank_transfer',
            'payment_reference'     => $reference,
        ]);

        Mail::raw(
            "New bank transfer pending for store subscription.\n\n" .
            "Store: {$store->name} (ID: {$store->id})\n" .
            "Plan: {$planData['name']} / " . ucfirst($cycle) . "\n" .
            "Amount: \${$price}\n" .
            "Reference: {$reference}\n" .
            "Owner: " . auth()->user()->name . " (" . auth()->user()->email . ")",
            fn ($mail) => $mail->to('hello@voxura.com')
                ->subject("Bank Transfer Pending — {$store->name}")
        );

        $this->clearApplicationSession();

        return redirect()->route('partner.success')
            ->with('payment_method', 'bank_transfer')
            ->with('store_id', $store->id)
            ->with('reference', $reference);
    }

    // ── Tap callback ──────────────────────────────────────────────────────────

    public function tapCallback(Request $request)
    {
        $storeId = session('partner_store_id');
        $store   = Store::find($storeId);

        if (!$store) {
            return redirect()->route('partner.payment')
                ->with('error', __('general.payment_verification_failed'));
        }

        $tapId = $request->get('tap_id') ?? $request->get('id');

        if (!$tapId) {
            return redirect()->route('partner.payment')
                ->with('error', __('general.payment_verification_failed'));
        }

        $tapConfig = config('payment.gateways.tap');
        $response  = Http::withToken($tapConfig['secret_key'] ?? '')
            ->get("{$tapConfig['base_url']}/charges/{$tapId}");

        if ($response->failed()) {
            return redirect()->route('partner.payment')
                ->with('error', __('general.payment_verification_failed'));
        }

        $charge    = $response->json();
        $tapStatus = $charge['status'] ?? '';

        if ($tapStatus === 'CAPTURED') {
            $store->update([
                'onboarding_status'      => 'paid',
                'subscription_active'    => true,
                'subscription_start'     => now(),
                'subscription_expires_at' => $store->billing_cycle === 'yearly'
                    ? now()->addYear()
                    : now()->addMonth(),
                'payment_method'         => 'tap',
                'payment_reference'      => $tapId,
                'last_payment_at'        => now(),
            ]);

            $this->clearApplicationSession();

            return redirect()->route('partner.success')
                ->with('payment_method', 'tap')
                ->with('store_id', $store->id);
        }

        return redirect()->route('partner.payment')
            ->with('error', __('general.payment_not_successful'));
    }

    // ── Success page ──────────────────────────────────────────────────────────

    public function success()
    {
        return view('partner.success');
    }

    // ── Contact form (custom plan) ────────────────────────────────────────────

    public function contactForm()
    {
        return view('partner.contact');
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email',
            'phone'   => 'required|string|max:30',
            'message' => 'nullable|string|max:1000',
        ]);

        Mail::raw(
            "New partner inquiry:\n\n" .
            "Name: {$validated['name']}\n" .
            "Email: {$validated['email']}\n" .
            "Phone: {$validated['phone']}\n" .
            "Message: " . ($validated['message'] ?? 'None'),
            fn ($mail) => $mail->to('hello@voxura.com')
                ->subject('New Custom Plan Inquiry — ' . $validated['name'])
        );

        return back()->with('success', __('general.contact_sent'));
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function createDraftStore(string $plan, string $cycle, float $price): Store
    {
        $appData  = session('partner_application');
        $planData = config("voxura_plans.{$plan}");

        $slug         = Str::slug($appData['store_name']);
        $originalSlug = $slug;
        $counter      = 1;
        while (Store::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        $store = Store::create([
            'name'               => $appData['store_name'],
            'slug'               => $slug,
            'business_name'      => $appData['business_name'],
            'business_id'        => $appData['business_id'],
            'business_phone'     => $appData['business_phone'],
            'description'        => $appData['description'],
            'category_tags'      => $appData['category_tags'],
            'social_links'       => $appData['social_links'],
            'owner_id'           => auth()->id(),
            'status'             => 'pending',
            'onboarding_status'  => 'draft',
            'plan_type'          => $plan,
            'billing_cycle'      => $cycle,
            'monthly_fee'        => $planData['monthly_price'],
            'yearly_fee'         => $planData['yearly_price'],
            'subscription_fee'   => $price,
            'commission_rate'    => $planData['commission_rate'],
            'subscription_active' => false,
        ]);

        $logoTemp = session('partner_logo_temp');
        if ($logoTemp) {
            $ext     = pathinfo($logoTemp, PATHINFO_EXTENSION);
            $newPath = 'images/stores/' . $slug . '-logo.' . $ext;
            @rename(
                public_path('images/stores/temp/' . $logoTemp),
                public_path($newPath)
            );
            $store->update(['logo_path' => $newPath]);
        }

        $bannerTemp = session('partner_banner_temp');
        if ($bannerTemp) {
            $ext     = pathinfo($bannerTemp, PATHINFO_EXTENSION);
            $newPath = 'images/stores/' . $slug . '-banner.' . $ext;
            @rename(
                public_path('images/stores/temp/' . $bannerTemp),
                public_path($newPath)
            );
            $store->update(['banner_path' => $newPath]);
        }

        return $store;
    }

    private function clearApplicationSession(): void
    {
        session()->forget([
            'partner_application',
            'partner_plan',
            'partner_cycle',
            'partner_logo_temp',
            'partner_banner_temp',
            'partner_store_id',
        ]);
    }
}
