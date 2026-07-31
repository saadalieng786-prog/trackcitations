<?php
/*
 * Copyright © 2024 Mohamed A. Shehata (elza3ym@icloud.com)
 * All rights reserved.
 */

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Attorney;
use App\Models\Company;
use App\Models\Driver;
use App\Models\Manager;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Violation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {


        // Define permissions
        Permission::firstOrCreate(['name' => 'create tickets']);
        Permission::firstOrCreate(['name' => 'read tickets']);
        Permission::firstOrCreate(['name' => 'update tickets']);
        Permission::firstOrCreate(['name' => 'delete tickets']);

        Permission::firstOrCreate(['name' => 'create company']);
        Permission::firstOrCreate(['name' => 'read company']);
        Permission::firstOrCreate(['name' => 'update company']);
        Permission::firstOrCreate(['name' => 'delete company']);

        Permission::firstOrCreate(['name' => 'create attorneys']);
        Permission::firstOrCreate(['name' => 'read attorneys']);
        Permission::firstOrCreate(['name' => 'update attorneys']);
        Permission::firstOrCreate(['name' => 'delete attorneys']);

        Permission::firstOrCreate(['name' => 'create drivers']);
        Permission::firstOrCreate(['name' => 'read drivers']);
        Permission::firstOrCreate(['name' => 'update drivers']);
        Permission::firstOrCreate(['name' => 'delete drivers']);

        Permission::firstOrCreate(['name' => 'create citation']);
        Permission::firstOrCreate(['name' => 'read citation']);
        Permission::firstOrCreate(['name' => 'update citation']);
        Permission::firstOrCreate(['name' => 'delete citation']);

        Permission::firstOrCreate(['name' => 'write.company.*']);

        // Define the admin role and assign permissions
        $adminPermissions = [
            'create tickets',
            'read tickets',
            'update tickets',
            'delete tickets',
            'create company',
            'read company',
            'update company',
            'delete company',
            'create attorneys',
            'read attorneys',
            'update attorneys',
            'delete attorneys',
            'create drivers',
            'read drivers',
            'update drivers',
            'delete drivers',
            'create citation',
            'read citation',
            'update citation',
            'delete citation',
        ];

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo($adminPermissions);

        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdminRole->givePermissionTo($adminPermissions);

        $staffAdminRole = Role::firstOrCreate(['name' => 'staff_admin']);
        $staffAdminRole->givePermissionTo($adminPermissions);

        // Define the manager role and assign permissions
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $companyAdminRole = Role::firstOrCreate(['name' => 'company_admin']);

        // Define the attorney role and assign permissions
        $attorneyRole = Role::firstOrCreate(['name' => 'attorney']);

        // Define the driver role and assign permissions
        $driverRole = Role::firstOrCreate(['name' => 'driver']);

        // Create role Models.
        $admin = Admin::create([]);
        $company = Company::create([
            'name' => 'Company #1',
            'ct_email' => 'citation@email.com',
            'ct_fname' => 'Company Fname',
            'ct_lname' => 'Company Lname',
            'dot' => '15081997'
        ]);
        $childCompany = Company::create([
            'name' => 'Company #1 - Fleet A',
            'parent_company_id' => $company->id,
            'ct_email' => 'fleet-a@email.com',
            'ct_fname' => 'Fleet',
            'ct_lname' => 'Admin',
            'dot' => '15081998'
        ]);
        $managerWithWriteAccess = Manager::create([]);
        $managerWithWriteAccess->companies()->attach($company->id, [
            'is_write_access' => true
        ]);
        $managerWithoutWriteAccess = Manager::create([]);
        $managerWithoutWriteAccess->companies()->attach($company->id, [
            'is_write_access' => false
        ]);

        $attorney = Attorney::create([]);

        $driver = Driver::create([
            'company_id' => $company->id
        ]);

        // Create users.
        $adminUser = User::factory()->create([
            'name' => 'Admin Account',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin1234'),
            'roleable_id' => $admin->id,
            'roleable_type' => Admin::class,
        ]);
        $adminUser->assignRole('admin');

        $driverUser = User::factory()->create([
            'name' => 'Driver Account',
            'email' => 'driver@example.com',
            'password' => Hash::make('admin1234'),
            'roleable_id' => $driver->id,
            'roleable_type' => Driver::class,
        ]);
        $driverUser->assignRole('driver');

        $managerUser = User::factory()->create([
            'name' => 'Manager Account',
            'email' => 'manager@example.com',
            'password' => Hash::make('admin1234'),
            'roleable_id' => $managerWithWriteAccess->id,
            'roleable_type' => Manager::class,
        ]);
        $managerUser->assignRole('manager');

        $companyAdminUser = User::factory()->create([
            'name' => 'Company Admin Account',
            'email' => 'companyadmin@example.com',
            'password' => Hash::make('admin1234'),
            'roleable_id' => $managerWithWriteAccess->id,
            'roleable_type' => Manager::class,
        ]);
        $companyAdminUser->assignRole('company_admin');

        $managerUserNoAccess = User::factory()->create([
            'name' => 'Manager2 Account',
            'email' => 'manager2@example.com',
            'password' => Hash::make('admin1234'),
            'roleable_id' => $managerWithoutWriteAccess->id,
            'roleable_type' => Manager::class,
        ]);
        $managerUserNoAccess->assignRole('manager');

        $attorneyUser = User::factory()->create([
            'name' => 'Attorney Account',
            'email' => 'attorney@example.com',
            'password' => Hash::make('admin1234'),
            'roleable_id' => $attorney->id,
            'roleable_type' => Attorney::class,
        ]);
        $attorneyUser->assignRole('attorney');

        $superAdmin = Admin::create([]);
        $superAdminUser = User::factory()->create([
            'name' => 'Super Admin Account',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('admin1234'),
            'roleable_id' => $superAdmin->id,
            'roleable_type' => Admin::class,
        ]);
        $superAdminUser->assignRole('super_admin');

        $staffAdmin = Admin::create([]);
        $staffAdminUser = User::factory()->create([
            'name' => 'Staff Admin Account',
            'email' => 'staffadmin@example.com',
            'password' => Hash::make('admin1234'),
            'roleable_id' => $staffAdmin->id,
            'roleable_type' => Admin::class,
        ]);
        $staffAdminUser->assignRole('staff_admin');



        // Create Violations.
        Violation::create(['violation' => 'Aggravated assault with motor vehicle']);
        Violation::create(['violation' => 'ALR CMV .04 - ADM']);
        Violation::create(['violation' => 'ALR CMV HZMT .04 - ADM ']);
        Violation::create(['violation' => 'ALR-CMV HZMT REF-ADM ']);
        Violation::create(['violation' => 'ALR-CMV REFUSAL-ADM ']);
        Violation::create(['violation' => 'Backed up on shoulder (or roadway) of controlled access highway ']);
        Violation::create(['violation' => 'Bus driver failed to activate warning signal/equipment ']);
        Violation::create(['violation' => 'Bus failed to stop at RR crossing ']);
        Violation::create(['violation' => 'Bus shifting gears while crossing RR tracks ']);
        Violation::create(['violation' => 'Changed lane when unsafe ']);
        Violation::create(['violation' => 'Coasting ']);
        Violation::create(['violation' => 'Coasting (truck, truck tractor or bus, specify) with clutch disengaged ']);
        Violation::create(['violation' => 'Consume alcohol while driving ']);
        Violation::create(['violation' => 'Criminal negligent homicide with motor vehicle - 1st or 2nd degree ']);
        Violation::create(['violation' => 'Crossed RR with heavy equipment without tice ']);
        Violation::create(['violation' => 'Crossed RR with heavy equipment without stop (or safety) ']);
        Violation::create(['violation' => 'Crossing fire hose without permission ']);
        Violation::create(['violation' => 'Crossing physical barrier ']);
        Violation::create(['violation' => 'Cut across driveway to make turn ']);
        Violation::create(['violation' => 'Cut corner left turn ']);
        Violation::create(['violation' => 'Cut in after passing ']);
        Violation::create(['violation' => 'Did t use designated lane or direction ']);
        Violation::create(['violation' => 'Disregard solid green turn signal arrow ']);
        Violation::create(['violation' => 'Disregarded flashing red signal (at stop sign, etc.) ']);
        Violation::create(['violation' => 'Disregarded flashing yellow signal ']);
        Violation::create(['violation' => 'Disregarded lane control signal ']);
        Violation::create(['violation' => 'Disregarded  lane change sign ']);
        Violation::create(['violation' => 'Disregarded  passing zone ']);
        Violation::create(['violation' => 'Disregarded police officer ']);
        Violation::create(['violation' => 'Disregarded RR crossing gate or flagman ']);
        Violation::create(['violation' => 'Disregarded signal at RR crossing ']);
        Violation::create(['violation' => 'Disregarded traffic control device ']);
        Violation::create(['violation' => 'Disregarded turn marks at intersection ']);
        Violation::create(['violation' => 'Disregarded warning sign at construction ']);
        Violation::create(['violation' => 'Drive into block where fire engine stopped ']);
        Violation::create(['violation' => 'Driving under influence ']);
        Violation::create(['violation' => 'Driving under influence (DUI) - mir ']);
        Violation::create(['violation' => 'Driving under influence of drugs ']);
        Violation::create(['violation' => 'Driving while impaired ']);
        Violation::create(['violation' => 'Driving while intoxicated - 0.16 ']);
        Violation::create(['violation' => 'Driving while intoxicated with child younger than 15 yoa ']);
        Violation::create(['violation' => 'Driving while intoxicated - felony ']);
        Violation::create(['violation' => 'Driving while intoxicated - juvenile ']);
        Violation::create(['violation' => 'Driving while intoxicated - misdemear ']);
        Violation::create(['violation' => 'Driving while intoxicated - on beach ']);
        Violation::create(['violation' => 'Driving while intoxicated - probated ']);
        Violation::create(['violation' => 'Driving while intoxicated - under 21 ']);
        Violation::create(['violation' => 'Driving while license disqualified - CMV ']);
        Violation::create(['violation' => 'Driving while license suspended under provisions of DL laws ']);
        Violation::create(['violation' => 'Driving while license suspended - SR ']);
        Violation::create(['violation' => 'Drove center lane (t passing, t turning left) ']);
        Violation::create(['violation' => 'Drove on (or across) streetcar tracks where prohibited ']);
        Violation::create(['violation' => 'Drove on sidewalk ']);
        Violation::create(['violation' => 'Drove on wrong side - RR crossing ']);
        Violation::create(['violation' => 'Drove on wrong side of approaching bridge ']);
        Violation::create(['violation' => 'Drove on wrong side of divided highway ']);
        Violation::create(['violation' => 'Drove on wrong side of road ']);
        Violation::create(['violation' => 'Drove on wrong side road approaching intersection ']);
        Violation::create(['violation' => 'Drove on wrong side road approaching RR grade crossing ']);
        Violation::create(['violation' => 'Drove on wrong side road awaiting access to ferry ']);
        Violation::create(['violation' => 'Drove onto (or from) controlled access highway where prohibited ']);
        Violation::create(['violation' => 'Drove through safety zone ']);
        Violation::create(['violation' => 'Drove to left of rotary traffic island ']);
        Violation::create(['violation' => 'Drove without lights - when required ']);
        Violation::create(['violation' => 'Drove wrong way in designated lane ']);
        Violation::create(['violation' => 'Drove wrong way on one-way roadway ']);
        Violation::create(['violation' => 'Endorsement violation CDL ']);
        Violation::create(['violation' => 'Excessive acceleration ( LONGER OFFENSE 9/01/2003) ']);
        Violation::create(['violation' => 'Exhibition of Acceleration ( LONGER OFFENSE 9/01/2003) ']);
        Violation::create(['violation' => 'Fail to control speed ']);
        Violation::create(['violation' => 'Fail to dim headlights - following ']);
        Violation::create(['violation' => 'Fail to dim headlights - meeting ']);
        Violation::create(['violation' => 'Fail to drive in single lane ']);
        Violation::create(['violation' => 'Fail to give hand signals when required ']);
        Violation::create(['violation' => 'Fail to give info/render aid ']);
        Violation::create(['violation' => 'Fail to give one-half of roadway ']);
        Violation::create(['violation' => 'Fail to keep to right on mountain road ']);
        Violation::create(['violation' => 'Fail to pass left safely ']);
        Violation::create(['violation' => 'Fail to pass met vehicle to right ']);
        Violation::create(['violation' => 'Fail to pass to right safely ']);
        Violation::create(['violation' => 'Fail to signal for stop ']);
        Violation::create(['violation' => 'Fail to signal required distance before turning ']);
        Violation::create(['violation' => 'Fail to signal turn ']);
        Violation::create(['violation' => 'Fail to signal with turn indicator ']);
        Violation::create(['violation' => 'Fail to sound horn - mountain road ']);
        Violation::create(['violation' => 'Fail to stop - designated point - at stop sign ']);
        Violation::create(['violation' => 'Fail to stop - designated point - at yield sign ']);
        Violation::create(['violation' => 'Fail to stop and render aid - felony ']);
        Violation::create(['violation' => 'Fail to stop and render aid - misdemear ']);
        Violation::create(['violation' => 'Fail to stop at marked RR crossing ']);
        Violation::create(['violation' => 'Fail to stop at proper place (at traffic light) ']);
        Violation::create(['violation' => 'Fail to stop at proper place (flashing red signal) ']);
        Violation::create(['violation' => 'Fail to stop at proper place (t at intersection) ']);
        Violation::create(['violation' => 'Fail to stop for approaching train ']);
        Violation::create(['violation' => 'Fail to stop for approaching train - hazardous proximity ']);
        Violation::create(['violation' => 'Fail to stop for school bus (or remain stopped, specify) ']);
        Violation::create(['violation' => 'Fail to stop for streetcar - or stop at wrong location ']);
        Violation::create(['violation' => 'Fail to stop - emerging from alley, driveway or bldg. ']);
        Violation::create(['violation' => 'Fail to use due care for pedestrian ']);
        Violation::create(['violation' => 'Fail to use proper headlight beam ']);
        Violation::create(['violation' => 'Fail to yield at stop intersection ']);
        Violation::create(['violation' => 'Fail to yield at yield intersection ']);
        Violation::create(['violation' => 'Fail to yield for blind or incapacitated person ']);
        Violation::create(['violation' => 'Fail to yield right of way ']);
        Violation::create(['violation' => 'Fail to yield right of way from private road ']);
        Violation::create(['violation' => 'Fail to yield row at open intersection (specify type) ']);
        Violation::create(['violation' => 'Fail to yield row leaving (private drive, alley, building) ']);
        Violation::create(['violation' => 'Fail to yield row on green arrow signal ']);
        Violation::create(['violation' => 'Fail to yield row on green signal ']);
        Violation::create(['violation' => 'Fail to yield row on left at obstruction ']);
        Violation::create(['violation' => 'Fail to yield row to emergency vehicle ']);
        Violation::create(['violation' => 'Fail to yield row to pedestrian at signal intersection ']);
        Violation::create(['violation' => 'Fail to yield row to pedestrian in crosswalk ']);
        Violation::create(['violation' => 'Fail to yield row to pedestrian in crosswalk - signal ']);
        Violation::create(['violation' => 'Fail to yield row to pedestrian on sidewalk ']);
        Violation::create(['violation' => 'Fail to yield row to pedestrian turning right or left at intersection ']);
        Violation::create(['violation' => 'Fail to yield row to pedestrian - green arrow signal ']);
        Violation::create(['violation' => 'Fail to yield row - changing lanes ']);
        Violation::create(['violation' => 'Fail to yield row - turning left (at intersection, alley, private road or driveway) ']);
        Violation::create(['violation' => 'Fail to yield row - turning right on red signal ']);
        Violation::create(['violation' => 'Fail to yield to vehicle in intersection ']);
        Violation::create(['violation' => 'Fail to yield to vehicle leaving highway ']);
        Violation::create(['violation' => 'Failed to give way when overtaken ']);
        Violation::create(['violation' => 'Failed to signal lane change ']);
        Violation::create(['violation' => 'Fleeing from police officer ']);
        Violation::create(['violation' => 'Following ambulance ']);
        Violation::create(['violation' => 'Following fire apparatus ']);
        Violation::create(['violation' => 'Following too closely ']);
        Violation::create(['violation' => 'Following too closely - caravan ']);
        Violation::create(['violation' => 'Following too closely - truck ']);
        Violation::create(['violation' => 'Head lamps glaring t adjusted ']);
        Violation::create(['violation' => 'Heavy equipment disregarded signal of train ']);
        Violation::create(['violation' => 'Illegal backing ']);
        Violation::create(['violation' => 'Illegal pass on right ']);
        Violation::create(['violation' => 'Illegally passed streetcar ']);
        Violation::create(['violation' => 'Impeding traffic ']);
        Violation::create(['violation' => 'Improper turn ']);
        Violation::create(['violation' => 'Improper turn or stop hand signal ']);
        Violation::create(['violation' => 'Improper use of auxiliary driving lamps ']);
        Violation::create(['violation' => 'Improper use of auxiliary passing lamps ']);
        Violation::create(['violation' => 'Improper use of lighting - hwy. equip. ']);
        Violation::create(['violation' => 'Improper use of spot lamps ']);
        Violation::create(['violation' => 'Improper use of turn indicator ']);
        Violation::create(['violation' => 'Increased speed while being overtaken ']);
        Violation::create(['violation' => 'Interfere with streetcar ']);
        Violation::create(['violation' => 'Intoxication assault ']);
        Violation::create(['violation' => 'Intoxication assault motor vehicle ']);
        Violation::create(['violation' => 'Intoxication manslaughter ']);
        Violation::create(['violation' => 'Intoxication manslaughter motor vehicle ']);
        Violation::create(['violation' => 'Involuntary manslaughter with motor vehicle ']);
        Violation::create(['violation' => 'Leaving scene of accident ']);
        Violation::create(['violation' => 'Leaving scene of accident - vehicle damage ']);
        Violation::create(['violation' => 'Made U-turn on curve or hill ']);
        Violation::create(['violation' => 'Murder - with motor vehicle ']);
        Violation::create(['violation' => 'Negligent collision ']);
        Violation::create(['violation' => ' commercial driver license (CDL) ']);
        Violation::create(['violation' => ' double trailer endorsement (CDL) ']);
        Violation::create(['violation' => ' driver license ']);
        Violation::create(['violation' => ' hazmat endorsement (CDL) ']);
        Violation::create(['violation' => ' motorcycle endorsement ']);
        Violation::create(['violation' => ' passenger vehicle endorsement (CDL) ']);
        Violation::create(['violation' => ' tank vehicle endorsement (CDL) ']);
        Violation::create(['violation' => 'Obstructed view through windshield ']);
        Violation::create(['violation' => 'Obstructing traffic ']);
        Violation::create(['violation' => 'Open Container Driver ']);
        Violation::create(['violation' => 'Operate vehicle more than one passenger-mir ']);
        Violation::create(['violation' => 'Operate vehicle where prohibited ']);
        Violation::create(['violation' => 'Operate vehicle with child in open bed ']);
        Violation::create(['violation' => 'Passed streetcar on left without reducing speed or without caution ']);
        Violation::create(['violation' => 'Passed vehicle stopped for pedestrian ']);
        Violation::create(['violation' => 'Passed - insufficient clearance ']);
        Violation::create(['violation' => 'Passengers/load obstruct driver\'s view or control ']);
        Violation::create(['violation' => 'Passing authorized emergency vehicle ']);
        Violation::create(['violation' => 'Permitted/operated unsafe vehicle ']);
        Violation::create(['violation' => 'Person(s) riding in trailer or semi - trailer ']);
        Violation::create(['violation' => 'Prohibited motor vehicle on controlled - access highway ']);
        Violation::create(['violation' => 'Racing - drag racing, acceleration contest, etc. ']);
        Violation::create(['violation' => 'Ran red light ']);
        Violation::create(['violation' => 'Ran stop sign ']);
        Violation::create(['violation' => 'Reckless driving ']);
        Violation::create(['violation' => 'Restriction violation - CDL ']);
        Violation::create(['violation' => 'Slower vehicle failed to keep to right ']);
        Violation::create(['violation' => 'Speed - under minimum ']);
        Violation::create(['violation' => 'Speeding ']);
        Violation::create(['violation' => 'Speeding - 10% above posted speed limit ']);
        Violation::create(['violation' => 'Speeding - 15 miles or over (CDL) ']);
        Violation::create(['violation' => 'Speeding - school zone ']);
        Violation::create(['violation' => 'Too many riders on motorcycle ']);
        Violation::create(['violation' => 'Turned across dividing section ']);
        Violation::create(['violation' => 'Turned left from wrong lane ']);
        Violation::create(['violation' => 'Turned right from wrong lane ']);
        Violation::create(['violation' => 'Turned right too wide ']);
        Violation::create(['violation' => 'Turned so as to impede or interfere with streetcar ']);
        Violation::create(['violation' => 'Turned when unsafe ']);
        Violation::create(['violation' => 'Unauthorized use of siren, bell or whistle ']);
        Violation::create(['violation' => 'Unsafe speed (too fast for conditions) ']);
        Violation::create(['violation' => 'Unsafe start from parked, stopped or standing position ']);
        Violation::create(['violation' => 'Use of school bus signal for wrong purpose ']);
        Violation::create(['violation' => 'Veh. hauling explosives (or flammable materials) failed to stop at RR crossing ']);
        Violation::create(['violation' => 'Veh. hauling explosives failed to reduce speed at RR crossing ']);
        Violation::create(['violation' => 'Vehicle without required equipment or in unsafe condition ']);
        Violation::create(['violation' => 'Violate DL restriction ']);
        Violation::create(['violation' => 'Violate DL restriction on occupational license ']);
        Violation::create(['violation' => 'Violate operating hours - mir ']);
        Violation::create(['violation' => 'Violated out of service order ']);
        Violation::create(['violation' => 'Wrong side road - t passing ']);
        Violation::create(['violation' => 'Wrong side - 4 or more lanes - two-way roadway']);

    }
}
