<?php
namespace Home\Controller;
use Think\Controller;
class UsersController extends Controller {
    public function index(){
       // $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background:
       #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
       // $this->display("index");
        echo "users/index.html";
    }

    public function init(){
        //header('Content-type:text/html; Charset=UTF8');
        header('Content-type: text/json; charset=UTF8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST");
        header("Access-Control-Allow-Headers: Origin, No-Cache, X-Requested-With, If-Modified-Since, Pragma, Last-Modified, Cache-Control, Expires, Content-Type, X-E4M-With");

    }

    //初始化类
    public function _initialize() {
        /*$openhtml=array(
            '',
            ''
        );
        if(!in_array(ACTION_NAME,$openhtml)){
            if(!session('userid')){
                $this -> redirect('login');
            }
            else{
                $this->ascheck();
            }
        }*/
        $this->init();
    }

    //登录验证
    public function dl(){
        $username = I('username','');
        $password = I('password','');

        $arr = Array();
        if($username == "" || $password == ""){
            $arr['status'] = -1; //账号不存在
            echo json_encode($arr);
            exit;
        }

        $users = M('users');
        $users_data['username'] = array('eq',$username);
        $userslist = $users->where($users_data)->field('id,password,islock,loginnum')->limit(1)->find();


        if(!$userslist){
            //echo '<script type="text/javascript">alert(\'登录失败，请重新登录\');history.back();</script>';
            $arr['status'] = 0; //账号不存在
        }
        else{
            if($userslist['password'] != md5($password)){
                //echo '<script type="text/javascript">alert(\'密码不正确，请重新填写\');history.back();</script>';
                $arr['status'] = 1; //密码错误
            }else {
                if ($userslist['islock'] == 1) {
                    unset($userslist, $users, $users_data);
                    //echo '<script type="text/javascript">alert(\'你的账号被锁定，请与管理员联系\');history.back();</script>';
                    $arr['status'] = 2; //账号被锁定
                } else {
                    $guid = guid();
                    $arr['status'] = 3; //账号成功.
                    $arr['guid'] = $guid;

                    //修改guid字段
                    $Users_data['id'] = array('eq',$userslist['id']);
                    $Users_data['guid'] = $guid;
                    $Users2 = M('Users');
                    $Users2->save($Users_data);
                    unset($guid,$Users2,$Users_data,$Users_update_data);

                }
            }
        }
        echo json_encode($arr);
    }

    //注册新用户
    public function reg(){
        $username = I('username','');
        $password = I('password','');
        $againpassword = I('againpassword','');
        $email = I('email','');


        $arr = Array();
        if($username == "" || $password == "" || $againpassword == "" || $email == ""){
            $arr['status'] = -1; //填写不完整
            echo json_encode($arr);
            exit;
        }

        if(strlen($username)<6||strlen($password)<6||strlen($againpassword)<6||strlen($email)<6){
            $arr['status'] = 1; //长度不够
            echo json_encode($arr);
            exit;
        }

        if($password != $againpassword){
            $arr['status'] = 2; //长度不够
            echo json_encode($arr);
            exit;
        }

        //判断是否重复
        $Users = M('users');
        $Users_data['username'] = array('eq',$username);
        $Userslist = $Users
            ->where($Users_data)
            ->field('`id`')
            ->limit(1)
            ->find();
        unset($Users,$Users_data);
        if($Userslist){
            $arr['status'] = 0; //已经存在
            echo json_encode($arr);
            unset($username,$Userslist);
            exit;
        }

        //注册成功
        $islock = 0;
        $regtime = time();
        $regip = getIP();
        $isdel = 0;
        // $guid = guid();
        $Users = M('Users');
        $Users_data['username'] = $username;
        $Users_data['true_name'] = $username;
        $Users_data['password'] = md5($password);
        $Users_data['islock'] = $islock;
        $Users_data['email'] = $email;
        $Users_data['regtime'] = $regtime;
        $Users_data['regip'] = $regip;
        $Users_data['isdel'] = $isdel;
        //$Users_data['guid'] = $guid;
        $Users->add($Users_data);
        unset($username,$password,$islock,$email,$regtime,$loginip,$regip,$isdel,$Users,$Users_data);

        $arr['status'] = 3; //账号成功.
        //$arr['guid'] = $guid;
        echo json_encode($arr);
        unset($arr);

    }

    //取回密码
    public function getpassword(){
        $username = I('username','');
        $email = I('email','');

        //帐号为空
        $arr = Array();
        if($username == "" || $email == ""){
            $arr['status'] = -1; //账号为空
            echo json_encode($arr);
            exit;
        }

        //检查账号是否存在
        $users = M('users');
        $users_data['username'] = array('eq',$username);
        $userslist = $users->where($users_data)->field('id,password,islock,email')->limit(1)->find();

        if(!$userslist){
            $arr['status'] = 0; //账号不存在
            echo json_encode($arr);
            exit;
        }
        //判断邮箱是否正确
        if($userslist['email'] != $email){
            $arr['status'] = 1; //邮箱不正确
            echo json_encode($arr);
            exit;
        }
        $userid = $userslist['id'];
        unset($userslist,$users,$users_data);

        $newpassword = myRands(3);
        $content = "新密码是：".$newpassword;
        //修改guid字段
        $Users_data['id'] = array('eq',$userid);
        $Users_data['password'] = md5($newpassword);
        $Users2 = M('Users');
        $Users2->save($Users_data);
        unset($guid,$Users2,$Users_data,$Users_update_data);


        //发送邮件
        SendMail($email, "取回密码", $content);
        $arr['status'] = 3; //发送成功
        echo json_encode($arr);
        exit;

    }

    public function userslist(){
        $Users = M('Users');
        $nowPage = I('page')?I('page'):1;
        $count = $Users
            ->count();
        $Userslist['count'] = $count;
        $Page = new \Think\Page($count,10);
        $Userslist['list'] = $Users
            ->order('id desc')
            ->field('`id`,`username`,`face`')
            ->page($nowPage.','.$Page->listRows)
            ->select();
        /*$objPage = array();
        $Userslist['pagefooter'] = showpage($nowPage,$count,$objPage);*/
        unset($nowPage,$count,$Page,$Users,$Users_data);
        //print_r($Userslist);exit;
        echo json_encode($Userslist);
    }

}