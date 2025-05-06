<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $table = 'gallery'; // Define the table name

    protected $fillable = [
        'user_id',
        'content',
        'media'
    ];

    public $timestamps = true; // Enables created_at & updated_at

    // Relationship: A gallery item belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
