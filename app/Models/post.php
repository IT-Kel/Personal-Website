<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'content', 'media'];

    /**
     * Get the media attribute as a URL.
     */
    public function getMediaAttribute($value)
    {
        return $value ? Storage::url($value) : null;
    }

    /**
     * Store uploaded media file.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return string|null
     */
    public static function uploadMedia($file)
    {
        if ($file) {
            return $file->store('media', 'public');
        }
        return null;
    }

    /**
     * Define the relationship with the User model.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
{
    return $this->hasMany(Comment::class);
}


// In Post model
public function usersWhoLiked()
{
    return $this->belongsToMany(User::class, 'post_user_like')->withTimestamps();
}


}