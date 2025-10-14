<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['exam_id','order_no','type','question_text','options','correct_answer'];
    protected $casts = [ 'options' => 'array' ];
    public function exam(){ return $this->belongsTo(Exam::class); }
}
