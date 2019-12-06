<?php
$bgUrl = 'https://www.toptal.com/designers/subtlepatterns/page/' . rand(1, 50);
$bgList = [];
$content = file_get_contents($bgUrl);
$bgCount = preg_match_all('/style="background-image:\s*url\(\'?([^\'\)]*)\'?\)/i', $content, $bgList);
$bgImage = $bgCount ? $bgList[1][array_rand($bgList[1])] : '';
$bgImage = strpos($bgImage, 'http') === 0 ? $bgImage : 'https://www.toptal.com' . $bgImage;
echo $bgImage;