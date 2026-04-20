<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    protected $fillable = ['user_id','quiz_id','score','max_score','time_taken','completed_at'];

    public function quiz()        { return $this->belongsTo(Quiz::class); }
    public function userAnswers() { return $this->hasMany(UserAnswer::class, 'attempt_id'); }
}