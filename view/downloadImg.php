<?php
define('DOWNLOAD_PATH','http://www.lio.com/');
echo 'beign';
spider();
die('end');
function spider($path = DOWNLOAD_PATH) {
	$urls = array();

	addPage('http://www.baidu.com', $urls);
	// 	print_r($urls);
	// 	error_reporting(0);
	foreach ($urls as $url => $referer) {
		// 		echo $url, "\n";
		$filename = pathinfo($url, PATHINFO_FILENAME) . '.' . pathinfo($url, PATHINFO_EXTENSION);
		download($url, $path . $filename, $referer);
	}
	// 	error_reporting(1);
}


function addPage($page, &$urls) {
	$cur = extractPage($page);
	for ($i = 0, $n = count($cur); $i < $n; ++$i) {
		$j = $cur[$i];

		if (!isset($urls[$j]))
			$urls[$j] = $page;
	}
}

function extractPage($page, $reg = '/<img.*?src="(.*?)".*?>/') {
	$content = file_get_contents($page);
	// $content = '<img src="1.png">';
	preg_match_all($reg, $content, $matches);

	return $matches[1];
}

function download($url, $file, $referer = '') {
	$url = abs_url($url, $referer);
	echo $url, "\n";

	$opts = array(
			'http' => array(
					'method' => 'GET',
					'header' => "Accept-language: en\r\n" .
					// "Cookie: \r\n" .
					"Referer: " . $url . "\r\n"
			)
	);

	$context = stream_context_create($opts);

	file_put_contents($file, file_get_contents($url, false, $context));

	return $file;
}

function abs_url($url, $referer) {
	$com = parse_url($url);

	if (!isset($com['scheme']))
		$com['scheme'] = 'http';

	if ($referer !== '') {
		$ref = parse_url($referer);

		if (!isset($com['host'])) {
			if (isset($ref['host'])) {
				$com['host'] = $ref['host'];
				if (!isset($ref['path']))
					$ref['path'] = '/';
			}

			if (isset($com['path'][0])) {
				if ($com['path'][0] !== '/')
					$com['path'] = $ref['path'] . $com['path'];
			} else if (isset($ref['host'])) {
				$com['path'] = $ref['path'];
			}
		} else {
			if (!isset($com['path']))
				$com['path'] = '';
		}
	}

	return unparse_url($com);
}

function unparse_url($com) {
	return (isset($com['host']) ? ($com['scheme'] . '://' . $com['host']) : '') . $com['path'] . (isset($com['query']) ? '?'.$com['query'] : '');
}