<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentFiles extends Model
{
    /** @use HasFactory<\Database\Factories\CommentFilesFactory> */
    use HasFactory;

    protected $fillable = ["file", "comments_id"];

    public function comments()
    {
        return $this->belongsTo(Comments::class, 'comments_id');
    }
}
