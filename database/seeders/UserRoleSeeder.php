<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Role;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Artisan::call('passport:install');

        $roles = [
            User::ADMIN_ROLE,
            User::SECRETARY_ROLE,
            User::ACCOUNTANT_ROLE,
            User::MEMBER_ROLE,
        ];

        foreach ($roles as $role) {
            Role::findOrCreate($role,'api');
        }
    }
}
