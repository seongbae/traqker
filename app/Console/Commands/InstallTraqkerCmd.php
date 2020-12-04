<?php

namespace App\Console\Commands;

use App\Mail\TestMail;
use App\Models\Project;
use App\Models\ReceivedMail;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Artisan;

class InstallTraqkerCmd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install:traqker';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Rolling back migrations...');

        Artisan::call('migrate:reset');

        $this->info('Rolling back complete.');

        $this->info('Linking storage to public.');

        Artisan::call('storage:link');

        $this->info('Linking complete.');

        $this->info('Running migrations...');

        Artisan::call('migrate');

        $this->info('Migrations complete.');

        $this->info('Seeding database...');

        $this->runUserSeed();
        $this->runRoleSeed();
        $this->runPermissionSeed();
        $this->runRolePermissionSeed();
        $this->runUserRoleSeed();
        $this->runSiteSettingsSeed();

        $this->info('Seeding complete.');
    }

    private function runUserSeed()
    {
        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }

    private function runRoleSeed()
    {
        DB::table('roles')->insert([
            [
                'id'         => 1,
                'name'  => 'Administrator',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id'         => 2,
                'name'  => 'Editor',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id'         => 3,
                'name'  => 'User',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }

    private function runPermissionSeed()
    {
        DB::table('permissions')->insert([
            [
                'id'         => 1,
                'name'  => 'access-admin-ui',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id'         => 2,
                'name'  => 'manage-users',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id'         => 3,
                'name'  => 'manage-site-settings',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id'         => 4,
                'name'  => 'manage-pages',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id'         => 5,
                'name'  => 'manage-media',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id'         => 6,
                'name'  => 'manage-logs',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }

    private function runRolePermissionSeed()
    {
        DB::table('role_has_permissions')->insert([
            // Admin - should have all
            [
                'permission_id' => 1,
                'role_id'       => 1
            ],
            [
                'permission_id' => 2,
                'role_id'       => 1
            ],
            [
                'permission_id' => 3,
                'role_id'       => 1
            ],
            [
                'permission_id' => 4,
                'role_id'       => 1
            ],
            [
                'permission_id' => 5,
                'role_id'       => 1
            ],
            [
                'permission_id' => 6,
                'role_id'       => 1
            ],
            // Editor - access admin, manage users, manage pages, manage media
            [
                'permission_id' => 1,
                'role_id'       => 2
            ],
            [
                'permission_id' => 4,
                'role_id'       => 2
            ],
            [
                'permission_id' => 5,
                'role_id'       => 2
            ]

        ]);
    }

    private function runUserRoleSeed()
    {
        DB::table('model_has_roles')->insert([
            [
                'role_id'       => 1,
                'model_type'    => 'App\Models\User',
                'model_id'       => 1
            ]
        ]);
    }

    private function runSiteSettingsSeed()
    {
        DB::table('options')->insert([
            [
                'key'   =>  'version',
                'value' =>  '0.1'
            ],
            [
                'key'   =>  'site_name',
                'value' =>  'Traqker'
            ],
            [
                'key'   =>  'site_description',
                'value' =>  'Project and task management tool.'
            ],
            [
                'key'   =>  'from_name',
                'value' =>  'Traqker Admin'
            ],
            [
                'key'   =>  'from_email',
                'value' =>  'admin@admin.com'
            ],
            [
                'key'   =>  'notification_email',
                'value' =>  'noreply@admin.com'
            ],
            [
                'key'   =>  'maintenance_mode',
                'value' =>  0
            ],
            [
                'key'   =>  'google_analytics_id',
                'value' =>  ''
            ],
            [
                'key'   =>  'modules',
                'value' =>  ''
            ],
            [
                'key'   =>  'business_name',
                'value' =>  'Traqker'
            ],
            [
                'key'   =>  'business_email',
                'value' =>  'admin@admin.com'
            ],
            [
                'key'   =>  'business_phone',
                'value' =>  '123-456-7890'
            ],
            [
                'key'   =>  'business_address',
                'value' =>  '123 Sesame St'
            ]
        ]);
    }
}
