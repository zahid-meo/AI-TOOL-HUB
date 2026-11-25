(function () {
  'use strict';
  var ZH = window.ZH, $ = ZH.$;
  var file = null, source = null;
  var q = $('#qualityRange'), qv = $('#qualityVal'), out = $('#output'), info = $('#fileInfo');

  q.addEventListener('input', function () { qv.textContent = q.value; });

  ZH.img.dropzone($('#dropzone'), $('#fileInput'), function (f) {
    file = f; source = null;
    info.textContent = f.name + ' — ' + ZH.formatBytes(f.size);
    info.classList.remove('hidden'); out.classList.add('hidden');
  });

  $('#compressBtn').addEventListener('click', function () {
    if (!file) return ZH.toast('Please choose an image first.', 'error');
    var btn = this; btn.disabled = true;
    var type = $('#outFormat').value;
    if (type === 'auto') type = file.type === 'image/png' ? 'image/jpeg' : (file.type === 'image/webp' ? 'image/webp' : 'image/jpeg');

    ZH.img.load(file).then(function (s) {
      source = s;
      var canvas = ZH.img.draw(s.img, s.width, s.height, type);
      return ZH.img.toBlob(canvas, type, q.value / 100);
    }).then(function (blob) {
      var saved = file.size - blob.size, pct = Math.round(saved / file.size * 100);
      var url = URL.createObjectURL(blob);
      var name = 'compressed_' + ZH.img.baseName(file.name) + '.' + ZH.img.ext(type);
      out.innerHTML =
        '<div class="stat-grid">' +
        '<div class="stat"><b>' + ZH.formatBytes(file.size) + '</b><span>Original</span></div>' +
        '<div class="stat"><b>' + ZH.formatBytes(blob.size) + '</b><span>Compressed</span></div>' +
        '<div class="stat"><b>' + (saved > 0 ? pct + '%' : '0%') + '</b><span>Saved</span></div></div>' +
        (saved <= 0 ? '<p class="muted" style="margin-top:.6rem">This image is already well optimized — try a lower quality value.</p>' : '') +
        '<img class="preview-img" alt="Compressed preview" src="' + url + '">' +
        '<div class="btn-row" style="margin-top:.75rem"><a class="btn btn-accent" href="' + url + '" download="' + ZH.esc(name) + '">⬇ Download</a></div>';
      out.classList.remove('hidden');
      ZH.used();
    }).catch(function (e) { ZH.toast(e.message, 'error'); })
      .then(function () { btn.disabled = false; if (source) URL.revokeObjectURL(source.url); });
  });
})();
