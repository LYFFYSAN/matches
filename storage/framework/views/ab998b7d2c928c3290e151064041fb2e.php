<?php $__env->startSection('title', $match->team1 . ' vs ' . $match->team2 . ' — World Cup 2026'); ?>

<?php $__env->startSection('content'); ?>

<div class="match-detail-wrapper">

    <a href="<?php echo e(route('home')); ?>" class="back-link">← Back to matches</a>

    <div class="match-header-detail">
        <div class="match-stage-detail">
            <?php echo e($match->stage); ?><?php echo e($match->group_name ? ' — ' . $match->group_name : ''); ?>

        </div>
        <div class="match-teams-detail">
            <div class="team-detail">
                <?php if($match->team1_flag): ?>
                    <img src="<?php echo e($match->team1_flag); ?>" alt="<?php echo e($match->team1); ?>" class="team-flag-lg">
                <?php endif; ?>
                <h2><?php echo e($match->team1); ?></h2>
            </div>
            <div class="match-vs-detail">
                <span class="vs-large">vs</span>
                <span class="match-date-detail"><?php echo e($match->match_date->format('d M Y, H:i')); ?></span>
                <span class="badge <?php echo e($match->status_badge_class); ?>"><?php echo e($match->status_label); ?></span>
            </div>
            <div class="team-detail">
                <?php if($match->team2_flag): ?>
                    <img src="<?php echo e($match->team2_flag); ?>" alt="<?php echo e($match->team2); ?>" class="team-flag-lg">
                <?php endif; ?>
                <h2><?php echo e($match->team2); ?></h2>
            </div>
        </div>
    </div>

    
    <div class="spoiler-shield" id="spoilerShield">
        <?php if($match->status === 'finished' && $match->score1 !== null): ?>
            <div class="score-hidden" id="scoreHidden">
                <button class="btn-reveal" onclick="revealScore()">👁️ Reveal Final Score</button>
                <p class="spoiler-hint">Score hidden — click when you're ready</p>
            </div>
            <div class="score-revealed" id="scoreRevealed" style="display:none">
                <div class="scoreboard">
                    <span class="score-number"><?php echo e($match->score1); ?></span>
                    <span class="score-dash">—</span>
                    <span class="score-number"><?php echo e($match->score2); ?></span>
                </div>
                <?php if($match->score1 > $match->score2): ?>
                    <p class="winner-label">🏆 <?php echo e($match->team1); ?> wins!</p>
                <?php elseif($match->score2 > $match->score1): ?>
                    <p class="winner-label">🏆 <?php echo e($match->team2); ?> wins!</p>
                <?php else: ?>
                    <p class="winner-label">🤝 Draw!</p>
                <?php endif; ?>
                <button class="btn-hide-score" onclick="hideScore()">🙈 Hide score</button>
            </div>
        <?php elseif($match->status === 'live'): ?>
            <div class="match-live-notice">🔴 This match is currently LIVE!</div>
        <?php else: ?>
            <div class="match-upcoming-notice">🕐 Match hasn't started yet.</div>
        <?php endif; ?>
    </div>

    
    <?php if($match->videos->isNotEmpty()): ?>
    <section class="highlights-section">

        <div class="highlights-header">
            <h2>🎬 Match Highlights</h2>
            <div class="lang-selector">
                <button class="lang-pill active" data-lang="all" onclick="filterLang('all',this)">🌍 All</button>
                <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button class="lang-pill" data-lang="<?php echo e($lang); ?>" onclick="filterLang('<?php echo e($lang); ?>',this)">
                        <?php switch($lang):
                            case ('ar'): ?> 🇩🇿 Arabic <?php break; ?>
                            <?php case ('en'): ?> 🇬🇧 English <?php break; ?>
                            <?php case ('fr'): ?> 🇫🇷 French <?php break; ?>
                            <?php case ('es'): ?> 🇪🇸 Spanish <?php break; ?>
                            <?php case ('pt'): ?> 🇧🇷 Portuguese <?php break; ?>
                            <?php default: ?> <?php echo e(strtoupper($lang)); ?>

                        <?php endswitch; ?>
                    </button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <div class="video-grid" id="videoGrid">
            <?php $__currentLoopData = $match->videos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $isYoutube = $video->platform === 'youtube';
                $ytId = $isYoutube ? basename(parse_url($video->embed_url, PHP_URL_PATH)) : null;
                $thumb = $ytId ? "https://img.youtube.com/vi/{$ytId}/mqdefault.jpg" : null;
            ?>
            <div class="video-card" data-lang="<?php echo e($video->language); ?>" data-index="<?php echo e($index); ?>">

                <div class="yt-facade" id="facade-<?php echo e($index); ?>" onclick="loadVideo(<?php echo e($index); ?>, '<?php echo e(addslashes($video->embed_url)); ?>')">
                    <?php if($thumb): ?>
                    <img src="<?php echo e($thumb); ?>" alt="Highlight #<?php echo e($index + 1); ?>" class="yt-facade-img">
                    <?php else: ?>
                    <div class="yt-facade-img yt-facade-blank"></div>
                    <?php endif; ?>
                    <div class="yt-facade-overlay"></div>
                    <div class="yt-facade-label">Highlight #<?php echo e($index + 1); ?></div>
                    <div class="yt-play-btn">&#9654;</div>
                </div>

                <div class="player-wrap" id="player-<?php echo e($index); ?>" style="display:none">
                    <div class="player-inner">
                        <iframe
                            id="iframe-<?php echo e($index); ?>"
                            src=""
                            title="Highlight #<?php echo e($index + 1); ?>"
                            allowfullscreen
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            style="pointer-events:none"
                        ></iframe>
                        
                        <div class="yt-title-blur" id="title-blur-<?php echo e($index); ?>"></div>
                        
                        <div class="iframe-blocker" id="blocker-<?php echo e($index); ?>">
                        </div>
                    </div>
                    <button class="btn-hide-video" onclick="hideVideo(<?php echo e($index); ?>)">✕ Hide</button>
                </div>

                <div class="video-meta">
                    <div class="video-badges">
                        <span class="platform-badge platform-<?php echo e($video->platform); ?>">
                            <?php switch($video->platform):
                                case ('youtube'): ?> ▶ YouTube <?php break; ?>
                                <?php case ('dailymotion'): ?> 🎬 Dailymotion <?php break; ?>
                                <?php case ('scorebat'): ?> ⚽ Scorebat <?php break; ?>
                                <?php case ('vimeo'): ?> 🎥 Vimeo <?php break; ?>
                                <?php default: ?> 🌐 <?php echo e(ucfirst($video->platform)); ?>

                            <?php endswitch; ?>
                        </span>
                        <span class="lang-badge">
                            <?php switch($video->language):
                                case ('ar'): ?> 🇩🇿 AR <?php break; ?>
                                <?php case ('en'): ?> 🇬🇧 EN <?php break; ?>
                                <?php case ('fr'): ?> 🇫🇷 FR <?php break; ?>
                                <?php case ('es'): ?> 🇪🇸 ES <?php break; ?>
                                <?php case ('pt'): ?> 🇧🇷 PT <?php break; ?>
                                <?php default: ?> <?php echo e(strtoupper($video->language)); ?>

                            <?php endswitch; ?>
                        </span>
                        <?php if($video->duration_s): ?>
                            <span class="duration-badge">⏱ <?php echo e(gmdate('i:s', $video->duration_s)); ?></span>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <p class="no-videos-msg" id="noVideosMsg" style="display:none">
            No highlights available in this language yet.
        </p>
    </section>
    <?php else: ?>
        <div class="no-highlights">
            <span>🎬</span>
            <p>No highlights available yet for this match.</p>
        </div>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
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

        // Hide .yt-title-blur when THIS player goes fullscreen, restore on exit
        (function(idx) {
            function onFsChange() {
                var isFs = !!(document.fullscreenElement
                           || document.webkitFullscreenElement
                           || document.mozFullScreenElement
                           || document.msFullscreenElement);
                var blur = document.getElementById('title-blur-' + idx);
                if (blur) blur.style.opacity = isFs ? '0' : '1';
            }
            document.addEventListener('fullscreenchange',       onFsChange);
            document.addEventListener('webkitfullscreenchange', onFsChange);
            document.addEventListener('mozfullscreenchange',    onFsChange);
            document.addEventListener('MSFullscreenChange',     onFsChange);
        })(index);

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
                        '?autoplay=1&mute=1&controls=1&queue-enable=0&api=postMessage';
            document.getElementById('iframe-' + index).src = dmSrc;
            players[index].type = 'dm';
            // Listen for Dailymotion postMessage state events
            window.addEventListener('message', function(evt) {
                try {
                    var data = typeof evt.data === 'string' ? JSON.parse(evt.data) : evt.data;
                    if (!data || data.event === undefined) return;
                    var pauseBtn = document.getElementById('pause-' + index);
                    if (data.event === 'playing') {
                        players[index].playing = true;
                        if (pauseBtn) pauseBtn.innerHTML = '⏸ Pause';
                    } else if (data.event === 'pause') {
                        players[index].playing = false;
                        if (pauseBtn) pauseBtn.innerHTML = '▶ Play';
                    }
                } catch(e) {}
            });
            setTimeout(function() { injectButtons(index); }, 600);
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

    // Send postMessage command to Dailymotion iframe
    // DM requires the message sent to 'https://www.dailymotion.com'
    function dmCommand(index, cmd) {
        var iframe = document.getElementById('iframe-' + index);
        if (!iframe || !iframe.contentWindow) return;
        iframe.contentWindow.postMessage(
            JSON.stringify({ command: cmd }),
            'https://www.dailymotion.com'
        );
    }

    function injectButtons(index) {
        var blocker = document.getElementById('blocker-' + index);
        if (!blocker || document.getElementById('btn-group-' + index)) return;

        var p = players[index];

        // Wrap all buttons in a single flex row container anchored at bottom-center
        var group = document.createElement('div');
        group.id = 'btn-group-' + index;
        group.style.cssText = [
            'position:absolute',
            'bottom:.75rem',
            'left:50%',
            'transform:translateX(-50%)',
            'display:flex',
            'flex-direction:row',
            'align-items:center',
            'gap:.5rem',
            'z-index:20',
            'opacity:0',
            'transition:opacity .25s',
            'pointer-events:none',
            'white-space:nowrap',
        ].join(';');

        // Show group on blocker hover
        blocker.addEventListener('mouseenter', function() { group.style.opacity = '1'; group.style.pointerEvents = 'all'; });
        blocker.addEventListener('mouseleave', function() { group.style.opacity = '0'; group.style.pointerEvents = 'none'; });

        var btnStyle = [
            'background:rgba(0,0,0,.78)',
            'color:#fff',
            'font-size:.8rem',
            'font-weight:700',
            'padding:.35rem .85rem',
            'border-radius:20px',
            'cursor:pointer',
            'border:1px solid rgba(255,255,255,.22)',
            'letter-spacing:.02em',
            'transition:background .15s,transform .15s',
            'white-space:nowrap',
        ].join(';');

        function hoverOn(el)  { el.style.background='#f59e0b'; el.style.color='#000'; el.style.borderColor='#f59e0b'; el.style.transform='translateY(-2px)'; }
        function hoverOff(el) { el.style.background='rgba(0,0,0,.78)'; el.style.color='#fff'; el.style.borderColor='rgba(255,255,255,.22)'; el.style.transform=''; }

        // ── Unmute ──────────────────────────
        var unmuteBtn = document.createElement('div');
        unmuteBtn.id = 'unmute-' + index;
        unmuteBtn.style.cssText = btnStyle;
        unmuteBtn.innerHTML = '🔇 Unmute';
        unmuteBtn.addEventListener('mouseenter', function() { hoverOn(unmuteBtn); });
        unmuteBtn.addEventListener('mouseleave', function() { hoverOff(unmuteBtn); });
        unmuteBtn.onclick = function(e) {
            e.stopPropagation();
            if (p && p.player && p.player.unMute) {
                p.player.unMute();
                p.player.setVolume(100);
            } else if (p && p.type === 'dm') {
                dmCommand(index, 'unmute');
            } else {
                var iframe = document.getElementById('iframe-' + index);
                if (iframe) iframe.src = iframe.src.replace('&mute=1','').replace('mute=1&','').replace('mute=1','');
            }
            p.muted = false;
            unmuteBtn.remove();
        };
        group.appendChild(unmuteBtn);

        // ── Fullscreen ───────────────────────
        var fsBtn = document.createElement('div');
        fsBtn.id = 'fs-' + index;
        fsBtn.style.cssText = btnStyle;
        fsBtn.innerHTML = '⛶ Fullscreen';
        fsBtn.addEventListener('mouseenter', function() { hoverOn(fsBtn); });
        fsBtn.addEventListener('mouseleave', function() { hoverOff(fsBtn); });
        fsBtn.onclick = function(e) {
            e.stopPropagation();
            var el = document.getElementById('yt-player-' + index + '-iframe')
                  || document.getElementById('iframe-' + index)
                  || document.getElementById('player-' + index);
            if (el && el.requestFullscreen) el.requestFullscreen();
            else if (el && el.webkitRequestFullscreen) el.webkitRequestFullscreen();
        };
        group.appendChild(fsBtn);

        // ── Pause / Play ─────────────────────
        var pauseBtn = document.createElement('div');
        pauseBtn.id = 'pause-' + index;
        pauseBtn.style.cssText = btnStyle;
        pauseBtn.innerHTML = '⏸ Pause';
        pauseBtn.addEventListener('mouseenter', function() { hoverOn(pauseBtn); });
        pauseBtn.addEventListener('mouseleave', function() { hoverOff(pauseBtn); });

        if (p && p.type === 'yt') {
            // YouTube: use IFrame API
            pauseBtn.onclick = function(e) {
                e.stopPropagation();
                if (p.playing) {
                    p.player.pauseVideo();
                    pauseBtn.innerHTML = '▶ Play';
                } else {
                    p.player.playVideo();
                    pauseBtn.innerHTML = '⏸ Pause';
                }
                p.playing = !p.playing;
            };
            group.appendChild(pauseBtn);

        } else if (p && p.type === 'dm') {
            // Dailymotion: postMessage API
            // DM supports: play, pause, seek, mute, unmute via postMessage
            pauseBtn.onclick = function(e) {
                e.stopPropagation();
                if (p.playing) {
                    dmCommand(index, 'pause');
                    pauseBtn.innerHTML = '▶ Play';
                } else {
                    dmCommand(index, 'play');
                    pauseBtn.innerHTML = '⏸ Pause';
                }
                p.playing = !p.playing;
            };
            group.appendChild(pauseBtn);
        }

        blocker.appendChild(group);
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
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
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
/* ── Blocker ────────────────────────────────────────────────
   Transparent overlay that intercepts hover events so the
   YouTube title / controls don't show on mouse-over.
   Button group is injected absolutely via JS at bottom-center. */
.iframe-blocker {
    position: absolute;
    inset: 0;
    z-index: 10;
    cursor: default;
    background: transparent;
    pointer-events: all;
    user-select: none;
    -webkit-user-select: none;
}

/* Button styles are applied inline via JS injectButtons() */

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

/* Fullscreen hide is handled via JS fullscreenchange event below */

</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ufcmsila\Desktop\matches\worldcup-scorebat\resources\views/matches/show.blade.php ENDPATH**/ ?>