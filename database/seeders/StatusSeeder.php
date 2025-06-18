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
            ],
            [
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
                'name' => 'inactive',
                'type' => 'tenant',
                'class' => 'info'
            ],
            // Plan
            [
                'name' => 'active',
                'type' => 'plan',
                'class' => 'success'
            ],
            [
                'name' => 'inactive',
                'type' => 'plan',
                'class' => 'warning'
            ],
        ];

        Status::query()->insert($statuses);
    }
}
