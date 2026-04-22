<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Quiz;
use App\Models\QuizAttempt;

class HistoryController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // All attempts grouped by quiz, with count and best score
        $attempts = QuizAttempt::with('quiz.category')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Summary per quiz: times taken, best score, last attempt
        $summary = $attempts->groupBy('quiz_id')->map(function ($group) {
            $best = $group->sortByDesc(function ($a) {
                return $a->max_score > 0 ? ($a->score / $a->max_score) * 100 : 0;
            })->first();

            return [
                'quiz'        => $group->first()->quiz,
                'times_taken' => $group->count(),
                'best_score'  => $best->score,
                'max_score'   => $best->max_score,
                'best_pct'    => $best->max_score > 0
                                    ? round(($best->score / $best->max_score) * 100)
                                    : 0,
                'last_played' => $group->first()->created_at,
                'attempts'    => $group,
            ];
        })->sortByDesc('last_played');

        $totalAttempts = $attempts->count();
        $avgScore      = $attempts->where('max_score', '>', 0)->avg(function ($a) {
            return ($a->score / $a->max_score) * 100;
        });

        return view('history.index', compact('summary', 'totalAttempts', 'avgScore'));
    }
}