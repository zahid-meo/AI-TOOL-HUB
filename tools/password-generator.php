<?php
require_once dirname(__DIR__) . '/includes/bootstrap.php';
$toolSlug = 'password-generator';
require INCLUDES_PATH . '/tool-start.php';
?>
    <label for="pwOut">Your password</label>
    <div class="btn-row" style="flex-wrap:nowrap">
      <input type="text" id="pwOut" class="output-box" style="min-height:0" readonly aria-live="polite">
      <button id="copyBtn" class="btn btn-primary" type="button">Copy</button>
    </div>
    <div class="meter" aria-hidden="true"><i id="meterBar"></i></div>
    <div class="muted" id="meterText">Strength: —</div>

    <label for="lenRange">Length: <span id="lenVal">16</span></label>
    <input type="range" id="lenRange" min="8" max="64" value="16">
    <div class="check-grid">
      <label class="check"><input type="checkbox" id="optUpper" checked> Uppercase (A–Z)</label>
      <label class="check"><input type="checkbox" id="optLower" checked> Lowercase (a–z)</label>
      <label class="check"><input type="checkbox" id="optNum" checked> Numbers (0–9)</label>
      <label class="check"><input type="checkbox" id="optSym" checked> Symbols (!@#$…)</label>
      <label class="check"><input type="checkbox" id="optAmb"> Avoid look-alikes (O/0, l/1)</label>
    </div>
    <div class="btn-row"><button id="genBtn" class="btn btn-accent" type="button">Generate new password</button></div>
<?php require INCLUDES_PATH . '/tool-end.php'; ?>
