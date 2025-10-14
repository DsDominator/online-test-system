<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    protected $fillable = ['user_id','exam_id','question_id','answer_text','score','submitted_at'];
    protected $dates = ['submitted_at'];
    public function exam(){ return $this->belongsTo(Exam::class); }
    public function question(){ return $this->belongsTo(Question::class); }
    public function user(){ return $this->belongsTo(User::class); }
}
