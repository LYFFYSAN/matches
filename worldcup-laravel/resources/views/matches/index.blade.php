@extends('layouts.app')

@section('title', '⚽ World Cup 2026 — Match Highlights')

@section('content')

<div class="hero">
    <h1>⚽ World Cup 2026</h1>
    <p>Watch highlights — score hidden until you're ready</p>
</div>

{{-- Filter Bar --}}
<div class="filter-bar">
    <form method="GET" action="{{ route('home') }}" class="filter-form">
        <div class="filter-group">
            <label>Status</label>
            <div class="filter-pills">
                <a href="{{ route('home', array_merge(request()->except('status'))) }}"
                   class="pill {{ !request('status') ? 'active' : '' }}">All</a>
                <a href="{{ route('home', array_merge(request()->all(), ['status'=>'live'])) }}"
                   class="pill pill-live {{ request('status')==='live' ? 'active' : '' }}">🔴 Live</a>
                <a href="{{ route('home', array_merge(request()->all(), ['status'=>'finished'])) }}"
                   class="pill pill-finished {{ request('status')==='finished' ? 'active' : '' }}">✅ Finished</a>
                <a href="{{ route('home', array_merge(request()->all(), ['status'=>'upcoming'])) }}"
                   class="pill pill-upcoming {{ request('status')==='upcoming' ? 'active' : '' }}">🕐 Upcoming</a>
            </div>
        </div>

        @if($stages->isNotEmpty())
        <div class="filter-group">
            <label for="stage">Stage</label>
            <select name="stage" id="stage" onchange="this.form.submit()" class="filter-select">
                <option value="">All Stages</option>
                @foreach($stages as $stage)
                    <option value="{{ $stage }}" {{ request('stage') === $stage ? 'selected' : '' }}>{{ $stage }}</option>
                @endforeach
            </select>
        </div>
        @endif
    </form>
</div>

{{-- Match Grid --}}
@if($matches->isEmpty())
    <div class="empty-state">
        <span class="empty-icon">🏟️</span>
        <p>No matches found. Check back soon!</p>
    </div>
@else
    <div class="matches-grid">
        @foreach($matches as $match)
            <a href="{{ route('match.show', $match->id) }}" class="match-card">

                {{-- Stage Label --}}
                <div class="match-stage">
                    {{ $match->stage }}{{ $match->group_name ? ' — ' . $match->group_name : '' }}
                </div>

                {{-- Teams (NO SCORE) --}}
                <div class="match-teams">
                    <div class="team">
                        @if($match->team1_flag)
                            <img src="{{ $match->team1_flag }}" alt="{{ $match->team1 }} flag" class="team-flag">
                        @else
                            <div class="team-flag-placeholder">🏳️</div>
                        @endif
                        <span class="team-name">{{ $match->team1 }}</span>
                    </div>

                    <div class="match-vs">
                        <span class="vs-text">vs</span>
                        <span class="match-date-small">
                            {{ $match->match_date->format('d M Y') }}<br>
                            {{ $match->match_date->format('H:i') }}
                        </span>
                    </div>

                    <div class="team">
                        @if($match->team2_flag)
                            <img src="{{ $match->team2_flag }}" alt="{{ $match->team2 }} flag" class="team-flag">
                        @else
                            <div class="team-flag-placeholder">🏳️</div>
                        @endif
                        <span class="team-name">{{ $match->team2 }}</span>
                    </div>
                </div>

                {{-- Status Badge --}}
                <div class="match-footer">
                    <span class="badge {{ $match->status_badge_class }}">
                        {{ $match->status_label }}
                    </span>

                    @if($match->videos_count > 0)
                        <span class="video-count">
                            🎬 {{ $match->videos_count }} highlight{{ $match->videos_count > 1 ? 's' : '' }}
                        </span>
                    @endif
                </div>

            </a>
        @endforeach
    </div>
@endif

@endsection
