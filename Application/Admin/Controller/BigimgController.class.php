<?php
namespace Admin\Controller;
class BigimgController extends BaseController{
	/*图片列表*/
	public function imgList(){
		// 查询
		$imgs=D('Bigimg')->getimg();
		// 删除图片
		if(IS_POST){
			$imgid=I('post.imgid');
			if(!empty($imgid)){
				$res=D('Bigimg')->delete($imgid);
				if($res){
					$this->ajaxReturn(show(1,'删除成功'));
				}else{
					$this->ajaxReturn(show(0,'删除失败'));
				}
			}
		}
		$this->imgs=$imgs;
		$this->display();
	}

	public function uploadImg(){
		if(IS_POST){
			$bigimg=I('post.image');
			if(!empty($bigimg)){
				$data=array('time'=>date('Y-m-d H:i:s'),'bigimg'=>$bigimg);
				$res=D('Bigimg')->insertimg($data);
				if($res){
					$this->ajaxReturn(show(1,'保存成功'));
				}else{
					$this->ajaxReturn(show(0,'保存失败'));
				}
			}else{
					$this->ajaxReturn(show(0,'保存失败'));
			}
		}
		$this->display();
	}
}