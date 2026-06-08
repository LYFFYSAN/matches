@extends('layouts.app')

@section('title', '{{ $match->team1 }} vs {{ $match->team2 }} — World Cup 2026')

@section('content')

<div class="match-detail-wrapper">

    {{-- Back --}}
    <a href="{{ route('home') }}" class="back-link">← Back to matches</a>

    {{-- Match Header --}}
    <div class="match-header-detail">
        <div class="match-stage-detail">
            {{ $match->stage }}{{ $match->group_name ? ' — ' . $match->group_name : '' }}
        </div>

        <div class="match-teams-detail">
            <div class="team-detail">
                @if($match->team1_flag)
                    <img src="{{ $match->team1_flag }}" alt="{{ $match->team1 }}" class="team-flag-lg">
                @endif
                <h2>{{ $match->team1 }}</h2>
            </div>

            <div class="match-vs-detail">
                <span class="vs-large">vs</span>
                <span class="match-date-detail">{{ $match->match_date->format('d M Y, H:i') }}</span>
                <span class="badge {{ $match->status_badge_class }}">{{ $match->status_label }}</span>
            </div>

            <div class="team-detail">
                @if($match->team2_flag)
                    <img src="{{ $match->team2_flag }}" alt="{{ $match->team2 }}" class="team-flag-lg">
                @endif
                <h2>{{ $match->team2 }}</h2>
            </div>
        </div>
    </div>

    {{-- ===== SPOILER SHIELD ===== --}}
    <div class="spoiler-shield" id="spoilerShield">
        @if($match->status === 'finished' && $match->score1 !== null)

            <div class="score-hidden" id="scoreHidden">
                <button class="btn-reveal" onclick="revealScore()">
                    👁️ Reveal Final Score
                </button>
                <p class="spoiler-hint">Score hidden — click when you're ready</p>
            </div>

            <div class="score-revealed" id="scoreRevealed" style="display:none">
                <div class="scoreboard">
                    <span class="score-number">{{ $match->score1 }}</span>
                    <span class="score-dash">—</span>
                    <span class="score-number">{{ $match->score2 }}</span>
                </div>
                @if($match->score1 > $match->score2)
                    <p class="winner-label">🏆 {{ $match->team1 }} wins!</p>
                @elseif($match->score2 > $match->score1)
                    <p class="winner-label">🏆 {{ $match->team2 }} wins!</p>
                @else
                    <p class="winner-label">🤝 Draw!</p>
                @endif
                <button class="btn-hide-score" onclick="hideScore()">
                    🙈 Hide score
                </button>
            </div>

        @elseif($match->status === 'live')
            <div class="match-live-notice">🔴 This match is currently LIVE!</div>
        @else
            <div class="match-upcoming-notice">🕐 Match hasn't started yet.</div>
        @endif
    </div>

    {{-- ===== HIGHLIGHTS / VIDEO PLAYER ===== --}}
    @if($match->videos->isNotEmpty())

        <section class="highlights-section">
            <div class="highlights-header">
                <h2>🎬 Match Highlights</h2>

                {{-- Language Filter --}}
                <div class="lang-selector" id="langSelector">
                    <button class="lang-pill active" data-lang="all" onclick="filterLang('all', this)">
                        🌍 All
                    </button>
                    @foreach($languages as $lang)
                        <button class="lang-pill" data-lang="{{ $lang }}" onclick="filterLang('{{ $lang }}', this)">
                            @switch($lang)
                                @case('ar') 🇸🇦 Arabic @break
                                @case('en') 🇬🇧 English @break
                                @case('fr') 🇫🇷 French @break
                                @case('es') 🇪🇸 Spanish @break
                                @case('pt') 🇧🇷 Portuguese @break
                                @default {{ strtoupper($lang) }}
                            @endswitch
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="video-grid" id="videoGrid">
                @foreach($match->videos as $video)
                    <div class="video-card" data-lang="{{ $video->language }}">
                        <div class="video-title">
                            {{ $video->title }}
                            <span class="video-lang-tag">
                                @switch($video->language)
                                    @case('ar') 🇸🇦 @break
                                    @case('en') 🇬🇧 @break
                                    @case('fr') 🇫🇷 @break
                                    @case('es') 🇪🇸 @break
                                    @case('pt') 🇧🇷 @break
                                @endswitch
                            </span>
                        </div>
                        <div class="video-embed-wrapper">
                            <iframe
                                src="{{ $video->embed_url }}"
                                title="{{ $video->title }}"
                                allowfullscreen
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                loading="lazy"
                            ></iframe>
                        </div>
                    </div>
                @endforeach
            </div>

            <p class="no-videos-msg" id="noVideosMsg" style="display:none">
                No highlights available in this language yet.
            </p>
        </section>

    @else
        <div class="no-highlights">
            <span>🎬</span>
            <p>No highlights available yet for this match.</p>
        </div>
    @endif

</div>

@endsection

@push('scripts')
<script>
    // Spoiler shield reveal
    function revealScore() {
        document.getElementById('scoreHidden').style.display = 'none';
        document.getElementById('scoreRevealed').style.display = 'block';
    }

    function hideScore() {
        document.getElementById('scoreRevealed').style.display = 'none';
        document.getElementById('scoreHidden').style.display = 'flex';
    }

    // Language filter
    function filterLang(lang, btn) {
        // Update active pill
        document.querySelectorAll('.lang-pill').forEach(p => p.classList.remove('active'));
        btn.classList.add('active');

        // Filter video cards
        const cards = document.querySelectorAll('.video-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const match = lang === 'all' || card.dataset.lang === lang;
            card.style.display = match ? 'block' : 'none';
            if (match) visibleCount++;
        });

        document.getElementById('noVideosMsg').style.display = visibleCount === 0 ? 'block' : 'none';
    }
</script>
@endpush
