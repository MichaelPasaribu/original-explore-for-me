(() => {
    'use strict';

    const $  = (s, r = document) => r.querySelector(s);
    const $$ = (s, r = document) => Array.from(r.querySelectorAll(s));

    /* =====================================================
       1. PEMUTAR LAGU (tetap hidup saat pindah halaman)
       ===================================================== */
    const audio  = $('#audio');
    const player = $('#player');
    const ui = {
        cover: $('#pl-cover'), title: $('#pl-title'), artist: $('#pl-artist'),
        play: $('#pl-play'), prev: $('#pl-prev'), next: $('#pl-next'),
        seek: $('#pl-seek'), cur: $('#pl-cur'), dur: $('#pl-dur'),
    };
    const ICON_PLAY  = '<svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>';
    const ICON_PAUSE = '<svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M6 5h4v14H6zm8 0h4v14h-4z"/></svg>';

    let queue = [], idx = -1;
    let actx = null, analyser = null, bins = null;

    const fmt = (s) => {
        if (!isFinite(s)) return '0:00';
        return Math.floor(s / 60) + ':' + String(Math.floor(s % 60)).padStart(2, '0');
    };

    // Web Audio dibuat saat pertama kali play (butuh interaksi pengguna)
    function setupAnalyser() {
        if (actx) return;
        try {
            const AC = window.AudioContext || window.webkitAudioContext;
            actx = new AC();
            analyser = actx.createAnalyser();
            analyser.fftSize = 512;
            analyser.smoothingTimeConstant = 0.85;
            const source = actx.createMediaElementSource(audio);
            source.connect(analyser);
            analyser.connect(actx.destination);
            bins = new Uint8Array(analyser.frequencyBinCount);
        } catch (err) {
            actx = analyser = null; // lagu tetap bisa diputar, hanya aurora tidak bereaksi
        }
    }

    // Energi bass / mid / treble, nilai 0..1
    function energy() {
        if (!analyser || audio.paused) return { bass: 0, mid: 0, high: 0 };
        analyser.getByteFrequencyData(bins);
        const avg = (a, b) => { let s = 0; for (let i = a; i < b; i++) s += bins[i]; return s / ((b - a) * 255); };
        return { bass: Math.min(1, avg(0, 4) * 1.2), mid: Math.min(1, avg(4, 30) * 1.6), high: Math.min(1, avg(30, 100) * 2.4) };
    }

    async function play() {
        setupAnalyser();
        try { if (actx && actx.state === 'suspended') await actx.resume(); } catch (e) {}
        try { await audio.play(); } catch (e) {}
    }

    function load(i, autoplay = true) {
        idx = i;
        const s = queue[i];
        audio.src = s.src;
        ui.title.textContent  = s.title;
        ui.artist.textContent = s.artist || 'Original';
        ui.cover.hidden = !s.cover;
        if (s.cover) ui.cover.src = s.cover;
        player.hidden = false;
        document.body.classList.add('has-player');
        markPlaying();
        if (autoplay) play();
    }

    function step(dir) {
        if (!queue.length) return;
        load((idx + dir + queue.length) % queue.length);
    }

    // Tandai lagu yang sedang aktif di halaman Lagu
    function markPlaying() {
        const cur = queue[idx];
        ui.play.innerHTML = audio.paused ? ICON_PLAY : ICON_PAUSE;
        $$('#page .song').forEach((row) => {
            const on = !!cur && row.dataset.src === cur.src;
            row.classList.toggle('active', on);
            const b = $('.play-btn', row);
            if (b) b.innerHTML = (on && !audio.paused) ? ICON_PAUSE : ICON_PLAY;
        });
    }

    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.play-btn');
        if (!btn) return;
        const rows = $$('#page .song');
        const row = btn.closest('.song');
        if (queue[idx] && queue[idx].src === row.dataset.src) {
            audio.paused ? play() : audio.pause();
            return;
        }
        queue = rows.map((r) => ({ src: r.dataset.src, title: r.dataset.title, artist: r.dataset.artist, cover: r.dataset.cover }));
        load(rows.indexOf(row));
    });

    ui.play.addEventListener('click', () => { if (idx >= 0) (audio.paused ? play() : audio.pause()); });
    ui.next.addEventListener('click', () => step(1));
    ui.prev.addEventListener('click', () => { audio.currentTime > 3 ? (audio.currentTime = 0) : step(-1); });

    let seeking = false;
    ui.seek.addEventListener('input',  () => { seeking = true; ui.cur.textContent = fmt(ui.seek.value); });
    ui.seek.addEventListener('change', () => { audio.currentTime = +ui.seek.value; seeking = false; });

    audio.addEventListener('timeupdate', () => {
        if (seeking) return;
        ui.seek.value = audio.currentTime;
        ui.cur.textContent = fmt(audio.currentTime);
    });
    audio.addEventListener('loadedmetadata', () => { ui.seek.max = audio.duration || 0; ui.dur.textContent = fmt(audio.duration); });
    audio.addEventListener('play',  markPlaying);
    audio.addEventListener('pause', markPlaying);
    audio.addEventListener('ended', () => step(1));
    audio.addEventListener('error', () => { ui.artist.textContent = 'File audio tidak ditemukan'; });

    /* =====================================================
       2. AURORA (canvas, bereaksi pelan pada irama lagu)
       ===================================================== */
    const cv = $('#aurora');
    const cx = cv.getContext('2d');
    const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
    let W = 0, H = 0, stars = [], sky = null, lastW = 0;

    function makeStrip(rgb) {
        const c = document.createElement('canvas');
        c.width = 1; c.height = 128;
        const g = c.getContext('2d');
        const gr = g.createLinearGradient(0, 0, 0, 128);
        gr.addColorStop(0,   `rgba(${rgb},0)`);
        gr.addColorStop(.55, `rgba(${rgb},.28)`);
        gr.addColorStop(.9,  `rgba(${rgb},.75)`);
        gr.addColorStop(1,   `rgba(${rgb},0)`);
        g.fillStyle = gr;
        g.fillRect(0, 0, 1, 128);
        return c;
    }

    const bands = [
        { rgb: '126,240,176', y: .40, amp: .10, h: .34, s1: .30, s2: .20 }, // hijau
        { rgb: '111,227,214', y: .46, amp: .08, h: .28, s1: .22, s2: .28 }, // teal
        { rgb: '180,163,255', y: .34, amp: .12, h: .30, s1: .26, s2: .18 }, // ungu
        { rgb: '242,184,255', y: .42, amp: .09, h: .22, s1: .34, s2: .24 }, // merah muda
    ].map((b) => ({
        ...b, img: makeStrip(b.rgb),
        p1: Math.random() * 6.28, p2: Math.random() * 6.28,
        rnd: 1, rndT: 1, shift: 0, shiftT: 0, next: 0,
    }));

    function resize() {
        lastW = innerWidth;
        W = cv.width  = Math.max(240, Math.round(innerWidth  * 0.5)); // setengah resolusi: ringan & lembut
        H = cv.height = Math.max(240, Math.round(innerHeight * 0.5));
        sky = cx.createLinearGradient(0, 0, 0, H);
        sky.addColorStop(0,   '#04071a');
        sky.addColorStop(.6,  '#09122e');
        sky.addColorStop(1,   '#0b2038');
        stars = Array.from({ length: Math.round(W * H / 2200) }, () => ({
            x: Math.random() * W, y: Math.random() * H * 0.8, r: Math.random() * 0.9 + 0.3, p: Math.random() * 6.28,
        }));
    }
    resize();
    addEventListener('resize', () => { if (innerWidth !== lastW) resize(); });

    const e = { bass: 0, mid: 0, high: 0 };
    let last = 0, t = 0;

    function frame(now) {
        requestAnimationFrame(frame);
        if (now - last < 33) return;                 // ~30 fps, hemat baterai
        const dt = Math.min(0.1, (now - last) / 1000);
        last = now;

        // Haluskan energi lagu supaya perubahan pelan (half-life ±0.7 detik)
        const tg = energy();
        const k = 1 - Math.pow(0.5, dt / 0.7);
        e.bass += (tg.bass - e.bass) * k;
        e.mid  += (tg.mid  - e.mid)  * k;
        e.high += (tg.high - e.high) * k;
        const lvl = e.bass * 0.5 + e.mid * 0.35 + e.high * 0.15;

        t += dt * (reduce ? 0.25 : 1) * (0.45 + lvl * 1.1);

        cx.globalCompositeOperation = 'source-over';
        cx.globalAlpha = 1;
        cx.fillStyle = sky;
        cx.fillRect(0, 0, W, H);

        cx.fillStyle = '#fff';
        for (const s of stars) {
            cx.globalAlpha = 0.35 + 0.35 * Math.sin(t * 1.5 + s.p);
            cx.fillRect(s.x, s.y, s.r, s.r);
        }

        cx.globalCompositeOperation = 'lighter';
        const bandEnergy = [e.bass, (e.bass + e.mid) / 2, e.mid, Math.min(1, e.high * 1.5)];

        bands.forEach((b, i) => {
            // target acak baru setiap beberapa detik, dikejar dengan lambat
            if (now > b.next) {
                b.rndT   = 0.75 + Math.random() * 0.6;
                b.shiftT = (Math.random() - 0.5) * 0.08;
                b.next   = now + 3500 + Math.random() * 4500;
            }
            b.rnd   += (b.rndT   - b.rnd)   * k * 0.4;
            b.shift += (b.shiftT - b.shift) * k * 0.3;

            const en = bandEnergy[i];
            cx.globalAlpha = Math.min(1, 0.5 + en * 0.6);

            for (let x = 0; x < W; x += 2) {
                const nx = x / W;
                const wave = Math.sin(nx * 5.5 + t * b.s1 + b.p1) * 0.5
                           + Math.sin(nx * 12  - t * b.s2 + b.p2) * 0.3
                           + Math.sin(nx * 2.4 + t * 0.15 + b.p1) * 0.4;
                const base = H * (b.y + b.shift) + wave * H * b.amp * (0.8 + lvl * 0.9);
                const ray  = 0.72 + 0.28 * Math.sin(nx * 58 + t * b.s1 * 2.4 + b.p2);
                const h    = H * b.h * b.rnd * (1 + en * 0.9) * ray;
                cx.drawImage(b.img, 0, 0, 1, 128, x, base - h, 3, h);
            }
        });

        cx.globalAlpha = 1;
        cx.globalCompositeOperation = 'source-over';
    }
    requestAnimationFrame(frame);

    /* =====================================================
       3. JENDELA SAMPING
       ===================================================== */
    const menuBtn = $('#menu-btn');
    const drawer  = $('#drawer');

    function setDrawer(open) {
        document.body.classList.toggle('drawer-open', open);
        menuBtn.setAttribute('aria-expanded', String(open));
        menuBtn.setAttribute('aria-label', open ? 'Tutup menu' : 'Buka menu');
        drawer.setAttribute('aria-hidden', String(!open));
    }
    menuBtn.addEventListener('click', () => setDrawer(!document.body.classList.contains('drawer-open')));
    $('#scrim').addEventListener('click', () => setDrawer(false));
    addEventListener('keydown', (ev) => { if (ev.key === 'Escape') setDrawer(false); });

    function setActive() {
        const path = location.pathname.replace(/\/+$/, '') || '/';
        $$('#drawer .menu a').forEach((a) => {
            const p = new URL(a.href).pathname.replace(/\/+$/, '') || '/';
            a.classList.toggle('active', p === '/' ? path === '/' : path.startsWith(p));
        });
    }

    /* =====================================================
       4. PINDAH HALAMAN TANPA MEMUAT ULANG (agar lagu lanjut)
       ===================================================== */
    const page = $('#page');
    let ctrl = null;

    async function go(url, { push = true, method = 'GET', body = null } = {}) {
        if (ctrl) ctrl.abort();
        ctrl = new AbortController();
        page.classList.add('loading');
        try {
            const res  = await fetch(url, { method, body, signal: ctrl.signal, credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const html = await res.text();
            const doc  = new DOMParser().parseFromString(html, 'text/html');
            const next = doc.getElementById('page');

            if (!next) { // respons bukan halaman biasa (misalnya error)
                if (method === 'GET') location.href = url;
                else { document.open(); document.write(html); document.close(); }
                return;
            }

            document.title = doc.title;
            page.innerHTML = next.innerHTML;
            if (push) history.pushState(null, '', res.url);

            setDrawer(false);
            setActive();
            markPlaying();
            window.scrollTo(0, 0);
            page.style.animation = 'none'; void page.offsetWidth; page.style.animation = '';
        } catch (err) {
            if (err.name !== 'AbortError') location.href = url;
        } finally {
            page.classList.remove('loading');
        }
    }

    document.addEventListener('click', (ev) => {
        if (ev.defaultPrevented || ev.button !== 0 || ev.metaKey || ev.ctrlKey || ev.shiftKey || ev.altKey) return;
        const a = ev.target.closest('a[href]');
        if (!a || (a.target && a.target !== '_self') || a.hasAttribute('download')) return;
        const u = new URL(a.href, location.href);
        if (u.origin !== location.origin) return;
        if (u.pathname === location.pathname && u.search === location.search && u.hash) return;
        ev.preventDefault();
        go(u.href);
    });

    document.addEventListener('submit', (ev) => {
        const f = ev.target.closest('form[data-pjax]');
        if (!f) return;
        ev.preventDefault();
        go(f.action, { method: f.method.toUpperCase(), body: new FormData(f) });
    });

    addEventListener('popstate', () => go(location.href, { push: false }));

    setActive();
})();