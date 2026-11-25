(function () {
  'use strict';
  var ZH = window.ZH, $ = ZH.$;
  var file = null, natW = 0, natH = 0;
  var wIn = $('#widthInput'), hIn = $('#heightInput'), pIn = $('#pctInput'), lock = $('#lockRatio');
  var mode = $('#resizeMode'), out = $('#output'), info = $('#fileInfo');

  function syncMode() {
    var px = mode.value === 'px';
    $('#pxWidthWrap').classList.toggle('hidden', !px);
    $('#pxHeightWrap').classList.toggle('hidden', !px);
    $('#pctWrap').classList.toggle('hidden', px);
  }
  mode.addEventListener('change', syncMode);

  wIn.addEventListener('input', function () {
    if (lock.checked && natW && wIn.value) hIn.value = Math.max(1, Math.round(wIn.value * natH / natW));
  });
  hIn.addEventListener('input', function () {
    if (lock.checked && natH && hIn.value) wIn.value = Math.max(1, Math.round(hIn.value * natW / natH));
  });

  ZH.img.dropzone($('#dropzone'), $('#fileInput'), function (f) {
    file = f;
    ZH.img.load(f).then(function (s) {
      natW = s.width; natH = s.height; wIn.value = natW; hIn.value = natH;
      info.textContent = f.name + ' — ' + natW + '×' + natH + 'px, ' + ZH.formatBytes(f.size);
      info.classList.remove('hidden'); out.classList.add('hidden');
      URL.revokeObjectURL(s.url);
    }).catch(function (e) { ZH.toast(e.message, 'error'); });
  });

  $('#resizeBtn').addEventListener('click', function () {
    if (!file) return ZH.toast('Please choose an image first.', 'error');
    var w, h;
    if (mode.value === 'pct') {
      var p = parseFloat(pIn.value);
      if (!(p > 0)) return ZH.toast('Enter a valid percentage.', 'error');
      w = Math.round(natW * p / 100); h = Math.round(natH * p / 100);
    } else {
      w = parseInt(wIn.value, 10); h = parseInt(hIn.value, 10);
    }
    if (!(w > 0 && h > 0)) return ZH.toast('Enter a valid width and height.', 'error');
    if (w > 10000 || h > 10000) return ZH.toast('Maximum size is 10000 px per side.', 'error');

    var type = $('#outFormat').value;
    if (type === 'same') type = /^image\/(jpeg|png|webp)$/.test(file.type) ? file.type : 'image/png';
    var btn = this; btn.disabled = true; var src;

    ZH.img.load(file).then(function (s) {
      src = s;
      return ZH.img.toBlob(ZH.img.draw(s.img, w, h, type), type, 0.92);
    }).then(function (blob) {
      var url = URL.createObjectURL(blob);
      var name = ZH.img.baseName(file.name) + '_' + w + 'x' + h + '.' + ZH.img.ext(type);
      out.innerHTML =
        '<div class="stat-grid">' +
        '<div class="stat"><b>' + natW + '×' + natH + '</b><span>Original</span></div>' +
        '<div class="stat"><b>' + w + '×' + h + '</b><span>New size</span></div>' +
        '<div class="stat"><b>' + ZH.formatBytes(blob.size) + '</b><span>File size</span></div></div>' +
        '<img class="preview-img" alt="Resized preview" src="' + url + '">' +
        '<div class="btn-row" style="margin-top:.75rem"><a class="btn btn-accent" href="' + url + '" download="' + ZH.esc(name) + '">⬇ Download</a></div>';
      out.classList.remove('hidden');
      ZH.used();
    }).catch(function (e) { ZH.toast(e.message, 'error'); })
      .then(function () { btn.disabled = false; if (src) URL.revokeObjectURL(src.url); });
  });
})();
