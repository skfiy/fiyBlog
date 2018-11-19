<?php
namespace Admin\Controller;
use Think\Controller;
class BaseController extends Controller{
    public function __construct(){
        parent::__construct();
        $this->__init();
    }

    public function _empty($method, $args){
        $this->redirect('Index/index');
    }

    public function __init(){
        $user=session('aid');
        $act = strtolower(ACTION_NAME);
        $ctl = strtolower(CONTROLLER_NAME);
        if($user==null){// || $act=='qrlogin' || $act='qrcheck'
            if(!((($act=='index' || $act=='verify' || $act=='check') && $ctl=='login') || ($act=='index' && $ctl=='upload'))){
                $this->redirect('admin/login/index');
            }
        }
    }
}