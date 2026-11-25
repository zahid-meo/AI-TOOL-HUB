<?php
require_once dirname(__DIR__) . '/includes/bootstrap.php';
$toolSlug = 'cgpa-calculator';
require INCLUDES_PATH . '/tool-start.php';
?>
    <p class="muted">Enter each course’s credit hours and grade point (0–4.00 scale). Optionally add your previous CGPA to get a cumulative result.</p>
    <div class="table-wrap">
      <table class="data" id="gradesTable">
        <thead><tr><th>#</th><th>Course (optional)</th><th>Credits</th><th>Grade point</th><th></th></tr></thead>
        <tbody></tbody>
      </table>
    </div>
    <div class="btn-row">
      <button id="addRow" class="btn btn-secondary" type="button">+ Add course</button>
      <button id="resetBtn" class="btn btn-ghost" type="button">Reset</button>
    </div>
    <details class="faq" id="prevBox"><summary>Include previous semesters (optional)</summary>
      <div class="form-row" style="margin-top:.75rem">
        <div><label for="prevCgpa">Previous CGPA</label><input type="number" id="prevCgpa" min="0" max="4" step="0.01"></div>
        <div><label for="prevCredits">Previous total credits</label><input type="number" id="prevCredits" min="0" step="0.5"></div>
      </div>
    </details>
    <div class="btn-row"><button id="calcBtn" class="btn btn-primary" type="button">Calculate</button></div>
    <div id="result" class="result-box hidden"></div>
<?php require INCLUDES_PATH . '/tool-end.php'; ?>
