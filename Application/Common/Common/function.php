<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/10/23
 * Time: 13:52
 */

//����logo�Ķ�ά��
//$url //��ַ�������ı�����
function qrcode($url){
    Vendor('phpqrcode.phpqrcode');
    //���ɶ�ά��ͼƬ
    $object = new \QRcode();
    $level=3;
    $size=4;
    $errorCorrectionLevel =intval($level) ;//�ݴ���
    $matrixPointSize = intval($size);//����ͼƬ��С
    $object->png($url, false, $errorCorrectionLevel, $matrixPointSize, 2);
}
//��logo�Ķ�ά��
function qrcode_img()
{
    Vendor('phpqrcode.phpqrcode');
    //���ɶ�ά��ͼƬ
    $object = new \QRcode();
    $qrcode_path = '';
    $file_tmp_name = '';
    $errors = array();
    if (!empty($_POST)) {
        $content = trim($_POST['content']); //��ά������
        $contentSize = $this->getStringLength($content);
        if ($contentSize > 150) {
            $errors[] = '�������������ܶ���150���ַ���';
        }
        if (isset($_FILES['upimage']['tmp_name']) && $_FILES['upimage']['tmp_name'] && is_uploaded_file($_FILES['upimage']['tmp_name'])) {
            if ($_FILES['upimage']['size'] > 512000) {
                $errors[] = "���ϴ����ļ���������ܳ���500K��";
            }
            $file_tmp_name = $_FILES['upimage']['tmp_name'];
            $fileext = array("image/pjpeg", "image/jpeg", "image/gif", "image/x-png", "image/png");
            if (!in_array($_FILES['upimage']['type'], $fileext)) {
                $errors[] = "���ϴ����ļ���ʽ����ȷ����֧�� png, jpg, gif��ʽ��";
            }
        }
        $tpgs = $_POST['tpgs'];//ͼƬ��ʽ
        $qrcode_bas_path = 'upload/qrcode/';
        if (!is_dir($qrcode_bas_path)) {
            mkdir($qrcode_bas_path, 0777, true);
        }
        $uniqid_rand = date("Ymdhis") . uniqid() . rand(1, 1000);
        $qrcode_path = $qrcode_bas_path . $uniqid_rand . "_1." . $tpgs;//ԭʼͼƬ·��
        $qrcode_path_new = $qrcode_bas_path . $uniqid_rand . "_2." . $tpgs;//��ά��ͼƬ·��
        if (Helper::getOS() == 'Linux') {
            $mv = move_uploaded_file($file_tmp_name, $qrcode_path);
        } else {
            //���windows�������ļ������������
            $save_path = Helper::safeEncoding($qrcode_path, 'GB2312');
            if (!$save_path) {
                $errors[] = '�ϴ�ʧ�ܣ������ԣ�';
            }
            $mv = move_uploaded_file($file_tmp_name, $qrcode_path);
        }
        if (empty($errors)) {
            $errorCorrectionLevel = $_POST['errorCorrectionLevel'];//�ݴ���
            $matrixPointSize = $_POST['matrixPointSize'];//����ͼƬ��С
            $matrixMarginSize = $_POST['matrixMarginSize'];//�߾��С
            //���ɶ�ά��ͼƬ
            $object::png($content, $qrcode_path_new, $errorCorrectionLevel, $matrixPointSize, $matrixMarginSize);
            $QR = $qrcode_path_new;//�Ѿ����ɵ�ԭʼ��ά��ͼ
            $logo = $qrcode_path;//׼���õ�logoͼƬ
            if (file_exists($logo)) {
                $QR = imagecreatefromstring(file_get_contents($QR));
                $logo = imagecreatefromstring(file_get_contents($logo));
                $QR_width = imagesx($QR);//��ά��ͼƬ���
                $QR_height = imagesy($QR);//��ά��ͼƬ�߶�
                $logo_width = imagesx($logo);//logoͼƬ���
                $logo_height = imagesy($logo);//logoͼƬ�߶�
                $logo_qr_width = $QR_width / 5;
                $scale = $logo_width / $logo_qr_width;
                $logo_qr_height = $logo_height / $scale;
                $from_width = ($QR_width - $logo_qr_width) / 2;
                //�������ͼƬ��������С
                imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,
                    $logo_qr_height, $logo_width, $logo_height);
                //���ͼƬ
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

//�ж��ǲ����ֻ�����
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

//����$numλ�����
function myRands($num=5){
    $randArr = array();
    for($i = 0; $i < $num; $i++){
        $randArr[$i] = rand(0, 9);
        $randArr[$i + $num] = chr(rand(0, 25) + 97);
    }
    shuffle($randArr);
    return implode('', $randArr);
}

//��ȡ�ַ���
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
    if($suffix) return $slice."��";
    return $slice;

}

//ץȡԶ��htmlҳ��
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

//�û���ʹ��UTF-8��ΪphpԴ���ļ��ı����ʽ�����������������
function request_curl_get($url,$param_array){
    $ca_info = dirname(__FILE__) . '/cacert.pem';		//��֤���ļ�·��,���·���;���·������,�Ƽ�ʹ�þ���·��;demo���ļ���Դ�����һ���ˣ�Ϊ�˰�ȫ֤���ļ���ò�Ҫ��Ӧ�ô������һ��
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url . '?' . http_build_query($param_array));
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ;
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);		//��֤����֤��
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); 		//���SSL֤�鹫�����Ƿ���ڣ������Ƿ����ṩ��������ƥ��
    curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);		//����ʵ��Э��ΪTLS1.0�汾
    curl_setopt($ch, CURLOPT_CAINFO,  $ca_info); 		//���ø�֤���ļ�·��
    $data = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);
    if(!empty($error)){		//curl�д���
        echo $error;
    }else{		//���������
        echo $data;
    }
}

/**
 * ����curl��post���ʷ�ʽ(�Ƽ�)��Ҫ��php��curl��չ
 * $url @string �����ַ
 * $param_array @array ��������
 */
function request_curl_post($url,$param_array){
    $ca_info = dirname(__FILE__) . '/cacert.pem';		//��֤���ļ�·��,���·���;���·������,�Ƽ�ʹ�þ���·��;demo���ļ���Դ�����һ���ˣ�Ϊ�˰�ȫ֤���ļ���ò�Ҫ��Ӧ�ô������һ��
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($param_array));
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);		//��֤����֤��
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); 		//���SSL֤�鹫�����Ƿ���ڣ������Ƿ����ṩ��������ƥ��
    curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);		//����ʵ��Э��ΪTLS1.0�汾
    curl_setopt($ch, CURLOPT_CAINFO,  $ca_info);

    $data = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);
    $str = "";
    if(!empty($error)){		//curl�д���
        $str = '����ʧ��';
    }else{		//���������
        $str='yes';
    }
    return $str;
}

/*
 * �����ʼ�
 * @param $to string
 * @param $title string
 * @param $content string
 * @return bool
 * */
function SendMail($to, $title, $content) {
    Vendor('PHPMailer.PHPMailerAutoload');
    $mail = new PHPMailer(); //ʵ����
    $mail->IsSMTP(); // ����SMTP
    $mail->Host=C('MAIL_HOST'); //smtp�����������ƣ�������QQ����Ϊ����
    $mail->SMTPAuth = C('MAIL_SMTPAUTH'); //����smtp��֤
    $mail->Username = C('MAIL_USERNAME'); //������������
    $mail->Password = C('MAIL_PASSWORD') ; //163���䷢������Ȩ����
    $mail->From = C('MAIL_FROM'); //�����˵�ַ��Ҳ������������ַ��
    $mail->FromName = C('MAIL_FROMNAME'); //����������
    $mail->AddAddress($to,"�𾴵Ŀͻ�");
    $mail->WordWrap = 50; //����ÿ���ַ�����
    $mail->IsHTML(C('MAIL_ISHTML')); // �Ƿ�HTML��ʽ�ʼ�
    $mail->CharSet=C('MAIL_CHARSET'); //�����ʼ�����
    $mail->Subject =$title; //�ʼ�����
    $mail->Body = $content; //�ʼ�����
    $mail->AltBody = "����һ�����ı��������ڷ�Ӫ����HTML�����ʼ��ͻ���"; //�ʼ����Ĳ�֧��HTML�ı�����ʾ
    return($mail->Send());
}

//��ȡIP��ַ
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

//����GUID
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

