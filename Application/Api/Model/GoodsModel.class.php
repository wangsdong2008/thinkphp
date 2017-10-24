<?php
namespace Api\Model;
use Think\Model;

class GoodsModel extends Model{

    /* 自动验证规则 */
    protected $_validate = array(
        /*
           array('name', 'require', '标识不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
           array('name', '', '标识已经存在', self::VALUE_VALIDATE, 'unique', self::MODEL_BOTH),
           array('title', 'require', '名称不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
        */
    );

    /* 自动完成规则 */
    protected $_auto = array(
        /*
           array('model', 'arr2str', self::MODEL_BOTH, 'function'),
           array('model', null, self::MODEL_BOTH, 'ignore'),
           array('extend', 'json_encode', self::MODEL_BOTH, 'function'),
           array('extend', null, self::MODEL_BOTH, 'ignore'),
           array('create_time', NOW_TIME, self::MODEL_INSERT),
           array('update_time', NOW_TIME, self::MODEL_BOTH),
           array('status', '1', self::MODEL_BOTH),
        */
    );

    //下面是你要定义的函数

    /*
     * 函数输出单条记录
     * 参数：
     * $goods_id为ID
     * $company_id 主公司id
     * $subcompany_id 子公司id
     * @return 单条记录
    */
    public function showgoods($good_id = 0,$company_id = 0,$subcompany_id = 0){
        $goods = M('goods');
        $goods_data['good_id'] = array('eq',$good_id);
        $goods_data['company_id'] = array('eq',$company_id);
        $goods_data['subcompany_id'] = array('eq',$subcompany_id);
        $goods_data['isdel'] = array('eq',0);
        $goods_data['is_show'] = array('eq',1);
        $goodslist = $goods->where($goods_data)->limit(1)->find();
        unset($goods,$goods_data);
        return $goodslist;
    }

    //显示所有记录
    public function showAllList($cat_id = 0,$company_id = 0,$subcompany_id = 0){
        $goods = M('goods');
        $goods_data['good_id'] = array('gt',0);
        $goods_data['company_id'] = array('eq',$company_id);
        $goods_data['subcompany_id'] = array('eq',$subcompany_id);
        $goods_data['isdel'] = array('eq',0);
        $goods_data['is_show'] = array('eq',1);
        if($cat_id > 0){
            $goods_data['cat_id'] = array('eq',$cat_id);
        }
        $goodslist = $goods->where($goods_data)->field('goods_id,goods_name,goods_price,goods_img,goods_num')->select();
        unset($goods,$goods_data);
        return $goodslist;
    }

    /*
     * 分页显示所有记录
     * $pages为当前页数，$pagesize每页数量
     * $company_id 主公司id
     * $subcompany_id 子公司id
     * @return 分页记录
    */
    public function pagegoodslist($pages = 1,$pagesize = 10,$company_id = 0,$subcompany_id = 0){
        $goods = M('goods');
        $goods_data['good_id'] = array('gt',0);
        $goods_data['company_id'] = array('eq',$company_id);
        $goods_data['subcompany_id'] = array('eq',$subcompany_id);
        $goods_data['isdel'] = array('eq',0);
        $goods_data['is_show'] = array('eq',1);
        $count = $goods->where($goods_data)->count();
        $goodslist['count'] = $count;
        $Page = new \Think\Page($count,$pagesize);
        $goodslist['list'] = $goods->where($goods_data)->order('good_id desc')->page($pages.','.$Page->listRows)->select();
        $objPage = array();
        $goodslist['pagefooter'] = showpage($pages,$count,$objPage);
        unset($goods,$goods_data,$count,$Page,$objPage);
        return $goodslist;
    }

    //保存商品
    public function goodsSave($data)
    {
         $rules = array ( //以下三行根据表实际情况增加和修改,写法参考_auto函数
                  //array('status','1'),  // 新增的时候把status字段设置为1
                  //array('addtime', NOW_TIME, self::MODEL_INSERT),
                  //array('create_time', NOW_TIME, self::MODEL_INSERT),
         );
         $goods =  D('goods');
         $goods->auto($rules)->create($data);
         if(!array_key_exists('good_id',$data)){
             return $goods->add();
         }
         else {
           if ($data['good_id'] == 0) {
             return $goods->add();
           } else {
             return $goods->save();
           }
        }
    }

    //删除一个商品
    public function goodsDel($good_id,$company_id = 0,$subcompany_id = 0)
    {
        $Model =  M();
        //$sql = "delete from my_goods where `good_id` = '$good_id'";
        $sql = "update my_goods set isdel = 1 where `good_id` = '$good_id' and company_id ='$company_id' and
        subcompany_id = '$subcompany_id'";
        $Model->execute($sql);
        unset($good_id,$sql,$Model);
    }

    //取商最大ID
    public function getMaxId()
    {
        $Model =  M();
        $sql = "select max(good_id) as id from my_goods";
        $result = $Model->query($sql);
        unset($sql,$Model);
        return $result[0]['id'];
    }

}
