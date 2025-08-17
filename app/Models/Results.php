<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Results extends Model
{
    protected $table = 'results';
    protected $fillable = [
        'registration_id',
        'number',
        'is_win',
        'amount',
    ];

    public $timestamps = true;
}
