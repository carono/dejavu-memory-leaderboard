/*
 * dejavu-leaderboard — front-end behaviour.
 * Progressive enhancement only: the pages work without JS, this adds
 * client-side filtering, a mobile nav toggle, and copy-to-clipboard.
 */
(function () {
    'use strict';

    // ---- mobile nav toggle ----
    var toggle = document.querySelector('[data-nav-toggle]');
    var links = document.getElementById('nav-links');
    if (toggle && links) {
        toggle.addEventListener('click', function () {
            var open = links.classList.toggle('open');
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
    }

    // ---- leaderboard live filter ----
    var search = document.querySelector('[data-board-search]');
    var board = document.querySelector('[data-board]');
    if (search && board) {
        var rows = Array.prototype.slice.call(board.querySelectorAll('tbody tr[data-search]'));
        var countEl = document.querySelector('[data-board-count]');
        var noResults = document.querySelector('[data-no-results]');
        var total = rows.length;

        var apply = function () {
            var q = search.value.trim().toLowerCase();
            var shown = 0;
            rows.forEach(function (tr) {
                var hit = q === '' || tr.getAttribute('data-search').indexOf(q) !== -1;
                tr.style.display = hit ? '' : 'none';
                if (hit) { shown++; }
            });
            if (countEl) {
                countEl.textContent = q === ''
                    ? total + (total === 1 ? ' entry' : ' entries')
                    : shown + ' of ' + total;
            }
            if (noResults) { noResults.style.display = shown === 0 ? 'block' : 'none'; }
        };

        search.addEventListener('input', apply);
    }

    // ---- copy to clipboard ----
    document.querySelectorAll('[data-copy]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var sel = btn.getAttribute('data-copy');
            var src = document.querySelector(sel);
            if (!src) { return; }
            var text = src.innerText;
            var done = function () {
                var label = btn.textContent;
                btn.textContent = 'Copied';
                btn.classList.add('copied');
                setTimeout(function () {
                    btn.textContent = label;
                    btn.classList.remove('copied');
                }, 1400);
            };
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(done, function () {});
            } else {
                var ta = document.createElement('textarea');
                ta.value = text;
                document.body.appendChild(ta);
                ta.select();
                try { document.execCommand('copy'); done(); } catch (e) {}
                document.body.removeChild(ta);
            }
        });
    });
})();
