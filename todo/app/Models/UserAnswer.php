<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAnswer extends Model
{
    protected $fillable = ['attempt_id','question_id','answer_id','is_correct'];

    public function question() { return $this->belongsTo(Question::class); }
    public function answer()   { return $this->belongsTo(Answer::class); }
}