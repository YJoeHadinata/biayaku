<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    /**
     * Display available subscription plans
     */
    public function plans()
    {
        $plans = SubscriptionPlan::active()->ordered()->get();
        $currentSubscription = auth()->user()->activeSubscription();

        return view('subscriptions.plans', compact('plans', 'currentSubscription'));
    }

    /**
     * Show subscription status for current user
     */
    public function status()
    {
        $user = auth()->user();
        $subscription = $user->activeSubscription();
        $pendingSubscriptions = $user->subscriptions()->pending()->with('plan')->get();
        $plans = SubscriptionPlan::active()->ordered()->get();

        return view('subscriptions.status', compact('subscription', 'pendingSubscriptions', 'plans'));
    }

    /**
     * Subscribe to a plan
     */
    public function subscribe(Request $request, $planSlug)
    {
        $plan = SubscriptionPlan::where('slug', $planSlug)->active()->firstOrFail();
        $user = auth()->user();

        // Check if user already has an active subscription to this plan
        $existingSubscription = $user->subscriptions()
            ->where('subscription_plan_id', $plan->id)
            ->active()
            ->first();

        if ($existingSubscription) {
            return redirect()->route('subscriptions.status')
                ->with('info', 'Anda sudah berlangganan paket ini.');
        }

        // Cancel any existing active subscriptions
        $user->subscriptions()->active()->update(['status' => 'cancelled']);

        // Create new subscription - pending for paid plans, active for free plans
        $status = $plan->price > 0 ? 'pending' : 'active';
        $subscription = UserSubscription::create([
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
            'status' => $status,
            'current_period_start' => $status === 'active' ? now() : null,
            'current_period_end' => $status === 'active' ? now()->addMonth() : null, // For monthly plans
        ]);

        $message = $status === 'pending'
            ? "Permintaan langganan paket {$plan->name} telah dikirim. Tunggu konfirmasi dari admin."
            : "Berhasil berlangganan paket {$plan->name}!";

        return redirect()->route('subscriptions.status')
            ->with('success', $message);
    }

    /**
     * Cancel subscription
     */
    public function cancel(Request $request)
    {
        $user = auth()->user();
        $subscription = $user->activeSubscription();

        if (!$subscription) {
            return redirect()->route('subscriptions.status')
                ->with('error', 'Tidak ada langganan aktif.');
        }

        try {
            $subscription->cancel();

            return redirect()->route('subscriptions.status')
                ->with('success', 'Langganan berhasil dibatalkan.');
        } catch (\Exception $e) {
            return redirect()->route('subscriptions.status')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Upgrade subscription
     */
    public function upgrade(Request $request, $planSlug)
    {
        $plan = SubscriptionPlan::where('slug', $planSlug)->active()->firstOrFail();
        $user = auth()->user();
        $currentSubscription = $user->activeSubscription();

        if (!$currentSubscription) {
            return redirect()->route('subscriptions.plans')
                ->with('error', 'Anda belum memiliki langganan aktif.');
        }

        // Cancel current subscription (directly update status to avoid restrictions on free plans)
        $currentSubscription->update(['status' => 'cancelled']);

        // Create new subscription - pending for paid plans, active for free plans
        $status = $plan->price > 0 ? 'pending' : 'active';
        $subscription = UserSubscription::create([
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
            'status' => $status,
            'current_period_start' => $status === 'active' ? now() : null,
            'current_period_end' => $status === 'active' ? now()->addMonth() : null,
        ]);

        $message = $status === 'pending'
            ? "Permintaan upgrade ke paket {$plan->name} telah dikirim. Tunggu konfirmasi dari admin."
            : "Berhasil upgrade ke paket {$plan->name}!";

        return redirect()->route('subscriptions.status')
            ->with('success', $message);
    }

    /**
     * Show upgrade required page
     */
    public function upgradeRequired(Request $request)
    {
        $feature = $request->get('feature', 'fitur ini');
        $plans = SubscriptionPlan::active()->ordered()->get();

        return view('subscriptions.upgrade-required', compact('feature', 'plans'));
    }
}
