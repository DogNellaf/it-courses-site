<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        $applications = Application::with('course.category')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('home', compact('applications'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('title')->get();
        $courses    = Course::with('category')->orderBy('title')->get();

        return view('courses.create', compact('categories', 'courses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:100'],
            'duration'    => ['required', 'integer', 'min:1'],
            'cost'        => ['required', 'numeric', 'min:0'],
            'description' => ['required', 'string'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'image'       => ['nullable', 'string', 'max:255'],
        ]);

        Course::create($validated);

        return redirect()->route('home')->with('success', 'Курс успешно добавлен!');
    }

    public function destroyApplication(Application $application): RedirectResponse
    {
        $application->delete();

        return redirect()->route('home')->with('success', 'Заявка удалена.');
    }
}
