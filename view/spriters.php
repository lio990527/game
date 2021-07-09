<?php 
function download($furl, $fname, $fpath = '') {
    $fheaders = get_headers($furl, 1);
    if (! strpos($fheaders[0], '200')) {
        throw new Exception("get [{$fname}] failed:{$fheaders[0]}");
    }
    if (isset($fheaders['Content-Disposition'])) {
        preg_match('/filename="(.*)"/', $fheaders['Content-Disposition'], $fname);
        $fname = $fname[1];
    } elseif(strpos($furl, '.') && !strpos($fname, '.')) {
        $fname .= substr($furl, strrpos($furl, '.'));
    }
    if (file_exists($fpath . '/' . $fname)) {
        $fname = preg_replace('/\.(png|jpg|jpeg|gif)/', '_' . $fname . '.$1', $fname);
    }
    file_put_contents($fpath . '/' . $fname, file_get_contents($furl));
    return $fname;
}

$host = 'https://www.spriters-resource.com';
$uris = [
    'megamanx2',
    'megamanx3',
    'melfand',
    'metalmax2',
    'metalmaxreturns',
    'metalwarriors',
    'mightymorphinpowerrangersthefightingedition',
    'mightymorphinpowerrangersthemovie',
    'mortalkombat',
    'mortalkombat3',
    'mortalkombat2',
    
];

foreach ($uris as $uri) {
    $uri = '/snes/' . $uri . '/';
    $stream_opts = stream_context_create([
        "ssl" => [
            "verify_peer" => false,
            "verify_peer_name" => false,
        ],
        'http' => [
            'timeout' => '30',
            'method' => 'GET',
        ]
    ]);

    $fpath = str_replace('/', ' - ', trim($uri, '/'));
    $body = file_get_contents($host . $uri, 0, $stream_opts);
    // var_dump($body);
    $title = $icon = $sects = $sheets = array();
    preg_match('/<title>([^<]+)<\/title>/', $body, $title);
    preg_match('/<div\s+class="gameiconbody">\s*<img\s+src="([^"]+)"/', $body, $icon);
    preg_match_all('/<div\s+class="sect-name"\s+title="([^"]*)">/', $body, $sects);
    preg_match_all('/<div\s+class="updatesheeticons">(.*?)<\/a>\s*<\/div>/s', $body, $sheets);
    if ($title[1]) {
        $fpath = str_replace([':','\\','/','|','<','>','*','?','"'], '', $title[1]);
    }
    $fpath = 'E:/下载/pic/' . $fpath;
    echo $fpath.PHP_EOL;
    file_exists($fpath) OR mkdir($fpath);

    if ($icon[1]) {
        try {
            $fname = download($host . $icon[1], 'gameicon', $fpath);
            echo '>' . $fname . ' saved' . PHP_EOL;
        } catch (Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }
    foreach ($sheets[1] as $index => $sheet) {
        $links = array();
        preg_match_all('/<a\s+href="[\w\/]+\/(\d*)\/"/s', $sheet, $links);
        if (count($links[1]) >=3 && isset($sects[1][$index])) {
            $ffpath = $fpath . '/' . str_replace([':','\\','/','|','<','>','*','?','"'], ' ', $sects[1][$index]);
            file_exists($ffpath) OR mkdir($ffpath);
        } else {
            $ffpath = $fpath;
        }
        foreach ($links[1] as $fid) {
            $fname = $fid;
            $furl = $host . '/download/' . $fid  . '/';
            try {
                $fname = download($furl, $fname, $ffpath);
                echo '>' . $fname . ' saved' . PHP_EOL;
            } catch (Exception $e) {
                echo $e->getMessage() . PHP_EOL;
                continue;
            }
        }
    }
}
die('end');