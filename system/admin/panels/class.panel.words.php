<?
@session_start();

class words extends Get{

	
	public function __construct()
	{
		parent::init();
	}
	

	public function load()
	{
 		$res = $this->db->prepare("SELECT * FROM `words` ORDER BY `word_desc` ");
		$res->execute();
		$resArray = $res->fetchAll();
		$this->template->assign('wordsArray', $resArray);
		
		if( $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['uid'] == 'developer') $this->template->assign('developer', 'developer');
	}
		
	public function render()
	{
		#display template
		$this->template->display('panel.words.tpl');
	}
	
	
	public function save( $post )
	{
		if( !isset( $this->userPrivilege['words_edit'] )){
			die('access denied');
		}
		
		
		if( isset( $post['airMode'] ) && isset( $post['action'] ) && $post['action']=='editWord' )
		{
			$res = $this->db->prepare("UPDATE `words` SET `word_value`=:word_value WHERE `word_key`=:word_key LIMIT 1 ");
			$res->bindValue('word_key', $post['key']);
			$res->bindValue('word_value', $post['value']);
			$res->execute();
			die();
		}
		
		
		
		
		if(!isset($post['word_value']))
		{
			$this->db->query("TRUNCATE TABLE `words`");
			return;
		}		

		$query = "INSERT INTO `words` ( `word_key`, `word_value`, `word_desc`) VALUES ";

		foreach( $post['word_value'] as $index => $value)
		{
			$query .= "( '".$post['word_key'][ $index ]."', '".$value."', '".$post['word_desc'][ $index ]."'),";

		}
		
		$query = substr( $query, 0, -1 );

		$this->db->query("TRUNCATE TABLE `words`");
		$this->db->query( $query );
		
		
		$this->log('Словари', 'Изменение словарей');
	}
	
	

	
}
?>