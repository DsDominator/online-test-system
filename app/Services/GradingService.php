<?php
namespace App\Services;
use App\Models\{Exam, Question, StudentAnswer};


class GradingService {
    /**
    * @param array $answers [question_id => answer_text]
    * @return float totalScore
    */
    public function gradeAndPersist(int $userId, Exam $exam, array $answers, \Carbon\Carbon $submittedAt): float {
        $questions = $exam->questions()->get()->keyBy('id');


        $perQuestion = 100 / max(1, $questions->count());
        $total = 0.0;


        foreach($answers as $qid => $text){
            /** @var Question|null $q */
            $q = $questions->get((int)$qid);
            if(!$q) continue;


            $score = null;
            if($q->type === 'multiple_choice'){
                $score = trim((string)$text) === trim((string)$q->correct_answer) ? $perQuestion : 0.0;
                $total += $score;
            }
            // essay -> score remains null until admin grading


            StudentAnswer::updateOrCreate(
                ['user_id'=>$userId,'exam_id'=>$exam->id,'question_id'=>$q->id],
                ['answer_text'=>$text,'score'=>$score,'submitted_at'=>$submittedAt]
            );
        }
        // Ensure unanswered questions exist as rows (null answers)
        foreach($questions as $qid => $q){
            if(!array_key_exists($qid, $answers)){
                StudentAnswer::firstOrCreate(
                    ['user_id'=>$userId,'exam_id'=>$exam->id,'question_id'=>$qid],
                    ['answer_text'=>null,'score'=> $q->type==='multiple_choice' ? 0.0 : null, 'submitted_at'=>$submittedAt]
                );
            }
        }
        return round($total, 2);
    }
}