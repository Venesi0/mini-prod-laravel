<?php

namespace App\Support;

use App\Models\Client;
use App\Models\Project;
use App\Models\Ticket;

class ProjectMetrics
{
    public static function parseHours(?string $value): ?float
    {
        if ($value === null) {
            return null;
        }

        $raw = trim($value);
        if ($raw === '') {
            return null;
        }

        // Accept "6", "6.5", "6h", "6.5h"
        if (preg_match('/^([0-9]+(?:\\.[0-9]+)?)\\s*h?$/i', $raw, $m)) {
            return (float) $m[1];
        }

        return null;
    }

    public static function recalcProject(Project|int $project): void
    {
        $p = is_int($project) ? Project::find($project) : $project;
        if (!$p) {
            return;
        }

        $tickets = Ticket::where('project_id', $p->id)->get(['status', 'time_real']);

        $sum = 0.0;
        foreach ($tickets as $ticket) {
            $h = self::parseHours($ticket->time_real);
            if ($h !== null) {
                $sum += $h;
            }
        }

        $openTickets = 0;
        foreach ($tickets as $ticket) {
            if ((string) $ticket->status !== 'Closed') {
                $openTickets++;
            }
        }

        // Keep schema as int for now.
        $p->usedHours = (int) round($sum);
        $p->openTickets = $openTickets;
        $p->save();

        self::recalcClient((int) $p->client_id);
    }

    public static function recalcClient(Client|int $client): void
    {
        $c = is_int($client) ? Client::find($client) : $client;
        if (!$c) {
            return;
        }

        $projects = Project::where('client_id', $c->id_client)->get(['id', 'usedHours']);
        $c->projectsNb = $projects->count();
        $c->totalHours = (int) $projects->sum('usedHours');

        $projectIds = $projects->pluck('id')->all();
        $c->openedTickets = count($projectIds) > 0
            ? Ticket::whereIn('project_id', $projectIds)->where('status', '!=', 'Closed')->count()
            : 0;

        $c->save();
    }
}

