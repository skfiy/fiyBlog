<?php
namespace Admin\Controller;
class StatisticsController extends BaseController{
	public function index(){
		/*近期12个月每个月的*/
		$sql1="SELECT FROM_UNIXTIME(create_time,'%Y年%m') months,COUNT(*) count FROM cms_article where `status`=1 GROUP BY months limit 12 ";
		$res1=M()->query($sql1);
		/*近3个月每天的*/
		/*$month=date('m');
		$m_1=$month-1;
		$m_2=$month-2;
		$sql2="SELECT FROM_UNIXTIME(create_time, '%Y%m%d' ) days ,count(*) count FROM cms_article group by days having month(days) in ($m_2,$m_1,$month)";*/

		/*最近30天数据*/
		$sql2="SELECT
				    date(dday) days,
				    count(*) - 1 as count
				FROM
				    (
				        SELECT
				            datelist as dday
				        FROM
				            calendar 
				            where  DATE_SUB(CURDATE(), INTERVAL 30 DAY) <= date(datelist) and date(datelist)<=CURDATE() 
				        UNION ALL
				            SELECT
				                FROM_UNIXTIME(create_time,'%Y-%m-%d') times
				            FROM
				                cms_article WHERE status=1 AND UNIX_TIMESTAMP( DATE_SUB(CURDATE(), INTERVAL 30 DAY))<=`create_time`
				    ) a
				GROUP BY days";
		$res2=M()->query($sql2);
		// var_dump($res2);
		/*查询所有的，文章分类所占百分比*/
		$sql3="SELECT m.name,m.menu_bgcolor,count(a.article_id) count FROM cms_article a left join cms_menu m on m.menu_id=a.catid group by catid";
		$res3=M()->query($sql3);
		$this->chars1=$res1;
		$this->chars2=$res2;
		$this->chars3=$res3;
		$this->display();
	}
}