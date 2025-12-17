<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_module_id',
        'type',
        'url',
        'title',
        'description',
        'thumbnail',
        'views',
        'score',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userModule()
    {
        return $this->belongsTo(UserModule::class);
    }

    public function interactions()
    {
        return $this->hasMany(ProjectInteraction::class);
    }

    public function likes()
    {
        return $this->hasMany(ProjectInteraction::class)->where('type', 'like');
    }

    public function loves()
    {
        return $this->hasMany(ProjectInteraction::class)->where('type', 'love');
    }

    public function stars()
    {
        return $this->hasMany(ProjectInteraction::class)->where('type', 'star');
    }
}
