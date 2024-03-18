<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReputationScore extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'reputation_score';

    protected $fillable =
    [
        'name',
        'score',
        'status'
    ];
}
