/* ============================================
   main.js — AI Tools Hub (global behaviour)
   Exposes window.ZH helpers used by tool scripts.
   ============================================ */
(function () {
  'use strict';

  var CFG = window.ZH || {};
  var ZH = window.ZH = Object.assign(CFG, {});

  /* ---------- tiny helpers ---------- */
  var $ = function (sel, ctx) { return (ctx || document).querySelector(sel); };
  var $$ = function (sel, ctx) { return Array.prototype.slice.call((ctx || document).querySelectorAll(sel)); };
  var store = {
    get: function (k, d) { try { var v = localStorage.getItem(k); return v === null ? d : JSON.parse(v); } catch (e) { return d; } },
    set: function (k, v) { try { localStorage.setItem(k, JSON.stringify(v)); } catch (e) {} }
  };
  ZH.$ = $; ZH.$$ = $$; ZH.store = store;

  ZH.esc = function (s) {
    return String(s).replace(/[&<>"']/g, function (c) {
      return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
    });
  };

  /* ---------- toast ---------- */
  ZH.toast = function (msg, type) {
    var wrap = $('#toast-wrap');
    if (!wrap) return;
    var t = document.createElement('div');
    t.className = 'toast' + (type === 'error' ? ' toast--error' : '');
    t.textContent = msg;
    wrap.appendChild(t);
    setTimeout(function () { t.remove(); }, 2600);
  };

  /* ---------- clipboard ---------- */
  ZH.copy = function (text, okMsg) {
    var done = function () { ZH.toast(okMsg || 'Copied to clipboard'); };
    if (navigator.clipboard && window.isSecureContext) {
      navigator.clipboard.writeText(text).then(done, function () { fallback(); });
    } else { fallback(); }
    function fallback() {
      var ta = document.createElement('textarea');
      ta.value = text; ta.style.position = 'fixed'; ta.style.opacity = '0';
      document.body.appendChild(ta); ta.select();
      try { document.execCommand('copy'); done(); } catch (e) { ZH.toast('Copy failed', 'error'); }
      ta.remove();
    }
  };

  /* ---------- POST helper (adds CSRF) ---------- */
  ZH.post = function (url, data) {
    var fd = data instanceof FormData ? data : new FormData();
    if (!(data instanceof FormData)) Object.keys(data || {}).forEach(function (k) { fd.append(k, data[k]); });
    if (!fd.has('csrf')) fd.append('csrf', ZH.csrf || '');
    return fetch(url, { method: 'POST', body: fd, credentials: 'same-origin' })
      .then(function (r) { return r.json().catch(function () { return { ok: false, message: 'Unexpected server response.' }; }); })
      .catch(function () { return { ok: false, message: 'Network error. Please try again.' }; });
  };

  /* ---------- download helper ---------- */
  ZH.download = function (blobOrUrl, filename) {
    var a = document.createElement('a');
    a.href = typeof blobOrUrl === 'string' ? blobOrUrl : URL.createObjectURL(blobOrUrl);
    a.download = filename;
    document.body.appendChild(a); a.click(); a.remove();
  };

  ZH.formatBytes = function (b) {
    if (b < 1024) return b + ' B';
    if (b < 1048576) return (b / 1024).toFixed(2) + ' KB';
    return (b / 1048576).toFixed(2) + ' MB';
  };

  /* ---------- usage tracking (anonymous counters) ---------- */
  var usedSent = false;
  function track(type) {
    if (!ZH.tool || !ZH.api || !ZH.api.track) return;
    var fd = new FormData();
    fd.append('slug', ZH.tool); fd.append('type', type); fd.append('csrf', ZH.csrf || '');
    if (navigator.sendBeacon) { navigator.sendBeacon(ZH.api.track, fd); }
    else { fetch(ZH.api.track, { method: 'POST', body: fd, credentials: 'same-origin', keepalive: true }); }
  }
  ZH.used = function () { if (!usedSent) { usedSent = true; track('use'); } };

  /* ---------- theme ---------- */
  function setThemeIcons(theme) {
    var sun = $('#icon-sun'), moon = $('#icon-moon');
    if (!sun || !moon) return;
    sun.classList.toggle('hidden', theme !== 'dark');
    moon.classList.toggle('hidden', theme === 'dark');
  }
  function toggleTheme() {
    var next = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', next);
    try { localStorage.setItem('zh-theme', next); } catch (e) {}
    setThemeIcons(next);
  }

  /* ---------- favorites + recent ---------- */
  function favs() { return store.get('zh-favs', []); }
  function syncFavButtons() {
    var list = favs();
    $$('[data-fav]').forEach(function (b) {
      var on = list.indexOf(b.getAttribute('data-fav')) !== -1;
      b.classList.toggle('is-fav', on);
      b.textContent = on ? '★' : '☆';
    });
  }
  function toggleFav(slug) {
    var list = favs(), i = list.indexOf(slug);
    if (i === -1) { list.push(slug); ZH.toast('Added to favorites'); } else { list.splice(i, 1); ZH.toast('Removed from favorites'); }
    store.set('zh-favs', list);
    syncFavButtons();
    renderPersonalRows();
  }
  function pushRecent(slug) {
    var r = store.get('zh-recent', []).filter(function (s) { return s !== slug; });
    r.unshift(slug); store.set('zh-recent', r.slice(0, 6));
  }

  /* Favorites / recently used rows on the home page */
  function renderPersonalRows() {
    if (!ZH.tools) return;
    var map = {}; ZH.tools.forEach(function (t) { map[t.slug] = t; });
    [['zh-favs', '#favorites-row', '#favorites-section'], ['zh-recent', '#recent-row', '#recent-section']].forEach(function (cfg) {
      var row = $(cfg[1]), sec = $(cfg[2]); if (!row || !sec) return;
      var items = store.get(cfg[0], []).map(function (s) { return map[s]; }).filter(Boolean);
      sec.classList.toggle('hidden', !items.length);
      row.innerHTML = items.map(function (t) {
        return '<a class="chip" href="' + ZH.esc(t.url) + '">' + t.icon + ' ' + ZH.esc(t.name) + '</a>';
      }).join('');
    });
  }

  /* ---------- home: filter pills ---------- */
  function initFilters() {
    var pills = $$('.filter-pill');
    if (!pills.length) return;
    pills.forEach(function (p) {
      p.addEventListener('click', function () {
        pills.forEach(function (x) { x.classList.remove('active'); });
        p.classList.add('active');
        var cat = p.getAttribute('data-filter');
        $$('.tool-card[data-cat]', $('#tools-grid')).forEach(function (card) {
          card.style.display = (cat === 'all' || card.getAttribute('data-cat') === cat) ? '' : 'none';
        });
      });
    });
  }

  /* ---------- home: search ---------- */
  function initSearch() {
    var input = $('#tool-search'), box = $('#search-results');
    if (!input || !box || !ZH.tools) return;
    var active = -1;

    function render(q) {
      q = q.trim().toLowerCase();
      if (!q) { box.classList.add('hidden'); box.innerHTML = ''; active = -1; return; }
      var words = q.split(/\s+/);
      var hits = ZH.tools.filter(function (t) {
        var hay = (t.name + ' ' + t.kw + ' ' + t.short + ' ' + t.cat).toLowerCase();
        return words.every(function (w) { return hay.indexOf(w) !== -1; });
      }).slice(0, 8);
      box.classList.remove('hidden');
      active = hits.length ? 0 : -1;
      box.innerHTML = hits.length ? hits.map(function (t, i) {
        return '<a href="' + ZH.esc(t.url) + '" class="' + (i === 0 ? 'is-active' : '') + '"><span style="font-size:1.4rem">' + t.icon +
          '</span><span><strong>' + ZH.esc(t.name) + '</strong><small>' + ZH.esc(t.cat) + '</small></span></a>';
      }).join('') : '<div class="empty">No tools found for “' + ZH.esc(q) + '”.</div>';
    }
    input.addEventListener('input', function () { render(input.value); });
    input.addEventListener('keydown', function (e) {
      var links = $$('a', box);
      if (!links.length) return;
      if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
        e.preventDefault();
        active = (active + (e.key === 'ArrowDown' ? 1 : -1) + links.length) % links.length;
        links.forEach(function (l, i) { l.classList.toggle('is-active', i === active); });
      } else if (e.key === 'Enter' && links[active]) { window.location.href = links[active].href; }
      else if (e.key === 'Escape') { input.value = ''; render(''); }
    });
    document.addEventListener('click', function (e) { if (!e.target.closest('.search-box')) box.classList.add('hidden'); });
    document.addEventListener('keydown', function (e) {
      if (e.key === '/' && !/^(INPUT|TEXTAREA|SELECT)$/.test(document.activeElement.tagName)) { e.preventDefault(); input.focus(); }
    });
  }

  /* ---------- generic AJAX forms ---------- */
  function bindForm(sel, url, onOk) {
    var form = $(sel); if (!form) return;
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var btn = $('button[type="submit"]', form);
      if (!form.checkValidity()) { form.reportValidity(); return; }
      if (btn) { btn.disabled = true; }
      ZH.post(url, new FormData(form)).then(function (res) {
        if (btn) btn.disabled = false;
        if (res.ok) { form.reset(); if (onOk) onOk(res); }
        ZH.toast(res.message || (res.ok ? 'Done!' : 'Something went wrong.'), res.ok ? '' : 'error');
        var box = $('#form-result');
        if (box) { box.className = 'alert alert--' + (res.ok ? 'success' : 'error'); box.textContent = res.message || ''; }
      });
    });
  }

  /* ---------- feedback widget ---------- */
  function initFeedback() {
    var box = $('#feedback'); if (!box) return;
    $$('[data-rate]', box).forEach(function (b) {
      b.addEventListener('click', function () {
        ZH.post(ZH.api.feedback, { slug: box.getAttribute('data-tool'), rating: b.getAttribute('data-rate') }).then(function (res) {
          var msg = $('#feedback-msg');
          if (msg) msg.textContent = res.ok ? 'Thanks for the feedback!' : (res.message || 'Could not save feedback.');
          if (res.ok) $$('[data-rate]', box).forEach(function (x) { x.disabled = true; });
        });
      });
    });
  }

  /* ---------- boot ---------- */
  document.addEventListener('DOMContentLoaded', function () {
    setThemeIcons(document.documentElement.getAttribute('data-theme') || 'light');
    var tb = $('#theme-toggle'); if (tb) tb.addEventListener('click', toggleTheme);

    var hb = $('#hamburger-btn'), mn = $('#mobile-nav');
    if (hb && mn) hb.addEventListener('click', function () {
      var open = mn.classList.toggle('open'); hb.setAttribute('aria-expanded', open ? 'true' : 'false');
    });

    document.addEventListener('click', function (e) {
      var fav = e.target.closest('[data-fav]');
      if (fav) { e.preventDefault(); e.stopPropagation(); toggleFav(fav.getAttribute('data-fav')); }
    });
    syncFavButtons();
    if (ZH.tool) { pushRecent(ZH.tool); track('view'); }
    renderPersonalRows();
    initFilters(); initSearch(); initFeedback();

    bindForm('#newsletter-form', ZH.api && ZH.api.newsletter);
    bindForm('#contact-form', ZH.api && ZH.api.contact);

    var btt = $('#back-to-top');
    if (btt) {
      window.addEventListener('scroll', function () { btt.classList.toggle('hidden', window.scrollY < 500); }, { passive: true });
      btt.addEventListener('click', function () { window.scrollTo({ top: 0, behavior: 'smooth' }); });
    }

    var cb = $('#cookie-bar');
    if (cb && !store.get('zh-cookie-ok', false)) {
      cb.classList.remove('hidden');
      $('#cookie-accept').addEventListener('click', function () { store.set('zh-cookie-ok', true); cb.classList.add('hidden'); });
    }
  });
})();
