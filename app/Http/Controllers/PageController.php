<?php
namespace App\Http\Controllers;

use App\Models\Song;
use App\Models\Story;

class PageController extends Controller
{
    public function home()
    {
        return view('home', [
            'storyCount' => Story::count(),
            'songCount'  => Song::count(),
        ]);
    }

    public function profile()
    {
        return view('profile');
    }
}