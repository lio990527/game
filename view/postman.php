<?php

$name = $_REQUEST['server'] ?? 'default';

$domain = 'http://api-local.imxyb.com';
$service = [
    [
        'uri' => '/api/test',
        'method' => 'POST',
        'param' => [
            'string' => 'abc'
        ]
    ],
    [
        'uri' => '/api/user',
        'method' => 'GET',
        'param' => [
            'merchant_id' => '11343'
        ]
    ]
];
if ($_POST) {
    $response = 'header:dsadas 
body:asdasd';
    $result = [];
}
$server = isset($_REQUEST['detail']) ? $service[$_REQUEST['detail']] : null;
?>

<!DOCTYPE html>
<html lang="zh">
<head>
<title>--</title>
</head>
<body>
	<?php if (! $server):?>
	<div>
    	domain:<input size="50" value="<?php echo $domain?>"/>
    	<h3>service</h3>
    	<ol>
    		<?php foreach ($service as $server):?>
    		<li><?php echo $server['uri']?></li>
    		<?php endforeach;?>
    	</ol>
	</div>
	<?php else: ?>
	<div>
		<form method="post">
    		<input type="hidden" name="url" value="<?php echo $domain . $server['uri']?>"/>
    		<input type="hidden" name="method" value="<?php echo $server['method']?>"/>
    		<b><?php echo $server['method']?></b>: <?php echo $domain . $server['uri']?>
    		<h4>param:</h4>
    		<ul style="font-size:12px;list-style:none;">
    			<?php foreach ($server['param'] as $key => $val):?>
    			<li><?php echo $key?>: <input name="param[<?php echo $key?>]" value="<?php echo $val?>"/><input type="checkbox"></li>
    			<?php endforeach;?>
    		</ul>
    		<button name="query" value="submit">submit</button>
		</form>
	</div>
	<?php endif;?>
	<?php if($response):?>
	<div style="margin-top:20px;">
		<textarea rows="20" cols="120"><?php echo $response?></textarea>
		<textarea rows="30" cols="120"><?php print_r($result);?></textarea>
	</div>
	<?php endif;?>
</body>
</html>