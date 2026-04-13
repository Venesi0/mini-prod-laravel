<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        Client::insert([
            [
                'name' => 'Northbridge',
                'email' => 'contact@northbridge.com',
                'password_hash' => 'hash1',
                'status' => 'Standard',
                'date' => '2026-03-01',
                'projectsNb' => 0,
                'openedTickets' => 0,
                'totalHours' => 0,
                'avatarColor' => '#3A7BD5',
            ],
            [
                'name' => 'SummitLabs',
                'email' => 'hello@summitlabs.com',
                'password_hash' => 'hash2',
                'status' => 'Premium',
                'date' => '2026-03-02',
                'projectsNb' => 0,
                'openedTickets' => 0,
                'totalHours' => 0,
                'avatarColor' => '#E76F51',
            ],
            [
                'name' => 'Bluecrest',
                'email' => 'info@bluecrest.com',
                'password_hash' => 'hash3',
                'status' => 'Standard',
                'date' => '2026-03-03',
                'projectsNb' => 0,
                'openedTickets' => 0,
                'totalHours' => 0,
                'avatarColor' => '#2A9D8F',
            ],
            [
                'name' => 'OrionTech',
                'email' => 'support@oriontech.com',
                'password_hash' => 'hash4',
                'status' => 'Premium',
                'date' => '2026-03-04',
                'projectsNb' => 0,
                'openedTickets' => 0,
                'totalHours' => 0,
                'avatarColor' => '#F4A261',
            ],
            [
                'name' => 'HarborCo',
                'email' => 'contact@harborco.com',
                'password_hash' => 'hash5',
                'status' => 'Standard',
                'date' => '2026-03-05',
                'projectsNb' => 0,
                'openedTickets' => 0,
                'totalHours' => 0,
                'avatarColor' => '#8E9AAF',
            ],
            [
                'name' => 'VertexWorks',
                'email' => 'hello@vertexworks.com',
                'password_hash' => 'hash6',
                'status' => 'Premium',
                'date' => '2026-03-06',
                'projectsNb' => 0,
                'openedTickets' => 0,
                'totalHours' => 0,
                'avatarColor' => '#1D3557',
            ],
            [
                'name' => 'Silverline',
                'email' => 'info@silverline.com',
                'password_hash' => 'hash7',
                'status' => 'Standard',
                'date' => '2026-03-07',
                'projectsNb' => 0,
                'openedTickets' => 0,
                'totalHours' => 0,
                'avatarColor' => '#457B9D',
            ],
            [
                'name' => 'RedwoodGrp',
                'email' => 'contact@redwoodgrp.com',
                'password_hash' => 'hash8',
                'status' => 'Premium',
                'date' => '2026-03-08',
                'projectsNb' => 0,
                'openedTickets' => 0,
                'totalHours' => 0,
                'avatarColor' => '#E63946',
            ],
            [
                'name' => 'NimbusData',
                'email' => 'hello@nimbusdata.com',
                'password_hash' => 'hash9',
                'status' => 'Standard',
                'date' => '2026-03-09',
                'projectsNb' => 0,
                'openedTickets' => 0,
                'totalHours' => 0,
                'avatarColor' => '#6D597A',
            ],
            [
                'name' => 'AtlasVent',
                'email' => 'info@atlasvent.com',
                'password_hash' => 'hash10',
                'status' => 'Premium',
                'date' => '2026-03-10',
                'projectsNb' => 0,
                'openedTickets' => 0,
                'totalHours' => 0,
                'avatarColor' => '#4CAF50',
            ],
        ]);
    }
}
