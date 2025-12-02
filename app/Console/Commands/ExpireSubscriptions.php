<?php

namespace App\Console\Commands;

use App\Models\UserSubscription;
use Illuminate\Console\Command;

class ExpireSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:expire {--dry-run : Show what would be expired without actually expiring}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire subscriptions that have passed their current period end date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->info('🔍 DRY RUN MODE - No changes will be made');
        }

        // Find active subscriptions that have expired
        $expiredSubscriptions = UserSubscription::where('status', 'active')
            ->where('current_period_end', '<', now())
            ->with(['user', 'plan'])
            ->get();

        if ($expiredSubscriptions->isEmpty()) {
            $this->info('✅ No expired subscriptions found.');
            return;
        }

        $this->info("📊 Found {$expiredSubscriptions->count()} expired subscription(s):");
        $this->table(
            ['User', 'Plan', 'Expired At', 'Status'],
            $expiredSubscriptions->map(function ($subscription) {
                return [
                    $subscription->user->name . ' (' . $subscription->user->email . ')',
                    $subscription->plan->name,
                    $subscription->current_period_end->format('Y-m-d H:i'),
                    'Will expire → expired'
                ];
            })
        );

        if ($dryRun) {
            $this->info('🔍 Dry run completed. Use without --dry-run to actually expire subscriptions.');
            return;
        }

        if (!$this->confirm('Do you want to expire these subscriptions?', true)) {
            $this->info('❌ Operation cancelled.');
            return;
        }

        $expiredCount = 0;
        foreach ($expiredSubscriptions as $subscription) {
            $subscription->update(['status' => 'expired']);
            $expiredCount++;
            $this->line("⏰ Expired subscription for {$subscription->user->name} ({$subscription->plan->name})");
        }

        $this->info("✅ Successfully expired {$expiredCount} subscription(s).");
        $this->info('💡 Users will now have access to Free plan features only.');
    }
}
