<?php
namespace Admin\Controller;
class BasicController extends BaseController{
    public function index(){
        if(IS_POST){
            if(is_array($_POST) && !empty($_POST)){
                // D("Basic")->save();
                $data=I('post.');
                F('basic_web_config',$data);
                $this->ajaxReturn(show(1,'保存成功'));
            }else{
                $this->ajaxReturn(show(0,'没有数据'));
            }
        }
        $this->web_cfg=F('basic_web_config');
        $this->display();
    }

    public function db_back(){
        if(IS_POST){
            if($_POST['db_back']==1 && !empty($_POST['db_back'])){
            	dumpmysql();
                $this->ajaxReturn(show(1,'备份成功'));
            }else{
                $this->ajaxReturn(show(0,'备份失败'));
            }
        }
    }
}
?>