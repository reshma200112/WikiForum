<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTag extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable=[

        'user_id',
        'tag_id'
    ];
}