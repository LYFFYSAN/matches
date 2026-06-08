<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FootballMatch extends Model
{
    use HasFactory;

    protected $table = 'matches';

    protected $fillable = [
        'team1',
        'team2',
        'team1_flag',
        'team2_flag',
        'score1',
        'score2',
        'match_date',
        'stage',
        'group_name',
        'status',
    ];

    protected $casts = [
        'match_date' => 'datetime',
        'score1'     => 'integer',
        'score2'     => 'integer',
    ];

    /**
     * Videos associated with this match.
     */
    public function videos()
    {
        return $this->hasMany(Video::class, 'match_id');
    }

    /**
     * How many videos are linked.
     */
    public function getVideoCountAttribute(): int
    {
        return $this->videos()->count();
    }

    /**
     * Badge class for Blade templates.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'live'     => 'badge-live',
            'finished' => 'badge-finished',
            default    => 'badge-upcoming',
        };
    }

    /**
     * Readable status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'live'     => '🔴 LIVE',
            'finished' => '✅ Finished',
            default    => '🕐 Upcoming',
        };
    }
}
