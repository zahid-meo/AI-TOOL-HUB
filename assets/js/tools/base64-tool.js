(function () {
  'use strict';
  var ZH = window.ZH, $ = ZH.$;
  var inp = $('#b64In'), out = $('#b64Out'), err = $('#b64Status');

  function fail(msg) { err.textContent = msg; err.classList.remove('hidden'); out.value = ''; }
  function ok() { err.classList.add('hidden'); }

  function encode(text) {
    var bytes = new TextEncoder().encode(text), bin = '';
    for (var i = 0; i < bytes.length; i += 0x8000) bin += String.fromCharCode.apply(null, bytes.subarray(i, i + 0x8000));
    var b64 = btoa(bin);
    return $('#urlSafe').checked ? b64.replace(/\+/g, '-').replace(/\//g, '_').replace(/=+$/, '') : b64;
  }
  function decode(b64) {
    var s = b64.replace(/\s+/g, '').replace(/-/g, '+').replace(/_/g, '/');
    while (s.length % 4) s += '=';
    var bin = atob(s), bytes = new Uint8Array(bin.length);
    for (var i = 0; i < bin.length; i++) bytes[i] = bin.charCodeAt(i);
    return new TextDecoder('utf-8', { fatal: true }).decode(bytes);
  }

  $('#encodeBtn').addEventListener('click', function () {
    if (!inp.value) return ZH.toast('Enter some text first.', 'error');
    try { out.value = encode(inp.value); ok(); ZH.used(); } catch (e) { fail('Could not encode this text.'); }
  });
  $('#decodeBtn').addEventListener('click', function () {
    if (!inp.value.trim()) return ZH.toast('Paste a Base64 string first.', 'error');
    try { out.value = decode(inp.value); ok(); ZH.used(); }
    catch (e) { fail('This is not valid Base64 (or it does not contain UTF-8 text).'); }
  });
  $('#swapBtn').addEventListener('click', function () { var t = inp.value; inp.value = out.value; out.value = t; ok(); });
  $('#clearBtn').addEventListener('click', function () { inp.value = ''; out.value = ''; ok(); });
  $('#copyBtn').addEventListener('click', function () { if (out.value) ZH.copy(out.value, 'Output copied'); else ZH.toast('Nothing to copy yet.', 'error'); });
})();
