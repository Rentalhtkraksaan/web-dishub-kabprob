<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'content', 'image_url', 'pdf_url', 'is_published'];

    public function getPdfFullUrlAttribute()
    {
        if (!$this->pdf_url) return null;
        if (str_starts_with($this->pdf_url, 'http://') || str_starts_with($this->pdf_url, 'https://')) {
            return $this->pdf_url;
        }
        return asset(ltrim($this->pdf_url, '/'));
    }

    public function getImageUrlFullAttribute()
    {
        if (!$this->image_url) return null;
        if (str_starts_with($this->image_url, 'http://') || str_starts_with($this->image_url, 'https://')) {
            return $this->image_url;
        }
        return asset(ltrim($this->image_url, '/'));
    }
}
