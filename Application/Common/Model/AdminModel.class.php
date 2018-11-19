<?php
namespace Common\Model;
use Think\Model;
class AdminModel extends Model{
    private $_db='';
    public function __construct(){
        parent::__construct();
        $this->_db=M('admin');
    }
    public function checkUser($username,$password){
        $res=$this->_db->where(array('username'=>$username,'password'=>$password,'status'=>1))->find();
        return $res;
    }
    /*修改登录信息*/
    public function updateLoginInfo($ip,$time){
        $data=array('lastloginip'=>$ip,'lastlogintime'=>$time,'admin_id'=>session('aid'));
        $this->_db->save($data);
    }
    /*查询admin信息*/
    public function getAdminInfo($aid){
        if(!empty($aid)){
            return $this->_db->where('admin_id='.$aid)->find();
        }else{
            return false;
        }
    }

    /*保存信息*/
    public function saveInfos($data){
        if(!empty($data)){
            return $this->_db->save($data);
        }else{
            return false;
        }
    }

    /*检查密码*/
    public function checkPass($aid,$password){
        return $this->_db->where(array("admin_id"=>$aid,"password"=>$password))->select();
    }

    /*修改密码*/
    public function cPassword($data){
        if(!empty($data)){
            return $this->_db->save($data);
        }else{
            return false;
        }
    }

    /*修改头像*/
    public function cAvatar($data){
        if(!empty($data)){
            return $this->_db->save($data);
        }else{
            return false;
        }
    }
}
