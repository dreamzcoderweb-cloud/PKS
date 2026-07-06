<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create default branch
        $branch = new \App\Models\Branch();
        $branch->branch_id = 1;
        $branch->name = 'madurai';
        $branch->price = 1000.00;
        $branch->status = 1;
        $branch->save();

        // 2. Create default users
        $user1 = new \App\Models\User();
        $user1->id = 19;
        $user1->name = 'ajis';
        $user1->email = 'ajis@gmail.com';
        $user1->password = '$2y$12$DUOptsJwjFebRyHNjr67Q.AYdn70aJ9UaOXnpm.hCmUr2p9jiEfDy';
        $user1->role = 'user';
        $user1->mobile_number = '9489042187';
        $user1->branch_id = 1;
        $user1->status = 1;
        $user1->save();

        $user2 = new \App\Models\User();
        $user2->id = 20;
        $user2->name = 'tharikajis';
        $user2->email = 'tharikajis@gmail.com';
        $user2->password = '$2y$12$8RUBFLD7DI0AYA.MURmPguOiihFT6/pOJxhLo7OLNtnKmTM7W2LJ6';
        $user2->role = 'admin';
        $user2->mobile_number = '9489042085';
        $user2->branch_id = null;
        $user2->status = 1;
        $user2->save();
    }
}
