<?php

namespace App\Http\Controllers;

use App\Models\Drawing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DrawingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $sort = $request->query('sort', 'latest');

        $drawingsQuery = Drawing::with(['user', 'replies']);

        switch ($sort) {
            case 'oldest':
                $drawingsQuery->oldest();
                break;
            case 'most_replies':
                $drawingsQuery->withCount('replies')->orderByDesc('replies_count');
                break;
            case 'least_replies':
                $drawingsQuery->withCount('replies')->orderBy('replies_count');
                break;
            case 'latest':
            default:
                $drawingsQuery->latest();
                break;
        }

        $drawings = $drawingsQuery->paginate(10);
        return view('drawings.index', compact('drawings', 'sort'));
    }

    public function create()
    {
        return view('drawings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'image_data' => 'required|string',
        ]);

        Auth::user()->drawings()->create([
            'title' => $request->title,
            'image_data' => $request->image_data,
        ]);

        return redirect()->route('drawings.index')->with('success', 'Drawing posted successfully!');
    }

    public function show(Drawing $drawing)
    {
        $drawing->load(['user', 'replies.user']);
        return view('drawings.show', compact('drawing'));
    }

    public function edit(Drawing $drawing)
    {
        $this->authorize('update', $drawing);
        return view('drawings.edit', compact('drawing'));
    }

    public function update(Request $request, Drawing $drawing)
    {
        $this->authorize('update', $drawing);

        $request->validate([
            'title' => 'nullable|string|max:255',
            'image_data' => 'required|string',
        ]);

        $drawing->update([
            'title' => $request->title,
            'image_data' => $request->image_data,
        ]);

        return redirect()->route('drawings.show', $drawing)->with('success', 'Drawing updated successfully!');
    }

    public function destroy(Drawing $drawing)
    {
        $this->authorize('delete', $drawing);
        $drawing->delete();
        return redirect()->route('drawings.index')->with('success', 'Drawing deleted successfully!');
    }

    public function dashboard()
    {
        $user = Auth::user();
        $userDrawings = $user->drawings()->latest()->take(5)->get();
        $userReplies = $user->replies()->with('drawing')->latest()->take(5)->get();
        $popularDrawings = Drawing::withCount('replies')->orderByDesc('replies_count')->take(5)->get();

        return view('dashboard', compact('userDrawings', 'userReplies', 'popularDrawings'));
    }
}
