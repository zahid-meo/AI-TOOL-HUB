(function () {
  'use strict';
  var ZH = window.ZH, $ = ZH.$;

  /* factor = how many BASE units one of this unit equals */
  var CATS = {
    length:  { label: 'Length', base: 'm', units: { mm: ['Millimetre', 0.001], cm: ['Centimetre', 0.01], m: ['Metre', 1], km: ['Kilometre', 1000], in: ['Inch', 0.0254], ft: ['Foot', 0.3048], yd: ['Yard', 0.9144], mi: ['Mile', 1609.344], nmi: ['Nautical mile', 1852] } },
    weight:  { label: 'Weight / Mass', base: 'kg', units: { mg: ['Milligram', 1e-6], g: ['Gram', 0.001], kg: ['Kilogram', 1], t: ['Tonne', 1000], oz: ['Ounce', 0.028349523125], lb: ['Pound', 0.45359237], st: ['Stone', 6.35029318], tola: ['Tola', 0.01166375] } },
    area:    { label: 'Area', base: 'm²', units: { 'cm²': ['Square cm', 0.0001], 'm²': ['Square metre', 1], 'km²': ['Square km', 1e6], ha: ['Hectare', 10000], 'ft²': ['Square foot', 0.09290304], 'yd²': ['Square yard', 0.83612736], acre: ['Acre', 4046.8564224], marla: ['Marla (272.25 ft²)', 25.2928526], kanal: ['Kanal (20 marla)', 505.857052] } },
    volume:  { label: 'Volume', base: 'L', units: { mL: ['Millilitre', 0.001], L: ['Litre', 1], 'm³': ['Cubic metre', 1000], tsp: ['Teaspoon (US)', 0.00492892159], tbsp: ['Tablespoon (US)', 0.0147867648], 'fl oz': ['Fluid ounce (US)', 0.0295735296], cup: ['Cup (US)', 0.2365882365], pt: ['Pint (US)', 0.473176473], qt: ['Quart (US)', 0.946352946], gal: ['Gallon (US)', 3.785411784] } },
    speed:   { label: 'Speed', base: 'm/s', units: { 'm/s': ['Metres/second', 1], 'km/h': ['Kilometres/hour', 1 / 3.6], mph: ['Miles/hour', 0.44704], kn: ['Knot', 0.514444444], 'ft/s': ['Feet/second', 0.3048] } },
    time:    { label: 'Time', base: 's', units: { ms: ['Millisecond', 0.001], s: ['Second', 1], min: ['Minute', 60], h: ['Hour', 3600], day: ['Day', 86400], week: ['Week', 604800], year: ['Year (365 d)', 31536000] } },
    data:    { label: 'Digital storage', base: 'B', units: { bit: ['Bit', 0.125], B: ['Byte', 1], KB: ['Kilobyte (1024 B)', 1024], MB: ['Megabyte', 1048576], GB: ['Gigabyte', 1073741824], TB: ['Terabyte', 1099511627776] } },
    temp:    { label: 'Temperature', temp: true, units: { C: ['Celsius °C'], F: ['Fahrenheit °F'], K: ['Kelvin K'] } }
  };

  var cat = $('#category'), from = $('#fromUnit'), to = $('#toUnit'), val = $('#fromVal');
  var used = false;

  function toKelvin(v, u) { return u === 'C' ? v + 273.15 : u === 'F' ? (v - 32) * 5 / 9 + 273.15 : v; }
  function fromKelvin(k, u) { return u === 'C' ? k - 273.15 : u === 'F' ? (k - 273.15) * 9 / 5 + 32 : k; }
  function convert(v, f, t) {
    var c = CATS[cat.value];
    if (c.temp) return fromKelvin(toKelvin(v, f), t);
    return v * c.units[f][1] / c.units[t][1];
  }
  function fmt(n) {
    if (!isFinite(n)) return '—';
    if (n === 0) return '0';
    var abs = Math.abs(n);
    if (abs >= 1e12 || abs < 1e-6) return n.toExponential(6);
    return Number(n.toPrecision(10)).toLocaleString(undefined, { maximumFractionDigits: 8 });
  }

  function fillUnits() {
    var c = CATS[cat.value], keys = Object.keys(c.units);
    var opts = keys.map(function (k) { return '<option value="' + ZH.esc(k) + '">' + ZH.esc(c.units[k][0]) + ' (' + ZH.esc(k) + ')</option>'; }).join('');
    from.innerHTML = opts; to.innerHTML = opts;
    from.value = keys[Math.min(2, keys.length - 1)]; to.value = keys[Math.min(3, keys.length - 1)];
    if (c.temp) { from.value = 'C'; to.value = 'F'; }
    update();
  }

  function update() {
    var v = parseFloat(val.value), c = CATS[cat.value];
    if (isNaN(v)) { $('#resultVal').textContent = '—'; $('#allTable').innerHTML = ''; return; }
    var r = convert(v, from.value, to.value);
    $('#resultLabel').textContent = fmt(v) + ' ' + from.value + ' =';
    $('#resultVal').textContent = fmt(r) + ' ' + to.value;
    $('#allTable').innerHTML = '<tbody>' + Object.keys(c.units).map(function (k) {
      return '<tr><td>' + ZH.esc(c.units[k][0]) + '</td><td class="num">' + fmt(convert(v, from.value, k)) + ' ' + ZH.esc(k) + '</td></tr>';
    }).join('') + '</tbody>';
    if (!used) { used = true; ZH.used(); }
  }

  cat.innerHTML = Object.keys(CATS).map(function (k) { return '<option value="' + k + '">' + CATS[k].label + '</option>'; }).join('');
  cat.addEventListener('change', fillUnits);
  [from, to].forEach(function (el) { el.addEventListener('change', update); });
  val.addEventListener('input', update);
  $('#swapBtn').addEventListener('click', function () { var t = from.value; from.value = to.value; to.value = t; update(); });
  fillUnits();
})();
