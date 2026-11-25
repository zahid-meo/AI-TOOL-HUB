(function () {
  'use strict';
  var ZH = window.ZH, $ = ZH.$;
  var input = $('#inputText'), list = $('#styleList'), used = false;

  /* Build a mapper from Unicode code-point ranges (+ exceptions for "holes" in the math alphabets). */
  function range(upper, lower, digit, ex) {
    ex = ex || {};
    return function (ch) {
      var c = ch.codePointAt(0);
      if (ex[ch]) return ex[ch];
      if (c >= 65 && c <= 90) return String.fromCodePoint(upper + c - 65);
      if (c >= 97 && c <= 122) return lower === null ? ch : String.fromCodePoint(lower + c - 97);
      if (digit && c >= 48 && c <= 57) return String.fromCodePoint(digit + c - 48);
      return ch;
    };
  }
  function table(str, from) {   // map a-z through a 26-char string
    var m = {}; Array.from(str).forEach(function (c, i) { m[String.fromCharCode(from + i)] = c; }); return m;
  }
  var SMALLCAPS = table('ᴀʙᴄᴅᴇꜰɢʜɪᴊᴋʟᴍɴᴏᴘǫʀꜱᴛᴜᴠᴡxʏᴢ', 97);
  var FLIP = { a: 'ɐ', b: 'q', c: 'ɔ', d: 'p', e: 'ǝ', f: 'ɟ', g: 'ƃ', h: 'ɥ', i: 'ᴉ', j: 'ɾ', k: 'ʞ', l: 'l', m: 'ɯ', n: 'u', o: 'o', p: 'd', q: 'b', r: 'ɹ', s: 's', t: 'ʇ', u: 'n', v: 'ʌ', w: 'ʍ', x: 'x', y: 'ʎ', z: 'z', '.': '˙', ',': '\'', '?': '¿', '!': '¡' };

  function circled(ch) {
    var c = ch.codePointAt(0);
    if (c >= 65 && c <= 90) return String.fromCodePoint(0x24B6 + c - 65);
    if (c >= 97 && c <= 122) return String.fromCodePoint(0x24D0 + c - 97);
    if (c === 48) return '⓪';
    if (c >= 49 && c <= 57) return String.fromCodePoint(0x2460 + c - 49);
    return ch;
  }
  function combine(mark) { return function (s) { return Array.from(s).map(function (c) { return /\s/.test(c) ? c : c + mark; }).join(''); }; }
  function per(fn) { return function (s) { return Array.from(s).map(fn).join(''); }; }

  var STYLES = [
    ['Bold', per(range(0x1D400, 0x1D41A, 0x1D7CE))],
    ['Italic', per(range(0x1D434, 0x1D44E, null, { h: 'ℎ' }))],
    ['Bold Italic', per(range(0x1D468, 0x1D482, null))],
    ['Script', per(range(0x1D49C, 0x1D4B6, null, { B: 'ℬ', E: 'ℰ', F: 'ℱ', H: 'ℋ', I: 'ℐ', L: 'ℒ', M: 'ℳ', R: 'ℛ', e: 'ℯ', g: 'ℊ', o: 'ℴ' }))],
    ['Bold Script', per(range(0x1D4D0, 0x1D4EA, null))],
    ['Fraktur', per(range(0x1D504, 0x1D51E, null, { C: 'ℭ', H: 'ℌ', I: 'ℑ', R: 'ℜ', Z: 'ℨ' }))],
    ['Double-struck', per(range(0x1D538, 0x1D552, 0x1D7D8, { C: 'ℂ', H: 'ℍ', N: 'ℕ', P: 'ℙ', Q: 'ℚ', R: 'ℝ', Z: 'ℤ' }))],
    ['Sans-serif', per(range(0x1D5A0, 0x1D5BA, 0x1D7E2))],
    ['Sans Bold', per(range(0x1D5D4, 0x1D5EE, 0x1D7EC))],
    ['Monospace', per(range(0x1D670, 0x1D68A, 0x1D7F6))],
    ['Circled', per(circled)],
    ['Small Caps', per(function (c) { return SMALLCAPS[c.toLowerCase()] || c; })],
    ['Upside down', function (s) { return Array.from(s.toLowerCase()).map(function (c) { return FLIP[c] || c; }).reverse().join(''); }],
    ['Underline', combine('\u0332')],
    ['Strikethrough', combine('\u0336')],
    ['Wide', per(function (c) { var n = c.charCodeAt(0); return n > 32 && n < 127 ? String.fromCharCode(n + 0xFEE0) : (c === ' ' ? '\u3000' : c); })],
    ['Sparkles', function (s) { return '✨ ' + s + ' ✨'; }],
    ['Brackets', function (s) { return '【' + s + '】'; }]
  ];

  function render() {
    var text = input.value || 'Your text';
    list.innerHTML = '';
    STYLES.forEach(function (st) {
      var out = st[1](text);
      var row = document.createElement('div'); row.className = 'style-item';
      var left = document.createElement('div');
      var small = document.createElement('small'); small.textContent = st[0];
      var txt = document.createElement('span'); txt.className = 'txt'; txt.textContent = out;
      left.appendChild(small); left.appendChild(txt);
      var btn = document.createElement('button'); btn.type = 'button'; btn.className = 'btn btn-secondary btn-sm'; btn.textContent = 'Copy';
      btn.addEventListener('click', function () { ZH.copy(out, st[0] + ' copied'); if (!used) { used = true; ZH.used(); } });
      row.appendChild(left); row.appendChild(btn); list.appendChild(row);
    });
  }
  input.addEventListener('input', render);
  render();
})();
