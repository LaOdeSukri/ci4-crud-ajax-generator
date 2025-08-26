<?php namespace App\Models;

use CodeIgniter\Model;

class GenericModel extends Model
{
    public function setTableFromJson($jsonPath){
        if(!is_file($jsonPath)){
            throw new \RuntimeException("JSON not found: {$jsonPath}");
        }
        $json = file_get_contents($jsonPath);
        $data = json_decode($json,true);
        if(!$data){
            throw new \RuntimeException("Invalid JSON: {$jsonPath}");
        }
        $this->table = $data['table'];
        $this->primaryKey = $data['primaryKey'];
        $this->allowedFields = array_map(fn($c)=>$c['name'],$data['columns']);
        // timestamps optional if fields exist
        $cols = $this->allowedFields;
        $this->useTimestamps = in_array('created_at',$cols) && in_array('updated_at',$cols);
        $this->createdField = 'created_at';
        $this->updatedField = 'updated_at';
        return $data;
    }
}
