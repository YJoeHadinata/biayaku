<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssignFreeSubscriptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $freePlan = SubscriptionPlan::where('slug', 'free')->first();

        if (!$freePlan) {
            $this->command->error('Free subscription plan not found. Please run SubscriptionPlanSeeder first.');
            return;
        }

        $usersWithoutSubscriptions = User::whereDoesntHave('subscriptions', function ($query) {
            $query->active();
        })->get();

        foreach ($usersWithoutSubscriptions as $user) {
            UserSubscription::create([
                'user_id' => $user->id,
                'subscription_plan_id' => $freePlan->id,
                'status' => 'active',
                'current_period_start' => now(),
                'current_period_end' => now()->addYears(100), // Effectively unlimited for free plan
            ]);

            $this->command->info("Assigned free subscription to user: {$user->email}");
        }

        $this->command->info("Assigned free subscriptions to {$usersWithoutSubscriptions->count()} users.");
    }
}
