(function () {
  'use strict';
  var ZH = window.ZH, $ = ZH.$;
  var dobIn = $('#dob'), todIn = $('#tod'), out = $('#result');

  function pad(n) { return String(n).padStart(2, '0'); }
  function todayStr() { var d = new Date(); return d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate()); }
  /* Parse YYYY-MM-DD as a LOCAL date (new Date('YYYY-MM-DD') would be UTC and can shift the day). */
  function parse(s) { var p = s.split('-').map(Number); return { y: p[0], m: p[1], d: p[2] }; }
  function daysInMonth(y, m) { return new Date(y, m, 0).getDate(); }        // m = 1..12
  function utc(o) { return Date.UTC(o.y, o.m - 1, o.d); }
  var WEEKDAYS = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
  var MONTHS = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

  todIn.value = todayStr();
  $('#todayBtn').addEventListener('click', function () { todIn.value = todayStr(); });

  $('#calcAge').addEventListener('click', function () {
    if (!dobIn.value) return ZH.toast('Please select your date of birth.', 'error');
    var a = parse(dobIn.value), b = parse(todIn.value || todayStr());
    if (utc(b) < utc(a)) return ZH.toast('The target date must be after the birth date.', 'error');

    var y = b.y - a.y, m = b.m - a.m, d = b.d - a.d;
    if (d < 0) { m--; var pm = b.m - 1 || 12, py = b.m - 1 ? b.y : b.y - 1; d += daysInMonth(py, pm); }
    if (m < 0) { y--; m += 12; }

    var totalDays = Math.round((utc(b) - utc(a)) / 86400000);
    var totalMonths = y * 12 + m;

    // Next birthday (29 Feb birthdays fall on 28 Feb in non-leap years)
    var ny = b.y, nm = a.m, nd = a.d;
    function clamp(yy) { return (nm === 2 && nd === 29 && daysInMonth(yy, 2) < 29) ? 28 : nd; }
    var next = { y: ny, m: nm, d: clamp(ny) };
    if (utc(next) < utc(b)) { ny++; next = { y: ny, m: nm, d: clamp(ny) }; }
    var untilBday = Math.round((utc(next) - utc(b)) / 86400000);
    var bornDay = WEEKDAYS[new Date(a.y, a.m - 1, a.d).getDay()];

    out.innerHTML =
      '<p class="muted">Your age</p>' +
      '<p class="result-big">' + y + ' years, ' + m + ' months, ' + d + ' days</p>' +
      '<div class="stat-grid" style="margin-top:1rem">' +
      '<div class="stat"><b>' + totalMonths.toLocaleString() + '</b><span>Total months</span></div>' +
      '<div class="stat"><b>' + Math.floor(totalDays / 7).toLocaleString() + '</b><span>Total weeks</span></div>' +
      '<div class="stat"><b>' + totalDays.toLocaleString() + '</b><span>Total days</span></div>' +
      '<div class="stat"><b>' + (totalDays * 24).toLocaleString() + '</b><span>Total hours</span></div></div>' +
      '<p style="margin-top:1rem">🎂 Next birthday: <strong>' + (untilBday === 0 ? 'today — happy birthday!' : untilBday + ' day' + (untilBday === 1 ? '' : 's') + ' to go') +
      '</strong> (' + next.d + ' ' + MONTHS[next.m - 1] + ' ' + next.y + ')</p>' +
      '<p>📅 You were born on a <strong>' + bornDay + '</strong>.</p>';
    out.classList.remove('hidden');
    ZH.used();
  });
})();
