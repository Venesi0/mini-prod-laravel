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

class ProjectDetailsController extends Controller
{
    public function index(Project $project)
    {
        $client = Client::where('id_client', $project->client_id)->first();

        $collabIds = is_array($project->collaborator_ids) ? $project->collaborator_ids : [];
        $collabIds = array_values(array_filter($collabIds, fn($id) => is_int($id) || ctype_digit((string) $id)));

        $collaborators = count($collabIds) > 0
            ? Collaborator::whereIn('id_collab', $collabIds)->get()
            : collect();

        $tickets = Ticket::where('project_id', $project->id)->get();

        $nextTicketCode = sprintf('#TK-%04d', 1000 + ((int) Ticket::max('id_ticket') + 1));

        return view('admin.project', [
            'project' => $project,
            'client' => $client,
            'collaborators' => $collaborators,
            'tickets' => $tickets,
            'nextTicketCode' => $nextTicketCode,
        ]);
    }

    public function storeTicket(Request $request, Project $project)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:100',
            'description' => 'nullable|string',
            'priority' => 'nullable|string|max:20',
            'type' => 'nullable|string|max:20|in:Included,Billable',
            'time_est' => 'nullable|string|max:10',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('admin.projects.show', $project)
                ->withErrors($validator)
                ->withInput()
                ->with('open_ticket_modal', 'new');
        }

        $data = $validator->validated();

        // Code is finalized after insert, based on the real auto-incremented id_ticket.
        $data['code'] = 'TEMP';
        $data['client_id'] = (int) $project->client_id;
        $data['project_id'] = (int) $project->id;
        $data['status'] = 'Opened';
        $data['time_real'] = null;
        $data['created_at'] = now()->toDateString();

        if (isset($data['time_est']) && $data['time_est'] !== null && $data['time_est'] !== '') {
            $value = trim((string) $data['time_est']);
            if (preg_match('/^[0-9]+(\\.[0-9]+)?$/', $value)) {
                $data['time_est'] = $value . 'h';
            }
        }

        $ticket = Ticket::create($data);
        $ticket->code = sprintf('#TK-%04d', 1000 + (int) $ticket->id_ticket);
        $ticket->save();

        ProjectMetrics::recalcProject((int) $project->id);

        return redirect()->route('admin.projects.show', $project);
    }
}
