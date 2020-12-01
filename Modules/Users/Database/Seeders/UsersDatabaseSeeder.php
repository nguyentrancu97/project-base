<?php

namespace Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Countries\Database\Seeders\UTCTimeTableSeeder;
use Modules\Users\Entities\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Faker\Factory;

class UsersDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(UTCTimeZoneSeederTableSeeder::class);
//        $this->call(UsersFakerSeedTableSeeder::class);
    }
}
