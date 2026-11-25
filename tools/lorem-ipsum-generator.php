<?php
require_once dirname(__DIR__) . '/includes/bootstrap.php';
$toolSlug = 'lorem-ipsum-generator';
require INCLUDES_PATH . '/tool-start.php';
?>
    <div class="form-row">
      <div><label for="loremType">Generate</label>
        <select id="loremType"><option value="paragraphs">Paragraphs</option><option value="sentences">Sentences</option><option value="words">Words</option></select></div>
      <div><label for="loremCount">Amount</label><input type="number" id="loremCount" min="1" max="100" value="3"></div>
    </div>
    <label class="check"><input type="checkbox" id="startLorem" checked> Start with “Lorem ipsum dolor sit amet…”</label>
    <div class="btn-row">
      <button id="genBtn" class="btn btn-primary" type="button">Generate</button>
      <button id="copyBtn" class="btn btn-secondary" type="button">Copy</button>
    </div>
    <textarea id="loremOut" class="output-box" rows="10" readonly></textarea>
<?php require INCLUDES_PATH . '/tool-end.php'; ?>
