<meta http-equiv="Content-Type" content="text/html; charset=gbk">
<body bgcolor="gray">
	<div id="gallery-content">
<?php
require_once 'class/comm/class.file.php';
$files = File::pathFileList('source/image/gif');

function digui($files)
{
    foreach ($files as $file) {
        if (is_array($file)) {
            digui($file);
        } else {
            echo '<img border="1" style="margin:1px;" src="/source/image/gif/' . $file . '"/>';
        }
    }
}
digui($files);
?>
</div>
</body>