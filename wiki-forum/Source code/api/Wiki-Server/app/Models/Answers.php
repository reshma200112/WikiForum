<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answers extends Model
{
    use HasFactory;


    protected $fillable = [
        'question_id',
        'user_id',
        'answer',
        'upvote',
        'downvote',
        'verified',
        'status'
    ];

    public function questions()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}
