<?php
require_once dirname(__DIR__) . '/includes/bootstrap.php';
$toolSlug = 'unit-converter';
require INCLUDES_PATH . '/tool-start.php';
?>
    <div class="form-row">
      <div><label for="category">Category</label><select id="category"></select></div>
    </div>
    <div class="form-row" style="align-items:end">
      <div><label for="fromVal">Value</label><input type="number" id="fromVal" step="any" value="1"></div>
      <div><label for="fromUnit">From</label><select id="fromUnit"></select></div>
      <div><label for="toUnit">To</label><select id="toUnit"></select></div>
    </div>
    <div class="btn-row"><button id="swapBtn" class="btn btn-ghost btn-sm" type="button">⇄ Swap units</button></div>
    <div class="result-box"><div class="muted" id="resultLabel">Result</div><div class="result-big" id="resultVal">—</div></div>
    <details class="faq" open><summary>All conversions for this value</summary>
      <div class="table-wrap" style="margin-top:.6rem"><table class="data" id="allTable"></table></div>
    </details>
<?php require INCLUDES_PATH . '/tool-end.php'; ?>
