@extends('layouts.admin')
@section('title', 'Admin — Add Match')

@section('content')

<div class="admin-page-header">
    <h1>➕ Add New Match</h1>
    <a href="{{ route('admin.index') }}" class="btn btn-secondary">← Back</a>
</div>

<div class="form-card">
    <form action="{{ route('admin.matches.store') }}" method="POST">
        @csrf

        <div class="form-grid">
            <div class="form-group">
                <label for="team1">Team 1 *</label>
                <input type="text" id="team1" name="team1" class="form-input @error('team1') is-error @enderror"
                       value="{{ old('team1') }}" placeholder="e.g. Morocco" required>
                @error('team1')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="team2">Team 2 *</label>
                <input type="text" id="team2" name="team2" class="form-input @error('team2') is-error @enderror"
                       value="{{ old('team2') }}" placeholder="e.g. Brazil" required>
                @error('team2')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="team1_flag">Team 1 Flag URL</label>
                <input type="url" id="team1_flag" name="team1_flag" class="form-input"
                       value="{{ old('team1_flag') }}" placeholder="https://flagcdn.com/w80/ma.png">
                <small class="form-hint">Use flagcdn.com/w80/{country-code}.png</small>
            </div>

            <div class="form-group">
                <label for="team2_flag">Team 2 Flag URL</label>
                <input type="url" id="team2_flag" name="team2_flag" class="form-input"
                       value="{{ old('team2_flag') }}" placeholder="https://flagcdn.com/w80/br.png">
            </div>

            <div class="form-group">
                <label for="score1">Score — Team 1</label>
                <input type="number" id="score1" name="score1" class="form-input"
                       value="{{ old('score1') }}" min="0" placeholder="Leave blank if not played">
            </div>

            <div class="form-group">
                <label for="score2">Score — Team 2</label>
                <input type="number" id="score2" name="score2" class="form-input"
                       value="{{ old('score2') }}" min="0">
            </div>

            <div class="form-group">
                <label for="match_date">Match Date & Time *</label>
                <input type="datetime-local" id="match_date" name="match_date"
                       class="form-input @error('match_date') is-error @enderror"
                       value="{{ old('match_date') }}" required>
                @error('match_date')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="status">Status *</label>
                <select id="status" name="status" class="form-input">
                    <option value="upcoming" {{ old('status','upcoming') === 'upcoming' ? 'selected' : '' }}>🕐 Upcoming</option>
                    <option value="live"     {{ old('status') === 'live'     ? 'selected' : '' }}>🔴 Live</option>
                    <option value="finished" {{ old('status') === 'finished' ? 'selected' : '' }}>✅ Finished</option>
                </select>
            </div>

            <div class="form-group">
                <label for="stage">Stage *</label>
                <input type="text" id="stage" name="stage" class="form-input @error('stage') is-error @enderror"
                       value="{{ old('stage') }}" placeholder="Group Stage / Quarter Final / Final…" required>
                @error('stage')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="group_name">Group</label>
                <input type="text" id="group_name" name="group_name" class="form-input"
                       value="{{ old('group_name') }}" placeholder="Group A (leave blank for knockouts)">
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Create Match</button>
            <a href="{{ route('admin.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

@endsection
