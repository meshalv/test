<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{

    use HasFactory;

    protected $table = 'registration';

    protected $fillable = [
        'username',
        'phone_number',
        'token',
        'expired_at',
        'is_active',
    ];

    public $timestamps = true;

    public function results()
    {
        return $this->hasMany(Results::class);
    }
}
