/* Shared helpers for the image tools (compressor, converter, resizer). */
(function () {
  'use strict';
  var ZH = window.ZH;

  var EXT = { 'image/jpeg': 'jpg', 'image/png': 'png', 'image/webp': 'webp' };

  ZH.img = {
    ext: function (type) { return EXT[type] || 'png'; },
    baseName: function (name) { return (name || 'image').replace(/\.[^.]+$/, ''); },

    /** Load a File into an <img>. Resolves with { img, width, height, url }. */
    load: function (file) {
      return new Promise(function (resolve, reject) {
        var url = URL.createObjectURL(file);
        var img = new Image();
        img.onload = function () { resolve({ img: img, width: img.naturalWidth, height: img.naturalHeight, url: url }); };
        img.onerror = function () { URL.revokeObjectURL(url); reject(new Error('This file could not be read as an image.')); };
        img.src = url;
      });
    },

    /** Draw to a canvas. JPEG has no alpha, so we paint white behind transparent pixels. */
    draw: function (img, w, h, type) {
      var c = document.createElement('canvas');
      c.width = w; c.height = h;
      var ctx = c.getContext('2d');
      if (type === 'image/jpeg') { ctx.fillStyle = '#fff'; ctx.fillRect(0, 0, w, h); }
      ctx.imageSmoothingQuality = 'high';
      ctx.drawImage(img, 0, 0, w, h);
      return c;
    },

    toBlob: function (canvas, type, quality) {
      return new Promise(function (resolve, reject) {
        canvas.toBlob(function (b) { b ? resolve(b) : reject(new Error('Your browser could not create this format.')); }, type, quality);
      });
    },

    /** Wire up a drop zone + file input. onFile(file) is called with a validated image file. */
    dropzone: function (zone, input, onFile) {
      function take(file) {
        if (!file) return;
        if (!/^image\//.test(file.type)) { ZH.toast('Please choose an image file.', 'error'); return; }
        onFile(file);
      }
      input.addEventListener('change', function () { take(input.files[0]); });
      ['dragenter', 'dragover'].forEach(function (ev) {
        zone.addEventListener(ev, function (e) { e.preventDefault(); zone.classList.add('is-over'); });
      });
      ['dragleave', 'drop'].forEach(function (ev) {
        zone.addEventListener(ev, function (e) { e.preventDefault(); zone.classList.remove('is-over'); });
      });
      zone.addEventListener('drop', function (e) { take(e.dataTransfer.files[0]); });
    }
  };
})();
