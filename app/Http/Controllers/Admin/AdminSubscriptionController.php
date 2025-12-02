<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserSubscription;
use Illuminate\Http\Request;

class AdminSubscriptionController extends Controller
{
    /**
     * Display a listing of pending subscriptions
     */
    public function index(Request $request)
    {
        $query = UserSubscription::with(['user', 'plan'])
            ->pending()
            ->latest();

        if ($request->has('search') && $request->search) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $pendingSubscriptions = $query->paginate(10);

        return view('admin.subscriptions.index', compact('pendingSubscriptions'));
    }

    /**
     * Approve a pending subscription
     */
    public function approve($id)
    {
        $subscription = UserSubscription::findOrFail($id);

        if ($subscription->status !== 'pending') {
            return redirect()->back()->with('error', 'Subscription tidak dalam status pending.');
        }

        $subscription->update([
            'status' => 'active',
            'current_period_start' => now(),
            'current_period_end' => now()->addMonth(),
        ]);

        return redirect()->back()->with('success', 'Subscription berhasil disetujui.');
    }

    /**
     * Reject a pending subscription
     */
    public function reject($id)
    {
        $subscription = UserSubscription::findOrFail($id);

        if ($subscription->status !== 'pending') {
            return redirect()->back()->with('error', 'Subscription tidak dalam status pending.');
        }

        // Free subscriptions cannot be rejected
        if ($subscription->plan->slug === 'free') {
            return redirect()->back()->with('error', 'Free subscription tidak dapat ditolak.');
        }

        $subscription->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Subscription berhasil ditolak.');
    }

    /**
     * Show all subscriptions (active, pending, cancelled)
     */
    public function all(Request $request)
    {
        $query = UserSubscription::with(['user', 'plan'])->latest();

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $subscriptions = $query->paginate(15);

        return view('admin.subscriptions.all', compact('subscriptions'));
    }
}
