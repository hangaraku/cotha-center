<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentClassroom extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','classroom_id','credit_left','status','engagement_score'];

    public function user(){
       return $this->belongsTo(User::class);
    }

    public function classroom(){
       return $this->belongsTo(Classroom::class);
    }
}
