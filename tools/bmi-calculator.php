<?php
require_once dirname(__DIR__) . '/includes/bootstrap.php';
$toolSlug = 'bmi-calculator';
require INCLUDES_PATH . '/tool-start.php';
?>
    <div class="btn-row">
      <label class="check"><input type="radio" name="units" value="metric" checked> Metric (kg, cm)</label>
      <label class="check"><input type="radio" name="units" value="imperial"> Imperial (lb, ft/in)</label>
    </div>
    <div id="metricFields" class="form-row">
      <div><label for="heightCm">Height (cm)</label><input type="number" id="heightCm" min="50" max="260" step="0.1" placeholder="e.g. 170"></div>
      <div><label for="weightKg">Weight (kg)</label><input type="number" id="weightKg" min="10" max="400" step="0.1" placeholder="e.g. 70"></div>
    </div>
    <div id="imperialFields" class="form-row hidden">
      <div><label for="heightFt">Height (ft)</label><input type="number" id="heightFt" min="1" max="8" step="1" placeholder="5"></div>
      <div><label for="heightIn">Height (in)</label><input type="number" id="heightIn" min="0" max="11" step="0.1" placeholder="7"></div>
      <div><label for="weightLb">Weight (lb)</label><input type="number" id="weightLb" min="20" max="900" step="0.1" placeholder="154"></div>
    </div>
    <div class="btn-row"><button id="calcBmi" class="btn btn-primary" type="button">Calculate BMI</button></div>
    <div id="result" class="result-box hidden"></div>
<?php require INCLUDES_PATH . '/tool-end.php'; ?>
