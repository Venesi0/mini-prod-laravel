<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    //
    protected $table = 'clients';
    protected $primaryKey = 'id_client';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'name',
        'email',
        'password_hash',
        'status',
        'date',
        'projectsNb',
        'openedTickets',
        'totalHours',
        'avatarColor',
    ];

    protected $casts = [
        'date' => 'date',
        'projectsNb' => 'integer',
        'openedTickets' => 'integer',
        'totalHours' => 'integer',
    ];
}