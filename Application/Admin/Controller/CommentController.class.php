<?php
namespace Admin\Controller;
class CommentController extends BaseController{
	public function commList(){
		$page=I("get.p")?I("get.p"):1;
		$pageSize=15;
		$count=D("Comment")->getCommCount();
		$where=array("c.status"=>1);
		$commList=D("Comment")->getAllComment($where,$page,$pageSize);
		$Page=new \Think\Page($count,$pageSize);
        $Page->setConfig('header', '共<b> %TOTAL_ROW% </b>条记录&nbsp;第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页');
        $Page->setConfig("prev","上一页");
        $Page->setConfig("next","下一页");
        $Page->setConfig('theme', '<ul class="am-pagination"><li>%UP_PAGE%</li>%LINK_PAGE%<li>%DOWN_PAGE%</li><li>%HEADER%</li></ul>');
        $pageComm=$Page->show();
        $this->pageComm=$pageComm;//分页
        $this->comments=$commList;
		$this->display();
	}
	/*删除评论*/
	public function deleteCommon(){
		if(IS_POST){
			$id=I("post.id");
			if(!empty($id)){
				$res=D("Comment")->deleteComm($id);
				if($res){
					$this->ajaxReturn(show(1,'删除成功'));
				}else{
					$this->ajaxReturn(show(0,'删除失败'));
				}
			}else{
				$this->ajaxReturn(show(0,'评论ID为空'));
			}
		}
	}
}