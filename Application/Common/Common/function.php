<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/10/23
 * Time: 13:52
 */

//不带logo的二维码
//$url //网址或者是文本内容
function qrcode($url){
    Vendor('phpqrcode.phpqrcode');
    //生成二维码图片
    $object = new \QRcode();
    $level=3;
    $size=4;
    $errorCorrectionLevel =intval($level) ;//容错级别
    $matrixPointSize = intval($size);//生成图片大小
    $object->png($url, false, $errorCorrectionLevel, $matrixPointSize, 2);
}
//带logo的二维码
function qrcode_img()
{
    Vendor('phpqrcode.phpqrcode');
    //生成二维码图片
    $object = new \QRcode();
    $qrcode_path = '';
    $file_tmp_name = '';
    $errors = array();
    if (!empty($_POST)) {
        $content = trim($_POST['content']); //二维码内容
        $contentSize = $this->getStringLength($content);
        if ($contentSize > 150) {
            $errors[] = '字数过长，不能多于150个字符！';
        }
        if (isset($_FILES['upimage']['tmp_name']) && $_FILES['upimage']['tmp_name'] && is_uploaded_file($_FILES['upimage']['tmp_name'])) {
            if ($_FILES['upimage']['size'] > 512000) {
                $errors[] = "你上传的文件过大，最大不能超过500K。";
            }
            $file_tmp_name = $_FILES['upimage']['tmp_name'];
            $fileext = array("image/pjpeg", "image/jpeg", "image/gif", "image/x-png", "image/png");
            if (!in_array($_FILES['upimage']['type'], $fileext)) {
                $errors[] = "你上传的文件格式不正确，仅支持 png, jpg, gif格式。";
            }
        }
        $tpgs = $_POST['tpgs'];//图片格式
        $qrcode_bas_path = 'upload/qrcode/';
        if (!is_dir($qrcode_bas_path)) {
            mkdir($qrcode_bas_path, 0777, true);
        }
        $uniqid_rand = date("Ymdhis") . uniqid() . rand(1, 1000);
        $qrcode_path = $qrcode_bas_path . $uniqid_rand . "_1." . $tpgs;//原始图片路径
        $qrcode_path_new = $qrcode_bas_path . $uniqid_rand . "_2." . $tpgs;//二维码图片路径
        if (Helper::getOS() == 'Linux') {
            $mv = move_uploaded_file($file_tmp_name, $qrcode_path);
        } else {
            //解决windows下中文文件名乱码的问题
            $save_path = Helper::safeEncoding($qrcode_path, 'GB2312');
            if (!$save_path) {
                $errors[] = '上传失败，请重试！';
            }
            $mv = move_uploaded_file($file_tmp_name, $qrcode_path);
        }
        if (empty($errors)) {
            $errorCorrectionLevel = $_POST['errorCorrectionLevel'];//容错级别
            $matrixPointSize = $_POST['matrixPointSize'];//生成图片大小
            $matrixMarginSize = $_POST['matrixMarginSize'];//边距大小
            //生成二维码图片
            $object::png($content, $qrcode_path_new, $errorCorrectionLevel, $matrixPointSize, $matrixMarginSize);
            $QR = $qrcode_path_new;//已经生成的原始二维码图
            $logo = $qrcode_path;//准备好的logo图片
            if (file_exists($logo)) {
                $QR = imagecreatefromstring(file_get_contents($QR));
                $logo = imagecreatefromstring(file_get_contents($logo));
                $QR_width = imagesx($QR);//二维码图片宽度
                $QR_height = imagesy($QR);//二维码图片高度
                $logo_width = imagesx($logo);//logo图片宽度
                $logo_height = imagesy($logo);//logo图片高度
                $logo_qr_width = $QR_width / 5;
                $scale = $logo_width / $logo_qr_width;
                $logo_qr_height = $logo_height / $scale;
                $from_width = ($QR_width - $logo_qr_width) / 2;
                //重新组合图片并调整大小
                imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,
                    $logo_qr_height, $logo_width, $logo_height);
                //输出图片
                //header("Content-type: image/png");
                imagepng($QR, $qrcode_path);
                imagedestroy($QR);
            } else {
                $qrcode_path = $qrcode_path_new;
            }
        } else {
            $qrcode_path = '';
        }
    }
    $data = array('data' => array('errors' => $errors, 'qrcode_path' => $qrcode_path));
    $this->assign('data', $data);
    $this->display();
}

//判断是不是手机访问
function is_mobile()
{
    $_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';
    $mobile_browser = '0';
    if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', strtolower($_SERVER['HTTP_USER_AGENT'])))
        $mobile_browser++;
    if ((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']), 'application/vnd.wap.xhtml+xml') !== false))
        $mobile_browser++;
    if (isset($_SERVER['HTTP_X_WAP_PROFILE']))
        $mobile_browser++;
    if (isset($_SERVER['HTTP_PROFILE']))
        $mobile_browser++;
    $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
    $mobile_agents = array(
        'w3c ', 'acs-', 'alav', 'alca', 'amoi', 'audi', 'avan', 'benq', 'bird', 'blac',
        'blaz', 'brew', 'cell', 'cldc', 'cmd-', 'dang', 'doco', 'eric', 'hipt', 'inno',
        'ipaq', 'java', 'jigs', 'kddi', 'keji', 'leno', 'lg-c', 'lg-d', 'lg-g', 'lge-',
        'maui', 'maxo', 'midp', 'mits', 'mmef', 'mobi', 'mot-', 'moto', 'mwbp', 'nec-',
        'newt', 'noki', 'oper', 'palm', 'pana', 'pant', 'phil', 'play', 'port', 'prox',
        'qwap', 'sage', 'sams', 'sany', 'sch-', 'sec-', 'send', 'seri', 'sgh-', 'shar',
        'sie-', 'siem', 'smal', 'smar', 'sony', 'sph-', 'symb', 't-mo', 'teli', 'tim-',
        'tosh', 'tsm-', 'upg1', 'upsi', 'vk-v', 'voda', 'wap-', 'wapa', 'wapi', 'wapp',
        'wapr', 'webc', 'winw', 'winw', 'xda', 'xda-'
    );
    if (in_array($mobile_ua, $mobile_agents))
        $mobile_browser++;
    if (strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false)
        $mobile_browser++;
    // Pre-final check to reset everything if the user is on Windows
    if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') !== false)
        $mobile_browser = 0;
    // But WP7 is also Windows, with a slightly different characteristic
    if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows phone') !== false)
        $mobile_browser++;
    if ($mobile_browser > 0)
        return true;
    else
        return false;
}

//生成$num位随机数
function myRands($num=5){
    $randArr = array();
    for($i = 0; $i < $num; $i++){
        $randArr[$i] = rand(0, 9);
        $randArr[$i + $num] = chr(rand(0, 25) + 97);
    }
    shuffle($randArr);
    return implode('', $randArr);
}

//截取字符串
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true)
{
    if(function_exists("mb_substr")){
        if($suffix)
            return mb_substr($str, $start, $length, $charset)."...";
        else
            return mb_substr($str, $start, $length, $charset);
    }
    elseif(function_exists('iconv_substr')) {
        if($suffix)
            return iconv_substr($str,$start,$length,$charset)."...";
        else
            return iconv_substr($str,$start,$length,$charset);
    }
    $re['utf-8'] = "/[x01-x7f]|[xc2-xdf][x80-xbf]|[xe0-xef][x80-xbf]{2}|[xf0-xff][x80-xbf]{3}/";
    $re['gb2312'] = "/[x01-x7f]|[xb0-xf7][xa0-xfe]/";
    $re['gbk'] = "/[x01-x7f]|[x81-xfe][x40-xfe]/";
    $re['big5'] = "/[x01-x7f]|[x81-xfe]([x40-x7e]|xa1-xfe])/";
    preg_match_all($re[$charset], $str, $match);
    $slice = join("",array_slice($match[0], $start, $length));
    if($suffix) return $slice."…";
    return $slice;

}

//抓取远程html页面
function socket_data($url){
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $contents = curl_exec($ch);
    curl_close($ch);
    return $contents;
}

//用户请使用UTF-8作为php源码文件的保存格式，避免出现乱码问题
function request_curl_get($url,$param_array){
    $ca_info = dirname(__FILE__) . '/cacert.pem';		//根证书文件路径,相对路径和绝对路径均可,推荐使用绝对路径;demo里文件和源码放在一起了，为了安全证书文件最好不要和应用代码放在一起
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url . '?' . http_build_query($param_array));
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ;
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);		//验证交换证书
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); 		//检查SSL证书公用名是否存在，并且是否与提供的主机名匹配
    curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);		//设置实现协议为TLS1.0版本
    curl_setopt($ch, CURLOPT_CAINFO,  $ca_info); 		//设置根证书文件路径
    $data = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);
    if(!empty($error)){		//curl有错误
        echo $error;
    }else{		//输出请求结果
        echo $data;
    }
}

/**
 * 基于curl的post访问方式(推荐)，要求php打开curl扩展
 * $url @string 请求地址
 * $param_array @array 参数数组
 */
function request_curl_post($url,$param_array){
    $ca_info = dirname(__FILE__) . '/cacert.pem';		//根证书文件路径,相对路径和绝对路径均可,推荐使用绝对路径;demo里文件和源码放在一起了，为了安全证书文件最好不要和应用代码放在一起
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($param_array));
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);		//验证交换证书
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); 		//检查SSL证书公用名是否存在，并且是否与提供的主机名匹配
    curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);		//设置实现协议为TLS1.0版本
    curl_setopt($ch, CURLOPT_CAINFO,  $ca_info);

    $data = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);
    $str = "";
    if(!empty($error)){		//curl有错误
        $str = '发送失败';
    }else{		//输出请求结果
        $str='yes';
    }
    return $str;
}

/*
 * 发送邮件
 * @param $to string
 * @param $title string
 * @param $content string
 * @return bool
 * */
function SendMail($to, $title, $content) {
    Vendor('PHPMailer.PHPMailerAutoload');
    $mail = new PHPMailer(); //实例化
    $mail->IsSMTP(); // 启用SMTP
    $mail->Host=C('MAIL_HOST'); //smtp服务器的名称（这里以QQ邮箱为例）
    $mail->SMTPAuth = C('MAIL_SMTPAUTH'); //启用smtp认证
    $mail->Username = C('MAIL_USERNAME'); //发件人邮箱名
    $mail->Password = C('MAIL_PASSWORD') ; //163邮箱发件人授权密码
    $mail->From = C('MAIL_FROM'); //发件人地址（也就是你的邮箱地址）
    $mail->FromName = C('MAIL_FROMNAME'); //发件人姓名
    $mail->AddAddress($to,"尊敬的客户");
    $mail->WordWrap = 50; //设置每行字符长度
    $mail->IsHTML(C('MAIL_ISHTML')); // 是否HTML格式邮件
    $mail->CharSet=C('MAIL_CHARSET'); //设置邮件编码
    $mail->Subject =$title; //邮件主题
    $mail->Body = $content; //邮件内容
    $mail->AltBody = "这是一个纯文本的身体在非营利的HTML电子邮件客户端"; //邮件正文不支持HTML的备用显示
    return($mail->Send());
}

//获取IP地址
function getIP() {
    if (getenv('HTTP_CLIENT_IP')) {
        $ip = getenv('HTTP_CLIENT_IP');
    }
    elseif (getenv('HTTP_X_FORWARDED_FOR')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
    }
    elseif (getenv('HTTP_X_FORWARDED')) {
        $ip = getenv('HTTP_X_FORWARDED');
    }
    elseif (getenv('HTTP_FORWARDED_FOR')) {
        $ip = getenv('HTTP_FORWARDED_FOR');

    }
    elseif (getenv('HTTP_FORWARDED')) {
        $ip = getenv('HTTP_FORWARDED');
    }
    else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

//创建GUID
function guid(){
    if (function_exists('com_create_guid')){
        return com_create_guid();
    }else{
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = chr(123)// "{"
            .substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12)
            .chr(125);// "}"
        return $uuid;
    }
}

