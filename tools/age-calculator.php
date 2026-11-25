<?php
require_once dirname(__DIR__) . '/includes/bootstrap.php';
$toolSlug = 'age-calculator';
require INCLUDES_PATH . '/tool-start.php';
?>
    <div class="form-row">
      <div><label for="dob">Date of birth</label><input type="date" id="dob"></div>
      <div><label for="tod">Calculate age on</label><input type="date" id="tod"></div>
    </div>
    <div class="btn-row"><button id="calcAge" class="btn btn-primary" type="button">Calculate age</button>
      <button id="todayBtn" class="btn btn-ghost" type="button">Use today</button></div>
    <div id="result" class="result-box hidden"></div>
<?php require INCLUDES_PATH . '/tool-end.php'; ?>
