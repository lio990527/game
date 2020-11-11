<?php 
function get_path_files($path){
    if(!is_dir($path)){
        return array();
    }
    
    $obj = dir($path);
    $files = array();
    while (($file = $obj->read()) !== false){
        if($file == '.' || $file == '..'){
            continue;
        }
        
        $fpath = $path.'/'.$file;
        $finfo = array('name' => $file,'type' => is_file($fpath));
        if(is_file($fpath)){
            $finfo['size'] = getB(filesize($fpath));
            $finfo['update_at'] = date('y/m/d H:i:s', filemtime($fpath));
        }
        $files[] = $finfo;
    }
    return $files;
}

function get_file_content($path, $file, $page = 1, $count = 200){
    $fp = $path. '/' . $file;
    $data = array(
        'name' => $file,
        'next' => $page+1,
        'count' => $count,
        'eof' => 0,
    );
    
    if(!is_file($fp)) return $data;
    $sfo = new SplFileObject($fp, 'r');
    $sfo->seek(($page - 1) * $count);// 转到第N行, seek方法参数从0开始计数
    for($i = 1; $i <= $count; $i++) {
        if ($sfo->eof()) {
            $data['eof'] = 1;
            break;
        }
        $info = $sfo->current();
        $data['content'][($page - 1) * $count + $i] = $info;
        $data['rows'] = $i;
        $sfo->next();// 下一行
    }
    return $data;
}

function get_file_download($path, $fname){
    $fpath = $path.$fname;
    
    Header("Content-type: application/octet-stream");
    Header("Accept-Ranges: bytes");
    Header("Content-Disposition: attachment; filename=" . $fname);
    
    is_file($fpath) OR die;
    Header("Accept-Length: " . filesize ($fpath));
    
    $file = fopen ($fpath, "rb" );
    echo fread ($file, filesize($fpath));
    fclose ($file);
    exit;
}

function getB($size){
    $t = 0;
    while ($size > 1024) {
        $size /= 1024;
        $t++;
    }
    switch ($t){
        case 0:
            $t = 'B';break;
        case 1:
            $t = 'KB';break;
        case 2:
            $t = 'MB';break;
        case 3:
            $t = 'GB';break;
        case 4:
            $t = 'TB';break;
    }
    return number_format($size, 2).$t;
}