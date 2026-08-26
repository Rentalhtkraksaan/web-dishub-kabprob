<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryAlbum extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'cover_image',
        'photos',
        'created_by',
    ];

    protected $casts = [
        'photos' => 'array',
    ];

    public function getCoverImageFullAttribute()
    {
        if (empty($this->cover_image)) {
            return asset('images/default-album.jpg');
        }
        if (\Illuminate\Support\Str::startsWith($this->cover_image, ['http://', 'https://'])) {
            return $this->cover_image;
        }
        return asset(ltrim($this->cover_image, '/'));
    }

    public function getPhotoUrlsAttribute()
    {
        $rawPhotos = is_array($this->photos) && count($this->photos) > 0 ? $this->photos : [$this->cover_image];
        $fullUrls = [];
        foreach ($rawPhotos as $photo) {
            if (empty($photo)) continue;
            if (\Illuminate\Support\Str::startsWith($photo, ['http://', 'https://'])) {
                $fullUrls[] = $photo;
            } else {
                $fullUrls[] = asset(ltrim($photo, '/'));
            }
        }
        return count($fullUrls) > 0 ? $fullUrls : [$this->cover_image_full];
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
