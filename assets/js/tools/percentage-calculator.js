(function () {
  'use strict';
  var ZH = window.ZH, $ = ZH.$;
  function num(id) { var v = parseFloat($(id).value); return isNaN(v) ? null : v; }
  function fmt(n) { return Number(n.toFixed(6)).toLocaleString(undefined, { maximumFractionDigits: 6 }); }
  function show(id, html) { $(id).innerHTML = html; }
  var used = false;
  function mark() { if (!used) { used = true; ZH.used(); } }

  function c1() {
    var x = num('#p1x'), y = num('#p1y');
    if (x === null || y === null) return show('#r1', 'Result: <strong>—</strong>');
    show('#r1', '<strong>' + fmt(x) + '%</strong> of <strong>' + fmt(y) + '</strong> = <span class="result-big">' + fmt(x / 100 * y) + '</span>'); mark();
  }
  function c2() {
    var x = num('#p2x'), y = num('#p2y');
    if (x === null || y === null) return show('#r2', 'Result: <strong>—</strong>');
    if (y === 0) return show('#r2', 'Y cannot be zero.');
    show('#r2', '<strong>' + fmt(x) + '</strong> is <span class="result-big">' + fmt(x / y * 100) + '%</span> of <strong>' + fmt(y) + '</strong>'); mark();
  }
  function c3() {
    var a = num('#p3a'), b = num('#p3b');
    if (a === null || b === null) return show('#r3', 'Result: <strong>—</strong>');
    if (a === 0) return show('#r3', 'The old value cannot be zero.');
    var ch = (b - a) / Math.abs(a) * 100;
    show('#r3', (ch >= 0 ? '📈 Increase' : '📉 Decrease') + ' of <span class="result-big">' + fmt(Math.abs(ch)) + '%</span> (difference: ' + fmt(b - a) + ')'); mark();
  }
  [['#p1x', c1], ['#p1y', c1], ['#p2x', c2], ['#p2y', c2], ['#p3a', c3], ['#p3b', c3]].forEach(function (p) {
    $(p[0]).addEventListener('input', p[1]);
  });
})();
