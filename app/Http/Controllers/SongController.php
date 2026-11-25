<?php
namespace App\Http\Controllers;

use App\Models\Song;

class SongController extends Controller
{
    public function index()
    {
        $songs = Song::latest()->get();
        return view('songs.index', compact('songs'));
    }
}