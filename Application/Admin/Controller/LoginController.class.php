<?php
namespace Admin\Controller;
use Think\Verify;
class LoginController extends BaseController{
	public function index(){
        $aid=session('aid');
        if($aid!=null){
            $this->redirect('index/index.html');
        }
		$this->display();
	}
	public function check(){
        $username=I('post.username');
        $password=I('post.password');
        $verify=I('post.verify');
//        $remember=I('post.remember');
        if(!trim($username)){
            $this->ajaxReturn(show(0,'用户名不能为空！'));
        }
        if(!trim($password)){
            $this->ajaxReturn(show(0,'密码不能为空！'));
        }
        if(!trim($verify)){
            $this->ajaxReturn(show(0,'验证码不能为空！'));
        }
        $very=new Verify();
        if(!$very->check($verify)){
            $this->ajaxReturn(show(0,'验证码错误！'));
        }
        $res=D('Admin')->checkUser($username,password($password));
        if(is_array($res)){
            session('aid',$res['admin_id']);
            /*更新此次登录时间，ip*/
            $ip=get_client_ip();
            $loginTime=date("Y-m-d H:i:s");
            D("Admin")->updateLoginInfo($ip,$loginTime);
//            cookie('aid',$res['admin_id']);
            $this->ajaxReturn(show(1,'登陆成功！'));
        }else{
            $this->ajaxReturn(show(0,'用户名或者密码错误！'));
        }
    }

    public function verify(){
        //实例化vary对象
        $cfg=array(
            'imageH'=>37,       //验证码高度
            'imageW'=>120,      //验证码宽度
            'fontSize'=>16,     //验证码字体大小
            'length'=>4,        //验证码字符串长度
            'fontttf'=>'5.ttf'  //验证码字体
        );
        $very=new Verify($cfg);
        $very->entry();
    }

    public function logout(){
        session(null);
//        cookie(null);
        $this->redirect('index.html');
    }
}