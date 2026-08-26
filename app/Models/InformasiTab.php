<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformasiTab extends Model
{
    use HasFactory;

    protected $table = 'informasi_tabs';

    protected $fillable = [
        'name', 'slug', 'icon', 'order', 'is_active', 'filter_type', 'filter_value',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
