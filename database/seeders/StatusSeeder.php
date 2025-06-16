<?php

namespace Database\Seeders;

use App\Models\Status\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'name' => 'active',
                'type' => 'user',
                'class' => 'success'
            ],
            [
                'name' => 'inactive',
                'type' => 'user',
                'class' => 'warning'
            ],
            [
                'name' => 'invited',
                'type' => 'user',
                'class' => 'purple'
            ], [
                'name' => 'rejected',
                'type' => 'user',
                'class' => 'danger'
            ],
            // tenant
            [
                'name' => 'active',
                'type' => 'tenant',
                'class' => 'success'
            ],
            [
                'name' => 'expired',
                'type' => 'tenant',
                'class' => 'info'
            ],
            // Subscription module
            [
                'name' => 'active',
                'type' => 'subscription',
                'class' => 'success'
            ],
            [
                'name' => 'inactive',
                'type' => 'subscription',
                'class' => 'warning'
            ],
            [
                'name' => 'closed',
                'type' => 'subscription',
                'class' => 'info'
            ],
            [
                'name' => 'expired',
                'type' => 'subscription',
                'class' => 'danger'
            ],
            [
                'name' => 'trial_expired',
                'type' => 'subscription',
                'class' => 'danger'
            ],
        ];

        Status::query()->insert($statuses);
    }
}
