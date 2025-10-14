<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;
    protected $fillable = ['title','duration','total_questions'];
    public function questions(){ return $this->hasMany(Question::class); }
    public function results(){ return $this->hasMany(ExamResult::class); }
}
