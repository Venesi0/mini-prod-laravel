<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Ticket;
use App\Support\ProjectMetrics;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $projects = Project::all(['id', 'client_id', 'collaborator_ids']);
        if ($projects->count() === 0) {
            return;
        }

        // Make repeated seeding idempotent-ish for dev.
        Ticket::query()->delete();

        $statusPerProject = ['Opened', 'In progress', 'Wait for client', 'To validate', 'Closed'];
        $priorityPerProject = ['Low', 'Medium', 'High', 'Urgent', 'Medium'];
        $typePerProject = ['Included', 'Billable', 'Included', 'Billable', 'Included'];

        $verbs = ['Fix', 'Implement', 'Refactor', 'Improve', 'Audit', 'Add', 'Remove', 'Update', 'Optimize', 'Document'];
        $nouns = [
            'API integration',
            'custom plugin',
            'homepage layout',
            'payment gateway',
            'auth flow',
            'dashboard widgets',
            'mobile navigation',
            'CI pipeline',
            'billing export',
            'email notifications',
            'user roles',
            'database indexes',
            'search filters',
            'file upload',
            'rate limiting',
            'error handling',
            'timezone display',
            'access control',
        ];

        $rows = [];
        $counter = 0;

        foreach ($projects as $project) {
            for ($i = 0; $i < 5; $i++) {
                $status = $statusPerProject[$i];
                $priority = $priorityPerProject[$i];
                $type = $typePerProject[$i];

                $title = $verbs[($counter * 2) % count($verbs)] . ' ' . $nouns[($counter * 7) % count($nouns)];

                $est = 3 + (($counter * 5) % 13); // 3..15h
                $real = null;
                if ($status === 'Closed') {
                    $real = max(1, $est - (($counter * 3) % 4));
                } elseif ($status === 'In progress') {
                    $real = max(1, (int) floor($est / 2));
                } elseif ($status === 'To validate') {
                    $real = max(1, $est - 1);
                }

                $rows[] = [
                    'code' => sprintf('#TK-%04d', 1000 + $counter),
                    'title' => $title,
                    'description' => 'Auto-generated ticket for dev seeding.',
                    'client_id' => (int) $project->client_id,
                    'project_id' => (int) $project->id,
                    'status' => $status,
                    'priority' => $priority,
                    'type' => $type,
                    'time_est' => $est . 'h',
                    'time_real' => $real !== null ? ($real . 'h') : null,
                    'created_at' => now()->subDays($counter % 20)->toDateString(),
                ];

                $counter++;
            }
        }

        Ticket::insert($rows);

        foreach (Project::all(['id']) as $project) {
            ProjectMetrics::recalcProject((int) $project->id);
        }
    }
}
