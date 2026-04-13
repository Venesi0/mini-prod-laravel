<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $projects = [
            [
                'name' => 'WebsiteRevamp',
                'client_id' => 1,
                'status' => 'Active',
                'contractHours' => 120,
                'usedHours' => 0,
                'openTickets' => 0,
                'collaborator_ids' => [1, 3, 7],
            ],
            [
                'name' => 'MobileApp',
                'client_id' => 2,
                'status' => 'Ready',
                'contractHours' => 80,
                'usedHours' => 0,
                'openTickets' => 0,
                'collaborator_ids' => null,
            ],
            [
                'name' => 'DataDash',
                'client_id' => 3,
                'status' => 'Active',
                'contractHours' => 60,
                'usedHours' => 0,
                'openTickets' => 0,
                'collaborator_ids' => [6],
            ],
            [
                'name' => 'APIUpgrade',
                'client_id' => 4,
                'status' => 'Active',
                'contractHours' => 50,
                'usedHours' => 0,
                'openTickets' => 0,
                'collaborator_ids' => [2, 4],
            ],
            [
                'name' => 'BrandKit',
                'client_id' => 5,
                'status' => 'Archived',
                'contractHours' => 30,
                'usedHours' => 0,
                'openTickets' => 0,
                'collaborator_ids' => [1],
            ],
            [
                'name' => 'Onboarding',
                'client_id' => 6,
                'status' => 'Active',
                'contractHours' => 40,
                'usedHours' => 0,
                'openTickets' => 0,
                'collaborator_ids' => [7],
            ],
            [
                'name' => 'CloudMove',
                'client_id' => 7,
                'status' => 'Active',
                'contractHours' => 100,
                'usedHours' => 0,
                'openTickets' => 0,
                'collaborator_ids' => [4, 6],
            ],
            [
                'name' => 'SEOBoost',
                'client_id' => 8,
                'status' => 'Ready',
                'contractHours' => 25,
                'usedHours' => 0,
                'openTickets' => 0,
                'collaborator_ids' => null,
            ],
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }
    }
}
