(function () {
  'use strict';
  var ZH = window.ZH, $ = ZH.$;
  var out = $('#result');

  function units() { return document.querySelector('input[name="units"]:checked').value; }
  Array.prototype.forEach.call(document.querySelectorAll('input[name="units"]'), function (r) {
    r.addEventListener('change', function () {
      var metric = units() === 'metric';
      $('#metricFields').classList.toggle('hidden', !metric);
      $('#imperialFields').classList.toggle('hidden', metric);
      out.classList.add('hidden');
    });
  });

  function category(b) {
    if (b < 18.5) return ['Underweight', '#3b82f6'];
    if (b < 25) return ['Normal weight', '#22c55e'];
    if (b < 30) return ['Overweight', '#f59e0b'];
    return ['Obese', '#ef4444'];
  }

  $('#calcBmi').addEventListener('click', function () {
    var hM, wKg;
    if (units() === 'metric') {
      hM = parseFloat($('#heightCm').value) / 100; wKg = parseFloat($('#weightKg').value);
    } else {
      var ft = parseFloat($('#heightFt').value) || 0, inch = parseFloat($('#heightIn').value) || 0;
      hM = (ft * 12 + inch) * 0.0254; wKg = parseFloat($('#weightLb').value) * 0.45359237;
    }
    if (!(hM > 0.5 && hM < 2.6) || !(wKg > 10 && wKg < 500)) return ZH.toast('Please enter a realistic height and weight.', 'error');

    var bmi = wKg / (hM * hM), cat = category(bmi);
    var lo = 18.5 * hM * hM, hi = 24.9 * hM * hM;
    var range = units() === 'metric'
      ? lo.toFixed(1) + ' – ' + hi.toFixed(1) + ' kg'
      : (lo / 0.45359237).toFixed(1) + ' – ' + (hi / 0.45359237).toFixed(1) + ' lb';
    var pos = Math.min(100, Math.max(0, (bmi - 10) / 30 * 100));   // scale 10..40

    out.innerHTML =
      '<p class="muted">Your BMI</p><p class="result-big" style="color:' + cat[1] + '">' + bmi.toFixed(1) + ' — ' + cat[0] + '</p>' +
      '<div style="position:relative;height:10px;border-radius:99px;margin:1rem 0;background:linear-gradient(90deg,#3b82f6 0 28%,#22c55e 28% 50%,#f59e0b 50% 66%,#ef4444 66%)">' +
      '<span style="position:absolute;top:-5px;left:calc(' + pos + '% - 10px);width:20px;height:20px;border-radius:50%;background:var(--bg-card);border:3px solid ' + cat[1] + '"></span></div>' +
      '<p>Healthy weight range for your height: <strong>' + range + '</strong></p>' +
      '<p class="muted" style="font-size:.85rem">BMI is a screening tool, not a diagnosis. It does not account for muscle mass, age or body composition — speak to a healthcare professional for advice.</p>';
    out.classList.remove('hidden');
    ZH.used();
  });
})();
