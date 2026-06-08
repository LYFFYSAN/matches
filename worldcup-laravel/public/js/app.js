/**
 * World Cup 2026 — Main JS
 * Vanilla JS only — no build tools required.
 * Simply include as <script src="/js/app.js"></script>
 */

document.addEventListener('DOMContentLoaded', function () {

    // -------------------------------------------------------
    // Auto-dismiss alert messages after 4 seconds
    // -------------------------------------------------------
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function (alert) {
        setTimeout(function () {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(function () { alert.remove(); }, 500);
        }, 4000);
    });

    // -------------------------------------------------------
    // Live badge pulse — already handled by CSS animation,
    // but we refresh the page every 60s if a live badge exists
    // to pick up any status changes from the server.
    // -------------------------------------------------------
    if (document.querySelector('.badge-live')) {
        setTimeout(function () {
            window.location.reload();
        }, 60000);
    }

    // -------------------------------------------------------
    // Admin: confirm dangerous actions
    // -------------------------------------------------------
    document.querySelectorAll('[data-confirm]').forEach(function (el) {
        el.addEventListener('click', function (e) {
            if (!confirm(el.dataset.confirm)) {
                e.preventDefault();
            }
        });
    });

    // -------------------------------------------------------
    // Embed URL helper — convert watch URL to embed URL
    // on the admin add-video form
    // -------------------------------------------------------
    var embedInput = document.getElementById('embed_url');
    if (embedInput) {
        embedInput.addEventListener('blur', function () {
            var url = this.value.trim();

            // YouTube: watch?v=ID → embed/ID
            var ytMatch = url.match(/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/);
            if (ytMatch) {
                this.value = 'https://www.youtube.com/embed/' + ytMatch[1];
                showHint(this, '✅ Converted to YouTube embed URL');
                return;
            }

            // YouTube short: youtu.be/ID
            var ytShort = url.match(/youtu\.be\/([a-zA-Z0-9_-]+)/);
            if (ytShort) {
                this.value = 'https://www.youtube.com/embed/' + ytShort[1];
                showHint(this, '✅ Converted to YouTube embed URL');
                return;
            }

            // Dailymotion: /video/ID → /embed/video/ID
            var dmMatch = url.match(/dailymotion\.com\/video\/([a-zA-Z0-9]+)/);
            if (dmMatch) {
                this.value = 'https://www.dailymotion.com/embed/video/' + dmMatch[1];
                showHint(this, '✅ Converted to Dailymotion embed URL');
                return;
            }
        });
    }

    function showHint(input, msg) {
        var hint = input.parentNode.querySelector('.convert-hint');
        if (!hint) {
            hint = document.createElement('small');
            hint.className = 'convert-hint';
            hint.style.cssText = 'color:#22c55e;font-size:0.75rem;display:block;margin-top:0.3rem;';
            input.parentNode.appendChild(hint);
        }
        hint.textContent = msg;
        setTimeout(function () { hint.remove(); }, 3000);
    }

    // -------------------------------------------------------
    // Match card keyboard navigation (a11y)
    // -------------------------------------------------------
    document.querySelectorAll('.match-card').forEach(function (card) {
        card.setAttribute('tabindex', '0');
        card.setAttribute('role', 'link');
        card.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                card.click();
            }
        });
    });

});
