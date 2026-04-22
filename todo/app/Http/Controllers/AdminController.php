<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    // ── Dashboard ─────────────────────────────────────────────
    public function index()
    {
        $quizzes    = Quiz::with('category')->latest()->get();
        $categories = Category::all();
        return view('admin.index', compact('quizzes', 'categories'));
    }

    // ── Create quiz form ───────────────────────────────────────
    public function create()
    {
        $categories = Category::all();
        return view('admin.create', compact('categories'));
    }

    // ── Store quiz ─────────────────────────────────────────────
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'category_id'  => ['required', 'exists:categories,id'],
            'description'  => ['nullable', 'string'],
            'time_limit'   => ['nullable', 'integer', 'min:0'],
            'difficulty'   => ['required', 'in:easy,medium,hard'],
            'is_published' => ['nullable'],
        ]);

        $quiz = Quiz::create([
            'title'        => $data['title'],
            'slug'         => Str::slug($data['title']) . '-' . Str::random(4),
            'category_id'  => $data['category_id'],
            'created_by'   => Auth::id(),
            'description'  => $data['description'] ?? null,
            'time_limit'   => !empty($data['time_limit']) ? $data['time_limit'] * 60 : null,
            'difficulty'   => $data['difficulty'],
            'is_published' => $request->boolean('is_published'),
        ]);

        return redirect()->route('admin.quiz.questions', $quiz->id)
                         ->with('success', 'Quiz created! Now add your questions.');
    }

    // ── Question manager ───────────────────────────────────────
    public function manageQuestions(Quiz $quiz)
    {
        $quiz->load('questions.answers');
        return view('admin.questions', compact('quiz'));
    }

    // ── Store question ─────────────────────────────────────────
    public function storeQuestion(Request $request, Quiz $quiz)
    {
        $request->validate([
            'question_text' => ['required', 'string'],
            'question_type' => ['required', 'in:single,true_false'],
            'points'        => ['required', 'integer', 'min:1'],
            'answers'       => ['required', 'array', 'min:2'],
            'answers.*'     => ['required', 'string'],
            'correct'       => ['required'],
        ]);

        $question = Question::create([
            'quiz_id'       => $quiz->id,
            'question_text' => $request->question_text,
            'question_type' => $request->question_type,
            'points'        => $request->points,
            'order'         => $quiz->questions()->count() + 1,
        ]);

        $correct = (array) $request->input('correct');

        foreach ($request->input('answers') as $i => $text) {
            if (!trim($text)) continue;
            Answer::create([
                'question_id' => $question->id,
                'answer_text' => $text,
                'is_correct'  => in_array((string)$i, array_map('strval', $correct)) ? 1 : 0,
                'order'       => $i + 1,
            ]);
        }

        return back()->with('success', 'Question added!');
    }

    // ── Delete question ────────────────────────────────────────
    public function deleteQuestion(Question $question)
    {
        $quizId = $question->quiz_id;
        $question->delete();
        return redirect()->route('admin.quiz.questions', $quizId)
                         ->with('success', 'Question deleted.');
    }

    // ── Toggle publish ─────────────────────────────────────────
    public function togglePublish(Quiz $quiz)
    {
        $quiz->update(['is_published' => !$quiz->is_published]);
        return back()->with('success', $quiz->is_published ? 'Quiz published!' : 'Quiz unpublished.');
    }

    // ── Delete quiz ────────────────────────────────────────────
    public function deleteQuiz(Quiz $quiz)
    {
        $quiz->delete();
        return redirect()->route('admin.index')->with('success', 'Quiz deleted.');
    }

    // ── Add category ───────────────────────────────────────────
    public function storeCategory(Request $request)
    {
        $data = $request->validate([
            'name'  => ['required', 'string', 'max:100'],
            'icon'  => ['nullable', 'string', 'max:10'],
            'color' => ['nullable', 'string', 'max:7'],
        ]);

        Category::create([
            'name'  => $data['name'],
            'slug'  => Str::slug($data['name']),
            'icon'  => $data['icon'] ?? '📝',
            'color' => $data['color'] ?? '#6366f1',
        ]);

        return back()->with('success', 'Category added!');
    }
}