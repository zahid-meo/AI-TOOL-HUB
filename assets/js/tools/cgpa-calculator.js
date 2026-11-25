(function () {
  'use strict';
  var ZH = window.ZH, $ = ZH.$;
  var tbody = $('#gradesTable tbody'), out = $('#result');

  function renumber() {
    Array.prototype.forEach.call(tbody.rows, function (r, i) { r.cells[0].textContent = i + 1; });
  }
  function addRow(focus) {
    var tr = tbody.insertRow();
    tr.innerHTML =
      '<td></td>' +
      '<td><input type="text" class="name" maxlength="40" placeholder="Course name" aria-label="Course name"></td>' +
      '<td><input type="number" class="credit" min="0" step="0.5" placeholder="3" style="min-width:80px" aria-label="Credit hours"></td>' +
      '<td><input type="number" class="gp" min="0" max="4" step="0.01" placeholder="3.67" style="min-width:90px" aria-label="Grade point"></td>' +
      '<td><button type="button" class="btn btn-ghost btn-sm del" aria-label="Remove row">✕</button></td>';
    renumber();
    if (focus) tr.querySelector('.name').focus();
  }
  function reset() {
    tbody.innerHTML = '';
    for (var i = 0; i < 5; i++) addRow(false);
    $('#prevCgpa').value = ''; $('#prevCredits').value = '';
    out.classList.add('hidden');
  }

  tbody.addEventListener('click', function (e) {
    if (e.target.classList.contains('del') && tbody.rows.length > 1) {
      e.target.closest('tr').remove(); renumber();
    }
  });
  $('#addRow').addEventListener('click', function () { addRow(true); });
  $('#resetBtn').addEventListener('click', reset);

  $('#calcBtn').addEventListener('click', function () {
    var credits = 0, points = 0, used = 0, bad = false;
    Array.prototype.forEach.call(tbody.rows, function (r) {
      var c = parseFloat(r.querySelector('.credit').value), g = parseFloat(r.querySelector('.gp').value);
      if (isNaN(c) && isNaN(g)) return;                         // empty row
      if (isNaN(c) || isNaN(g) || c <= 0 || g < 0 || g > 4) { bad = true; return; }
      credits += c; points += c * g; used++;
    });
    if (bad) return ZH.toast('Check your rows: credits must be > 0 and grade points between 0 and 4.', 'error');
    if (!used) return ZH.toast('Enter at least one course.', 'error');

    var gpa = points / credits;
    var html = '<div class="stat-grid">' +
      '<div class="stat"><b>' + gpa.toFixed(2) + '</b><span>Semester GPA</span></div>' +
      '<div class="stat"><b>' + credits + '</b><span>Credits</span></div>' +
      '<div class="stat"><b>' + points.toFixed(2) + '</b><span>Quality points</span></div></div>';

    var pc = parseFloat($('#prevCgpa').value), pcr = parseFloat($('#prevCredits').value);
    if (!isNaN(pc) && !isNaN(pcr) && pcr > 0) {
      if (pc < 0 || pc > 4) return ZH.toast('Previous CGPA must be between 0 and 4.', 'error');
      var cum = (pc * pcr + points) / (pcr + credits);
      html += '<p style="margin-top:1rem">Cumulative CGPA</p><p class="result-big">' + cum.toFixed(3) + '</p>' +
        '<p class="muted">Based on ' + (pcr + credits) + ' total credits.</p>';
    } else {
      html += '<p style="margin-top:1rem">CGPA (this set of courses)</p><p class="result-big">' + gpa.toFixed(3) + '</p>';
    }
    out.innerHTML = html; out.classList.remove('hidden');
    ZH.used();
  });

  reset();
})();
