<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'video_url',
        'thumbnail_url',
        'description',
        'published_at',
        'created_by',
    ];

    protected $casts = [
        'published_at' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Helper to get YouTube ID from standard YouTube link
     */
    public function getYoutubeIdAttribute()
    {
        $url = $this->video_url ?? '';
        if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=|shorts\/))([\w-]{11})/', $url, $matches)) {
            return $matches[1];
        }
        return null;
    }

    /**
     * Helper to get YouTube Embed URL from standard YouTube link
     */
    public function getEmbedUrlAttribute()
    {
        $id = $this->youtube_id;
        if ($id) {
            return 'https://www.youtube.com/embed/' . $id . '?autoplay=1&rel=0';
        }
        return $this->video_url;
    }

    /**
     * Helper to get effective thumbnail (fallback to YouTube HQ thumbnail)
     */
    public function getEffectiveThumbnailAttribute()
    {
        if (!empty($this->thumbnail_url)) {
            return $this->thumbnail_url;
        }
        if ($this->youtube_id) {
            return 'https://img.youtube.com/vi/' . $this->youtube_id . '/hqdefault.jpg';
        }
        return 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?w=600';
    }
}
