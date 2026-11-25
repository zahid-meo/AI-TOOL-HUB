(function () {
  'use strict';
  var ZH = window.ZH, $ = ZH.$;
  var input = $('#inputText'), output = $('#outputText');

  function words(s) { return s.match(/[\p{L}\p{N}]+/gu) || []; }
  function cap(w) { return w.charAt(0).toUpperCase() + w.slice(1).toLowerCase(); }

  var modes = {
    upper: function (s) { return s.toUpperCase(); },
    lower: function (s) { return s.toLowerCase(); },
    title: function (s) { return s.toLowerCase().replace(/[\p{L}\p{N}][\p{L}\p{N}']*/gu, cap); },
    sentence: function (s) {
      return s.toLowerCase().replace(/(^\s*|[.!?۔؟]\s+|\n\s*)(\p{L})/gu, function (m, pre, ch) { return pre + ch.toUpperCase(); });
    },
    camel: function (s) { return words(s).map(function (w, i) { return i ? cap(w) : w.toLowerCase(); }).join(''); },
    pascal: function (s) { return words(s).map(cap).join(''); },
    snake: function (s) { return words(s).map(function (w) { return w.toLowerCase(); }).join('_'); },
    kebab: function (s) { return words(s).map(function (w) { return w.toLowerCase(); }).join('-'); },
    toggle: function (s) {
      return Array.from(s).map(function (c) { return c === c.toUpperCase() ? c.toLowerCase() : c.toUpperCase(); }).join('');
    }
  };

  $('#caseButtons').addEventListener('click', function (e) {
    var b = e.target.closest('[data-mode]');
    if (!b) return;
    if (!input.value) return ZH.toast('Enter some text first.', 'error');
    output.value = modes[b.getAttribute('data-mode')](input.value);
    ZH.used();
  });
  $('#copyBtn').addEventListener('click', function () { if (output.value) ZH.copy(output.value, 'Result copied'); else ZH.toast('Nothing to copy yet.', 'error'); });
  $('#useBtn').addEventListener('click', function () { if (output.value) { input.value = output.value; output.value = ''; } });
})();
