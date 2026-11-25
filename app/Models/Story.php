<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    protected $fillable = ['title', 'type', 'genre', 'synopsis', 'content', 'cover_path'];
}