<?php
require_once dirname(__DIR__) . '/includes/bootstrap.php';
$toolSlug = 'image-resizer';
require INCLUDES_PATH . '/tool-start.php';
?>
    <label class="dropzone" id="dropzone" for="fileInput">
      <input type="file" id="fileInput" accept="image/*">
      <strong>Click to choose an image</strong> or drag &amp; drop it here
    </label>
    <div id="fileInfo" class="muted hidden"></div>
    <div class="form-row">
      <div><label for="resizeMode">Resize by</label>
        <select id="resizeMode"><option value="px">Pixels</option><option value="pct">Percentage</option></select></div>
      <div id="pxWidthWrap"><label for="widthInput">Width (px)</label><input type="number" id="widthInput" min="1" max="10000"></div>
      <div id="pxHeightWrap"><label for="heightInput">Height (px)</label><input type="number" id="heightInput" min="1" max="10000"></div>
      <div id="pctWrap" class="hidden"><label for="pctInput">Scale (%)</label><input type="number" id="pctInput" min="1" max="500" value="50"></div>
    </div>
    <div class="form-row">
      <label class="check"><input type="checkbox" id="lockRatio" checked> Lock aspect ratio</label>
      <div><label for="outFormat">Output format</label>
        <select id="outFormat"><option value="same">Same as original</option><option value="image/jpeg">JPG</option><option value="image/png">PNG</option><option value="image/webp">WebP</option></select></div>
    </div>
    <div class="btn-row"><button id="resizeBtn" class="btn btn-primary" type="button">Resize image</button></div>
    <div id="output" class="result-box hidden"></div>
<?php require INCLUDES_PATH . '/tool-end.php'; ?>
