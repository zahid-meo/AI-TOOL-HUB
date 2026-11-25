<?php
require_once dirname(__DIR__) . '/includes/bootstrap.php';
$toolSlug = 'fancy-fonts';
require INCLUDES_PATH . '/tool-start.php';
?>
    <label for="inputText">Your text</label>
    <input type="text" id="inputText" maxlength="120" placeholder="Type your name or bio…" value="AI Tools">
    <div class="style-list" id="styleList" aria-live="polite"></div>
<?php require INCLUDES_PATH . '/tool-end.php'; ?>
