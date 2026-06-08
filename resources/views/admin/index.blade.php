@extends('layouts.admin')
@section('title', 'Admin — Match List')

@section('content')

<div class="admin-page-header">
    <h1>📋 All Matches</h1>
    <a href="{{ route('admin.matches.create') }}" class="btn btn-primary">+ Add Match</a>
</div>

@if($matches->isEmpty())
    <div class="empty-state">
        <span class="empty-icon">🏟️</span>
        <p>No matches yet. <a href="{{ route('admin.matches.create') }}">Add the first one!</a></p>
    </div>
@else
    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Match</th>
                    <th>Date</th>
                    <th>Stage</th>
                    <th>Status</th>
                    <th>Score</th>
                    <th>Videos</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($matches as $match)
                <tr>
                    <td class="match-cell">
                        <strong>{{ $match->team1 }}</strong>
                        <span class="vs-mini">vs</span>
                        <strong>{{ $match->team2 }}</strong>
                    </td>
                    <td>{{ $match->match_date->format('d M Y H:i') }}</td>
                    <td>
                        {{ $match->stage }}
                        @if($match->group_name)
                            <br><small class="text-muted">{{ $match->group_name }}</small>
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $match->status_badge_class }}">{{ $match->status_label }}</span>
                    </td>
                    <td>
                        @if($match->score1 !== null)
                            <strong>{{ $match->score1 }} — {{ $match->score2 }}</strong>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="video-count-badge">🎬 {{ $match->videos_count }}</span>
                    </td>
                    <td class="actions-cell">
                        <a href="{{ route('admin.matches.show', $match->id) }}" class="btn btn-sm btn-secondary">Manage</a>
                        <a href="{{ route('admin.matches.edit', $match->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.matches.destroy', $match->id) }}" method="POST" style="display:inline"
                              onsubmit="return confirm('Delete this match and all its videos?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

@endsection
