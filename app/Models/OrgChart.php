<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrgChart extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'title',
        'name',
        'nip',
        'image_url',
        'line_type',
        'order_no',
    ];

    public function parent()
    {
        return $this->belongsTo(OrgChart::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(OrgChart::class, 'parent_id')->orderBy('order_no', 'asc');
    }

    public function allChildren()
    {
        return $this->children()->with('allChildren');
    }
}
