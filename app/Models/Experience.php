<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $fillable = [
        'company', 'role', 'start_date', 'end_date',
        'is_current', 'description', 'is_published', 'sort_order',
    ];

    protected $casts = [
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
        'is_current' => 'boolean',
        'is_published' => 'boolean',
    ];

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderByDesc('is_current')
            ->orderByDesc('start_date')
            ->orderBy('sort_order');
    }

    public function getPeriodAttribute(): string
    {
        $start = $this->start_date?->format('M Y');
        $end = $this->is_current ? 'Present' : $this->end_date?->format('M Y');

        return trim(implode(' - ', array_filter([$start, $end]))) ?: 'Date not set';
    }
}
