<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default branch for super admin
        $defaultBranch = Branch::firstOrCreate([
            'name' => 'Kantor Pusat'
        ], [
            'address' => 'Jl. Contoh No. 123, Jakarta',
            'phone' => '021-12345678',
            'email' => 'admin@biayaku.com',
        ]);

        // Assign default branch to super admin users who don't have a branch
        $superAdmins = User::where('role', 'super_admin')->whereNull('branch_id')->get();
        foreach ($superAdmins as $admin) {
            $admin->update(['branch_id' => $defaultBranch->id]);
        }

        // Also assign to regular admin users who don't have a branch
        $admins = User::where('role', 'admin')->whereNull('branch_id')->get();
        foreach ($admins as $admin) {
            $admin->update(['branch_id' => $defaultBranch->id]);
        }
    }
}
