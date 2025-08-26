<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<a href="#" class="btn btn-success mb-3" onclick="document.getElementById('addForm').classList.toggle('d-none')">Add</a>

<div id="addForm" class="card card-body mb-3 d-none">
  <form method="post" action="<?= current_url() ?>/save" enctype="multipart/form-data" class="row">
    <?= csrf_field() ?>
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
    <div class="col-12">
      <button class="btn btn-primary">Save</button>
      <a class="btn btn-outline-secondary" href="<?= current_url() ?>/export/excel">Export Excel</a>
      <a class="btn btn-outline-danger" href="<?= current_url() ?>/export/pdf" target="_blank">Export PDF</a>
    </div>
  </form>
</div>

<table class="table table-bordered table-striped">
  <thead>
    <tr>
      <?php foreach($columns as $col): ?>
        <th><?= esc($col['label']) ?></th>
      <?php endforeach; ?>
      <th style="width:140px">Action</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach(($rows ?? []) as $row): ?>
      <tr>
        <?php foreach($columns as $col): ?>
          <td><?= esc($row[$col['name']] ?? '') ?></td>
        <?php endforeach; ?>
        <?php $pk = $row['id'] ?? $row['id_user'] ?? reset($row); ?>
        <td>
          <form onsubmit="return confirm('Delete?')" method="post" action="<?= current_url() ?>/delete/<?= esc($pk) ?>">
            <?= csrf_field() ?>
            <button class="btn btn-sm btn-danger">Delete</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?= $this->endSection() ?>
