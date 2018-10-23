<?php
// vim: set expandtab tabstop=4 shiftwidth=4 fdm=marker:
// +----------------------------------------------------------------------+
// | The Club Portal v1.0                                                 |
// +----------------------------------------------------------------------+ 
// | Copyright (c) 2011, Tencent Inc. All rights reserved.                |
// +----------------------------------------------------------------------+
// | Authors: The Club Dev Team, ISRD, Tencent Inc.                       |
// |          tenfyguo <tenfyguo@tencent.com>                             |                  
// +----------------------------------------------------------------------+


//�������
define("EC_GBK",    100);
define("EC_GB2312", 200);
define("EC_UTF8",   300);


//�̻�����������
$MALL_CONNECT_CONFIG = array (
    //������elong����̻�Ϊdemo���ӽ���˵��
    10069 => array(      
        'showmsgVersion' => 1,
		'cnName'         => '����������',   //�̼�������
        'enId'           => 'elong',        //�̼�Ӣ��Id
        'domain'         => 'elong',        //�̼�վ�������б��м�ͨ��'|'�ָ�
        'index'          => 'http://www.tieyou.com/',       //�̼���ҳ��ַ
        //'loginURL'       => 'http://www.elong.com/redirect/qqlogin.aspx',//�̼ҵ����ϵ�¼������תcgi URL  
		'loginURL'       => 'http://caibei.tieyou.com/QQCaiBei/login.html',//�̼ҵ����ϵ�¼������תcgi URL  

        'key1'           => 'w8U7o0Q@6Y7eppgif6qTx6Y5km!!u6YY',  //TODO:��������д���Ե�key,��������ǰ��ҪQQ�ʱ�������ʽ��key�滻����
        'key2'           => 'QaI6sk6uIOay2Tgq4w!*n1w2n9ee%kWn',  //TODO:��������д���Ե�key,��������ǰ��ҪQQ�ʱ�������ʽ��key�滻����  
        'charEncoding'   => EC_GBK            //�̼ҵ�վ�����
        
    )
    

);


//���Ե�QQ�������飬��������������Ժ��룬���ڲ��ԣ�������ǳƣ���Ҫ��ת���̻���վ���ܹ���������ʾ
$UIN_CONNECT_CONFIG = array(
	1002000201 => array(
		'Acct'     => '0000000000000000000000000000A4D9',
		'OpenId'   => '0000000000000000000000000000A4D9',
		'NickName' => "��.'HHH \   m",
		'ClubInfo' => 1,
		'CBPoints' => 1000
	),

    1002000202 => array(
		'Acct'     => '0000000000000000000000000000B47A',
		'OpenId'   => '0000000000000000000000000000B47A',
		'NickName' => "<script>alert()</script>",
		'ClubInfo' => 0,
		'CBPoints' => 100
	),

    1002000203 => array(
		'Acct'     => '00000000000000000000000000009D0B',
		'OpenId'   => '00000000000000000000000000009D0B',
		'NickName' => "�ټ����Z�����멹'Idiot��",
		'ClubInfo' => 1,
		'CBPoints' => 100
	),
 
    1002000204 => array(
		'Acct'     => '000000000000000000000000000081B4',
		'OpenId'   => '000000000000000000000000000081B4',
		'NickName' => "����@#��%&*����?><u>o</u>",
		'ClubInfo' => 1,
		'CBPoints' => 190
	)
);

/////////////////////////////////////////////////������������ʼ///////////////////////////////////////////////////////
//���ò�cache
function cbSetNoCache() {
    // HTTP/1.1
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    // HTTP/1.0
    header("Pragma: no-cache");
}

//�ǳ�������12���ַ�
function nick_filter( $nick, $encodeHTML=true ) {
    if(strlen( trim($nick) ) > 20) {
        $nick = substr( $nick, 0, 20); 
    }

    $nick = str_replace( "\n" , '' , $nick );
    $nick = str_replace( "\r" , '' , $nick );
    
    return $encodeHTML? str_replace(' ','&nbsp;',htmlspecialchars($nick, ENT_QUOTES)) : $nick; 
}


//��֤Ŀ��url�Ƿ�Ϸ�
function make_safe_url($mall_id, $to_url='') 
{
    global $MALL_CONNECT_CONFIG;
    $domain = @$MALL_CONNECT_CONFIG[$mall_id]['domain'];
    if( $domain == '' ) {
        return '';
    }

    if($to_url == '' || strlen($to_url) > 500) 
    {
        return ''; //������һ�����ȵ�url����Ĭ��Ϊ��
    }
      
    $url_arr  = parse_url($to_url);
    $url_host = @$url_arr['host'];

    //��ת����ƥ��
    if(preg_match ('/^(\w+\.)?'.$domain.'\./i', $url_host)) 
    {
        return $to_url;
    }
    else
    {
        return '';
    }
}

//GBKת����UTF-8����
function gbk2utf8($str) {
    return @iconv( "GBK", "UTF-8//IGNORE", $str ) ;
}


function getViewinfo($uin , $mId , $clubinfo)
{
    global $MALL_CONNECT_CONFIG, $UIN_CONNECT_CONFIG;
    //����
    $encode = @$MALL_CONNECT_CONFIG[$mId]['charEncoding'];

    //�ǳ�
    $nickname = nick_filter( $UIN_CONNECT_CONFIG[$uin]['NickName']);
    if( $encode == EC_UTF8 ) {
        $nickname = gbk2utf8($nickname);
    }

    $point = $UIN_CONNECT_CONFIG[$uin]['CBPoints'];

    $bonus = '5%';

    $viewinfoArr = array(
        'ShowMsg'  => getShowmsg($nickname , $clubinfo , $point , $bonus , $mId) ,
        'NickName' => $nickname ,
        'CBPoints'    => $point ,
        'CBBonus'    => $bonus 
    );

    return http_build_query($viewinfoArr);
}


function getShowmsg($nickname , $clubinfo , $point , $bonus , $mId)
{
    global $MALL_CONNECT_CONFIG;
    $showmsgVersion = @$MALL_CONNECT_CONFIG[$mId]['showmsgVersion'];
    $encode = @$MALL_CONNECT_CONFIG[$mId]['charEncoding'];

    $club = ($clubinfo==1 ? 'QQ��Ա' : 'QQ�û�') ;
    
    switch($showmsgVersion) {
        case 1 :
            $msg = $club . '��';

            if( $encode == EC_UTF8 ) {
                return  gbk2utf8($msg) . $nickname;
            } else {
                return  $msg . $nickname;
            }

            break;       

        case 2:
            $msg = '';
            if($clubinfo == 1) {
                 $msg = "��QQ�û��Ź��òʱ����֣�����<font color=\"red\">QQ��Ա</font>�������Ź��ɹ��񶩵����<font color=\"red\">10%</font>��˫�����ַ�����<a href=\"http://cb.qq.com/my/my_jifen.html\" target=\"_blank\">�鿴�ҵĲʱ�����</a>";
            } else {
                 $msg = "��QQ�û��Ź��òʱ����֣��Ź��ɹ��ɻ񶩵����<font color=\"red\">5%</font>�Ĳʱ����ַ�����<a href=\"http://cb.qq.com/my/my_jifen.html\" target=\"_blank\">�鿴�ҵĲʱ�����</a>&nbsp;&nbsp;&nbsp;<a href=\"http://pay.qq.com/qqvip/index.shtml?aid=vip.caibei.gaopeng.index.navbar.kaitong\" target=\"_blank\" title=\"QQ��Ա�û��Ź��ɹ����ɻ�ö������10%��˫���ʱ����ַ�����\"><font color=\"red\">��ͨQQ��Ա</font></a>";
            }

            if( $encode == EC_UTF8 ) {
                 return  gbk2utf8('���ã�') .  $nickname . gbk2utf8($msg);
            } else {
                 return  '���ã�' . $nickname . $msg;
            }
           
			break;

        default :
            return $nickname;
            break;
    }
}


function getVKey($mId , $param)
{
    global $MALL_CONNECT_CONFIG;
    $key1 = $MALL_CONNECT_CONFIG[$mId]['key1'];
    $key2 = $MALL_CONNECT_CONFIG[$mId]['key2'];

    ksort($param);
    $vkey  = implode('', $param);
    $md5_1 = strtolower(md5($vkey.$key1));
    $vkey  = strtolower(md5($md5_1.$key2));

    return $vkey;
}


function getOutputHtml($mId , $param , $vkey)
{
    global $MALL_CONNECT_CONFIG;
    $handleUrl = @$MALL_CONNECT_CONFIG[$mId]['loginURL'];

    $loginFrom = htmlspecialchars($param['LoginFrom']);
    $viewinfo  = htmlspecialchars($param['ViewInfo']);
    $toUrl     = htmlspecialchars($param['Url']);
    $attach    = htmlspecialchars($param['Attach']);

    return <<<EOD
        <html>
        <body>
            <form id="jumpForm" action="{$handleUrl}" method="post">
                <input type="hidden" name="Acct" value="{$param['Acct']}" />
                <input type="hidden" name="OpenId" value="{$param['OpenId']}" />
                <input type="hidden" name="LoginFrom" value="{$loginFrom}" />
                <input type="hidden" name="ClubInfo" value="{$param['ClubInfo']}" />
                <input type="hidden" name="ViewInfo" value="{$viewinfo}" />
                <input type="hidden" name="Url" value="{$toUrl}" />
                <input type="hidden" name="Ts" value="{$param['Ts']}" />
                <input type="hidden" name="Attach" value="{$attach}" />
                <input type="hidden" name="Vkey" value="{$vkey}" />
            </form>
        </body>

        <script language="JavaScript">
            document.getElementById('jumpForm').submit();
        </script>
        </html>
EOD;
}


////////////////////////////////////////////////////////��������������///////////////////////////////////////
cbSetNoCache();

//��1������ȡ���еĲ����б��жϲ�����Ч��
$toUrl      = @$_GET['to_url'];
$mId        = @intval($_GET['m_id']);
$attach     = @$_GET['attach'];
$loginFrom  = @$_GET['login_from'];
$uin        = @intval($_GET['uin']);



//��2����������֤�Ϸ���
if( !array_key_exists($mId , $MALL_CONNECT_CONFIG) ) {
    echo "�Ƿ����̻�id����Ӧ���̻�id�������ڶ�Ӧ���̻���������������";
    exit();
}

if( !array_key_exists($uin , $UIN_CONNECT_CONFIG) ) {
    echo "�ǲ���QQ����";
    exit();
}

//if($toUrl != '') {
//   $toUrl = make_safe_url($mId, $toUrl);
//}

if( strlen($attach) > 1024 ) {
    $attach = '';
}

if( $loginFrom == '' ) {
    $loginFrom = 'caibei';
}


//��4�����ؼ��Ĵ�����:���ϵ�½�߼���ʼ
$openId     = $UIN_CONNECT_CONFIG[$uin]['OpenId'];
$acct       = $UIN_CONNECT_CONFIG[$uin]['Acct'];
$clubinfo   = $UIN_CONNECT_CONFIG[$uin]['ClubInfo'];;
$viewinfo   = getViewinfo($uin , $mId , $clubinfo);
$ts         = date('YmdHis');

//�����̼ҵĲ���
$postParam = array(
    'Acct'      => $acct , 
    'OpenId'    => $openId ,
    'LoginFrom' => $loginFrom , 
    'ClubInfo'  => $clubinfo ,
    'ViewInfo'  => $viewinfo ,
    'Url'       => $toUrl ,
    'Ts'        => $ts ,
    'Attach'    => $attach
);

$vkey = getVKey($mId , $postParam);

//��5���������ת����
echo getOutputHtml($mId , $postParam , $vkey);

?>