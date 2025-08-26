<?php namespace App\Controllers\Backend;

use App\Controllers\BaseController;
use App\Models\GenericModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;

class CrudController extends BaseController
{
    protected $model;
    protected $columns;
    protected $crud_mode;
    protected $tableName;
    protected $jsonPath;

    protected function init(string $tableJson)
    {
        helper('crud');
        $this->jsonPath = WRITEPATH."uploads/json/{$tableJson}";
        $this->model = new GenericModel();
        $data = $this->model->setTableFromJson($this->jsonPath);
        $this->columns = $data['columns'];
        $this->crud_mode = $data['crud_mode'] ?? 'server';
        $this->tableName = $data['table'];
    }

    public function index(string $tableJson)
    {
        $this->init($tableJson);
        $data = ['columns'=>$this->columns, 'title'=>ucfirst($this->tableName)];
        if($this->crud_mode === 'ajax'){
            return view('backend/crud/index_ajax', $data);
        }
        $data['rows'] = $this->model->findAll();
        return view('backend/crud/index_server', $data);
    }

    public function json(string $tableJson)
    {
        $this->init($tableJson);
        $rows = $this->model->findAll();
        foreach($rows as &$r){
            foreach($this->columns as $col){
                if(($col['type']??'')==='file' && !empty($r[$col['name']])){
                    $r[$col['name'].'_preview'] = showFile($this->tableName,$r[$col['name']]);
                }
            }
        }
        return $this->response->setJSON($rows);
    }

    public function save(string $tableJson)
    {
        $this->init($tableJson);
        $post = $this->request->getPost();

        // transform password fields
        foreach($this->columns as $col){
            if(($col['type']??'')==='password' && !empty($post[$col['name']])){
                $post[$col['name']] = password_hash($post[$col['name']], PASSWORD_DEFAULT);
            }
        }

        // handle file uploads
        foreach($this->columns as $col){
            if(($col['type']??'')==='file'){
                $file = $this->request->getFile($col['name']);
                if($file && $file->isValid()){
                    $uploaded = uploadFile($file, $this->tableName);
                    if($uploaded){ $post[$col['name']] = $uploaded; }
                }
            }
        }

        $pk = $this->model->primaryKey;
        if(isset($post[$pk]) && $post[$pk]){
            $this->model->update($post[$pk], $post);
        } else {
            $this->model->insert($post);
        }

        if($this->crud_mode === 'ajax'){
            return $this->response->setJSON(['success'=>true]);
        }
        return redirect()->back();
    }

    public function delete(string $tableJson, $id)
    {
        $this->init($tableJson);
        $this->model->delete($id);
        if($this->crud_mode === 'ajax'){
            return $this->response->setJSON(['success'=>true]);
        }
        return redirect()->back();
    }

    public function exportExcel(string $tableJson)
    {
        $this->init($tableJson);
        $rows = $this->model->findAll();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $col = 1;
        foreach($this->columns as $c){ $sheet->setCellValueByColumnAndRow($col,1,$c['label']); $col++; }
        $rnum = 2;
        foreach($rows as $r){
            $col=1;
            foreach($this->columns as $c){ $sheet->setCellValueByColumnAndRow($col,$rnum,$r[$c['name']]??''); $col++; }
            $rnum++;
        }
        $writer = new Xlsx($spreadsheet);
        $filename = $this->tableName.'_'.date('YmdHis').'.xlsx';
        $path = WRITEPATH.$filename;
        $writer->save($path);
        return $this->response->download($path, null)->setFileName($filename);
    }

    public function exportPDF(string $tableJson)
    {
        $this->init($tableJson);
        $rows = $this->model->findAll();
        $html = view('backend/crud/export_pdf', ['rows'=>$rows,'columns'=>$this->columns,'title'=>ucfirst($this->tableName)]);
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4','landscape');
        $dompdf->render();
        $dompdf->stream($this->tableName.'_'.date('YmdHis').'.pdf', ['Attachment'=>true]);
    }
}
