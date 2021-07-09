<?php 

$base = 'http://xuexiao.51sxue.com/slist/';
$type = [4];
$page = 1;
foreach ($type as $t)
{
    // 2 = 1165
    $file = fopen('zxw' . $t . '.csv', 'a');
    do {
        $url = $base . '?t=' . $t . '&page=' . $page;
        $data = file_get_contents($url);
        if(empty($data)){
            var_dump($t . ':' . $page . 'end');
            $page = 1;
            break;
        }
        $data = iconv('GB2312', 'UTF-8//IGNORE', $data);
//         var_dump($data);exit;
        $infos = array();
        preg_match_all('/<h3>\s*<a[^>]*>([^<]*)<\/a><\/h3>.*地区:<b>([^<]*)<\/b>.*属性:<b>([^<]*)<\/b><\/li>.*性质:<b>([^<]*)<\/b>.*类型:<b>([^<]*)<\/b>.*学校地址:<b>([^<]*)<\/b>.*联系电话:<b>([^<]*)<\/b>/Us', $data, $infos);
//         var_dump($data,$infos);exit;
        if(empty($infos[1])) {
            var_dump($t . ':' . $page . 'empty');
        }
        foreach ($infos[1] as $index => $name) {
            fwrite($file, "{$name},{$infos[2][$index]},{$infos[3][$index]},{$infos[4][$index]},{$infos[5][$index]},{$infos[6][$index]},{$infos[7][$index]}" . PHP_EOL);
        }
        echo '>'. $t . ':' . $page . PHP_EOL;
        $page++;
    } while (true);
}