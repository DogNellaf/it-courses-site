<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CoursesController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::orderBy('title')->get();

        $courses = $request->filled('category')
            ? Course::where('category_id', $request->category)->with('category')->paginate(15)->withQueryString()
            : Course::with('category')->paginate(9)->withQueryString();

        return view('index', compact('courses', 'categories'));
    }

    public function detail(Course $course): View
    {
        $course->load('category');

        return view('detail', compact('course'));
    }

    public function storeApplication(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'fio'    => ['required', 'string', 'max:150'],
            'email'  => ['required', 'email', 'max:150'],
            'course' => ['required', 'integer', 'exists:courses,id'],
        ]);

        Application::create([
            'full_name'        => $validated['fio'],
            'email'            => $validated['email'],
            'course_id'        => $validated['course'],
            'application_date' => now(),
            'status'           => 'pending',
        ]);

        return redirect()->route('index')->with('success', 'Заявка успешно отправлена!');
    }
}
