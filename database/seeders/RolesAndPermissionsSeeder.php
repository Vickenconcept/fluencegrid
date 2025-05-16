<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        Permission::create(['name' => 'manage-reseller']);
        Permission::create(['name' => 'access-dfy-marketing']);
        Permission::create(['name' => 'access-affiliate-training']);

        // Define roles and assign permissions
        $frontendRole = Role::create(['name' => 'FE']);

        $OTO1 = Role::create(['name' => 'OTO1']);
        $OTO1->givePermissionTo(['manage-reseller']); 

        $OTO6 = Role::create(['name' => 'OTO6']);
        $OTO6->givePermissionTo(['manage-reseller']);

        
        $OTO4 = Role::create(['name' => 'OTO4']);
        $OTO4->givePermissionTo('access-dfy-marketing');


        $OTO5 = Role::create(['name' => 'OTO5']);
        $OTO5->givePermissionTo('access-affiliate-training');

        $Bundle = Role::create(['name' => 'Bundle']);
        $Bundle->givePermissionTo(Permission::all());
    }
}
