<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Answers;

class Question extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable=[

        'user_id',
        'title',
        'description',
        'status'

    ];

    public function answers()
    {
        return $this->hasMany(Answers::class, 'question_id', 'id');
    }
}
