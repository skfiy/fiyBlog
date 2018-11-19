<?php
namespace Admin\Controller;
class ArticleController extends BaseController {
    /*文章列表*/
	public function articleList(){
        $page=I('get.p')?I('get.p'):1;
        $pageSize=15;
        $where=array('status'=>array('neq',-1));
        $status=I("get.status")?I("get.status"):9;
        $copyfrom=I("get.copyfrom")?I("get.copyfrom"):9;
        if($status!=9){
            if($status==2){
                $where['status']=0;
            }else if($status==1){
                $where['status']=$status;
            }
        }
        if($copyfrom!=9){
            if($copyfrom!=''){
                $where['copyfrom']=$copyfrom;
            }
        }
        if(IS_POST){
            $where=array();
            $title=I("post.title");
            $where['title']=array('like',"%$title%");
            $where['status']=array('neq',-1);
            $articleLists=D("Article")->getArticles($where);
        }else{
            $count=D("Article")->getArticleCount($where);
            $articleLists=D("Article")->getArticles($where,$page,$pageSize);
            $Page=new \Think\Page($count,$pageSize);

            $Page->setConfig('header', '共<b> %TOTAL_ROW% </b>条记录&nbsp;第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页');
            $Page->setConfig("prev","上一页");
            $Page->setConfig("next","下一页");
            $Page->setConfig('theme', '<ul class="am-pagination"><li>%UP_PAGE%</li>%LINK_PAGE%<li>%DOWN_PAGE%</li><li>%HEADER%</li></ul>');
            $pageArticle=$Page->show();
            $this->pageArticle=$pageArticle;
        }
        
        $this->copyFrom=C("COPY_FROM");
        $this->status=$status;
        $this->positions=D("Position")->getNormalPositions();
        $this->cats=D('Menu')->getBarMenus();//获取栏目
        $this->copyfrom=$copyfrom;
        $this->articleLists=$articleLists;
		$this->display();
	}

    /*文章添加*/
    public function addArticle(){
    	if(IS_POST){
            $dataContent=htmlspecialchars($_POST['content']);
            unset($_POST['content']);
            $res1=D("Article")->insertArticle($_POST);
            $res2=D("ArticleContent")->insertContent($dataContent,$res1);
            if($res1 && $res2){
                $this->ajaxReturn(show(1,'保存成功'));
            }else{
                $this->ajaxReturn(show(0,'保存失败'));
            }
    	}else {
            $webSideMenu=D("Menu")->where('parent_id=28 and level=2 and status=1')->select();
            $titleFontColor=C('TITLE_FONT_COLOR');
            $copy_from=C('COPY_FROM');
            $this->webSideMenu=$webSideMenu;
            $this->titleFontColor=$titleFontColor;
            $this->copy_from=$copy_from;
            $this->title="添加文章";
            $this->display();
        }
    }

    /*删除staatus*/
    public function setStatus(){
        $id=I("post.id");
        $status=I("post.status");
        if($id=='' || $status==''){
            $this->ajaxReturn(show(0,"操作失败"));
        }
        $isDelete=I("post.isDelete");
        if($isDelete){
            $res=D("Article")->updateStatus($id,-1);
            if($res){
                $this->ajaxReturn(show(1,"操作成功"));
            }else{
                $this->ajaxReturn(show(0,"操作失败"));
            }
        }
    }
    /*排序*/
    public function listorder(){
        $errors=array();
        $success=array();
        if(is_array($_POST)){
            $lists=$_POST['listorder'];
            foreach ($lists as $article_id => $listorder) {
                $res=D("Article")->updateListOrder($article_id,$listorder);
                if($res===false){
                    $errors[]=$article_id;
                }else{
                    $success[]=$article_id;
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
    /*编辑文章*/
    public function editArticle(){
        $id=I('get.id');
        $where=array('article_id'=>$id);
        $info=D("Article")->getArticleById($where);
        $content=D("ArticleContent")->getContentById($where)['content'];
        $webSideMenu=D("Menu")->where('parent_id=28 and level=2 and status=1')->select();
        $titleFontColor=C('TITLE_FONT_COLOR');
        $copy_from=C('COPY_FROM');
        $this->webSideMenu=$webSideMenu;
        $this->titleFontColor=$titleFontColor;
        $this->copy_from=$copy_from;
        $this->info=$info;
        $this->content=$content;
        $this->title='修改文章';
        $this->display('addarticle');
    }
    /*修改文章*/
    public function updateArticle(){
        $dataContent=htmlspecialchars($_POST['content']);
        unset($_POST['content']);
        $res1=D("Article")->updateArticle($_POST);
        $res2=D("ArticleContent")->updateContent($dataContent,$_POST['article_id']);
        if($res1 && $res2){
            $this->ajaxReturn(show(1,'修改成功'));
        }else{
            $this->ajaxReturn(show(0,'修改失败'));
        }
    }
    /*推荐位推送*/
    public function pushArticle(){
        $pid=I('post.position_id');
        $positionId=intval($pid);
        $articleIds=I('post.pushs');
        $articles=D("Article")->getArticlesById($articleIds);
        if(!$articles){
            $this->ajaxReturn(show(0,'没有获取到相关内容'));
        }
        if(is_array($articles)){
            $errors=array();
            $success=array();
            foreach($articles as $article){
                $data=array(
                    'position_id'=>$positionId,
                    'title'=>$article['title'],
                    'thumb'=>$article['thumb'],
                    'article_id'=>$article['article_id'],
                    'status'=>$article['status'],
                    'create_time'=>time()
                );
                $res=D("PositionContent")->insert($data);
                if($res){
                    $success[]=$article['article_id'];
                }else{
                    $errors[]=$article['article_id'];
                }
            }
            if(empty($errors)){
                $this->ajaxReturn(show(1,'推送成功'.implode(',', $success)));
            }else if(empty($success)){
                $this->ajaxReturn(show(0,'推送失败'.implode(',', $success)));
            }
        }
    }
}