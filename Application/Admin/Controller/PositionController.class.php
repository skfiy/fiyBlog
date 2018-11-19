<?php
/**
 * Created by PhpStorm.
 * User: BaAGee
 * Date: 2016/7/24
 * Time: 14:45
 */
namespace Admin\Controller;
class PositionController extends BaseController{
    /*推荐位列表*/
    public function positionList(){
        $infos=D("Position")->getPositions();
        $this->info=$infos;
        $this->display();
    }
    /*添加推荐位*/
    public function addPosition(){
        if(IS_POST){
            $res=D("Position")->insertPosition($_POST);
            if($res){
                $this->ajaxReturn(show(1,'添加成功'));
            }else{
                $this->ajaxReturn(show(0,'添加失败'));
            }
        }
        $this->title='添加推荐位';
        $this->display();
    }
    /*修改推荐位*/
    public function updatePosition(){
        $id=I("get.id");
        if(IS_POST){
            $res=D("Position")->savePosition($_POST);
            if($res){
                $this->ajaxReturn(show(1,'修改成功'));
            }else{
                $this->ajaxReturn(show(0,'修改失败'));
            }
        }else{
            if(!empty($id) || $id==0){
                $info=D("Position")->getPositionById($id);
            }else{
                $this->message="推荐位ID错误";
            }
            $this->info=$info;
            $this->title="修改推荐位";
            $this->display('addposition');
        }
    }
    /*删除*/
    public function setStatus(){
        $id=I("post.id");
        $status=I("post.status");
        $isDelete=I("post.isDelete");
        $isPosCon=I("post.isPosCon");
        if($isDelete){
            if(isset($isPosCon)){
                $res=D("PositionContent")->updateStatus($id,-1);
                if($res){
                    $this->ajaxReturn(show(1,"操作成功"));
                }else{
                    $this->ajaxReturn(show(0,'操作失败'));
                }
            }else{
                $res=D("Position")->updateStatus($id,-1);
                if($res){
                    $this->ajaxReturn(show(1,"操作成功"));
                }else{
                    $this->ajaxReturn(show(0,'操作失败'));
                }
            }
        }
        if($id=='' || $status==''){
            $this->ajaxReturn(show(0,"操作失败"));
        }
    }
    /*推荐位内容列表*/
    public function posConList(){
        $page=I('get.p')?I('get.p'):1;
        $posid=I("get.posid")?I('get.posid'):2499;
        $status=I("get.status")?I('get.status'):9;
        $data=array();
        $data['pc.status']=array('neq',-1);
        if($posid!=2499){
            $data['pc.position_id']=$posid;
        }
        if($status!=9){
            if($status==2){
                $data['pc.status']=0;
            }else{
                $data['pc.status']=$status;
            }
        }
        if(IS_POST){
            $data=array();
            $title=I("post.title");
            $data['pc.title']=array('like',"%$title%");
            $data['pc.status']=array('neq',-1);
            $Lists=D("PositionContent")->getPosCons($data);
        }else{
            $pageSize=3;
            $count=D("PositionContent")->getPoConCount($data);
            $Lists=D("PositionContent")->getPosCons($data,$page,$pageSize);
            $Page=new \Think\Page($count,$pageSize);
            $Page->setConfig('header', '共<b> %TOTAL_ROW% </b>条记录&nbsp;第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页');
            $Page->setConfig("prev","上一页");
            $Page->setConfig("next","下一页");
            $Page->setConfig('theme', '<ul class="am-pagination"><li>%UP_PAGE%</li>%LINK_PAGE%<li>%DOWN_PAGE%</li><li>%HEADER%</li></ul>');
            $pagePoCon=$Page->show();
            $this->pageMenu=$pagePoCon;//分页
        }
        //获取推荐位
        $positions=D("Position")->getPositions();
        $this->positions=$positions;
        $this->info=$Lists;
        $this->posid=$posid;
        $this->status=$status;
        $this->display();
    }
    /*排序*/
    public function listorder(){
        $errors=array();
        $success=array();
        if(is_array($_POST)){
            $lists=$_POST['listorder'];
            foreach ($lists as $id => $listorder) {
                $res=D("PositionContent")->updateListOrder($id,$listorder);
                if($res===false){
                    $errors[]=$id;
                }else{
                    $success[]=$id;
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

    /*编辑*/
    public function editPosCon(){
        $id=I("get.id");
        if(!empty($id) || $id==0){
            $info=D("PositionContent")->getPosConById($id);
        }else{
            $this->message="ID错误,为空";
        }
        //获取推荐位
        $positions=D("Position")->getNormalPositions();
        $this->positions=$positions;
        $this->info=$info;
        $this->title="编辑";
        $this->display();
    }

    /*更改*/
    public function updatePosCon(){
        if(is_array($_POST) && !empty($_POST)){
            $res=D("PositionContent")->updatePosCon($_POST);
            if($res){
                $this->ajaxReturn(show(1,'修改成功'));
            }else{
                $this->ajaxReturn(show(0,'修改失败'));
            }
        }else{
            $this->ajaxReturn(show(0,'修改失败,为获取修改内容'));
        }
    }
}