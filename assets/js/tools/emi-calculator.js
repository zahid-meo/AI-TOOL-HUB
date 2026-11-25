(function () {
  'use strict';
  var ZH = window.ZH, $ = ZH.$;
  var out = $('#result'), wrap = $('#scheduleWrap'), table = $('#scheduleTable');

  function money(n, cur) {
    try { return new Intl.NumberFormat(undefined, { style: 'currency', currency: cur, maximumFractionDigits: 2 }).format(n); }
    catch (e) { return cur + ' ' + n.toFixed(2); }
  }

  $('#calcEmi').addEventListener('click', function () {
    var P = parseFloat($('#principal').value), ar = parseFloat($('#rate').value), t = parseInt($('#tenure').value, 10);
    var cur = $('#currency').value;
    if (!(P > 0) || isNaN(ar) || ar < 0 || !(t > 0)) return ZH.toast('Please fill in a valid amount, rate and tenure.', 'error');
    var n = $('#tenureUnit').value === 'y' ? t * 12 : t;
    if (n > 600) return ZH.toast('Maximum tenure is 600 months (50 years).', 'error');

    var r = ar / 12 / 100;
    var emi = r === 0 ? P / n : (P * r * Math.pow(1 + r, n)) / (Math.pow(1 + r, n) - 1);
    var total = emi * n, interest = total - P;
    var principalPct = Math.round(P / total * 100);

    out.innerHTML =
      '<p class="muted">Monthly EMI</p><p class="result-big">' + money(emi, cur) + '</p>' +
      '<div class="stat-grid" style="margin-top:1rem">' +
      '<div class="stat"><b>' + money(P, cur) + '</b><span>Principal</span></div>' +
      '<div class="stat"><b>' + money(interest, cur) + '</b><span>Total interest</span></div>' +
      '<div class="stat"><b>' + money(total, cur) + '</b><span>Total payment</span></div>' +
      '<div class="stat"><b>' + n + '</b><span>Instalments</span></div></div>' +
      '<div class="meter" style="margin-top:1rem" title="Principal vs interest"><i style="width:' + principalPct + '%;background:var(--primary)"></i></div>' +
      '<p class="muted" style="font-size:.85rem">Principal ' + principalPct + '% · Interest ' + (100 - principalPct) + '%</p>';
    out.classList.remove('hidden');

    var bal = P, rows = '<thead><tr><th class="num">#</th><th class="num">EMI</th><th class="num">Principal</th><th class="num">Interest</th><th class="num">Balance</th></tr></thead><tbody>';
    for (var i = 1; i <= n; i++) {
      var intr = bal * r, prin = emi - intr;
      bal = Math.max(0, bal - prin);
      if (i === n) bal = 0;
      rows += '<tr><td class="num">' + i + '</td><td class="num">' + money(emi, cur) + '</td><td class="num">' + money(prin, cur) +
        '</td><td class="num">' + money(intr, cur) + '</td><td class="num">' + money(bal, cur) + '</td></tr>';
    }
    table.innerHTML = rows + '</tbody>';
    wrap.classList.remove('hidden');
    ZH.used();
  });

  $('#resetBtn').addEventListener('click', function () {
    ['principal', 'rate', 'tenure'].forEach(function (id) { $('#' + id).value = ''; });
    out.classList.add('hidden'); wrap.classList.add('hidden');
  });
})();
