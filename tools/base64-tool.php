<?php
require_once dirname(__DIR__) . '/includes/bootstrap.php';
$toolSlug = 'base64-tool';
require INCLUDES_PATH . '/tool-start.php';
?>
    <label for="b64In">Input</label>
    <textarea id="b64In" class="output-box" rows="6" placeholder="Type text to encode, or paste Base64 to decode…"></textarea>
    <label class="check"><input type="checkbox" id="urlSafe"> URL-safe Base64 (− and _ instead of + and /)</label>
    <div class="btn-row">
      <button id="encodeBtn" class="btn btn-primary" type="button">Encode →</button>
      <button id="decodeBtn" class="btn btn-secondary" type="button">← Decode</button>
      <button id="swapBtn" class="btn btn-ghost" type="button">Swap</button>
      <button id="clearBtn" class="btn btn-ghost" type="button">Clear</button>
    </div>
    <div id="b64Status" class="alert alert--error hidden" role="alert"></div>
    <label for="b64Out">Output</label>
    <textarea id="b64Out" class="output-box" rows="6" readonly></textarea>
    <div class="btn-row"><button id="copyBtn" class="btn btn-secondary" type="button">Copy output</button></div>
<?php require INCLUDES_PATH . '/tool-end.php'; ?>
