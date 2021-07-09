<?php
require_once 'class/comm/class.file.php';
require_once 'class/user/class.user.php';

$objUser = new User();
// $objUser->checkAuth();
$strUips = $objUser->getUserIp();
if ($_POST['menuName'] && $_POST['menuUrl'] && $_POST['todo'] !== null) {
    $data = [
        $_POST['menuName'],
        $_POST['menuUrl'],
        $_POST['menuColor'],
        $_POST['menuType'] ? trim($_POST['menuType']) : '未命名',
        $strUips,
        $_POST['menuTarget'],
        $_POST['menuPublic']
    ];

    $info = implode('|', $data);
    if ($_POST['todo'] === 'upd') {
        $menu = File::getFileInfo('../lib/conf', 'menu.conf');
        $menu[$_GET['chg']] = $info;
        $info = implode("\n", $menu);
        File::writeFile('../lib/conf', 'menu.conf', $info, true);
    } else {
        File::writeFile('../lib/conf', 'menu.conf', $info);
    }
    Header("Location:" . parse_url($_SERVER['REQUEST_URI'])['path']);
} else if ($_GET['del'] !== null) {
    $menu = File::getFileInfo('../lib/conf', 'menu.conf');
    unset($menu[$_GET['del']]);
    $info = implode("\n", $menu);
    File::writeFile('../lib/conf', 'menu.conf', $info, true);
    Header("Location:" . parse_url($_SERVER['REQUEST_URI'])['path']);
} else if ($_GET['chg'] !== null) {
    $info = File::getFileInfo('../lib/conf', 'menu.conf', '|', $_GET['chg']);
}

if (true) {
    $rows = File::getFileInfo('../lib/conf', 'menu.conf');
    foreach ($rows as $r_k => $r_v) {
        $row = explode('|', trim($r_v));

        if (isset($row[6]) && $row[6] === '0' && $row['4'] != $strUips) {
            continue;
        }

        $ico = substr($row[1], strpos($row[1], '://') + 3);
        $ico = (strpos($ico, '/') === false) ? $ico : substr($ico, 0, strpos($ico, '/'));
        $yum = substr($ico, strrpos($ico, '.'));
        $ico = substr($ico, 0, strrpos($ico, '.'));
        $ico = (strrpos($ico, '.') === false) ? $ico : substr($ico, strrpos($ico, '.') + 1);

        $row['id'] = $r_k;
        $row['ico'] = strpos($row[1], '://') ? 'http://www.' . $ico . $yum . '/favicon.ico' : '/favicon.ico';
        $menu[$row[3]][] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="zh">
<head>
<title>网盟菜单</title>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="source/css/menu.css" />
<script type="text/javascript" src="source/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="source/js/comm.js"></script>
<script type="text/javascript" src="source/js/jscolor/jscolor.js"></script>
<script type="text/javascript">
    $(function(){
        $('div.menu ul').mouseover(function(){
            $(this).find('span').eq(0).addClass('btn_edt').attr('title','修改').click(function(){location.href = '?chg='+$(this).attr('key');});
            $(this).find('span').eq(1).addClass('btn_del').attr('title','删除').click(function(){if(confirm('确定删除?他娘的删除了就真没了!!!'))location.href = '?del='+$(this).attr('key');});
        }).mouseout(function(){
            $(this).find('span').eq(0).removeClass('btn_edt').removeAttr('title').unbind("click");
            $(this).find('span').eq(1).removeClass('btn_del').removeAttr('title').unbind("click");
        });

        $('select[name=menuType]').change(function(){
            if(this.value == '自定义'){
                $($(this).parent().append('<input name="menuType" size="8"/>'))
            }else{
                $('input[name=menuType]').remove();
            }
        });
    });

    function hidn(){
        if(get('w_show').style.display != 'none'){
            get('w_show').style.display = 'none'
            get('w_box').style.display = 'none';
            document.menuForm.todo.value = 'add';
            document.menuForm.menuName.value = '';
            document.menuForm.menuUrl.value = '';
            document.menuForm.menuTarget[1].checked = 'checked';
            document.menuForm.menuColor.value = '0000FF';
        }else{
            get('w_show').style.display = 'block'
            get('w_box').style.display = 'block';
        }
    }

    function mnag(){
        $('div.menu').find('span.edit').addClass('btn_edt').attr('title','修改').click(function(){location.href = '?chg='+$(this).attr('key');});
        $('div.menu').find('span.delet').addClass('btn_del').attr('title','删除').click(function(){location.href = '?del='+$(this).attr('key');});;
    }

    function linkBlank(){
        var objA = document.createElement('a');
            objA.target = '_blank';
            objA.href = document.getElementById('w_link').value;
        document.body.appendChild(objA);
        objA.click();
    }

    function Jump(){
        var lKeyCode = (navigator.appname=="Netscape") ? event.which : window.event.keyCode; //event.keyCode按的建的代码，13表示回车
        if (lKeyCode == 13 ){
            linkBlank();
        }
    }

    onload = function(){
        $.get('/randbg.php', function(data){
            if(data !== ''){
                $('body').css('background', 'url(' + data + ')');
            }
        });

        $('img[name=menu_ico]').each(function(){
            this.src = $(this).attr('data');
        });
    }
    </script>
</head>
<body>
    <div class="shadow" id="w_show" style="display: <?php if(!$info) echo 'none';?>;"></div>
    <div class="f_box" id="w_box" style="display: <?php if(!$info) echo 'none';?>;" align="center">
        <form name="menuForm" id="menuForm" method="post">
            <table class="menuTab" cellspacing="0" cellpadding="0">
                <tr>
                    <th colspan="2">添加菜单</th>
                </tr>
                <tr>
                    <td>菜单名称：</td>
                    <td><input value="<?php if($info) echo $info[0];?>" name="menuName" /></td>
                </tr>
                <tr>
                    <td>菜单地址：</td>
                    <td><input value="<?php if($info) echo $info[1];?>" name="menuUrl" /></td>
                </tr>
                <tr>
                    <td>菜单组别：</td>
                    <td>
                        <select name="menuType">
                            <?php foreach($menu as $type => $val):?>
                            <option value="<?php echo $type;?>"
                                <?php if($info[3] == $type){echo 'selected="selected"';}?>><?php echo $type;?></option>
                            <?php endforeach;?>
                            <option value="自定义">自定义</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>新 窗 口：</td>
                    <td><input name="menuTarget" type="radio" value="1"
                        <?php if($info[5] == 1){?> checked="checked" <?php }?> />是 <input
                        name="menuTarget" type="radio" value="0"
                        <?php if($info[5] == 0){?> checked="checked" <?php }?> />否</td>
                </tr>
                <tr>
                    <td title="公共化的标签则会被所有人看到，而私有化只能是自己看到.">公 共 化：</td>
                    <td>
                        <input name="menuPublic" type="radio" value="1"<?php if($info[6] == 1){?> checked="checked" <?php }?> />是 
                        <input name="menuPublic" type="radio" value="0"<?php if($info[6] == 0){?> checked="checked" <?php }?> />否</td>
                </tr>
                <tr>
                    <td>字体颜色：</td>
                    <td><input value="<?php if($info) echo $info[2];?>" name="menuColor" class="color" value="#666666" /></td>
                </tr>
                <tr>
                    <td align="center" colspan="2">
                        <input type="hidden" name="todo" value="<?php echo $info ? 'upd' : 'add'; ?>" />
                        <input type="submit" value="确定" />&nbsp; <input type="button" value="取消" onclick="location.href='/menu.php'" /></td>
                </tr>
            </table>
        </form>
    </div>

    <div class="my_menu">
        <div class="title">
            我的导航 
            <span class="btn btn_mng" title="管理" onclick="mnag()">+</span>
            <span class="btn btn_add" title="新建" onclick="hidn()">+</span>
        </div>
        <div class="content">
            <?php foreach($menu as $type => $val):?>
            <div class="line">
                <div class="type"><?php echo $type;?></div>
                <div class="menu">
                    <?php foreach($val as $v):?>
                    <ul>
                        <a href="<?php echo $v[1]?>" target="<?php if($v[5] == 1)echo '_blank';?>"><img name="menu_ico" width="16" height="16" data="<?php echo $v['ico']?>" /></a>
                        <li><a href="<?php echo $v[1]?>" title="<?php echo $v[0]?>" style="color:#<?php echo $v[2]?>" target="<?php if($v[5]) echo '_blank';?>"><?php echo $v[0]?></a></li>
                        <span class="btn edit" key="<?php echo $v['id'];?>">+</span>
                        <span class="btn delet" key="<?php echo $v['id'];?>">+</span>
                    </ul>
                    <?php endforeach;?>
                </div>
            </div>
            <?php endforeach;?>
            <div class="line">
                <input id="w_link" onkeydown="Jump()" style="margin: 3px 5px; width: 728px;" />
                <button onclick="linkBlank()" style="float: right; margin-right: 5px;">猛戳</button>
            </div>
        </div>
    </div>
</body>
</html>
