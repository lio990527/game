<?php 
$base = 'https://www.ruyile.com/xuexiao/';
$type = [1 => 5976, 2 => 5173, 3 => 4974];
$header = stream_context_create([
    'http' => [
        'timeout' => '10',
        'method' => 'GET',
        'header' => "accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9
referer: https://www.ruyile.com/xuexiao/
user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.104 Safari/537.36
",
    ]
]);
foreach ($type as $t => $max)
{
    $file = fopen('ruyile_' . $t . '.csv', 'w+');
    for ($p = 1; $p <= $max; $p++) {
        $url = $base . '?t=' . $t . '&p=' . $p;

        $data = file_get_contents($url, 0 , $header);
//         var_dump($data);
        $schools = array();
        preg_match_all('/(https\:\/\/www.ruyile.com\/school\/\w+\/)/s', $data, $schools);
//         var_dump($schools);
        if(empty($schools[1])){
            echo $t . ':' . $p . ' empty data!' . PHP_EOL;
            continue;
        }
        foreach ($schools[1] as $school) {
            $content = file_get_contents($school, 0 , $header);
            $info = array();
            $match = '<div\s+class="stk">\s*<h1>([^<]+)<\/h1>';
            $match.= '.*所属地区<\/strong>：(.*)<\/div>';
            $match.= '.*学校性质<\/strong>：([^<]*)<\/div>';
            $match.= '.*学校级别<\/strong>：([^<]*)<\/div>';
            $match.= '.*学校类型<\/strong>：([^<]*)<\/div>';
            $match.= '.*招生电话<\/strong>：([^<]*)<\/div>';
            $match.= '.*学校邮箱<\/strong>：([^<]*)<\/div>';
            $match.= '.*学校网址<\/strong>：(.*)<\/div>';
            $match.= '.*学校地址<\/strong>：([^<]*)<\/div>';
            $match.= '.*邮政编码<\/strong>：([^<]*)<\/div>';
            preg_match('/' . $match . '/Us', $content, $info);
            if(! empty($info)){
                unset($info[0]);
                $info = strip_tags(array_reduce($info, function($info, $v) {
                    $info .= $v . '|';
                    return $info;
                }));
                fwrite($file, $info . PHP_EOL);
            }
        }
        echo $t . ':' . $p . ' insert:' . count($schools[1]) . PHP_EOL;
    }
    die('end');
}