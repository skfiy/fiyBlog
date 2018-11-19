<?php
/**
 * Created by PhpStorm.
 * User: BaAGee
 * Date: 2016/7/24
 * Time: 15:06
 */
namespace Common\Model;
use Think\Model;

class PositionModel extends Model{
    private $_db='';
    public function __construct(){
        parent::__construct();
        $this->_db=M("position");
    }
    /*添加推荐位*/
    public function insertPosition($data){
        if(!empty($data)){
            $data['create_time']=time();
            return $this->_db->add($data);
        }
        return false;
    }
    /*查询所有推荐位*/
    public function getPositions(){
        $where=array("status"=>array('neq',-1));
        return $this->_db->where($where)->select();
    }
    /*查询推荐位*/
    public function getPositionById($id){
        return $this->_db->where("id=$id")->find();
    }
    /*修改保存*/
    public function savePosition($data){
        if(is_array($data) && !empty($data)){
            $data['update_time']=time();
            return $this->_db->save($data);
        }else{
            return false;
        }
    }
    /*修改状态*/
    public function updateStatus($id,$status){
        if(!empty($id)){
            $data=array('id'=>$id,'status'=>$status);
            return $this->_db->save($data);
        }
    }
    /*获取正常的positions*/
    public function getNormalPositions(){
        return $this->_db->where("status=1")->field("id,name")->select();
    }
}