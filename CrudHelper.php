<?php
if (!function_exists('uploadFile')) {
    function uploadFile($file, $folder='uploads', $id=null){
        if(!$file) return null;
        if($file->isValid() && !$file->hasMoved()){
            $path = WRITEPATH.$folder.($id ? "/$id" : '');
            if(!is_dir($path)) mkdir($path, 0777, true);
            $name = $file->getRandomName();
            $file->move($path, $name);
            return $name;
        }
        return null;
    }
}

if (!function_exists('showFile')) {
    function showFile($folder, $filename, $userId=null){
        if(!$filename) return '';
        $prefix = 'writable/'.$folder.($userId ? "/$userId" : '');
        $abs = WRITEPATH.$folder.($userId ? "/$userId" : '')."/".$filename;
        if(file_exists($abs)){
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            $url = base_url($prefix.'/'.$filename);
            if(in_array($ext,['jpg','jpeg','png','gif','webp'])){
                return "<img src='{$url}' class='img-thumbnail' style='max-width:100px;'>";
            }
            return "<a href='{$url}' target='_blank'>Download</a>";
        }
        return '';
    }
}
