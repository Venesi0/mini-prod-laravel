<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    public const STATUSES = [
        'Opened',
        'In progress',
        'Wait for client',
        'To validate',
        'Closed',
    ];

    protected $table = 'tickets';
    protected $primaryKey = 'id_ticket';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'code',
        'title',
        'description',
        'client_id',
        'project_id',
        'status',
        'priority',
        'type',
        'time_est',
        'time_real',
        'created_at',
    ];

    protected $casts = [
        'id_ticket' => 'integer',
        'client_id' => 'integer',
        'project_id' => 'integer',
        'created_at' => 'date',
    ];
}
