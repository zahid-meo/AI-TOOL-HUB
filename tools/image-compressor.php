<?php
require_once dirname(__DIR__) . '/includes/bootstrap.php';
$toolSlug = 'image-compressor';
require INCLUDES_PATH . '/tool-start.php';
?>
    <label class="dropzone" id="dropzone" for="fileInput">
      <input type="file" id="fileInput" accept="image/jpeg,image/png,image/webp">
      <strong>Click to choose an image</strong> or drag &amp; drop it here<br><small>JPG, PNG or WebP</small>
    </label>
    <div id="fileInfo" class="muted hidden"></div>
    <div class="form-row">
      <div>
        <label for="outFormat">Output format</label>
        <select id="outFormat">
          <option value="auto">Auto (JPG for PNG, same for others)</option>
          <option value="image/jpeg">JPG</option>
          <option value="image/webp">WebP</option>
        </select>
      </div>
      <div>
        <label for="qualityRange">Quality: <span id="qualityVal">75</span>%</label>
        <input type="range" id="qualityRange" min="10" max="100" value="75">
      </div>
    </div>
    <div class="btn-row"><button id="compressBtn" class="btn btn-primary" type="button">Compress image</button></div>
    <div id="output" class="result-box hidden"></div>
<?php require INCLUDES_PATH . '/tool-end.php'; ?>
