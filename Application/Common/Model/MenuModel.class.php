<?php
/**
 * Created by PhpStorm.
 * User: BaAGee
 * Date: 2016/7/19
 * Time: 18:57
 */
namespace Common\Model;
use Think\Model;
class MenuModel extends Model{
    private $_db='';

    public function __construct(){
        parent::__construct();
        $this->_db=M('menu');
    }
    /*添加菜单*/
    public function insert($data=array()){
        if(!$data || !is_array($data)){
            return 0;
        }
        return $this->_db->add($data);
    }
    /*获取菜单*/
    public function getMenus($data,$page,$pageSize){
        if(empty($pageSize) && empty($page)){
            return $this->_db->where($data)->order("listorder desc,menu_id desc")->select();
        }else{
            $offset=($page-1)*$pageSize;
            $lists=$this->_db->where($data)->order("listorder desc,menu_id desc")->limit($offset,$pageSize)->select();
            return $this->_getTree($lists,$parent_id=0);
        }
    }
    private function _getTree($data,$parent_id,$level=0){
        static $res=array();
        foreach ($data as $key => $value) {
            if($value['parent_id']==$parent_id){
                $value['levelk']=$level;
                $res[]=$value;
                // 找子分类
                $this->_getTree($data,$value['menu_id'],$level+1);
            }
        }
        return $res;
    }
    /*获得菜单数据条数*/
    public function getMenuCount($data=array()){
        // $data['status']=array('neq',-1);
        $count=$this->_db->where($data)->count();
        return $count;
    }
    /*更新菜单状态*/
    public function updateStatus($id,$status){
        $data=array('status'=>$status);
        $res=$this->_db->where("menu_id=$id")->save($data);
        return $res;
    }
    /*得到菜单信息*/
    public function getInfo($id){
        $info=$this->_db->where(array('status'=>array('neq',-1),'menu_id'=>$id))->find();
        return $info;
    }
    /*修改保存菜单*/
    public function editSave($data){
        $res=$this->_db->save($data);
        return $res;
    }
    /*更新菜单排序*/
    public function updateListOrder($menu_id,$listorder){
        if(!is_numeric(intval($menu_id)) || !is_numeric(intval($listorder))){
            throw_exception("不是有效的menu_id或者lisrorder");
        }else{
            $data=array('listorder'=>$listorder);
            $res=$this->_db->where("menu_id=$menu_id")->save($data);
        }
        return $res;
    }
    /*获取后台菜单展示*/
    public function getAdminMenus(){
        $where=array(
                'status'=>1,
                'type'=>1
            );
        return $this->_db->where($where)->order("listorder desc,menu_id desc")->select();
    }
    /*获取菜单列*/
    public function getBarMenus(){
        $where=array(
                'status'=>1,
                'type'=>0
            );
        return $this->_db->where($where)->order("listorder desc,menu_id desc")->select();
    }
}