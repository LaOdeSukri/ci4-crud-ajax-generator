<h3 style="margin-bottom:10px;"><?= esc($title ?? 'Export') ?></h3>
<table border="1" width="100%" cellpadding="5" cellspacing="0">
  <tr>
  <?php foreach($columns as $col): ?><th><?= esc($col['label']) ?></th><?php endforeach; ?>
  </tr>
  <?php foreach($rows as $row): ?>
  <tr>
    <?php foreach($columns as $col): ?><td><?= esc($row[$col['name']] ?? '') ?></td><?php endforeach; ?>
  </tr>
  <?php endforeach; ?>
</table>
