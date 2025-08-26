<div class="modal fade" id="modalForm" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="formAjax" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <div class="modal-header">
        <h5 class="modal-title">Form</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body row">
        <?php foreach($columns as $col): ?>
          <div class="col-md-<?= $col['col_md'] ?? 12 ?> mb-3">
            <label class="form-label"><?= esc($col['label']) ?></label>
            <?php if(($col['type'] ?? 'text') === 'select'): ?>
              <select name="<?= esc($col['name']) ?>" class="form-select">
                <?php foreach(($col['options'] ?? []) as $key=>$val): ?>
                  <option value="<?= esc($key) ?>"><?= esc($val) ?></option>
                <?php endforeach; ?>
              </select>
            <?php elseif(($col['type'] ?? 'text') === 'file'): ?>
              <input type="file" name="<?= esc($col['name']) ?>" class="form-control">
            <?php else: ?>
              <input type="<?= esc($col['type'] ?? 'text') ?>" name="<?= esc($col['name']) ?>" class="form-control">
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
      </form>
    </div>
  </div>
</div>
