<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use Modules\Users\Repositories\RoleRepository;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    private static $_DEFAULT_ROLES = array(
        ['display_name' => 'Admin', 'name' => 'admin', 'guard_name' => 'admin'],
        ['display_name' => 'Support', 'name' => 'support', 'guard_name' => 'admin'],
        ['display_name' => 'Teacher', 'name' => 'teacher', 'guard_name' => 'admin'],
        ['display_name' => 'TA', 'name' => 'ta', 'guard_name' => 'admin'],
    );
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->clear();
        $this->addAdminPermissions();
        $this->addTeacherTAPermissions();
    }

    private function clear()
    {
        Schema::disableForeignKeyConstraints();

        DB::table('roles')->truncate();
        DB::table('role_has_permissions')->truncate();
        DB::table('permissions')->truncate();
    }
    private function addAdminPermissions()
    {
        //Insert role & attach permissions

        //Default Permission & Role seeder
        $roleRepo = App::make(RoleRepository::class);

        // Synchronize permissions
        $roleRepo->syncModulePermissions(true);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        DB::table('roles')->insert(self::$_DEFAULT_ROLES);

        $admin = Role::findByName('admin');

        $permissions = Permission::all();
        if (count($permissions) == 0) {
            $roleRepo->syncModulePermissions();
            $permissions = Permission::all();
        }

        foreach ($permissions as $permission) {
            $admin->permissions()->attach($permission->id);
        }
    }

    private function addTeacherTAPermissions()
    {
        $permissions = Permission::all();
        $teacher = Role::findByName('teacher');
        $ta = Role::findByName('ta');
        foreach ($permissions as $permission) {
            $teacher->permissions()->attach($permission->id);
            $ta->permissions()->attach($permission->id);
        }
    }
}
