<?php
require_once dirname(__DIR__) . '/includes/bootstrap.php';
$toolSlug = 'case-converter';
require INCLUDES_PATH . '/tool-start.php';
?>
    <label for="inputText">Your text</label>
    <textarea id="inputText" rows="6" placeholder="Enter or paste text here…"></textarea>
    <div class="btn-row" id="caseButtons">
      <button class="btn btn-secondary btn-sm" data-mode="upper" type="button">UPPERCASE</button>
      <button class="btn btn-secondary btn-sm" data-mode="lower" type="button">lowercase</button>
      <button class="btn btn-secondary btn-sm" data-mode="title" type="button">Title Case</button>
      <button class="btn btn-secondary btn-sm" data-mode="sentence" type="button">Sentence case</button>
      <button class="btn btn-secondary btn-sm" data-mode="camel" type="button">camelCase</button>
      <button class="btn btn-secondary btn-sm" data-mode="pascal" type="button">PascalCase</button>
      <button class="btn btn-secondary btn-sm" data-mode="snake" type="button">snake_case</button>
      <button class="btn btn-secondary btn-sm" data-mode="kebab" type="button">kebab-case</button>
      <button class="btn btn-secondary btn-sm" data-mode="toggle" type="button">tOGGLE cASE</button>
    </div>
    <label for="outputText">Result</label>
    <textarea id="outputText" class="output-box" rows="6" readonly></textarea>
    <div class="btn-row">
      <button id="copyBtn" class="btn btn-primary" type="button">Copy result</button>
      <button id="useBtn" class="btn btn-ghost" type="button">Use result as input</button>
    </div>
<?php require INCLUDES_PATH . '/tool-end.php'; ?>
