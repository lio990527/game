<?php 
if($_FILES){
// 	var_dump($_FILES);
	if($_FILES['file']['type'] == 'application/vnd.ms-excel' && strtolower(substr($_FILES['file']['name'], strrpos($_FILES['file']['name'], '.')+1)) == 'csv'){
		if ($_FILES['file']['error'] > 0){
			$error = "Error:".$_FILES['file']['error'];
		}else{
// 			echo "Upload: " . $_FILES['file']['name'] . "<br />";
// 			echo "Type: " . $_FILES['file']['type'] . "<br />";
// 			echo "Size: " . ($_FILES['file']['size'] / 1024) . " Kb<br />";
// 			echo "Stored in: " . $_FILES['file']['tmp_name'];
			
			if (!file_exists('source/upload/'.$_FILES['file']['name'])){
				move_uploaded_file($_FILES['file']['tmp_name'], 'source/upload/' . $_FILES['file']['name']);
			}
			$file = 'source/upload/'.$_FILES['file']['name'];
		}
	}else{
		$error = 'ֻ֧��csv��ʽ...';
	}
}

if($_GET['line'] && $_GET['file']){
	require_once 'class/comm/class.file.php';
	echo File::getFileLineCount($_GET['file']);exit;
}

if($_POST['path'] && $_POST['num']){

	var_dump($_POST);
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gbk">
<title>XLS�ļ��ָ�</title>
<script type="text/javascript" src="/source/js/jquery-1.7.2.min.js"></script>
</head>
<script type="text/javascript">
	function getPage(path, num){
		$.get('?line=true&file='+path,function(sum){
			if(sum > 0){
				var select = document.createElement('select');
				document.forms[1].appendchild(select);			
			}
		});
	}
</script>
<body>
<div style="width:50%; margin:0 auto;padding:5px 5px;border:1px solid">
	<h2 align="center">XLS �ָ�</h2>
	<?php if (!$file){?>
	<form method="post" enctype="multipart/form-data">
		�ļ�:<input type="file" name="file"/>
		<input type="submit" value="�ύ"/><label style="color:red;"><?php echo $error?></label>
	</form>
	<?php }else{?>
	<form method="post" onsubmit="if(!parseInt(this.num.value) > 0){alert('�����������0');return false;}">
		<input type="button" value="��ѡ" onclick="location.href=location.href"/>
		<label>�ļ���</label>
		<input name="file" value="<?php echo $_FILES['file']['name'];?>" title="<?php echo $_FILES['file']['name'];?>" style="border:0;" readonly="readonly"/>
		<input type="hidden" name="path" value="<?php echo $file?>"/>
		<label>ÿҳ������</label>
		<input name="num" size="2" onblur="if(parseInt(this.value) > 0)getPage(this.form.path.value,this.value)"/>
		<input type="submit" value="����"/>
		<input type="button" value="Ԥ��"/>
	</form>
	<?php }?>
</div>
</body>
</html>