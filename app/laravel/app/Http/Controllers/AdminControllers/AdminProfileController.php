<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Project;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminProfileController extends Controller
{
    public function index()
    {
        $stats = [
            'projects' => Project::count(),
            'open_tickets' => Ticket::where('status', '!=', 'Closed')->count(),
            'clients' => Client::count(),
        ];

        $recentTickets = Ticket::orderByDesc('id_ticket')->limit(5)->get();
        $projectsById = Project::pluck('name', 'id');

        return view('admin.profile', [
            'stats' => $stats,
            'recentTickets' => $recentTickets,
            'projectsById' => $projectsById,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user?->id),
            ],
        ];

        $validated = $request->validate($rules);

        if (!$user) {
            return back();
        }

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return back()->with('status', 'profile-updated');
    }
}
