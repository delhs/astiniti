<?
@session_start();

class log extends Get{

	public $num = 15;
	
	public function __construct()
	{
		parent::init();
	}
	

	public function load()
	{
		if( !isset( $this->userPrivilege['view_log'] )){
			die('access denied');
		}

		if( !isset($_POST['get']['page'] ) ) $_POST['get']['page'] = 1;

		if(  $_POST['get'] !='undefined'  )
		{
			$this->getLimitLinesArray(  $_POST['get']['page'], $_POST['get']['sort'] );
			return;
		}
		$this->getLimitLinesArray();
	}
		
	public function getLimitLinesArray( $currentPage=1, $sortArray = array('log_date'=>'desc', 'log_time'=>'desc') )
	{
		if( $sortArray==='null' ) $sortArray = array();
		
		#get current page
		$page = $currentPage;
		
		#get total lines in DB
		$res = $this->db->query("SELECT count(*) FROM `adm_log` ");
		$resArray = $res->fetch();
		
		#total lines in DB
		$total = intval( ($resArray[0] - 1) / $this->num) + 1; 
		
		#set current page
		$page = intval($page); 
		if(empty($page) or $page < 0) $page = 1; 
		if($page > $total) $page = $total;
		
		#set start line
		$start = $page * $this->num - $this->num;
		
		$sortStr = '';
		if( !empty( $sortArray ) )
		{
			foreach( $sortArray as $field => $way )
			{
				$sortStr .= "`".$field."` ".$way.",";
			}
		}
		$sortStr = preg_replace('/,$/', '', $sortStr);
		if( $sortStr!='' ) $sortStr = "ORDER BY ".$sortStr;
		
		#get limit lines from DB
		$res = $this->db->prepare("
			SELECT 
				`log`.*,
				`u`.`name` AS `user_name`,
				`u`.`login` AS `user_login`,
				`p`.`name` AS `page_name`
			FROM 
				`adm_log` AS `log`
			LEFT JOIN
				`adm_users` AS `u` 
			ON
				`u`.`uid` = `log`.`uid`
			LEFT JOIN
				`parts` AS `p`
			ON
				`p`.`id` = `log`.`page_id`
				".$sortStr."			
			LIMIT 
				:start,:num
		");
		
		$res->bindValue(':start', $start, PDO::PARAM_INT);
		$res->bindValue(':num', $this->num, PDO::PARAM_INT);

		$res->execute();
		$resArray = $res->fetchAll();
		
		#compile template
		if(!empty( $resArray )) $this->template->assign('logArray', $resArray);
	
		#get navArray
		#set arrow left
		$navArray = array();

		$toLeft = 5;
		$toRight = 5;

		#set pagers
		for( $i=1;$i<($total+1);$i++ ) 
		{
			if(  $page - $toLeft < $i && $page + $toRight > $i ) 
			{
				$navArray[] = array( 'num'=> $i, 'title'=>'На страницу '.$i, 'text'=>$i,'class'=>( $i==$page ) ? 'act' : '');
			}
		}

		#set arrows
		if( $page!=1 ) array_unshift( $navArray, array(	'num'=> 1, 'title'=>'К первой странице', 'text'=> '&laquo;', 'class'=> 'start'), array( 'num'=> $page-1, 'title'=>'Назад', 'text'=> '&larr;', 'class'=> 'back' ) );
		if( $page!=$total ) array_push( $navArray,  array('num'=> $page+1, 'title'=>'Вперед', 'text'=> '&rarr;','class'=> 'next'), array('num'=> $total, 'title'=>'К последней странице', 'text'=> '&raquo;','class'=> 'end') );
			

		#compile template
		$this->template->assign('sortArray', $sortArray);
		
		#compile template
		if( count( $navArray )>1 ) $this->template->assign('navArray', $navArray);
		
		if( $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['uid'] == 'developer') $this->template->assign('developer', 'developer');
		
	}	
		
	public function render()
	{
		#display template
		$this->template->display('panel.log.tpl');
	}
	
	
	public function save( $post )
	{
		switch( $post['action'] )
		{
			case 'clearLog':
				if( $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['uid'] == 'developer') $this->db->query("DELETE FROM `adm_log` ");
			break;
		}
	}
	
	

	
}
?>