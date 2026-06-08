@extends('layouts.admin')
@section('title', 'Manage — {{ $match->team1 }} vs {{ $match->team2 }}')

@section('content')

<div class="admin-page-header">
    <div>
        <h1>⚙️ {{ $match->team1 }} vs {{ $match->team2 }}</h1>
        <span class="badge {{ $match->status_badge_class }}">{{ $match->status_label }}</span>
        &nbsp;
        <span class="text-muted">{{ $match->match_date->format('d M Y, H:i') }}</span>
    </div>
    <div class="header-actions">
        <a href="{{ route('match.show', $match->id) }}" class="btn btn-secondary" target="_blank">View ↗</a>
        <a href="{{ route('admin.matches.edit', $match->id) }}" class="btn btn-warning">Edit Match</a>
        <a href="{{ route('admin.index') }}" class="btn btn-secondary">← All Matches</a>
    </div>
</div>

{{-- Match Info Summary --}}
<div class="info-card">
    <div class="info-grid">
        <div><strong>Stage:</strong> {{ $match->stage }}{{ $match->group_name ? ' — ' . $match->group_name : '' }}</div>
        <div><strong>Score:</strong>
            @if($match->score1 !== null)
                {{ $match->team1 }} {{ $match->score1 }} — {{ $match->score2 }} {{ $match->team2 }}
            @else
                <em>Not set</em>
            @endif
        </div>
    </div>
</div>

{{-- Videos list --}}
<div class="section-block">
    <h2>🎬 Videos ({{ $match->videos->count() }})</h2>

    @if($match->videos->isEmpty())
        <p class="text-muted">No videos added yet.</p>
    @else
        <div class="admin-table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr><th>Title</th><th>Platform</th><th>Language</th><th>Embed URL</th><th>Action</th></tr>
                </thead>
                <tbody>
                    @foreach($match->videos as $video)
                    <tr>
                        <td>{{ $video->title }}</td>
                        <td><span class="platform-badge">{{ $video->platform }}</span></td>
                        <td>{{ $video->language_label }}</td>
                        <td><code class="url-code">{{ Str::limit($video->embed_url, 50) }}</code></td>
                        <td>
                            <form action="{{ route('admin.videos.destroy', [$match->id, $video->id]) }}" method="POST"
                                  onsubmit="return confirm('Remove this video?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- Add Video Form --}}
<div class="section-block">
    <h2>➕ Add Video</h2>
    <div class="form-card">
        <form action="{{ route('admin.videos.store', $match->id) }}" method="POST">
            @csrf
            <div class="form-grid">
                <div class="form-group span-2">
                    <label for="title">Video Title *</label>
                    <input type="text" id="title" name="title" class="form-input @error('title') is-error @enderror"
                           value="{{ old('title') }}" placeholder="Full Highlights — Morocco vs Brazil" required>
                    @error('title')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group span-2">
                    <label for="embed_url">Embed URL *</label>
                    <input type="url" id="embed_url" name="embed_url" class="form-input @error('embed_url') is-error @enderror"
                           value="{{ old('embed_url') }}"
                           placeholder="https://www.youtube.com/embed/VIDEO_ID" required>
                    <small class="form-hint">
                        YouTube: https://youtube.com/watch?v=ID → https://youtube.com/embed/ID<br>
                        Dailymotion: https://dailymotion.com/video/ID → https://dailymotion.com/embed/video/ID
                    </small>
                    @error('embed_url')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label for="language">Commentary Language *</label>
                    <select id="language" name="language" class="form-input">
                        <option value="ar" {{ old('language') === 'ar' ? 'selected' : '' }}>🇸🇦 Arabic</option>
                        <option value="en" {{ old('language','en') === 'en' ? 'selected' : '' }}>🇬🇧 English</option>
                        <option value="fr" {{ old('language') === 'fr' ? 'selected' : '' }}>🇫🇷 French</option>
                        <option value="es" {{ old('language') === 'es' ? 'selected' : '' }}>🇪🇸 Spanish</option>
                        <option value="pt" {{ old('language') === 'pt' ? 'selected' : '' }}>🇧🇷 Portuguese</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="platform">Platform</label>
                    <select id="platform" name="platform" class="form-input">
                        <option value="youtube"    {{ old('platform','youtube') === 'youtube'    ? 'selected' : '' }}>YouTube</option>
                        <option value="dailymotion"{{ old('platform') === 'dailymotion' ? 'selected' : '' }}>Dailymotion</option>
                        <option value="twitter"    {{ old('platform') === 'twitter'    ? 'selected' : '' }}>Twitter/X</option>
                        <option value="other"      {{ old('platform') === 'other'      ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="duration_s">Duration (seconds)</label>
                    <input type="number" id="duration_s" name="duration_s" class="form-input"
                           value="{{ old('duration_s') }}" min="0" placeholder="300">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Add Video</button>
            </div>
        </form>
    </div>
</div>

@endsection
