<?php
/**
 * Created by PhpStorm.
 * User: BaAGee
 * Date: 2016/7/24
 * Time: 21:19
 */
namespace Common\Model;

use Think\Model;

class BasicModel extends Model{
    /*储存*/
    public function save($data){
        //缓存
        return F('basic_web_config',$data);
    }
    /*获取*/
    public function getCfg(){
        return F('basic_web_config');
    }
}