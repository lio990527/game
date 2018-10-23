<meta http-equiv="Content-Type" content="text/html; charset=gbk">
<body bgcolor="gray">
<div id="gallery-content">
	<div id="gallery-content-center">
<?php 
require_once 'class/comm/class.file.php';
$files = File::pathFileList('E:/image/gif');

function digui($files){
	foreach ($files as $file){
		if(is_array($file)){
			digui($file);
		}else{
			echo '<img border="1" style="margin:1px;" src="http://img.lio.com/gif/'.$file.'"/>';
		}
	}
}
digui($files);
?>
	</div>
</div>
<script type="text/javascript" src="http://www.lanrenzhijia.com/demos/48/4871/demo/js/jquery.min.js"></script>
<script type="text/javascript" src="http://www.lanrenzhijia.com/demos/48/4871/demo/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="http://www.lanrenzhijia.com/demos/48/4871/demo/js/jquery.isotope.min.js"></script>
<script type="text/javascript" src="http://www.lanrenzhijia.com/demos/48/4871/demo/js/animated-masonry-gallery.js"></script>
</body>