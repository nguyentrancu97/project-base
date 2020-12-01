<?php

namespace Modules\Users\Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Modules\Customers\Entities\Customer;
use Modules\Users\Entities\User;
use Spatie\Permission\Models\Role;

class UsersFakerSeedTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        $limit = 20;
        for ($i = 0; $i < $limit; $i++)
        {
            $userInsert = [
                'email' => $faker->email,
                'name' => $faker->name,
                'password' => Hash::make('123qaz'),
//                'time_id' => 85
            ];
            $user = Customer::create($userInsert);
//            $role = Role::findByName('support');
//            $user->syncRoles([$role->id]);
        }
    }
}
