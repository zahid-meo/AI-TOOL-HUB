(function () {
  'use strict';
  var ZH = window.ZH, $ = ZH.$;
  var WORDS = ('lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua enim ad minim veniam quis nostrud exercitation ullamco laboris nisi aliquip ex ea commodo consequat duis aute irure in reprehenderit voluptate velit esse cillum eu fugiat nulla pariatur excepteur sint occaecat cupidatat non proident sunt culpa qui officia deserunt mollit anim id est laborum curabitur pretium tincidunt lacus nunc porta vivamus feugiat pellentesque habitant morbi tristique senectus netus malesuada fames turpis egestas').split(' ');
  var START = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit';

  function rnd(n) { return Math.floor(Math.random() * n); }
  function cap(s) { return s.charAt(0).toUpperCase() + s.slice(1); }
  function sentence() {
    var len = 8 + rnd(10), w = [];
    for (var i = 0; i < len; i++) w.push(WORDS[rnd(WORDS.length)]);
    if (len > 10) w[4 + rnd(3)] += ',';
    return cap(w.join(' ')) + '.';
  }
  function paragraph() {
    var n = 3 + rnd(4), s = [];
    for (var i = 0; i < n; i++) s.push(sentence());
    return s.join(' ');
  }
  function generate() {
    var type = $('#loremType').value, count = Math.min(100, Math.max(1, parseInt($('#loremCount').value, 10) || 1));
    var start = $('#startLorem').checked, parts = [], i;
    if (type === 'words') {
      var w = []; for (i = 0; i < count; i++) w.push(WORDS[rnd(WORDS.length)]);
      if (start) { var s = START.toLowerCase().replace(',', '').split(' '); for (i = 0; i < Math.min(count, s.length); i++) w[i] = s[i]; }
      return cap(w.join(' ')) + '.';
    }
    for (i = 0; i < count; i++) parts.push(type === 'sentences' ? sentence() : paragraph());
    if (start) parts[0] = START + '. ' + (type === 'paragraphs' ? parts[0] : '').trim();
    return parts.join(type === 'sentences' ? ' ' : '\n\n').trim();
  }
  $('#genBtn').addEventListener('click', function () { $('#loremOut').value = generate(); ZH.used(); });
  $('#copyBtn').addEventListener('click', function () { var v = $('#loremOut').value; if (v) ZH.copy(v, 'Text copied'); else ZH.toast('Generate some text first.', 'error'); });
  $('#loremOut').value = generate();
})();
