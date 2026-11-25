(function () {
  'use strict';
  var ZH = window.ZH, $ = ZH.$, $$ = ZH.$$;
  var input = $('#textInput'), used = false;
  var STOP = /^(the|and|for|are|but|not|you|all|any|can|had|her|was|one|our|out|has|have|that|this|with|from|they|will|your|what|when|were|been|their|which|there|would|about|into|than|them|then|its|also|just)$/;

  function set(key, val) { var el = $('[data-s="' + key + '"]'); if (el) el.textContent = val; }
  function minutes(words, wpm) {
    if (!words) return '0 min';
    var m = words / wpm;
    return m < 1 ? Math.max(1, Math.round(m * 60)) + ' sec' : Math.ceil(m) + ' min';
  }

  function update() {
    var t = input.value, trimmed = t.trim();
    var words = trimmed ? trimmed.split(/\s+/).length : 0;
    set('words', words.toLocaleString());
    set('chars', t.length.toLocaleString());
    set('charsNoSpace', t.replace(/\s/g, '').length.toLocaleString());
    set('sentences', trimmed ? (trimmed.match(/[^.!?۔؟]+[.!?۔؟]*/g) || []).filter(function (s) { return s.trim(); }).length : 0);
    set('paragraphs', trimmed ? trimmed.split(/\n\s*\n/).filter(function (p) { return p.trim(); }).length : 0);
    set('reading', minutes(words, 200));
    set('speaking', minutes(words, 130));

    // keyword density (words of 3+ letters, ignoring very common words)
    var counts = {};
    (t.toLowerCase().match(/[\p{L}\p{N}']{3,}/gu) || []).forEach(function (w) { if (!STOP.test(w)) counts[w] = (counts[w] || 0) + 1; });
    var top = Object.keys(counts).filter(function (k) { return counts[k] > 1; })
      .sort(function (a, b) { return counts[b] - counts[a]; }).slice(0, 8);
    $('#keywordsBox').classList.toggle('hidden', !top.length);
    $('#keywords').innerHTML = top.map(function (k) { return '<span class="chip">' + ZH.esc(k) + ' · ' + counts[k] + '</span>'; }).join('');
    if (words && !used) { used = true; ZH.used(); }
  }

  input.addEventListener('input', update);
  $('#clearBtn').addEventListener('click', function () { input.value = ''; update(); input.focus(); });
  $('#copyBtn').addEventListener('click', function () { if (input.value) ZH.copy(input.value, 'Text copied'); });
  update();
})();
