@extends('layouts.app')

@section('title', $match->team1 . ' vs ' . $match->team2 . ' — World Cup 2026')

@section('content')

<div class="match-detail-wrapper">

    <a href="{{ route('home') }}" class="back-link">← Back to matches</a>

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

    {{-- SPOILER SHIELD --}}
    <div class="spoiler-shield" id="spoilerShield">
        @if($match->status === 'finished' && $match->score1 !== null)
            <div class="score-hidden" id="scoreHidden">
                <button class="btn-reveal" onclick="revealScore()">👁️ Reveal Final Score</button>
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
                <button class="btn-hide-score" onclick="hideScore()">🙈 Hide score</button>
            </div>
        @elseif($match->status === 'live')
            <div class="match-live-notice">🔴 This match is currently LIVE!</div>
        @else
            <div class="match-upcoming-notice">🕐 Match hasn't started yet.</div>
        @endif
    </div>

    {{-- HIGHLIGHTS --}}
    @if($match->videos->isNotEmpty())
    <section class="highlights-section">

        <div class="highlights-header">
            <h2>🎬 Match Highlights</h2>
            <div class="lang-selector">
                <button class="lang-pill active" data-lang="all" onclick="filterLang('all',this)">🌍 All</button>
                @foreach($languages as $lang)
                    <button class="lang-pill" data-lang="{{ $lang }}" onclick="filterLang('{{ $lang }}',this)">
                        @switch($lang)
                            @case('ar') 🇩🇿 Arabic @break
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
            @foreach($match->videos as $index => $video)
            @php
                $isYoutube = $video->platform === 'youtube';
                $ytId = $isYoutube ? basename(parse_url($video->embed_url, PHP_URL_PATH)) : null;
                $thumb = $ytId ? "https://img.youtube.com/vi/{$ytId}/mqdefault.jpg" : null;
            @endphp
            <div class="video-card" data-lang="{{ $video->language }}" data-index="{{ $index }}">

                <div class="yt-facade" id="facade-{{ $index }}" onclick="loadVideo({{ $index }}, '{{ addslashes($video->embed_url) }}')">
                    @if($thumb)
                    <img src="{{ $thumb }}" alt="Highlight #{{ $index + 1 }}" class="yt-facade-img">
                    @else
                    <div class="yt-facade-img yt-facade-blank"></div>
                    @endif
                    <div class="yt-facade-overlay"></div>
                    <div class="yt-facade-label">Highlight #{{ $index + 1 }}</div>
                    <div class="yt-play-btn">&#9654;</div>
                </div>

                <div class="player-wrap" id="player-{{ $index }}" style="display:none">
                    <div class="player-inner">
                        <iframe
                            id="iframe-{{ $index }}"
                            src=""
                            title="Highlight #{{ $index + 1 }}"
                            allowfullscreen
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            style="pointer-events:none"
                        ></iframe>
                        {{-- Permanent blurred strip that covers the YouTube title bar at the top.
                             YouTube's title occupies roughly the top 13% of the player height.
                             This div never disappears — it always hides the title text. --}}
                        <div class="yt-title-blur" id="title-blur-{{ $index }}"></div>
                        {{-- Blocker sits over iframe — prevents hover UI/title showing.
                             User clicks it once to "unlock" full interaction. --}}
                        <div class="iframe-blocker" id="blocker-{{ $index }}">
                        </div>
                    </div>
                    <button class="btn-hide-video" onclick="hideVideo({{ $index }})">✕ Hide</button>
                </div>

                <div class="video-meta">
                    <div class="video-badges">
                        <span class="platform-badge platform-{{ $video->platform }}">
                            @switch($video->platform)
                                @case('youtube') ▶ YouTube @break
                                @case('dailymotion') 🎬 Dailymotion @break
                                @case('scorebat') ⚽ Scorebat @break
                                @case('vimeo') 🎥 Vimeo @break
                                @default 🌐 {{ ucfirst($video->platform) }}
                            @endswitch
                        </span>
                        <span class="lang-badge">
                            @switch($video->language)
                                @case('ar') 🇩🇿 AR @break
                                @case('en') 🇬🇧 EN @break
                                @case('fr') 🇫🇷 FR @break
                                @case('es') 🇪🇸 ES @break
                                @case('pt') 🇧🇷 PT @break
                                @default {{ strtoupper($video->language) }}
                            @endswitch
                        </span>
                        @if($video->duration_s)
                            <span class="duration-badge">⏱ {{ gmdate('i:s', $video->duration_s) }}</span>
                        @endif
                    </div>
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
    function revealScore() {
        document.getElementById('scoreHidden').style.display = 'none';
        document.getElementById('scoreRevealed').style.display = 'block';
    }
    function hideScore() {
        document.getElementById('scoreRevealed').style.display = 'none';
        document.getElementById('scoreHidden').style.display = 'flex';
    }

    // Store player refs
    var players = {};

    function loadVideo(index, embedUrl) {
        // Capture thumbnail BEFORE hiding facade
        var facadeEl  = document.getElementById('facade-' + index);
        var facadeImg = facadeEl ? facadeEl.querySelector('img') : null;
        var thumbUrl  = facadeImg ? facadeImg.src : '';

        facadeEl.style.display = 'none';
        document.getElementById('player-' + index).style.display = 'block';

        var isYT = embedUrl.includes('youtube.com') || embedUrl.includes('youtu.be');
        var isDM = embedUrl.includes('dailymotion.com');
        var sep  = embedUrl.includes('?') ? '&' : '?';

        players[index] = { type: isYT ? 'yt' : isDM ? 'dm' : 'other', playing: true, muted: true, thumb: thumbUrl };

        if (isYT) {
            var ytId = embedUrl.split('/embed/')[1].split('?')[0];
            players[index].ytId = ytId;

            if (typeof YT !== 'undefined' && YT.Player) {
                createYTPlayer(index, ytId, thumbUrl);
            } else {
                if (!document.getElementById('yt-api-script')) {
                    var s = document.createElement('script');
                    s.id  = 'yt-api-script';
                    s.src = 'https://www.youtube.com/iframe_api';
                    document.head.appendChild(s);
                }
                window['ytPending'] = window['ytPending'] || [];
                window['ytPending'].push({ index: index, ytId: ytId, thumb: thumbUrl });
            }
        } else if (isDM) {
            // Dailymotion — use their postMessage API
            var dmId = embedUrl.split('/embed/video/')[1]
                        ? embedUrl.split('/embed/video/')[1].split('?')[0]
                        : '';
            players[index].dmId = dmId;
            var dmSrc = 'https://www.dailymotion.com/embed/video/' + dmId +
                        '?autoplay=1&mute=1&controls=0&queue-enable=0';
            document.getElementById('iframe-' + index).src = dmSrc;
            players[index].type = 'dm';
            setTimeout(function() { injectButtons(index); }, 500);
        } else {
            document.getElementById('iframe-' + index).src =
                embedUrl + sep + 'autoplay=1&mute=1';
            injectButtons(index);
        }
    }

    // Called by YouTube API when ready
    window.onYouTubeIframeAPIReady = function() {
        (window['ytPending'] || []).forEach(function(p) {
            createYTPlayer(p.index, p.ytId, p.thumb || '');
        });
        window['ytPending'] = [];
    };

    function createYTPlayer(index, ytId) {
        var iframeEl = document.getElementById('iframe-' + index);
        var divId    = 'yt-player-' + index;
        var div      = document.createElement('div');
        div.id       = divId;
        div.style.cssText = 'position:absolute;inset:0;width:100%;height:100%;';
        iframeEl.replaceWith(div);

        // Startup cover — hides title while YouTube loads (~3s)
        var startCover = document.createElement('div');
        startCover.id  = 'start-cover-' + index;
        // Use facade thumbnail as blurred start cover — looks nicer than black
        var facadeImg = document.getElementById('facade-' + index);
        var thumbUrl  = facadeImg ? (facadeImg.querySelector('img') || {}).src || '' : '';
        startCover.style.cssText = 'position:absolute;inset:0;z-index:9;pointer-events:none;transition:opacity .6s;overflow:hidden;';
        startCover.innerHTML = thumbUrl
            ? '<div style="position:absolute;inset:-20px;background:url('+JSON.stringify(thumbUrl)+') center/cover;filter:blur(14px) brightness(0.35);transform:scale(1.1)"></div>'
            : '<div style="position:absolute;inset:0;background:#000"></div>';
        document.getElementById('player-' + index).querySelector('.player-inner').appendChild(startCover);

        players[index].player = new YT.Player(divId, {
            videoId: ytId,
            playerVars: { autoplay: 1, mute: 1, rel: 0, modestbranding: 1, controls: 0, enablejsapi: 1 },
            events: {
                onReady: function(e) {
                    e.target.playVideo();
                    // Fade out startup cover after 3.5s — title gone by then
                    setTimeout(function() {
                        var sc = document.getElementById('start-cover-' + index);
                        if (sc) { sc.style.opacity = '0'; setTimeout(function() { sc.remove(); }, 600); }
                    }, 3500);
                    injectButtons(index);
                },
                onStateChange: function(e) {
                    var pauseBtn = document.getElementById('pause-' + index);
                    if (!pauseBtn) return;
                    if (e.data === YT.PlayerState.PLAYING) {
                        pauseBtn.innerHTML = '⏸ Pause';
                        players[index].playing = true;
                    } else if (e.data === YT.PlayerState.PAUSED) {
                        pauseBtn.innerHTML = '▶ Play';
                        players[index].playing = false;
                    }
                }
            }
        });
    }

    function injectButtons(index) {
        var blocker = document.getElementById('blocker-' + index);
        if (!blocker || document.getElementById('unmute-' + index)) return;

        // Unmute button
        var btn = document.createElement('div');
        btn.id = 'unmute-' + index;
        btn.className = 'unmute-btn';
        btn.innerHTML = '🔇 Unmute';
        btn.onclick = function(e) {
            e.stopPropagation();
            var p = players[index];
            if (p && p.player && p.player.unMute) {
                p.player.unMute();
                p.player.setVolume(100);
            } else {
                // Non-YT: reload without mute
                var iframe = document.getElementById('iframe-' + index);
                if (iframe) iframe.src = iframe.src.replace('&mute=1','').replace('mute=1&','').replace('mute=1','');
            }
            p.muted = false;
            btn.remove();
        };
        blocker.appendChild(btn);

        // Fullscreen button
        var fsBtn = document.createElement('div');
        fsBtn.id = 'fs-' + index;
        fsBtn.className = 'fs-btn';
        fsBtn.innerHTML = '⛶ Fullscreen';
        fsBtn.onclick = function(e) {
            e.stopPropagation();
            var el = document.getElementById('yt-player-' + index + '-iframe')
                  || document.getElementById('iframe-' + index)
                  || document.getElementById('player-' + index);
            if (el && el.requestFullscreen) el.requestFullscreen();
            else if (el && el.webkitRequestFullscreen) el.webkitRequestFullscreen();
        };
        blocker.appendChild(fsBtn);

        // Pause/Play button
        var pauseBtn = document.createElement('div');
        pauseBtn.id = 'pause-' + index;
        pauseBtn.className = 'pause-btn';
        pauseBtn.innerHTML = '⏸ Pause';
        // Only show pause for YouTube (DM doesn't support it reliably)
        if (players[index] && players[index].type === 'yt') {
            pauseBtn.onclick = function(e) {
                e.stopPropagation();
                var p = players[index];
                if (p.playing) { p.player.pauseVideo(); pauseBtn.innerHTML = '▶ Play'; }
                else           { p.player.playVideo();  pauseBtn.innerHTML = '⏸ Pause'; }
                p.playing = !p.playing;
            };
            blocker.appendChild(pauseBtn);
        }
    }

    function hideVideo(index) {
        document.getElementById('iframe-' + index).src = '';
        document.getElementById('player-' + index).style.display = 'none';
        document.getElementById('facade-' + index).style.display = 'block';
    }



    function filterLang(lang, btn) {
        document.querySelectorAll('.lang-pill').forEach(p => p.classList.remove('active'));
        btn.classList.add('active');
        const cards = document.querySelectorAll('.video-card');
        let visible = 0;
        cards.forEach(card => {
            const show = lang === 'all' || card.dataset.lang === lang;
            card.style.display = show ? 'block' : 'none';
            if (show) visible++;
        });
        document.getElementById('noVideosMsg').style.display = visible === 0 ? 'block' : 'none';
    }
</script>
@endpush

@push('styles')
<style>
.video-spoiler-cover {
    position: relative;
    padding-bottom: 56.25%;
    height: 0;
    overflow: hidden;
    border-radius: 8px 8px 0 0;
    cursor: pointer;
}
.thumb-blur {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center;
    filter: blur(10px) brightness(0.35);
    transform: scale(1.05);
    transition: filter .3s;
}
.thumb-blank {
    background: #0a0e1a;
}
.video-spoiler-cover:hover .thumb-blur {
    filter: blur(8px) brightness(0.45);
}
.thumb-overlay {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: .6rem;
}
.thumb-icon { font-size: 2.2rem; }
.thumb-label {
    font-size: .85rem;
    font-weight: 700;
    color: #e2e8f0;
    letter-spacing: .03em;
}
.btn-play-reveal {
    background: #f59e0b;
    color: #000;
    border: none;
    padding: .45rem 1.2rem;
    border-radius: 20px;
    font-size: .85rem;
    font-weight: 800;
    cursor: pointer;
    transition: background .15s, transform .15s;
}
.btn-play-reveal:hover {
    background: #d97706;
    transform: scale(1.05);
}
.video-embed-wrapper { position: relative; }
.btn-hide-video {
    position: absolute;
    top: .4rem;
    right: .4rem;
    background: rgba(0,0,0,.7);
    color: #9ca3af;
    border: none;
    border-radius: 6px;
    padding: .2rem .5rem;
    font-size: .75rem;
    cursor: pointer;
    z-index: 10;
}
.btn-hide-video:hover { color: #e2e8f0; }

.yt-facade {
    position: relative;
    width: 100%;
    padding-bottom: 56.25%;
    cursor: pointer;
    overflow: hidden;
    border-radius: 8px 8px 0 0;
    background: #000;
}
.yt-facade-img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .3s;
}
.yt-facade:hover .yt-facade-img { transform: scale(1.03); }
.yt-facade-blank { background: #111827; }
.yt-facade-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom,
        rgba(0,0,0,0.55) 0%,
        rgba(0,0,0,0.1) 40%,
        rgba(0,0,0,0.1) 60%,
        rgba(0,0,0,0.4) 100%);
}
.yt-facade-label {
    position: absolute;
    top: .7rem;
    left: .8rem;
    color: #fff;
    font-size: .85rem;
    font-weight: 700;
    text-shadow: 0 1px 4px rgba(0,0,0,.8);
    letter-spacing: .03em;
}
.yt-play-btn {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 64px;
    height: 44px;
    background: #f59e0b;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    color: #000;
    transition: background .2s, transform .2s;
    box-shadow: 0 4px 20px rgba(0,0,0,.5);
}
.yt-facade:hover .yt-play-btn {
    background: #d97706;
    transform: translate(-50%, -50%) scale(1.1);
}


/* ── Video player wrapper ─────────────────────────── */
.player-wrap {
    position: relative;
}
.player-inner {
    position: relative;
    width: 100%;
    aspect-ratio: 16 / 9;
}
.player-inner iframe {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    border: none;
    border-radius: 8px 8px 0 0;
    display: block;
}

/* 
 * Title cover bar.
 * YouTube title occupies the top ~8.3% of the player.
 * With aspect-ratio:16/9, height = width * (9/16) = width * 0.5625
 * Top 8.3% of that = width * 0.5625 * 0.083 = width * 0.04669
 * Express as % of width (which is what padding-top % means on absolute children
 * of an aspect-ratio container): 4.67% of width = 4.67/100 * width
 * But since we're inside aspect-ratio, we use % of the inner height instead.
 * Simplest: just use height: 8.5% — works because parent has defined height via aspect-ratio.
 */
.iframe-blocker {
    position: absolute;
    inset: 0;
    z-index: 10;
    cursor: default;
    background: transparent;
}
/* Keep blocker active inside fullscreen */
.iframe-blocker:-webkit-full-screen { display: flex; }
.iframe-blocker:-moz-full-screen    { display: flex; }
.iframe-blocker:-ms-fullscreen      { display: flex; }
.iframe-blocker:fullscreen          { display: flex; }

/* Prevent text selection on blocker */
.iframe-blocker {
    user-select: none;
    -webkit-user-select: none;
    pointer-events: all;
}

/* All video control buttons */
.unmute-btn, .fs-btn, .pause-btn {
    position: absolute;
    background: rgba(0,0,0,.75);
    color: #fff;
    font-size: .8rem;
    font-weight: 700;
    padding: .35rem .85rem;
    border-radius: 20px;
    cursor: pointer;
    z-index: 20;
    border: 1px solid rgba(255,255,255,.2);
    letter-spacing: .02em;
    /* Hidden by default — show on blocker hover */
    opacity: 0;
    transition: opacity .25s, background .15s;
}
.unmute-btn { top: .75rem; left: .75rem; }
.fs-btn     { top: .75rem; right: .75rem; }
.pause-btn  { bottom: .75rem; right: .75rem; }

/* Show all buttons when hovering the blocker */
.iframe-blocker:hover .unmute-btn,
.iframe-blocker:hover .fs-btn,
.iframe-blocker:hover .pause-btn {
    opacity: 1;
}
.unmute-btn:hover, .fs-btn:hover, .pause-btn:hover {
    background: #f59e0b;
    color: #000;
    border-color: #f59e0b;
}

/*
 * ── Persistent YouTube title blur bar ────────────────────────────────────────
 *
 * YouTube shows the video title in the top-left corner of the player.
 * This bar sits permanently on top of that area and blurs whatever is behind it,
 * so the title text is never readable — even after the startup cover fades out.
 *
 * Height: YouTube's title bar occupies roughly the top 13% of the player.
 * We use a frosted-glass style (backdrop-filter: blur) so it looks intentional
 * rather than just a black rectangle.
 *
 * z-index: 11 — above the iframe (0) and start-cover (9), below blocker (10)
 * is intentional: the blocker's buttons (z-index 20) still appear on top.
 * We bump it to 11 so it overlaps the iframe's title text even when the
 * blocker pointer-events are active.
 */
.yt-title-blur {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 13%;           /* covers YouTube title + gradient area */
    z-index: 11;
    pointer-events: none;  /* never blocks clicks */

    /* Frosted glass blur — hides the title text behind it */
    backdrop-filter: blur(12px) brightness(0.55) saturate(0.4);
    -webkit-backdrop-filter: blur(12px) brightness(0.55) saturate(0.4);

    /* Subtle dark gradient reinforces the blur in browsers that
       don't support backdrop-filter (Firefox < 103, older Safari) */
    background: linear-gradient(
        to bottom,
        rgba(6, 10, 20, 0.82) 0%,
        rgba(6, 10, 20, 0.55) 55%,
        rgba(6, 10, 20, 0.0)  100%
    );

    /* Rounded to match iframe top corners */
    border-radius: 8px 8px 0 0;
}

/* Inside fullscreen the title bar is taller — bump height slightly */
.player-inner:fullscreen          .yt-title-blur,
.player-inner:-webkit-full-screen .yt-title-blur,
.player-inner:-moz-full-screen    .yt-title-blur {
    height: 10%;
}

</style>
@endpush
