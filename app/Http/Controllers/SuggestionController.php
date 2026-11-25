<?php
namespace App\Http\Controllers;

use App\Models\Suggestion;
use Illuminate\Http\Request;

class SuggestionController extends Controller
{
    public function index()
    {
        $suggestions = Suggestion::latest()->take(30)->get();
        return view('suggestions.index', compact('suggestions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:80',
            'message' => 'required|string|max:1000',
        ]);

        Suggestion::create($data);

        return back()->with('success', 'Terima kasih atas masukannya!');
    }
}