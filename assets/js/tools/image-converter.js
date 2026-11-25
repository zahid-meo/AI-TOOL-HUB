(function () {
  'use strict';
  var ZH = window.ZH, $ = ZH.$;
  var file = null;
  var fmt = $('#formatSelect'), q = $('#qualityRange'), qv = $('#qualityVal'), out = $('#output'), info = $('#fileInfo');

  function syncQuality() { $('#qualityWrap').classList.toggle('hidden', fmt.value === 'image/png'); }
  fmt.addEventListener('change', syncQuality); syncQuality();
  q.addEventListener('input', function () { qv.textContent = q.value; });

  ZH.img.dropzone($('#dropzone'), $('#fileInput'), function (f) {
    file = f;
    info.textContent = f.name + ' — ' + ZH.formatBytes(f.size) + ' (' + (f.type || 'image') + ')';
    info.classList.remove('hidden'); out.classList.add('hidden');
  });

  $('#convertBtn').addEventListener('click', function () {
    if (!file) return ZH.toast('Please choose an image first.', 'error');
    var btn = this, type = fmt.value, src;
    btn.disabled = true;
    ZH.img.load(file).then(function (s) {
      src = s;
      return ZH.img.toBlob(ZH.img.draw(s.img, s.width, s.height, type), type, q.value / 100);
    }).then(function (blob) {
      var url = URL.createObjectURL(blob);
      var name = ZH.img.baseName(file.name) + '.' + ZH.img.ext(type);
      out.innerHTML =
        '<div class="stat-grid">' +
        '<div class="stat"><b>' + ZH.formatBytes(file.size) + '</b><span>Original</span></div>' +
        '<div class="stat"><b>' + ZH.formatBytes(blob.size) + '</b><span>Converted (' + ZH.img.ext(type).toUpperCase() + ')</span></div>' +
        '<div class="stat"><b>' + src.width + '×' + src.height + '</b><span>Dimensions</span></div></div>' +
        (type === 'image/jpeg' ? '<p class="muted" style="margin-top:.6rem">Transparent areas were filled with white because JPG has no transparency.</p>' : '') +
        '<img class="preview-img" alt="Converted preview" src="' + url + '">' +
        '<div class="btn-row" style="margin-top:.75rem"><a class="btn btn-accent" href="' + url + '" download="' + ZH.esc(name) + '">⬇ Download ' + ZH.img.ext(type).toUpperCase() + '</a></div>';
      out.classList.remove('hidden');
      ZH.used();
    }).catch(function (e) { ZH.toast(e.message, 'error'); })
      .then(function () { btn.disabled = false; if (src) URL.revokeObjectURL(src.url); });
  });
})();
