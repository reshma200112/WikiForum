<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable=[

        'user_id',
        'question_id',
        'answer_id',
        'comment',
        'status'

    ];
}
