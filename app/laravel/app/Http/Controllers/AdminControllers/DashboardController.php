<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Project;
use App\Models\Ticket;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'opened' => Ticket::where('status', 'Opened')->count(),
            'in_progress' => Ticket::where('status', 'In progress')->count(),
            'to_validate' => Ticket::where('status', 'To validate')->count(),
        ];

        $latestTickets = Ticket::orderByDesc('id_ticket')->limit(6)->get();
        $projectsById = Project::pluck('name', 'id');
        $clientsById = Client::pluck('name', 'id_client');

        return view('admin.dashboard', [
            'stats' => $stats,
            'latestTickets' => $latestTickets,
            'projectsById' => $projectsById,
            'clientsById' => $clientsById,
        ]);
    }
}
