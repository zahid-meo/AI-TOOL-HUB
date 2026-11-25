(function () {
  'use strict';
  var ZH = window.ZH, $ = ZH.$;
  var inp = $('#jsonIn'), out = $('#jsonOut'), status = $('#jsonStatus');

  function setStatus(msg, ok) {
    status.className = 'alert alert--' + (ok ? 'success' : 'error');
    status.textContent = msg;
  }
  function sortDeep(v) {
    if (Array.isArray(v)) return v.map(sortDeep);
    if (v && typeof v === 'object') {
      var o = {}; Object.keys(v).sort().forEach(function (k) { o[k] = sortDeep(v[k]); }); return o;
    }
    return v;
  }
  function locate(text, msg) {
    var m = /position (\d+)/i.exec(msg), line, col;
    if (m) {
      var pos = parseInt(m[1], 10), before = text.slice(0, pos).split('\n');
      line = before.length; col = before[before.length - 1].length + 1;
      return ' (line ' + line + ', column ' + col + ')';
    }
    m = /line (\d+) column (\d+)/i.exec(msg);
    return m ? ' (line ' + m[1] + ', column ' + m[2] + ')' : '';
  }
  function parse() {
    var text = inp.value;
    if (!text.trim()) { ZH.toast('Paste some JSON first.', 'error'); return undefined; }
    try { var v = JSON.parse(text); return $('#sortKeys').checked ? sortDeep(v) : v; }
    catch (e) { out.value = ''; setStatus('Invalid JSON: ' + e.message + locate(text, e.message), false); return undefined; }
  }
  function run(minify) {
    var v = parse(); if (v === undefined) return;
    var ind = $('#indent').value;
    out.value = minify ? JSON.stringify(v) : JSON.stringify(v, null, ind === 'tab' ? '\t' : parseInt(ind, 10));
    setStatus('✔ Valid JSON — ' + ZH.formatBytes(new Blob([out.value]).size) + (minify ? ' (minified)' : ' (formatted)'), true);
    ZH.used();
  }
  $('#formatBtn').addEventListener('click', function () { run(false); });
  $('#minifyBtn').addEventListener('click', function () { run(true); });
  $('#copyBtn').addEventListener('click', function () { if (out.value) ZH.copy(out.value, 'JSON copied'); else ZH.toast('Nothing to copy yet.', 'error'); });
  $('#downloadBtn').addEventListener('click', function () {
    if (!out.value) return ZH.toast('Format your JSON first.', 'error');
    ZH.download(new Blob([out.value], { type: 'application/json' }), 'data.json');
  });
  $('#clearBtn').addEventListener('click', function () { inp.value = ''; out.value = ''; status.className = 'hidden'; });
})();
