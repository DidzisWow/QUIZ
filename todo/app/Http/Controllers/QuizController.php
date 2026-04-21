<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Category;
use App\Models\QuizAttempt;
use App\Models\UserAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    // ── Quiz listing ─────────────────────────────────────────
    public function index()
    {
        $quizzes    = Quiz::with('category')
                         ->where('is_published', true)
                         ->latest()
                         ->get();
        $categories = Category::all();

        return view('quiz.index', compact('quizzes', 'categories'));
    }

    // ── Show single quiz (randomized questions & answers) ────
    public function show(Quiz $quiz)
    {
        abort_unless($quiz->is_published, 404);

        // Load questions filtered strictly by this quiz, in random order
        $quiz->load(['questions' => function ($query) use ($quiz) {
            $query->where('quiz_id', $quiz->id)->inRandomOrder();
        }]);

        // Randomize answers for each question individually
        foreach ($quiz->questions as $question) {
            $question->setRelation(
                'answers',
                $question->answers()->inRandomOrder()->get()
            );
        }

        return view('quiz.show', compact('quiz'));
    }

    // ── Submit quiz answers ───────────────────────────────────
    public function submit(Request $request, Quiz $quiz)
    {
        $quiz->load('questions.answers');

        $attempt = QuizAttempt::create([
            'user_id'      => Auth::id(),
            'quiz_id'      => $quiz->id,
            'max_score'    => $quiz->questions->sum('points'),
            'completed_at' => now(),
        ]);

        $score = 0;

        foreach ($quiz->questions as $question) {
            $answerId = $request->input('q_' . $question->id);
            if (!$answerId) continue;

            $answer    = $question->answers->find($answerId);
            $isCorrect = $answer?->is_correct ?? false;

            if ($isCorrect) $score += $question->points;

            UserAnswer::create([
                'attempt_id'  => $attempt->id,
                'question_id' => $question->id,
                'answer_id'   => $answerId,
                'is_correct'  => $isCorrect,
            ]);
        }

        $attempt->update(['score' => $score]);

        return redirect()->route('quiz.result', $attempt->id);
    }

    // ── Show result ───────────────────────────────────────────
    public function result(QuizAttempt $attempt)
    {
        abort_unless($attempt->user_id === Auth::id(), 403);

        $attempt->load([
            'quiz',
            'userAnswers.question.answers',
            'userAnswers.answer',
        ]);

        return view('quiz.result', compact('attempt'));
    }
}