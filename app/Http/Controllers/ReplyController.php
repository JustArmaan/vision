<?php

namespace App\Http\Controllers;

use App\Models\Drawing;
use App\Models\Reply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Drawing $drawing)
    {
        return view('replies.create', compact('drawing'));
    }

    public function store(Request $request, Drawing $drawing)
    {
        $request->validate([
            'image_data' => 'required|string',
        ]);

        Auth::user()->replies()->create([
            'drawing_id' => $drawing->id,
            'image_data' => $request->image_data,
        ]);

        return redirect()->route('drawings.show', $drawing)->with('success', 'Reply added successfully!');
    }

    public function edit(Reply $reply)
    {
        $this->authorize('update', $reply);
        $drawing = $reply->drawing;
        return view('replies.edit', compact('reply', 'drawing'));
    }

    public function update(Request $request, Reply $reply)
    {
        $this->authorize('update', $reply);

        $request->validate([
            'image_data' => 'required|string',
        ]);

        $reply->update([
            'image_data' => $request->image_data,
        ]);

        return redirect()->route('drawings.show', $reply->drawing)->with('success', 'Reply updated successfully!');
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('delete', $reply);
        $drawing = $reply->drawing;
        $reply->delete();
        return redirect()->route('drawings.show', $drawing)->with('success', 'Reply deleted successfully!');
    }
}
