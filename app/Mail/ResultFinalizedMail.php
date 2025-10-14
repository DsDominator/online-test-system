<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Exam;
use App\Models\ExamResult;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResultFinalizedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $student,
        public Exam $exam,
        public ExamResult $result
    ) {}

    public function build()
    {
        return $this->subject('Kết quả bài kiểm tra: ' . $this->exam->title)
            ->markdown('mail.results.finalized');
    }
}

