<?php
require_once dirname(__DIR__) . '/includes/bootstrap.php';
$toolSlug = 'json-formatter';
require INCLUDES_PATH . '/tool-start.php';
?>
    <label for="jsonIn">Paste JSON</label>
    <textarea id="jsonIn" class="output-box" rows="10" spellcheck="false" placeholder='{"name":"Zahid","skills":["Flutter","Dart"]}'></textarea>
    <div class="form-row">
      <div><label for="indent">Indent</label>
        <select id="indent"><option value="2">2 spaces</option><option value="4">4 spaces</option><option value="tab">Tab</option></select></div>
      <label class="check"><input type="checkbox" id="sortKeys"> Sort keys A→Z</label>
    </div>
    <div class="btn-row">
      <button id="formatBtn" class="btn btn-primary" type="button">Format</button>
      <button id="minifyBtn" class="btn btn-secondary" type="button">Minify</button>
      <button id="copyBtn" class="btn btn-secondary" type="button">Copy</button>
      <button id="downloadBtn" class="btn btn-ghost" type="button">Download .json</button>
      <button id="clearBtn" class="btn btn-ghost" type="button">Clear</button>
    </div>
    <div id="jsonStatus" class="alert alert--info hidden" role="status"></div>
    <label for="jsonOut">Output</label>
    <textarea id="jsonOut" class="output-box" rows="10" readonly spellcheck="false"></textarea>
<?php require INCLUDES_PATH . '/tool-end.php'; ?>
