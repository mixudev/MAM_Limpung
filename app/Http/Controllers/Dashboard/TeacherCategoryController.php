<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreTeacherCategoryRequest;
use App\Http\Requests\Dashboard\UpdateTeacherCategoryRequest;
use App\Models\TeacherCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class TeacherCategoryController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('view-teacher-categories');

        $query = TeacherCategory::withCount('teachers');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $categories = $query->orderBy('name')->paginate(10)->withQueryString();
        $editCategory = null;

        if ($request->filled('edit')) {
            $editCategory = TeacherCategory::find($request->input('edit'));
        }

        return view('dashboard.admin.academic.teacher-categories.index', compact('categories', 'editCategory'));
    }

    public function store(StoreTeacherCategoryRequest $request): RedirectResponse
    {
        TeacherCategory::create($request->validated());

        return redirect()->route('admin.teacher-categories.index')
            ->with('success', 'Kategori guru berhasil ditambahkan.');
    }

    public function update(UpdateTeacherCategoryRequest $request, TeacherCategory $teacherCategory): RedirectResponse
    {
        $teacherCategory->update($request->validated());

        return redirect()->route('admin.teacher-categories.index')
            ->with('success', 'Kategori guru berhasil diperbarui.');
    }

    public function destroy(TeacherCategory $teacherCategory): RedirectResponse
    {
        Gate::authorize('delete-teacher-categories');

        if ($teacherCategory->teachers()->count() > 0) {
            return redirect()->route('admin.teacher-categories.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki guru.');
        }

        $teacherCategory->delete();

        return redirect()->route('admin.teacher-categories.index')
            ->with('success', 'Kategori guru berhasil dihapus.');
    }
}
