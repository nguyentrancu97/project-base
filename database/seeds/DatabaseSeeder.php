<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Modules\Users\Entities\User;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(PermissionSeeder::class);

        DB::table('users')->truncate();
        $manager = [
            'email' => 'boss@mva.com',
            'name' => 'Boss',
        ];
        $manager['password'] = Hash::make('123qaz');
        $manager['time_id'] = 85;
        $manager['created_at'] = Carbon::now();
        $manager['updated_at'] = Carbon::now();
        $admin = Role::findByName('admin');
        DB::table('users')->insert($manager);
        User::find(1)->syncRoles([$admin->id]);

        $this->call(\Modules\Users\Database\Seeders\UsersDatabaseSeeder::class);
    }
}
