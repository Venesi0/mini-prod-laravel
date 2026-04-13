<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call(ClientSeeder::class);
        $this->call(CollaboratorSeeder::class);
        $this->call(ProjectSeeder::class);
        $this->call(TicketSeeder::class);

        // 1) Compte admin (à adapter à ton besoin)
        User::updateOrCreate(
            ['email' => 'admin@projecta.com'],
            ['name' => 'Admin', 'password' => 'admin00', 'role' => 'admin']
        );

        // 2) Comptes users pour chaque client (mdp commun: client00)
        Client::whereNotNull('email')->get()->each(function (Client $client) {
            User::updateOrCreate(
                ['email' => $client->email],
                ['name' => $client->name, 'password' => 'client00', 'role' => 'user']
            );
        });
    }
}
