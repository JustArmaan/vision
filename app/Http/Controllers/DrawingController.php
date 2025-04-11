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

    public function index()
    {
        $drawings = Drawing::with(['user', 'replies'])->latest()->paginate(10);
        return view('drawings.index', compact('drawings'));
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

    public function destroy(Drawing $drawing)
    {
        $this->authorize('delete', $drawing);
        $drawing->delete();
        return redirect()->route('drawings.index')->with('success', 'Drawing deleted successfully!');
    }
}
