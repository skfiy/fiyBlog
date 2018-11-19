<?php
namespace Admin\Controller;
class BlogrollController extends BaseController {
	public function index(){
		if(IS_POST){
			$id=I('post.id');
			if(!empty($id) && is_numeric($id)){
				$res=M('blogroll')->delete($id);
				if($res){
					$this->ajaxReturn(show(1,'删除成功'));
				}else{
					$this->ajaxReturn(show(0,'删除失败'));
				}
			}
		}
		$Blinks=M('blogroll')->select();
		$this->blogroll=$Blinks;
		$this->display();
	}

	public function addBlogRoll(){
		$inData=I('post.');
		$inData['time']=date('Y-m-d H:i:s');
		$res=M('blogroll')->add($inData);
		if($res){
			$this->ajaxReturn(show(1,'添加成功'));
		}else{
			$this->ajaxReturn(show(0,'添加失败'));
		}
	}
}