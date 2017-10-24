<?php
namespace Api\Controller;
use Think\Controller;
class IndexController extends Controller {
    //初始化类
    public function _initialize() {
        /*$index = A('Home/Index');
        $index->init();*/
        session('userid',1); //临时测试
        $arr = array(
            'login',
            'checkusers'
        );
        if(!in_array(ACTION_NAME,$arr)){
            if(!session('userid')){
                $this -> redirect('login');
                exit;
            }
            else{
                $this->checkusers();
                /*$users = D("Home/Users")->showusers(session('userid'));
                $this->assign('users',$users);
                //会员等级
                $grouplist = D("Home/Usersgroup")->showusersgroup($users['groupid']);
                $this->assign('grouplist',$grouplist);*/
            }
        }
    }

    public function login(){
        echo 'login';
    }

    public function checkusers(){
       // echo 'aaaa';
    }

    public function index(){
        $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family:
"微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height:
 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用
 <b>ThinkPHP API</b>！</p><br/>版本 V{$Think.version}</div>','utf-8');
    }

    //不带logo的二维码
    public function ShowQrcode(){
        $url = "http://www.baidu.com/";
        qrcode($url);
    }

    //显示所有商品
    public function showAllGoodslist(){
        $cat_id = I('cat_id');
        $company_id = session("company_id");
        $subcompany_id = session("subcompany_id");
        $list = D('goods')->showAllList($cat_id,$company_id,$subcompany_id);
        unset($cat_id,$company_id,$subcompany_id);
        echo json_encode($list);
    }




}