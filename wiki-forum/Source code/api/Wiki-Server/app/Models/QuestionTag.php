<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class QuestionTag extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable=[

        'question_id',
        'tag_id',

    ];
}
