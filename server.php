<span style="font-size:14px;color:#996633;"><?php
    date_default_timezone_set("Asia/Shanghai");
    set_time_limit(0);
    $host="192.168.1.254";
    $port=1003;

    //����һ������
    $socket=socket_create(AF_INET,SOCK_STREAM,SOL_TCP)or die("cannot create socket\n");
    //��socket���˿�  
    $result=socket_bind($socket,$host,$port) or die("cannot bind port to socket\n");
    //��ʼ��������˿�  
    $result=socket_listen($socket,4) or die("could not set up socket listen\n");
    $interval = 5;
    do{
        //�������ӣ���һ��socket������ͨ��
        $msgsock=socket_accept($socket) or die("cannot accept incoming connection\n");
        if($msgsock){
            echo date("Y-m-d H:i:s D a");
            //��ȡ�ͻ��˷��͹�������Ϣ
            $input = socket_read($msgsock,1024) or die("cannot read input\n");
            $input = trim($input);//.randpw(8,"NUMBER")
            $output = strrev($input)."˳�򷴹����˰�\n";
            $output = "�ͻ�����".$input."\n";
            echo $output;
            $output = "���ӳɹ�".$input;//�����input������������
            //�Խ��յ�����Ϣ���д���Ȼ�󷵻ص��ͻ���
            socket_write($msgsock,$output,strlen($output)) or die("cannot write");
        }


        socket_close($msgsock);
        sleep($interval);// �ȴ�5s
    }while(true);

    //�ر�socket����  

    //socket_close($socket);

    function randpw($len=8,$format='ALL'){
        $is_abc = $is_numer = 0;
        $password = $tmp ='';
        switch($format){
            case 'ALL':
                $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                break;
            case 'CHAR':
                $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
                break;
            case 'NUMBER':
                $chars='0123456789';
                break;
            default :
                $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                break;
        } // www.jb51.net
        mt_srand((double)microtime()*1000000*getmypid());
        while(strlen($password)<$len){
            $tmp =substr($chars,(mt_rand()%strlen($chars)),1);
            if(($is_numer <> 1 && is_numeric($tmp) && $tmp > 0 )|| $format == 'CHAR'){
                $is_numer = 1;
            }
            if(($is_abc <> 1 && preg_match('/[a-zA-Z]/',$tmp)) || $format == 'NUMBER'){
                $is_abc = 1;
            }
            $password.= $tmp;
        }
        if($is_numer <> 1 || $is_abc <> 1 || empty($password) ){
            $password = randpw($len,$format);
        }
        return $password;
    }


    ?>
</span>