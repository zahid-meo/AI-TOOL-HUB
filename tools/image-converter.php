<?php
require_once dirname(__DIR__) . '/includes/bootstrap.php';
$toolSlug = 'image-converter';
require INCLUDES_PATH . '/tool-start.php';
?>
    <label class="dropzone" id="dropzone" for="fileInput">
      <input type="file" id="fileInput" accept="image/*">
      <strong>Click to choose an image</strong> or drag &amp; drop it here<br><small>JPG, PNG, WebP, GIF, BMP…</small>
    </label>
    <div id="fileInfo" class="muted hidden"></div>
    <div class="form-row">
      <div>
        <label for="formatSelect">Convert to</label>
        <select id="formatSelect">
          <option value="image/jpeg">JPG</option>
          <option value="image/png">PNG</option>
          <option value="image/webp">WebP</option>
        </select>
      </div>
      <div id="qualityWrap">
        <label for="qualityRange">Quality: <span id="qualityVal">92</span>%</label>
        <input type="range" id="qualityRange" min="30" max="100" value="92">
      </div>
    </div>
    <div class="btn-row"><button id="convertBtn" class="btn btn-primary" type="button">Convert</button></div>
    <div id="output" class="result-box hidden"></div>
<?php require INCLUDES_PATH . '/tool-end.php'; ?>
