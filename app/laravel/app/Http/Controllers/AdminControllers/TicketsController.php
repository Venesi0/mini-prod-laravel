<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Collaborator;
use App\Models\Project;
use App\Models\Ticket;
use App\Support\ProjectMetrics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TicketsController extends Controller
{
    public function index()
    {
        $returnProjectId = request()->query('return_project_id');
        if (!($returnProjectId !== null && $returnProjectId !== '' && ctype_digit((string) $returnProjectId))) {
            $returnProjectId = null;
        }

        $selectedProjectId = request()->query('project_id');

        $ticketQuery = Ticket::query()->orderByDesc('id_ticket');
        if ($selectedProjectId !== null && $selectedProjectId !== '' && ctype_digit((string) $selectedProjectId)) {
            $ticketQuery->where('project_id', (int) $selectedProjectId);
        }

        $tickets = $ticketQuery->get();

        // Stats are computed on the whole pipeline (not filtered).
        $stats = [
            'opened' => Ticket::where('status', 'Opened')->count(),
            'in_progress' => Ticket::where('status', 'In progress')->count(),
            'to_validate' => Ticket::where('status', 'To validate')->count(),
            'closed' => Ticket::where('status', 'Closed')->count(),
        ];

        $clientsById = Client::pluck('name', 'id_client');
        $projects = Project::all(['id', 'name', 'client_id', 'collaborator_ids']);
        $projectsById = $projects->keyBy('id');

        $collabIds = [];
        foreach ($projects as $project) {
            $ids = is_array($project->collaborator_ids) ? $project->collaborator_ids : [];
            foreach ($ids as $id) {
                if (is_int($id) || ctype_digit((string) $id)) {
                    $collabIds[(int) $id] = true;
                }
            }
        }

        $collaboratorsById = count($collabIds) > 0
            ? Collaborator::whereIn('id_collab', array_keys($collabIds))->get()->keyBy('id_collab')
            : collect();

        $projectCollaboratorsByProjectId = [];
        foreach ($projects as $project) {
            $ids = is_array($project->collaborator_ids) ? $project->collaborator_ids : [];
            $ids = array_values(array_filter($ids, fn($id) => is_int($id) || ctype_digit((string) $id)));
            $collabs = [];
            foreach ($ids as $id) {
                $collab = $collaboratorsById[(int) $id] ?? null;
                if ($collab) {
                    $collabs[] = $collab;
                }
            }
            $projectCollaboratorsByProjectId[$project->id] = $collabs;
        }

        $nextCode = sprintf('#TK-%04d', 1000 + ((int) Ticket::max('id_ticket') + 1));

        return view('admin.tickets', [
            'tickets' => $tickets,
            'clientsById' => $clientsById,
            'projects' => $projects,
            'projectsById' => $projectsById,
            'projectCollaboratorsByProjectId' => $projectCollaboratorsByProjectId,
            'nextCode' => $nextCode,
            'selectedProjectId' => $selectedProjectId,
            'stats' => $stats,
            'returnProjectId' => $returnProjectId,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'nullable|string|max:20',
            'title' => 'required|string|max:100',
            'description' => 'nullable|string',
            'client_id' => 'required|integer|exists:clients,id_client',
            'project_id' => 'required|integer|exists:projects,id',
            'status' => 'nullable|string|max:20|in:Opened,In progress,Wait for client,To validate,Closed',
            'priority' => 'nullable|string|max:20',
            'type' => 'nullable|string|max:20',
            'time_est' => 'nullable|string|max:10',
            'time_real' => 'nullable|string|max:10',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('admin.tickets')
                ->withErrors($validator)
                ->withInput()
                ->with('open_ticket_modal', 'new');
        }

        $data = $validator->validated();

        $project = Project::find($data['project_id']);
        if (!$project || (int) $project->client_id !== (int) $data['client_id']) {
            return redirect()
                ->route('admin.tickets')
                ->withErrors(['project_id' => 'This project does not belong to the selected client.'])
                ->withInput()
                ->with('open_ticket_modal', 'new');
        }

        // Code is finalized after insert, based on the real auto-incremented id_ticket.
        $data['code'] = 'TEMP';
        $data['created_at'] = now()->toDateString();
        foreach (['time_est', 'time_real'] as $field) {
            if (!isset($data[$field]) || $data[$field] === null || $data[$field] === '') {
                continue;
            }
            $value = trim((string) $data[$field]);
            if (preg_match('/^[0-9]+(\\.[0-9]+)?$/', $value)) {
                $data[$field] = $value . 'h';
            }
        }

        $ticket = Ticket::create($data);
        $ticket->code = sprintf('#TK-%04d', 1000 + (int) $ticket->id_ticket);
        $ticket->save();

        ProjectMetrics::recalcProject((int) $ticket->project_id);

        $returnProjectId = $request->input('return_project_id');
        if ($returnProjectId !== null && $returnProjectId !== '' && ctype_digit((string) $returnProjectId)) {
            return redirect()->route('admin.projects.show', (int) $returnProjectId);
        }

        return redirect()->to(route('admin.tickets') . '#viewTicketModal' . $ticket->id_ticket);
    }

    public function update(Request $request, Ticket $ticket)
    {
        $previousProjectId = (int) $ticket->project_id;

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:100',
            'description' => 'nullable|string',
            'client_id' => 'required|integer|exists:clients,id_client',
            'project_id' => 'required|integer|exists:projects,id',
            'status' => 'nullable|string|max:20|in:Opened,In progress,Wait for client,To validate,Closed',
            'priority' => 'nullable|string|max:20',
            'type' => 'nullable|string|max:20',
            'time_est' => 'nullable|string|max:10',
            'time_real' => 'nullable|string|max:10',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('admin.tickets')
                ->withErrors($validator)
                ->withInput()
                ->with('open_ticket_modal', (string) $ticket->id_ticket);
        }

        $data = $validator->validated();

        $project = Project::find($data['project_id']);
        if (!$project || (int) $project->client_id !== (int) $data['client_id']) {
            return redirect()
                ->route('admin.tickets')
                ->withErrors(['project_id' => 'This project does not belong to the selected client.'])
                ->withInput()
                ->with('open_ticket_modal', (string) $ticket->id_ticket);
        }

        foreach (['time_est', 'time_real'] as $field) {
            if (!isset($data[$field]) || $data[$field] === null || $data[$field] === '') {
                continue;
            }
            $value = trim((string) $data[$field]);
            if (preg_match('/^[0-9]+(\\.[0-9]+)?$/', $value)) {
                $data[$field] = $value . 'h';
            }
        }

        $ticket->update($data);

        if ($previousProjectId !== (int) $ticket->project_id) {
            ProjectMetrics::recalcProject($previousProjectId);
        }
        ProjectMetrics::recalcProject((int) $ticket->project_id);

        $returnProjectId = $request->input('return_project_id');
        if ($returnProjectId !== null && $returnProjectId !== '' && ctype_digit((string) $returnProjectId)) {
            return redirect()->route('admin.projects.show', (int) $returnProjectId);
        }

        return redirect()->to(route('admin.tickets') . '#viewTicketModal' . $ticket->id_ticket);
    }

    public function destroy(Ticket $ticket)
    {
        $projectId = (int) $ticket->project_id;
        $ticket->delete();

        ProjectMetrics::recalcProject($projectId);

        $returnProjectId = request()->input('return_project_id');
        if ($returnProjectId !== null && $returnProjectId !== '' && ctype_digit((string) $returnProjectId)) {
            return redirect()->route('admin.projects.show', (int) $returnProjectId);
        }

        return redirect()->route('admin.tickets');
    }
}
