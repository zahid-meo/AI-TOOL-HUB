(function () {
  'use strict';
  var ZH = window.ZH, $ = ZH.$;
  var SETS = {
    upper: 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', lower: 'abcdefghijklmnopqrstuvwxyz',
    num: '0123456789', sym: '!@#$%^&*()-_=+[]{};:,.?/~'
  };
  var AMBIGUOUS = /[O0oIl1|]/g;
  var len = $('#lenRange'), out = $('#pwOut');

  /* Unbiased random integer in [0, max) using crypto.getRandomValues (rejection sampling). */
  function secureInt(max) {
    var buf = new Uint32Array(1), limit = Math.floor(4294967296 / max) * max;
    do { crypto.getRandomValues(buf); } while (buf[0] >= limit);
    return buf[0] % max;
  }

  function pools() {
    var chosen = [];
    [['optUpper', 'upper'], ['optLower', 'lower'], ['optNum', 'num'], ['optSym', 'sym']].forEach(function (p) {
      if ($('#' + p[0]).checked) {
        var s = SETS[p[1]]; if ($('#optAmb').checked) s = s.replace(AMBIGUOUS, '');
        chosen.push(s);
      }
    });
    return chosen;
  }

  function strength(length, poolSize) {
    var bits = length * Math.log2(poolSize);
    var label, color, pct;
    if (bits < 40) { label = 'Weak'; color = '#ef4444'; pct = 20; }
    else if (bits < 60) { label = 'Fair'; color = '#f59e0b'; pct = 45; }
    else if (bits < 80) { label = 'Good'; color = '#84cc16'; pct = 70; }
    else { label = 'Strong'; color = '#22c55e'; pct = 100; }
    return { bits: Math.round(bits), label: label, color: color, pct: pct };
  }

  function generate(manual) {
    var sets = pools();
    if (!sets.length) { if (manual) ZH.toast('Select at least one character type.', 'error'); out.value = ''; return; }
    var n = parseInt(len.value, 10), all = sets.join(''), chars = [], i;
    for (i = 0; i < sets.length; i++) chars.push(sets[i][secureInt(sets[i].length)]);   // at least one of each type
    while (chars.length < n) chars.push(all[secureInt(all.length)]);
    for (i = chars.length - 1; i > 0; i--) { var j = secureInt(i + 1), t = chars[i]; chars[i] = chars[j]; chars[j] = t; }   // shuffle
    out.value = chars.slice(0, n).join('');

    var s = strength(n, all.length);
    $('#meterBar').style.width = s.pct + '%'; $('#meterBar').style.background = s.color;
    $('#meterText').textContent = 'Strength: ' + s.label + ' (~' + s.bits + ' bits of entropy)';
    if (manual) ZH.used();
  }

  len.addEventListener('input', function () { $('#lenVal').textContent = len.value; generate(false); });
  ['optUpper', 'optLower', 'optNum', 'optSym', 'optAmb'].forEach(function (id) { $('#' + id).addEventListener('change', function () { generate(false); }); });
  $('#genBtn').addEventListener('click', function () { generate(true); });
  $('#copyBtn').addEventListener('click', function () { if (out.value) ZH.copy(out.value, 'Password copied'); });
  out.addEventListener('focus', function () { out.select(); });
  generate(false);
})();
