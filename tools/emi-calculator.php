<?php
require_once dirname(__DIR__) . '/includes/bootstrap.php';
$toolSlug = 'emi-calculator';
require INCLUDES_PATH . '/tool-start.php';
?>
    <div class="form-row">
      <div><label for="principal">Loan amount</label><input type="number" id="principal" min="0" step="any" placeholder="e.g. 1000000"></div>
      <div><label for="rate">Annual interest rate (%)</label><input type="number" id="rate" min="0" step="0.01" placeholder="e.g. 12.5"></div>
    </div>
    <div class="form-row">
      <div><label for="tenure">Tenure</label><input type="number" id="tenure" min="1" step="1" placeholder="e.g. 36"></div>
      <div><label for="tenureUnit">Tenure unit</label><select id="tenureUnit"><option value="m">Months</option><option value="y">Years</option></select></div>
      <div><label for="currency">Currency</label>
        <select id="currency">
          <option value="PKR">PKR (Rs)</option><option value="INR">INR (₹)</option><option value="USD">USD ($)</option>
          <option value="EUR">EUR (€)</option><option value="GBP">GBP (£)</option><option value="AED">AED</option><option value="SAR">SAR</option>
        </select></div>
    </div>
    <div class="btn-row"><button id="calcEmi" class="btn btn-primary" type="button">Calculate EMI</button>
      <button id="resetBtn" class="btn btn-ghost" type="button">Reset</button></div>
    <div id="result" class="result-box hidden"></div>
    <div id="scheduleWrap" class="hidden">
      <h3 style="margin:1rem 0 .5rem">Payment schedule</h3>
      <div class="table-wrap" style="max-height:360px;overflow:auto"><table class="data" id="scheduleTable"></table></div>
    </div>
<?php require INCLUDES_PATH . '/tool-end.php'; ?>
