<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $feature, ?string $requiredTier = null): Response
    {
        $user = Auth::user();

        // Super admin can access everything
        if ($user && $user->isSuperAdmin()) {
            return $next($request);
        }

        // Check if user has active subscription and handle expired subscriptions
        if (!$user || !$user->hasActiveSubscription()) {
            // Check if user has an expired subscription that needs to be marked as expired
            $subscription = $user ? $user->activeSubscription() : null;
            if ($subscription && $subscription->isExpired() && $subscription->status === 'active') {
                // Automatically mark as expired
                $subscription->update(['status' => 'expired']);
            }

            return redirect()->route('subscriptions.upgrade-required', ['feature' => $this->getFeatureName($feature)]);
        }

        // Check if user can access the specific feature
        if (!$user->canAccessFeature($feature, $requiredTier)) {
            return redirect()->route('subscriptions.upgrade-required', ['feature' => $this->getFeatureName($feature)]);
        }

        return $next($request);
    }

    /**
     * Get human-readable feature name
     */
    private function getFeatureName(string $feature): string
    {
        $featureNames = [
            'multi_branch' => 'Multi-branch',
            'export_excel' => 'Export Excel',
            'export_pdf' => 'Export PDF',
            'advanced_reports' => 'Laporan Lanjutan',
            'api_access' => 'API Access',
            'priority_support' => 'Dukungan Prioritas',
            'custom_reports' => 'Laporan Kustom',
        ];

        return $featureNames[$feature] ?? ucfirst(str_replace('_', ' ', $feature));
    }
}
