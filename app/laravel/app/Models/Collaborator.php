<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collaborator extends Model
{
    protected $table = 'collaborators';
    protected $primaryKey = 'id_collab';
    public $incrementing = true;

    protected $fillable = [
        'full_name',
        'email',
        'position',
        'work_status',
        'projects_count',
        'tickets_count',
        'rating',
        'avatar_color',
    ];

    protected $casts = [
        'projects_count' => 'integer',
        'tickets_count' => 'integer',
        'rating' => 'decimal:1',
    ];
}
