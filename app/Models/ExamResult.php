<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    protected $fillable = ['user_id','exam_id','total_score','submitted_at'];
    protected $dates = ['submitted_at'];
    public function exam(){ return $this->belongsTo(Exam::class); }
    public function user(){ return $this->belongsTo(User::class); }
}
