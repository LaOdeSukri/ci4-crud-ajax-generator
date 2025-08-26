<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex mb-3 gap-2">
  <button id="btnAdd" class="btn btn-success">Add</button>
  <a class="btn btn-outline-primary" href="<?= current_url() ?>/export/excel">Export Excel</a>
  <a class="btn btn-outline-danger" href="<?= current_url() ?>/export/pdf" target="_blank">Export PDF</a>
</div>

<div id="userTable"></div>

<?= $this->include('backend/crud/form_modal') ?>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
async function loadTable(){
    const res = await fetch(location.pathname + '/json');
    const data = await res.json();
    let html = '<table class="table table-bordered table-striped"><thead><tr>';
    <?php foreach($columns as $col): ?>
        html += '<th><?= esc($col["label"]) ?></th>';
    <?php endforeach; ?>
    html += '<th style="width:140px">Action</th></tr></thead><tbody>';

    data.forEach(row=>{
        html += '<tr>';
        <?php foreach($columns as $col): ?>
            <?php if(($col["type"] ?? "text") === "file"): ?>
                html += '<td>' + (row['<?= $col["name"] ?>_preview'] || '') + '</td>';
            <?php else: ?>
                html += '<td>' + (row['<?= $col["name"] ?>'] ?? '') + '</td>';
            <?php endif; ?>
        <?php endforeach; ?>
        const pk = row.id || row.id_user || row[Object.keys(row)[0]];
        html += `<td>
            <button class="btn btn-sm btn-warning me-1" onclick="editRow(${pk})">Edit</button>
            <button class="btn btn-sm btn-danger" onclick="deleteRow(${pk})">Delete</button>
        </td>`;
        html += '</tr>';
    });
    html += '</tbody></table>';
    document.getElementById('userTable').innerHTML = html;
}

async function deleteRow(id){
    if(!confirm('Delete?')) return;
    await fetch(location.pathname + '/delete/' + id, {method:'POST'});
    loadTable();
}

document.getElementById('btnAdd').addEventListener('click', ()=>{
    const form = document.getElementById('formAjax');
    form.reset();
    form.dataset.editing = '';
    const modal = new bootstrap.Modal(document.getElementById('modalForm'));
    modal.show();
});

document.getElementById('formAjax').addEventListener('submit', async (e)=>{
    e.preventDefault();
    const form = e.target;
    const fd = new FormData(form);
    if(form.dataset.editing){
        fd.append(form.dataset.pk, form.dataset.editing);
    }
    await fetch(location.pathname + '/save', {method:'POST', body: fd});
    bootstrap.Modal.getInstance(document.getElementById('modalForm')).hide();
    loadTable();
});

async function editRow(id){
    const res = await fetch(location.pathname + '/json');
    const data = await res.json();
    const row = data.find(r => String(r.id ?? r.id_user ?? r[Object.keys(r)[0]]) === String(id));
    const form = document.getElementById('formAjax');
    <?php foreach($columns as $col): ?>
        if(form['<?= $col['name'] ?>'] && '<?= $col['type'] ?? 'text' ?>' !== 'file'){
            form['<?= $col['name'] ?>'].value = row['<?= $col['name'] ?>'] ?? '';
        }
    <?php endforeach; ?>
    // guess PK name
    const pkName = Object.keys(row).find(k => k.endsWith('_id') || k.startsWith('id'));
    form.dataset.editing = id;
    form.dataset.pk = pkName || 'id';
    const modal = new bootstrap.Modal(document.getElementById('modalForm'));
    modal.show();
}

loadTable();
</script>
<?= $this->endSection() ?>
