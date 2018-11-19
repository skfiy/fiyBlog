<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
use Think\Verify;
class IndexController extends Controller {
    public function index($type=''){
        //如果登录了
        $openid=session('openid');
        if(isset($openid)){
            $userinfo=get_user_info();
            $this->userinfo=$userinfo;
        }
        //获取当前url
        $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];// $_SERVER['PHP_SELF'];
        cookie('url',$url,3600);
    	/*获取主要文章*/
    	$page=I('get.p')?I('get.p'):1;
    	$pageSize=10;
    	$data=array('a.status'=>1);
        $count=D("Article")->getArticleCount(array('status'=>1));
        $articleLists=D("Article")->getMainList($data,$page,$pageSize,'');
        /*获取评论数*/
        foreach($articleLists as $key =>$v){
            $articleLists[$key]['conum']=M("comment")->where(array("article_id"=>$v['article_id'],'status'=>1))->count();
        }
        $Page=new \Think\Page($count,$pageSize);
        $Page->setConfig("prev","上一页");
        $Page->setConfig("next","下一页");
		$Page->setConfig('theme', "<ul class='am-pagination'><li class='am-pagination-prev'>%UP_PAGE%</li><li class='am-pagination-next'>%DOWN_PAGE%</li></ul>");
        $pageArticle=$Page->show();
        $bigimg=D('Bigimg')->select();
        $this->pageArticle=$pageArticle;//分页
    	$this->mainlist=$articleLists;
        $this->active=1;
        // $this->article_id=$catid;
        $this->bigimg=$bigimg;
        if($type=='buildHtml'){
            $this->buildHtml('index',HTML_PATH,'Index/index');
        }else{
		  $this->display();
        }
    }

    public function detail(){
    	$article_id=I('get.article');
    	if(!empty($article_id) && is_numeric(intval($article_id))){
    		/*获得正文htmlspecialchars_decode*/
    		$article=D('Article')->selectContent($article_id);
            /*获得评论*/
            $comments=D('Comment')->getCommentByArticle($article_id);
            if(!empty($comments)){
            	$jsonComments='';
	            foreach ($comments as $key => $value) {
	                if(strlen($value['comment'])>27){
	                    $value['comment']=mb_substr($value['comment'],0,27,'utf-8')."...";
	                }
	                $jsonComments[$key]=array('info'=>$value['comment'],'img'=>$value['figureurl']);
	            }
            }
            $this->jsonComments=json_encode($jsonComments);
    	}
        /*更新浏览次数*/
        $visits=$article['visits']+1;
        D('Article')->updateArticle(array('article_id'=>$article_id,'visits'=>$visits));
        //获取当前url
        $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];// $_SERVER['PHP_SELF'];
        cookie('url',$url,3600);
        //如果登录了
        $openid=session('openid');
        if(isset($openid)){
            //查询openid是否已经存在
            $res=M('Visitors')->where('openid="'.$openid.'"')->find();
            $data=array();
            $userinfo=get_user_info();
            $this->userinfo=$userinfo;
            $data['openid']=session('openid');
            $data['gender']=$userinfo['gender'];
            $data['nickname']=$userinfo['nickname'];
            $data['province']=$userinfo['province'];
            $data['figureurl']=$userinfo['figureurl_1'];
            $data['birthyear']=$userinfo['year'];
            $data['city']=$userinfo['city'];
            if($res){
                //更新
                $a=M('Visitors')->where('id='.$res['id'])->save($data);
            }else{
                //添加
                M('Visitors')->add($data);
            }
        }
        // var_dump(json_encode($jsonComments));
        //获取上一页，下一页
        $sx=D('Article')->getSX($article_id);
        $this->sx=$sx;
        $this->article=$article;
        $this->comments=$comments;
        $this->article_id=$catid;
    	$this->display();
    }
    /*生成静态文件*/
    public function build_Html(){
        $build_html=I('post.build_html');
        if(!empty($build_html) && $build_html==1){
            $this->index('buildHtml');
            $this->ajaxReturn(show(1,'更新成功'));
        }else{
            $this->ajaxReturn(show(0,'更新失败'));
        }
    }

    public function articleList(){
        $pageSize=10;
        $Y=I('get.Y');
        $m=I('get.m');
        $catid=I('get.catid');
        $page=I('get.p')?I('get.p'):1;
        if(!empty($Y) && !empty($m)){
            $having="month(Ym)=$m and year(Ym)=".$Y;
            $count=count(D('Article')->field("FROM_UNIXTIME(create_time,'%Y%m%d') Ym")->having($having)->where('status=1')->select());
            $articleLists=D('Article')->getYmAList($having,$page,$pageSize);
            $this->catname=$Y."年$m"."月文章";
        }
        if(!empty($catid)){
            $where=array('status'=>1,'catid'=>$catid);
            $catname=D('Menu')->field('name')->find($catid)['name'];
            $count=D("Article")->getArticleCount($where);
            $articleLists=D("Article")->getMainList($where,$page,$pageSize,'');
            $this->catname=$catname;
        }
        /*获取评论数*/
        foreach($articleLists as $key =>$v){
            $articleLists[$key]['conum']=M("comment")->where(array("article_id"=>$v['article_id'],'status'=>1))->count();
        }
        $Page=new \Think\Page($count,$pageSize);
        $Page->setConfig("prev","上一页");
        $Page->setConfig("next","下一页");
        $Page->setConfig('theme', "<ul class='am-pagination'><li class='am-pagination-prev'>%UP_PAGE%</li><li class='am-pagination-next'>%DOWN_PAGE%</li></ul>");
        $pageArticle=$Page->show();
        $this->pageArticle=$pageArticle;//分页
        $this->articleLists=$articleLists;
        $this->display();
    }
    /*提交评论*/
    public function comment(){
        $verify=I('post.verify');
        $very=new Verify();
        if(!$very->check($verify)){
            $this->ajaxReturn(show(0,'验证码错误！'));
        }
        $data=I('post.');
        $openid=session('openid');
        if(empty($openid)){
            $this->ajaxReturn(show(0,'您未登陆,请先使用QQ登录'));
        }else{
            $email=I('post.email');
            if(empty($email)){
                $this->ajaxReturn(show(0,'邮箱为空'));
            }
            if(!empty($data['comment'])){
                $data['comment']=trim(strip_tags(htmlspecialchars_decode($data['comment'])));
                $data['time']=time();
                $data['openid']=session('openid');
                $data['browse']=GetBrowser();
                $data['os']=GetOs();
                $res=D('Comment')->addComment($data);
                if($res){
                    if($data['to']!=-1){
                        $toemail=D('Comment')->field('email')->find($data['to'])['email'];
                        $title="BaAGee|Blog系统回复";
                        $msg="有人在BaAGee|Blog（博客）中<a href='http://baagee.top/home/index/detail/article/{$data['article_id']}.html'>这篇文章</a>回复你:<p>{$data['comment']}</p>";
                        sendMail($toemail,$title,$msg);
                    }
                    $this->ajaxReturn(show(1,'评论成功'));    
                }else{
                    $this->ajaxReturn(show(0,'评论失败'));    
                }
            }else{
                $this->ajaxReturn(show(0,'内容为空'));
            }
        }
        
    }
    /*搜索文件*/
    public function search(){
        $title=I('post.title');
        if($title==''){
            $this->mainlist='';
        }else{
            $where=array('a.title'=>array('like',"%$title%"),'a.status'=>1);
            $articleLists=D("Article")->getMainList($where);
            /*获取评论数*/
            foreach($articleLists as $key =>$v){
                $articleLists[$key]['conum']=M("comment")->where(array("article_id"=>$v['article_id'],'status'=>1))->count();
            }
            $this->mainlist=$articleLists;
        }
        $this->Atitle=$title;
        $this->display('index');
    }
    /*点赞*/
    public function like(){
        $id=I('post.id');
        $like=I('post.like');
        if(!empty($id)){
            $res=D('Comment')->save(array('id'=>$id,'like'=>$like));
            if($res){
                $this->ajaxReturn(show(1,'点赞成功'));
            }else{
                $this->ajaxReturn(show(0,'点赞失败'));
            }
        }else{
            $this->ajaxReturn(show(0,'点赞失败'));
        }
    }

    //评论验证码
    public function verify(){
        //实例化vary对象
        $cfg=array(
            'imageH'=>37,       //验证码高度
            'imageW'=>120,      //验证码宽度
            'fontSize'=>16,     //验证码字体大小
            'length'=>4,        //验证码字符串长度
            'fontttf'=>'5.ttf',  //验证码字体
        );
        $very=new Verify($cfg);
        $very->entry();
    }
}