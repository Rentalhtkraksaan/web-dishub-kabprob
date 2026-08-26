<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'type', 'category', 'tahun',
        'file_path', 'file_url', 'file_zip_path', 'file_zip_url',
        'download_count', 'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getPdfFullUrlAttribute()
    {
        $url = $this->file_url ?: $this->file_path;
        if (!$url) return null;
        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return $url;
        }
        return asset(ltrim($url, '/'));
    }

    public function getZipFullUrlAttribute()
    {
        $url = $this->file_zip_url ?: $this->file_zip_path;
        if (!$url) return null;
        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return $url;
        }
        return asset(ltrim($url, '/'));
    }
}
