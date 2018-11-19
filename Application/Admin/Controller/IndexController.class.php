<?php
namespace Admin\Controller;
class IndexController extends BaseController {

    public function index(){
    	/*获得文章总数*/
    	$numArticle=D("Article")->getArticleCount(array('status'=>array('neq',-1)));
    	/*获得评论总数*/
    	$numComment=D("Comment")->getCommCount();
    	/*获得登录人数*/
    	$vito=M('Visitors')->count();
    	/*获取浏览器信息*/
    	$browser=D('Comment')->field('browse,count(*) as count')->group('browse')->select();
    	/*获取本月新增文章数*/
    	/*select sum(count) from (SELECT FROM_UNIXTIME(create_time,'%Y%m%d') months,COUNT(*) count FROM cms_article  HAVING month(months)='8') a*/
    	$month=date("m");
    	$monthArticle=M()->query("SELECT sum(count) articlec FROM (SELECT FROM_UNIXTIME(create_time,'%Y%m%d') months,COUNT(*) count FROM cms_article WHERE status=1 GROUP BY months HAVING month(months)='".$month."') a")[0]['articlec'];
    	$info = array(
			'操作系统'=>PHP_OS,
			'运行环境'=>$_SERVER["SERVER_SOFTWARE"],
			'PHP运行方式'=>php_sapi_name(),
			'ThinkPHP版本'=>THINK_VERSION.' [ <a href="http://thinkphp.cn" target="_blank">查看最新版本</a> ]',
			'上传附件限制'=>ini_get('upload_max_filesize'),
			'执行时间限制'=>ini_get('max_execution_time').'秒',
			'服务器时间'=>date("Y年n月j日 H:i:s"),
			'北京时间'=>gmdate("Y年n月j日 H:i:s",time()+8*3600),
			'服务器域名/IP'=>$_SERVER['SERVER_NAME'].' [ '.gethostbyname($_SERVER['SERVER_NAME']).' ]',
			'剩余空间'=>round((disk_free_space(".")/(1024*1024)),2).'M',
			'register_globals'=>get_cfg_var("register_globals")=="1" ? "ON" : "OFF",
			'magic_quotes_gpc'=>(1===get_magic_quotes_gpc())?'YES':'NO',
			'magic_quotes_runtime'=>(1===get_magic_quotes_runtime())?'YES':'NO',
		);
		$this->assign('info',$info);
		$this->numComment=$numComment;
		$this->vito=$vito;
		$this->monthArticle=$monthArticle;
		$this->browser=$browser;
		$this->numArticle=$numArticle;
        $this->display();
    }
}