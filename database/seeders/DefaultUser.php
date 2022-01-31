<?php

namespace Database\Seeders;

use App\Models\Admin\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultUser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminData = [];
        $adminData["name"]    = "Super Admin";
        $adminData["email"]    = "admin@admin.com";
        $adminData["is_employee"]    = "1";
        $adminData["is_admin"]    = "1";
        $adminData["password"]    = Hash::make("password");

        User::create( $adminData );

    }
}
