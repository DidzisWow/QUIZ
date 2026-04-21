<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = ['category_id','created_by','title','slug','description','time_limit','difficulty','is_published'];

    public function category()  { return $this->belongsTo(Category::class); }
public function questions() 
{ 
    return $this->hasMany(Question::class, 'quiz_id');
}
}