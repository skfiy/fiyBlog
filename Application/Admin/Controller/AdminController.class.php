<?php 
namespace Admin\Controller;
class AdminController extends BaseController{
	public function adminInfo(){
        $aid=session('aid');
        $info=D("Admin")->getAdminInfo($aid);
        $flag=0;
        foreach($info as $value){
            if($value!=''){
                $flag+=1;
            }
        }
        $info['completion']=$flag/count($info)*100;
        /*网站运行时间*/
        $runTime=floor((time()-strtotime("2016-07-23"))/86400);
        $this->runTime=$runTime;
        $this->admininfo=$info;
		$this->display('editadmin');
	}

    /*保存信息*/
    public function saveInfo(){
        if(IS_POST){
            $res=D('Admin')->saveInfos($_POST);
            if($res){
                $this->ajaxReturn(show(1,'保存成功'));
            }else{
                $this->ajaxReturn(show(0,'保存失败'));
            }
        }else{
            $this->ajaxReturn(show(0,'操作失败'));
        }
    }

    /*修改密码*/
    public function changePassword(){
        if(IS_POST){
            $oldPassword=I('post.oldpassword');
            $aid=I("post.admin_id");
            $res1=D("Admin")->checkPass($aid,password($oldPassword));
            if(!$res1){
                $this->ajaxReturn(show(0,'旧密码错误'));
            }
            $password=I('post.password');
            $password1=I('post.password1');
            if(empty($password) || empty($password1)){
                $this->ajaxReturn(show(0,'新密码不能为空'));
            }
            if($password!=$password1){
                $this->ajaxReturn(show(0,'两次密码不一样'));
            }
            $data=array('admin_id'=>$aid,'password'=>password($password));
            $res2=D("Admin")->cPassword($data);
            if($res2){
                $this->ajaxReturn(show(1,'修改成功'));
            }else{
                $this->ajaxReturn(show(0,'修改失败'));
            }
        }else{
            $this->ajaxReturn(show(0,'操作失败'));
        }
    }

    /*修改头像*/
    public function saveAvatar(){
        $admin_id=session('aid');
        $avatar=I("post.avatar");
        $data=array('admin_id'=>$admin_id,'avatar'=>$avatar);
        $res=D("Admin")->cAvatar($data);
        if($res){
            $this->ajaxReturn(show(1,'修改成功'));
        }else{
            $this->ajaxReturn(show(0,'修改失败'));
        }
    }

}
?>