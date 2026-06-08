<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_id',
        'title',
        'embed_url',
        'platform',
        'language',
        'duration_s',
    ];

    protected $casts = [
        'duration_s' => 'integer',
    ];

    /**
     * The match this video belongs to.
     */
    public function match()
    {
        return $this->belongsTo(FootballMatch::class, 'match_id');
    }

    /**
     * Human-readable language label.
     */
    public function getLanguageLabelAttribute(): string
    {
        return match ($this->language) {
            'ar' => '🇸🇦 Arabic',
            'en' => '🇬🇧 English',
            'fr' => '🇫🇷 French',
            'es' => '🇪🇸 Spanish',
            'pt' => '🇧🇷 Portuguese',
            default => strtoupper($this->language),
        };
    }
}
