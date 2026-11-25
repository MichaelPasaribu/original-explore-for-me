<?php

namespace Database\Seeders;

use App\Models\Song;
use App\Models\Story;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        // ===== CERPEN / NOVEL =====
        $stories = [
            [
                'title'      => 'Judul Cerpen Pertama',
                'type'       => 'cerpen',                        // cerpen atau novel
                'genre'      => 'Fantasi',
                'synopsis'   => 'Ringkasan singkat yang tampil di kartu.',
                'cover_path' => 'images/covers/cerpen-1.jpg',    // boleh null
                'content'    => <<<'TEXT'
                    Paragraf pertama ceritamu ditulis di sini.

                    Paragraf kedua. Baris kosong akan menjadi jarak antarparagraf.
                    TEXT,
            ],
            // tambah karya lain di bawah sini, salin blok [ ... ] di atas
        ];

        foreach ($stories as $s) {
            Story::updateOrCreate(['title' => $s['title']], $s);
        }

        // ===== LAGU =====
        $songs = [
    [
        'title'      => 'Venus',
        'artist'     => 'Regina Song',
        'lyrics'     => "I gotta admit I didn't think I could find something
Real from a summer fling
But time has passed and
Life just hasn't been the same
I hate to admit
That it's nice to miss someone
Past eight-hour shifts
At the back of the bus
Headphones on and grinning 'bout the stupid things

And you make me so happy I could cry

For you, I would do anything you want to
Make sure you're smiling in the rear view
Make me want to want you
Been waiting on this miracle so long
When all the stars align I might
Make you wanna lose control of your mind
And fall right for me
If you think you're crazy, make that two
I'm spinning wild and hazy, boo
Like Venus brought me right to you

You're one in a trillion
A rare phenomenon
Our souls intertwine like the vines of a mystical tree
Unconcealable magic floats in the air
Oh, I want my first with you
My now and forever
The boy behind the wheel
Of my fond, loving heart
Please steer me closer where your heart will be

Last night, I wished upon a star that you'll be mine

For you, I would do anything you want to
Make sure you're smiling in the rear view
Make me want to want you
Been waiting on this miracle so long
When all the stars align I might
Make you wanna lose control of your mind
And fall right for me
If you think you're crazy, make that two
I'm spinning wild and hazy, boo
Like Venus brought me right to you

Please take me dancing in the abandoned ballroom at the museum
Where we will kiss under the chandelier when no one's watching
And you will grow fonder and fonder of me
And you will laugh about how you never believed that

I was spinning wild and hazy, boo
Like Venus brought me right to you",
        'audio_path' => 'audio/venus - Regina Song.mp3',
        'cover_path' => 'photo/venus - Regina Song(photo).jpg',
    ],
    [
        'title'      => 'Venus',
        'artist'     => 'Gabe Watkins',
        'lyrics'     => "Honey I'll wait, in my dreams
'Til I see your face, and I'm at ease
Venus baby, come here
And fly me home again

You took the midnight train away
I waved goodbye, I wish you stayed
Here with me, all I need
I would give it up, just to feel your love

Could we stay here for a minute?
I know times won't change
But Venus baby I want it to
Cause it's you

Honey I'll play, make believe
It's that time of day, and I'm at ease
Venus baby, on we dream
Come fly me home again

I'll take the midnight train someday
I'll buy you flowers, you're always
Here with me, all I need
I would give it up, just to feel your love

Could we stay here for a minute?
I know times won't change
But Venus baby I want it to
Cause it's you

Could we stay here for a little?
Hold on, time won't fade
And Venus baby I'll fly with you
I'll fly with you",                      // tanpa lirik
        'audio_path' => 'audio/Gabe Watkins - Venus (Official Video) - Gabe Watkins.mp3',
        'cover_path' => 'photo/venus - Gabe Watkins(photo).jpg',                      // tanpa cover
    ],
];

        foreach ($songs as $s) {
    Song::updateOrCreate(
        ['title' => $s['title'], 'artist' => $s['artist']],
        $s
    );
}
    }
}