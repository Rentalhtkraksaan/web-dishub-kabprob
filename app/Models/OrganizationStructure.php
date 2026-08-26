<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationStructure extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'official_name',
        'nip',
        'level_type',
        'line_type',
        'parent_id',
        'order',
        'is_active',
    ];

    public function children()
    {
        return $this->hasMany(OrganizationStructure::class, 'parent_id')->where('is_active', true)->orderBy('order', 'asc');
    }

    public function parent()
    {
        return $this->belongsTo(OrganizationStructure::class, 'parent_id');
    }
}
