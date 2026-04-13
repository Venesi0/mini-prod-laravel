<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'projects';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'name',
        'client_id',
        'status',
        'contractHours',
        'usedHours',
        'openTickets',
        'collaborator_ids',
    ];

    protected $casts = [
        'client_id' => 'integer',
        'contractHours' => 'integer',
        'usedHours' => 'integer',
        'openTickets' => 'integer',
        'collaborator_ids' => 'array',
    ];
}
