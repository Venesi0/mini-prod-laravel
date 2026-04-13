<?php

namespace Database\Seeders;

use App\Models\Collaborator;
use Illuminate\Database\Seeder;

class CollaboratorSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Collaborator::insert([
            [
                'full_name' => 'Grace Whitman',
                'email' => 'grace.whitman@example.com',
                'position' => 'Product Designer',
                'work_status' => 'Available',
                'projects_count' => 2,
                'tickets_count' => 1,
                'rating' => 4.6,
                'avatar_color' => '#3A7BD5',
            ],
            [
                'full_name' => 'Miles Carter',
                'email' => 'miles.carter@example.com',
                'position' => 'Backend Developer',
                'work_status' => 'On project',
                'projects_count' => 4,
                'tickets_count' => 3,
                'rating' => 4.2,
                'avatar_color' => '#E76F51',
            ],
            [
                'full_name' => 'Nora Brooks',
                'email' => 'nora.brooks@example.com',
                'position' => 'Frontend Developer',
                'work_status' => 'Available',
                'projects_count' => 1,
                'tickets_count' => 0,
                'rating' => 4.9,
                'avatar_color' => '#2A9D8F',
            ],
            [
                'full_name' => 'Ethan Reed',
                'email' => 'ethan.reed@example.com',
                'position' => 'DevOps Engineer',
                'work_status' => 'Vacation / away',
                'projects_count' => 3,
                'tickets_count' => 2,
                'rating' => 4.1,
                'avatar_color' => '#F4A261',
            ],
            [
                'full_name' => 'Ava Collins',
                'email' => 'ava.collins@example.com',
                'position' => 'QA Engineer',
                'work_status' => 'Available',
                'projects_count' => 0,
                'tickets_count' => 0,
                'rating' => 4.0,
                'avatar_color' => '#8E9AAF',
            ],
            [
                'full_name' => 'Lucas Foster',
                'email' => 'lucas.foster@example.com',
                'position' => 'Data Analyst',
                'work_status' => 'On project',
                'projects_count' => 2,
                'tickets_count' => 5,
                'rating' => 4.3,
                'avatar_color' => '#1D3557',
            ],
            [
                'full_name' => 'Zoe Palmer',
                'email' => 'zoe.palmer@example.com',
                'position' => 'Project Manager',
                'work_status' => 'Available',
                'projects_count' => 5,
                'tickets_count' => 1,
                'rating' => 4.8,
                'avatar_color' => '#457B9D',
            ],
        ]);
    }
}
