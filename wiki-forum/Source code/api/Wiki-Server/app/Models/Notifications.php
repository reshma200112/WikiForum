<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class Notifications extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable=[
        'type',
        'user_id',
        'posted_user_id',
        'question_id',
        'answer_id',
        

    ];
}
