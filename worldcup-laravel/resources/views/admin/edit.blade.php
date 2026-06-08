@extends('layouts.admin')
@section('title', 'Edit — {{ $match->team1 }} vs {{ $match->team2 }}')

@section('content')

<div class="admin-page-header">
    <h1>✏️ Edit Match</h1>
    <a href="{{ route('admin.matches.show', $match->id) }}" class="btn btn-secondary">← Back</a>
</div>

<div class="form-card">
    <form action="{{ route('admin.matches.update', $match->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <div class="form-group">
                <label>Team 1 *</label>
                <input type="text" name="team1" class="form-input" value="{{ old('team1', $match->team1) }}" required>
            </div>
            <div class="form-group">
                <label>Team 2 *</label>
                <input type="text" name="team2" class="form-input" value="{{ old('team2', $match->team2) }}" required>
            </div>
            <div class="form-group">
                <label>Team 1 Flag URL</label>
                <input type="url" name="team1_flag" class="form-input" value="{{ old('team1_flag', $match->team1_flag) }}">
            </div>
            <div class="form-group">
                <label>Team 2 Flag URL</label>
                <input type="url" name="team2_flag" class="form-input" value="{{ old('team2_flag', $match->team2_flag) }}">
            </div>
            <div class="form-group">
                <label>Score — Team 1</label>
                <input type="number" name="score1" class="form-input" value="{{ old('score1', $match->score1) }}" min="0">
            </div>
            <div class="form-group">
                <label>Score — Team 2</label>
                <input type="number" name="score2" class="form-input" value="{{ old('score2', $match->score2) }}" min="0">
            </div>
            <div class="form-group">
                <label>Match Date & Time *</label>
                <input type="datetime-local" name="match_date" class="form-input"
                       value="{{ old('match_date', $match->match_date->format('Y-m-d\TH:i')) }}" required>
            </div>
            <div class="form-group">
                <label>Status *</label>
                <select name="status" class="form-input">
                    <option value="upcoming" {{ old('status', $match->status) === 'upcoming' ? 'selected' : '' }}>🕐 Upcoming</option>
                    <option value="live"     {{ old('status', $match->status) === 'live'     ? 'selected' : '' }}>🔴 Live</option>
                    <option value="finished" {{ old('status', $match->status) === 'finished' ? 'selected' : '' }}>✅ Finished</option>
                </select>
            </div>
            <div class="form-group">
                <label>Stage *</label>
                <input type="text" name="stage" class="form-input" value="{{ old('stage', $match->stage) }}" required>
            </div>
            <div class="form-group">
                <label>Group</label>
                <input type="text" name="group_name" class="form-input" value="{{ old('group_name', $match->group_name) }}">
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="{{ route('admin.matches.show', $match->id) }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

@endsection
