<?php
require_once dirname(__DIR__) . '/includes/bootstrap.php';
$toolSlug = 'percentage-calculator';
require INCLUDES_PATH . '/tool-start.php';
?>
    <h3>What is X% of Y?</h3>
    <div class="form-row">
      <div><label for="p1x">Percentage (%)</label><input type="number" id="p1x" step="any" placeholder="15"></div>
      <div><label for="p1y">Of number</label><input type="number" id="p1y" step="any" placeholder="2000"></div>
    </div>
    <div class="result-box" id="r1">Result: <strong>—</strong></div>

    <h3 style="margin-top:1.5rem">X is what percent of Y?</h3>
    <div class="form-row">
      <div><label for="p2x">Number X</label><input type="number" id="p2x" step="any" placeholder="45"></div>
      <div><label for="p2y">Number Y</label><input type="number" id="p2y" step="any" placeholder="60"></div>
    </div>
    <div class="result-box" id="r2">Result: <strong>—</strong></div>

    <h3 style="margin-top:1.5rem">Percentage increase / decrease</h3>
    <div class="form-row">
      <div><label for="p3a">From (old value)</label><input type="number" id="p3a" step="any" placeholder="80"></div>
      <div><label for="p3b">To (new value)</label><input type="number" id="p3b" step="any" placeholder="100"></div>
    </div>
    <div class="result-box" id="r3">Result: <strong>—</strong></div>
<?php require INCLUDES_PATH . '/tool-end.php'; ?>
