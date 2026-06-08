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

{{-- ═══════════════════════════════════════════════════════
     VIDEO PICKER
══════════════════════════════════════════════════════════ --}}
<div class="section-block">
    <div class="autofinder-header">
        <div>
            <h2>🎬 Find & Pick Highlights</h2>
            <p class="text-muted" style="font-size:0.82rem;margin-top:0.2rem">
                Search across platforms for <strong>{{ $match->team1 }}</strong> vs <strong>{{ $match->team2 }}</strong>.
                Pick what you want to save.
            </p>
        </div>
        <button id="btnSearchAll" class="btn btn-primary btn-search" onclick="searchAll()">
            🔍 Search All Platforms
        </button>
    </div>

    {{-- Search query input --}}
    <div class="picker-search-row">
        <input type="text" id="pickerQuery"
            value="{{ $match->team1 }} {{ $match->team2 }}"
            class="form-input"
            placeholder="Search query…"
            onkeydown="if(event.key==='Enter') searchAll()">
        <select id="pickerLang" class="form-input" style="max-width:140px">
            <option value="en">🇬🇧 English</option>
            <option value="ar">🇩🇿 Arabic</option>
            <option value="fr">🇫🇷 French</option>
            <option value="es">🇪🇸 Spanish</option>
        </select>
    </div>

    {{-- Platform filter pills --}}
    <div class="picker-pills" id="pickerPills" style="display:none">
        <button class="ppill ppill-active" onclick="filterPlatform('all', this)">🌍 All</button>
        <button class="ppill" id="pill-scorebat"    onclick="filterPlatform('scorebat', this)">⚽ Scorebat</button>
        <button class="ppill" id="pill-youtube"     onclick="filterPlatform('youtube', this)">▶ YouTube</button>
        <button class="ppill" id="pill-dailymotion" onclick="filterPlatform('dailymotion', this)">🎬 Dailymotion</button>
    </div>

    {{-- Status bar --}}
    <div id="searchStatus" class="search-status" style="display:none"></div>

    {{-- Unified results panel --}}
    <div id="resultsPanel" style="display:none">
        <div class="results-toolbar">
            <span id="resultsCount" class="results-count"></span>
            <div style="display:flex;gap:0.5rem;align-items:center">
                <button class="btn btn-sm btn-secondary" onclick="selectAll()">Select All</button>
                <button class="btn btn-sm btn-secondary" onclick="selectNone()">None</button>
                <button id="btnSave" class="btn btn-sm btn-primary" onclick="saveSelected()" disabled>
                    💾 Save Selected
                </button>
            </div>
        </div>

        <div id="videoResults" class="video-results-grid"></div>

        <p id="noResults" class="no-results-msg" style="display:none">
            😔 No highlights found for <strong>{{ $match->team1 }}</strong> vs <strong>{{ $match->team2 }}</strong> on Scorebat.<br>
            <small>Try searching manually or check back after the match has been played.</small>
        </p>
    </div>
</div>

{{-- Videos list --}}
<div class="section-block">
    <h2>🎬 Saved Videos (<span id="videoCount">{{ $match->videos->count() }}</span>)</h2>

    <div id="savedVideosList">
    @if($match->videos->isEmpty())
        <p class="text-muted" id="noSavedMsg">No videos saved yet.</p>
    @else
        <div class="admin-table-wrapper">
            <table class="admin-table" id="savedTable">
                <thead>
                    <tr><th>Title</th><th>Platform</th><th>Language</th><th>Embed URL</th><th>Action</th></tr>
                </thead>
                <tbody>
                    @foreach($match->videos as $video)
                    <tr id="video-row-{{ $video->id }}">
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
</div>

{{-- Manual Add Video Form --}}
<div class="section-block">
    <details>
        <summary class="manual-add-toggle">➕ Add Video Manually</summary>
        <div class="form-card" style="margin-top:1rem">
            <form action="{{ route('admin.videos.store', $match->id) }}" method="POST">
                @csrf
                <div class="form-grid">
                    <div class="form-group span-2">
                        <label>Video Title *</label>
                        <input type="text" name="title" class="form-input @error('title') is-error @enderror"
                               value="{{ old('title') }}" placeholder="Full Highlights — {{ $match->team1 }} vs {{ $match->team2 }}" required>
                        @error('title')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group span-2">
                        <label>Embed URL *</label>
                        <input type="url" id="embed_url" name="embed_url" class="form-input @error('embed_url') is-error @enderror"
                               value="{{ old('embed_url') }}" placeholder="https://www.youtube.com/embed/VIDEO_ID" required>
                        <small class="form-hint">YouTube: watch?v=ID → embed/ID (auto-converted on blur)</small>
                        @error('embed_url')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>Commentary Language *</label>
                        <select name="language" class="form-input">
                            <option value="ar">🇸🇦 Arabic</option>
                            <option value="en" selected>🇬🇧 English</option>
                            <option value="fr">🇫🇷 French</option>
                            <option value="es">🇪🇸 Spanish</option>
                            <option value="pt">🇧🇷 Portuguese</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Platform</label>
                        <select name="platform" class="form-input">
                            <option value="youtube">YouTube</option>
                            <option value="dailymotion">Dailymotion</option>
                            <option value="twitter">Twitter/X</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Add Video</button>
                </div>
            </form>
        </div>
    </details>
</div>

@endsection

@push('styles')
<style>
/* ── Auto-finder styles ─────────────────────────── */
.autofinder-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 1rem;
}
.btn-search { min-width: 180px; }

.search-status {
    padding: 0.85rem 1.2rem;
    border-radius: 8px;
    margin-bottom: 1rem;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.6rem;
}
.search-status.loading { background:#1e293b; color:#94a3b8; }
.search-status.error   { background:#450a0a; border:1px solid #7f1d1d; color:#fca5a5; }
.search-status.success { background:#14532d; border:1px solid #166534; color:#86efac; }

.results-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 0.75rem;
    padding: 0.75rem 0;
    border-bottom: 1px solid #1e293b;
    margin-bottom: 1rem;
}
.results-count { font-size: 0.88rem; color:#94a3b8; font-weight:600; }

.video-results-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 0.85rem;
}

.result-card {
    background: #0f1929;
    border: 2px solid #1e293b;
    border-radius: 10px;
    overflow: hidden;
    transition: border-color 0.2s, background 0.2s;
    cursor: pointer;
    position: relative;
}
.result-card:hover { border-color: #3b82f6; }
.result-card.selected {
    border-color: #22c55e;
    background: #052e16;
}
.result-card.selected .card-check { opacity:1; }

.card-check {
    position: absolute;
    top: 8px; right: 8px;
    width: 22px; height: 22px;
    background: #22c55e;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.75rem; color: #fff;
    opacity: 0;
    transition: opacity 0.15s;
    font-weight: 700;
}

.card-body {
    padding: 0.75rem;
}
.card-lang {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #94a3b8;
    margin-bottom: 0.4rem;
}
.card-title {
    font-size: 0.82rem;
    color: #e2e8f0;
    line-height: 1.4;
    margin-bottom: 0.5rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.card-platform {
    font-size: 0.7rem;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.card-preview {
    position: relative;
    padding-bottom: 56.25%;
    height: 0;
    overflow: hidden;
    background: #0a0e1a;
}
.card-preview iframe {
    position: absolute;
    inset: 0; width: 100%; height: 100%;
    border: none;
}

.no-results-msg {
    text-align: center;
    padding: 2rem;
    color: #94a3b8;
    line-height: 1.8;
}
.alert-warning {
    background: #451a03;
    border: 1px solid #92400e;
    color: #fcd34d;
    padding: 0.75rem 1.25rem;
    border-radius: 8px;
    margin-bottom: 1rem;
    font-size: 0.87rem;
}
.manual-add-toggle {
    cursor: pointer;
    color: #94a3b8;
    font-size: 0.9rem;
    font-weight: 600;
    user-select: none;
    padding: 0.4rem 0;
}
.manual-add-toggle:hover { color: #e2e8f0; }

/* Picker UI */
.picker-search-row { display:flex; gap:.5rem; margin:.75rem 0; }
.picker-search-row .form-input { flex:1; }
.picker-pills { display:flex; gap:.5rem; flex-wrap:wrap; margin-bottom:1rem; }
.ppill { padding:.3rem .8rem; border-radius:20px; border:1px solid #374151; color:#6b7280; font-size:.8rem; cursor:pointer; background:transparent; font-family:inherit; transition:all .15s; }
.ppill:hover { background:#1f2937; color:#e2e8f0; }
.ppill.ppill-active { background:#1f2937; color:#f59e0b; border-color:#f59e0b; font-weight:700; }
.card-meta-row { display:flex; align-items:center; gap:.4rem; margin-bottom:.35rem; flex-wrap:wrap; }
.card-plat-badge { padding:.15rem .5rem; border-radius:4px; font-size:.7rem; font-weight:700; }
.card-lang-tag { font-size:.72rem; color:#6b7280; }

</style>
@endpush

@push('scripts')
<script>
const MATCH_ID  = {{ $match->id }};
const CSRF      = document.querySelector('meta[name="csrf-token"]').content;
const SEARCH_URL = `/admin/matches/${MATCH_ID}/scorebat-search`;
const SAVE_URL   = `/admin/matches/${MATCH_ID}/scorebat-save`;

let foundVideos  = [];
let selectedIdxs = new Set();

// ── Search ───────────────────────────────────────────────────────────
async function searchAll() {
    const btn   = document.getElementById('btnSearchAll');
    const query = document.getElementById('pickerQuery').value.trim();
    const lang  = document.getElementById('pickerLang').value;

    btn.disabled = true;
    btn.textContent = '⏳ Searching…';
    setStatus('loading', '⏳ Searching Scorebat, YouTube & Dailymotion…');
    document.getElementById('resultsPanel').style.display = 'none';
    document.getElementById('pickerPills').style.display = 'none';

    foundVideos  = [];
    selectedIdxs = new Set();
    currentFilter = 'all';

    // Run Scorebat+YouTube (server) and Dailymotion (browser) in parallel
    const [serverResults, dmResults] = await Promise.allSettled([
        fetchScorbat(),
        fetchDailymotion(query, lang),
    ]);

    if (serverResults.status === 'fulfilled') foundVideos.push(...serverResults.value);
    if (dmResults.status === 'fulfilled')     foundVideos.push(...dmResults.value);

    if (foundVideos.length === 0) {
        setStatus('error', '❌ No results found on any platform. Try different search terms.');
    } else {
        setStatus('success', `✅ Found ${foundVideos.length} video(s) across all platforms — pick what you want to save`);
        document.getElementById('pickerPills').style.display = 'flex';
        renderResults();
    }

    btn.disabled = false;
    btn.textContent = '🔍 Search Again';
}

// Fetch from Scorebat + YouTube via server
async function fetchScorbat() {
    const resp = await fetch(SEARCH_URL, {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
    });
    const data = await resp.json();
    return data.videos || [];
}

// Fetch from Dailymotion directly (browser-side)
async function fetchDailymotion(query, lang) {
    const params = new URLSearchParams({
        search: query,
        fields: 'id,title,embed_url,thumbnail_240_url,duration',
        limit: 5,
        sort: 'relevance',
        no_live: 1,
        language: lang,
    });
    const resp = await fetch('https://api.dailymotion.com/videos?' + params);
    if (!resp.ok) return [];
    const data = await resp.json();
    return (data.list || []).map(v => ({
        title:     v.title,
        embed_url: 'https://www.dailymotion.com/embed/video/' + v.id,
        platform:  'dailymotion',
        language:  lang,
        lang_flag: langFlag(lang),
        _thumb:    v.thumbnail_240_url || '',
        _duration: v.duration || 0,
    }));
}

// ── Platform filter ───────────────────────────────────────────────────
let currentFilter = 'all';

function filterPlatform(platform, btn) {
    currentFilter = platform;
    document.querySelectorAll('.ppill').forEach(b => b.classList.remove('ppill-active'));
    btn.classList.add('ppill-active');
    document.querySelectorAll('.result-card').forEach(c => {
        c.style.display = (platform === 'all' || c.dataset.platform === platform) ? '' : 'none';
    });
}

// ── Preview modal ─────────────────────────────────────────────────────
function previewVideo(e, embedUrl) {
    e.stopPropagation();
    const modal = document.getElementById('previewModal');
    document.getElementById('previewIframe').src = embedUrl + '?autoplay=1&rel=0';
    modal.style.display = 'flex';
}
function closePreview() {
    document.getElementById('previewModal').style.display = 'none';
    document.getElementById('previewIframe').src = '';
}

// ── Helper functions ──────────────────────────────────────────────────
function getThumb(v) {
    if (v._thumb) return v._thumb;
    if (v.platform === 'youtube') {
        const m = v.embed_url.match(/embed\/([a-zA-Z0-9_-]+)/);
        return m ? 'https://img.youtube.com/vi/' + m[1] + '/mqdefault.jpg' : '';
    }
    return '';
}
function platformColor(p) {
    return {youtube:'#ff4444',dailymotion:'#4d90fe',scorebat:'#22c55e',vimeo:'#1ab7ea'}[p] || '#9ca3af';
}
function platformBg(p) {
    return {youtube:'#1a0a0a',dailymotion:'#0a1020',scorebat:'#052e16',vimeo:'#0a1a1a'}[p] || '#1f2937';
}
function platformLabel(p) {
    return {youtube:'▶ YouTube',dailymotion:'🎬 Dailymotion',scorebat:'⚽ Scorebat',vimeo:'🎥 Vimeo'}[p] || '🌐 '+p;
}
function langFlag(l) {
    return {ar:'🇩🇿',fr:'🇫🇷',es:'🇪🇸',pt:'🇧🇷'}[l] || '🇬🇧';
}

// Keep runSearch as alias so DOMContentLoaded still works
function runSearch() { searchAll(); }

// ── Render results ───────────────────────────────────────────────────
function renderResults() {
    const panel      = document.getElementById('resultsPanel');
    const grid       = document.getElementById('videoResults');
    const noResults  = document.getElementById('noResults');
    const countSpan  = document.getElementById('resultsCount');

    panel.style.display = 'block';
    grid.innerHTML = '';

    if (foundVideos.length === 0) {
        noResults.style.display = 'block';
        countSpan.textContent = '0 found';
        return;
    }

    noResults.style.display = 'none';
    countSpan.textContent = `${foundVideos.length} video(s) found`;

    foundVideos.forEach((v, i) => {
        const card = document.createElement('div');
        card.className = 'result-card';
        card.id = `rc-${i}`;
        card.onclick = () => toggleCard(i);

        card.innerHTML = `
            <div class="card-check">✓</div>
            <div class="card-preview">
                <iframe src="${escHtml(v.embed_url)}"
                        allow="autoplay"
                        allowfullscreen
                        loading="lazy"></iframe>
            </div>
            <div class="card-body">
                <div class="card-lang">${escHtml(v.lang_flag)} ${escHtml(v.language.toUpperCase())}</div>
                <div class="card-title">${escHtml(v.title)}</div>
                <div class="card-platform">${escHtml(v.platform)}</div>
            </div>
        `;
        grid.appendChild(card);
    });

    updateSaveBtn();
}

function toggleCard(i) {
    if (selectedIdxs.has(i)) {
        selectedIdxs.delete(i);
        document.getElementById(`rc-${i}`).classList.remove('selected');
    } else {
        selectedIdxs.add(i);
        document.getElementById(`rc-${i}`).classList.add('selected');
    }
    updateSaveBtn();
}

function selectAll() {
    foundVideos.forEach((_, i) => {
        selectedIdxs.add(i);
        const el = document.getElementById(`rc-${i}`);
        if (el) el.classList.add('selected');
    });
    updateSaveBtn();
}

function selectNone() {
    selectedIdxs.clear();
    foundVideos.forEach((_, i) => {
        const el = document.getElementById(`rc-${i}`);
        if (el) el.classList.remove('selected');
    });
    updateSaveBtn();
}

function updateSaveBtn() {
    const btn = document.getElementById('btnSave');
    btn.disabled = selectedIdxs.size === 0;
    btn.textContent = selectedIdxs.size > 0
        ? `💾 Save ${selectedIdxs.size} Video(s)`
        : '💾 Save Selected';
}

// ── Save ─────────────────────────────────────────────────────────────
async function saveSelected() {
    if (selectedIdxs.size === 0) return;

    const btn = document.getElementById('btnSave');
    btn.disabled = true;
    btn.textContent = '⏳ Saving…';

    const videos = [...selectedIdxs].map(i => foundVideos[i]);

    try {
        const resp = await fetch(SAVE_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept':       'application/json',
                'X-CSRF-TOKEN': CSRF,
            },
            body: JSON.stringify({ videos }),
        });
        const data = await resp.json();

        if (data.success) {
            setStatus('success', '✅ ' + data.message);
            // Reload page to refresh saved videos list
            setTimeout(() => window.location.reload(), 1200);
        } else {
            setStatus('error', '❌ Save failed: ' + (data.message || 'Unknown error'));
            btn.disabled = false;
            btn.textContent = `💾 Save ${selectedIdxs.size} Video(s)`;
        }
    } catch (err) {
        setStatus('error', '❌ Network error: ' + err.message);
        btn.disabled = false;
        updateSaveBtn();
    }
}

// ── Helpers ──────────────────────────────────────────────────────────
function setStatus(type, msg) {
    const el = document.getElementById('searchStatus');
    el.style.display = 'flex';
    el.className = 'search-status ' + type;
    el.innerHTML = msg;
}

function escHtml(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

// ── Embed URL auto-converter (manual form) ───────────────────────────
const embedInput = document.getElementById('embed_url');
if (embedInput) {
    embedInput.addEventListener('blur', function () {
        let url = this.value.trim();
        const ytWatch = url.match(/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/);
        if (ytWatch) { this.value = 'https://www.youtube.com/embed/' + ytWatch[1]; return; }
        const ytShort = url.match(/youtu\.be\/([a-zA-Z0-9_-]+)/);
        if (ytShort)  { this.value = 'https://www.youtube.com/embed/' + ytShort[1]; return; }
        const dm = url.match(/dailymotion\.com\/video\/([a-zA-Z0-9]+)/);
        if (dm)       { this.value = 'https://www.dailymotion.com/embed/video/' + dm[1]; }
    });
}
</script>
@endpush
