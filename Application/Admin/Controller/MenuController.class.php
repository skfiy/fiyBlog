<?php
namespace Admin\Controller;
class MenuController extends BaseController{
    /*获取所有菜单*/
    public function menuList(){
        $page=I('get.p')?I('get.p'):1;
        $type=I("get.type")?I('get.type'):9;
        $status=I("get.status")?I('get.status'):9;
        $data=array();
        $data['status']=array('neq',-1);
        if($type!=9){
            if($type==2){
                $data['type']=0;
            }else{
                $data['type']=$type;
            }
        }
        if($status!=9){
            if($status==2){
                $data['status']=0;
            }else{
                $data['status']=$status;
            }
        }
        if(IS_POST){
            $data=array();
            $name=I("post.name");
            $data['name']=array('like',"%$name%");
            $data['status']=array('neq',-1);
            $menuLists=D("Menu")->getMenus($data);
        }else{
            $pageSize=15;
            $count=D("Menu")->getMenuCount($data);
            $menuLists=D("Menu")->getMenus($data,$page,$pageSize);
            $Page=new \Think\Page($count,$pageSize);
            $Page->setConfig('header', '共<b> %TOTAL_ROW% </b>条记录&nbsp;第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页');
            $Page->setConfig("prev","上一页");
            $Page->setConfig("next","下一页");
            $Page->setConfig('theme', '<ul class="am-pagination"><li>%UP_PAGE%</li>%LINK_PAGE%<li>%DOWN_PAGE%</li><li>%HEADER%</li></ul>');
            $pageMenu=$Page->show();
            $this->pageMenu=$pageMenu;//分页
        }
        $this->menuLists=$menuLists;
        $this->type=$type;
        $this->status=$status;
        $this->display();
    }
    /*添加菜单*/
    public function addMenu(){
        if(IS_POST){
            $menu=D('Menu');
            $menuId=$menu->insert($_POST);
            if($menuId){
                $this->ajaxReturn(show(1,'数据保存成功',$menuId));
            }else{
                $this->ajaxReturn(show(0,'数据保存失败',$menuId));
            }
        }else{
            $parentmenu=D('Menu')->where('parent_id=0 and level=1')->select();
            $titleFontColor=C('TITLE_FONT_COLOR');
            $this->titleFontColor=$titleFontColor;
            $this->title="添加菜单";
            $this->parentmenu=$parentmenu;
            $this->display();
        }
    }
    /*设置菜单状态*/
    public function setStatus(){
        $id=I("post.id");
        $status=I("post.status");
        $isDelete=I("post.isDelete");
        if($isDelete){
            $res=D("Menu")->updateStatus($id,-1);
            if($res){
                $this->ajaxReturn(show(1,"操作成功"));
            }else{
                $this->ajaxReturn(show(0,'操作失败'));
            }
        }
        if($id=='' || $status==''){
            $this->ajaxReturn(show(0,"操作失败"));
        }
    }
    /*获取菜单信息*/
    public function getMenu($id){
        $info=D("Menu")->getInfo($id);
        $parentmenu=D('Menu')->where('parent_id=0 and level=1')->select();
        $this->title="修改";
        if(!is_array($info)){
            $this->message='菜单ID不存在';
        }
        $this->parentmenu=$parentmenu;
        $titleFontColor=C('TITLE_FONT_COLOR');
        $this->titleFontColor=$titleFontColor;
        $this->info=$info;
        $this->display('addmenu');
    }
    /*修改保存菜单*/
    public function editSave(){
        $menuId=D("Menu")->editSave($_POST);
        if($menuId){
            $this->ajaxReturn(show(1,'数据保存成功',$menuId));
        }else{
            $this->ajaxReturn(show(0,'数据保存失败',$menuId));
        }
    }
    /*修改菜单排序*/
    public function listOrder(){
        $errors=array();
        $success=array();
        if(is_array($_POST)){
            $lists=$_POST['listorder'];
            foreach ($lists as $menu_id => $listorder) {
                $res=D("Menu")->updateListOrder($menu_id,$listorder);
                if($res===false){
                    $errors[]=$menu_id;
                }else{
                    $success[]=$menu_id;
                }
            }
            if(!empty($success)){
                $this->ajaxReturn(show(1,'更新成功:'.implode(',', $success),array('url'=>$_SERVER['HTTP_REFERER'])));
            }elseif(!empty($errors)){
                $this->ajaxReturn(show(0,'操作失败:'.implode(',', $errors),array('url'=>$_SERVER['HTTP_REFERER'])));
            }
        }else{
            $this->ajaxReturn(show(0,'操作失败！',array('url'=>$_SERVER['HTTP_REFERER'])));
        }
    }

}