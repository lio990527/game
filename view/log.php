<?php 
function log_show($base_path, $sub_puth = '')
{
    $href = "/test/{$action}/";
    empty($sub_puth) OR $path.= $sub_puth.'/';
    echo '<body bgcolor="#C7EDCC">';
    echo "<b><a href='{$href}'>{$action}</a>/</b>";
    if(!empty($sub_puth)){
        foreach (explode('/', $sub_puth) as $name){
            $href .= "{$name}/";
            echo "<b><a href='{$href}'>{$name}</a>/</b>";
        }
    }
    
    echo "<br/>";
    if(is_dir($path)){
        $logs = dir($path);
        echo '<table>';
        echo '<tr bgcolor="#EAEAEA"><th></th><th>file|path</th><th>size</th><th>last_update_time</th></tr>';
        while (($file = $logs->read()) !== false){
            if($file == '.' || $file == '..'){
                continue;
            }
            if(is_dir($path.$file)){
                echo "<tr><td>&gt;</td><td><a href='{$href}{$file}/'>/{$file}</a></td><td></td><td></td></tr>";
            }else{
                $fs = number_format(floatval(filesize($path.$file)/1024), 2).'KB';
                $fm = date('y/m/d H:i:s', filemtime($path.$file));
                echo "<tr><td>&gt;</td><td><a href='{$href}?f={$file}'>{$file}</a></td><td>{$fs}</td><td>{$fm}</td></tr>";
            }
        }
        echo '</table>';exit;
    }elseif(is_file($path)){
        $i = 1; $c = 200;
        $pg = $this->input->get('page') ? intval($this->input->get('page')) : 1;
        $fp = new SplFileObject($path, 'r');
        $fp->seek(($pg - 1) * $c);// 转到第N行, seek方法参数从0开始计数
        echo '<table style="font-size:13px;">';
        $i = 1;
        for($i; $i <= $c; ++$i) {
            $context = $fp->current();
            if(empty($context)){
                break;
            }
            $l = $i + ($pg - 1) * $c;
            echo "<tr><td valign='top'>{$l}</td><td><pre>{$context}</pre></td></tr>";// current()获取当前行内容
            $fp->next();// 下一行
        }
        echo '</table>';
        ($i < $c) OR print('<a href="javascript:void(0)" onclick=more(this,'.($pg+1).')>more&gt;&gt;</a>');
        !$this->input->get('page') OR exit;
    }else{
        show_404();
    }
    
    echo '<script type="text/javascript" src="https://ss1.bdstatic.com/5eN1bjq8AAUYm2zgoY3K/r/www/cache/static/protocol/https/jquery/jquery-1.10.2.min_65682a2.js"></script>
              <script type="text/javascript">
                function more(dom,page){
                    $.get("'.$href.'?f='.$f.'&page='.'"+page,function(data){
//                          console.log($(body));
                         $("body").append(data);
                    });
                    console.log(dom);
                    $(dom).remove();
                }
                        
              </script>';
    ;
}
?>