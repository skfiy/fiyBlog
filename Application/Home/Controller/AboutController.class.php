<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class AboutController extends Controller {
	public function index(){
		$about=D("Admin")->field('username,synopsis,weibo,avatar,qq')->where('admin_id=1')->find();
		$this->about=$about;
		$this->display();
	}
}