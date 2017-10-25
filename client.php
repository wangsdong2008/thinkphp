<span style="font-size:14px;color:#996633;"><?php
    set_time_limit(0);
    $host="192.168.1.254";
    $port=1003;

    //创建一个socket
    $socket=socket_create(AF_INET,SOCK_STREAM,SOL_TCP)or die("cannot create socket\n");
    $conn=socket_connect($socket,$host,$port) or die("cannot connect server\n");
    if($conn){echo "client connect ok!";}
    socket_write($socket,"hello world!") or die("cannot write data\n");
    $buffer=socket_read($socket,1024,PHP_NORMAL_READ);
    if($buffer){
        echo "response was:".$buffer."\n";
    }
    socket_close($socket);
?></span>