<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\Project;

class ProjectsController extends Controller
{
    public function index(Request $request)
    {
        $clientId = $request->integer('client_id');

        $projectsQuery = Project::query();
        if (!empty($clientId)) {
            $projectsQuery->where('client_id', $clientId);
        }

        $projects = $projectsQuery->get();
        $clientsById = Client::pluck('name', 'id_client');

        return view('admin.projects', compact('projects', 'clientsById'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:15',
            'client' => 'required|string|max:15',
            'status' => 'nullable|string|max:15',
            'contractHours' => 'required|integer|min:10',
            'usedHours' => 'nullable|integer|min:0',
            'openTickets' => 'nullable|integer|min:0',
            'collaborator_ids' => 'nullable|array',
        ]);

        $client = Client::where('name', $data['client'])->first();
        if (!$client) {
            return redirect()
                ->route('admin.projects')
                ->withInput()
                ->withErrors(['client' => 'Client not found.']);
        }

        Project::create([
            'name' => $data['name'],
            'client_id' => $client->id_client,
            'status' => $data['status'] ?? 'Active',
            'contractHours' => $data['contractHours'],
            'usedHours' => $data['usedHours'] ?? 0,
            'openTickets' => $data['openTickets'] ?? 0,
            'collaborator_ids' => $data['collaborator_ids'] ?? null,
        ]);

        return redirect()->route('admin.projects');
    }

    public function update(Request $request, Project $project)
    {
        $data = $request->validate([
            'name' => 'required|string|max:15',
            'client_id' => 'required|integer|exists:clients,id_client',
            'status' => 'required|string|max:15',
            'contractHours' => 'required|integer|min:0',
            'usedHours' => 'required|integer|min:0',
            'openTickets' => 'required|integer|min:0',
            'collaborator_ids' => 'nullable|array',
        ]);

        $project->update($data);

        return redirect()->route('admin.projects.show', $project);
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('admin.projects');
    }
}
