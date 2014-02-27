<?
@session_start();

class backup extends Get{

	
	public function __construct()
	{
		parent::init();
	}
	

	public function load()
	{
		
		if( !isset( $this->userPrivilege['backup'] )){
			die('access denied');
		}
		
		$dir = $this->root.'/backup/';
		if( !is_dir( $dir ) ) mkdir( $dir, 0777, true );

		$resArray = array();
		
		$dirArray = scandir( $dir );
		
		foreach($dirArray as $file)
		{
			if( $file=='.' || $file=='..' ) continue;
			if( !preg_match( '/^\d{4}-\d{2}-\d{2}\.\d{2}-\d{2}-\d{2}\.sql$/', $file ) ) continue;
			$tmpArray = explode(".", $file);
			
			$tmpArray[1] = preg_replace('/-/', ':', $tmpArray[1]);
			
			$date = new DateTime( $tmpArray[0].' '.$tmpArray[1] );
			$key = $date->format("YmdHis");
			
			$resArray[$key]['date'] = $tmpArray[0];
			$resArray[$key]['time'] = $tmpArray[1];
			$resArray[$key]['filename'] = $file;
		}

		if(!empty( $resArray )) 
		{
			rsort($resArray);
			$this->template->assign('files', $resArray);
		}
	}
		
	public function render()
	{
		#display template
		$this->template->display('panel.backup.tpl');
		
	}
	
	public function save( $post )
	{
		
		if( !isset( $this->userPrivilege['backup'] )){
			die('access denied');
		}
		
		switch( $post['action'] )
		{
			#create backup file
			case 'createBackup':
				#check backup directory
				$dir = $this->root.'/backup/';
				if( !is_dir( $dir ) ) mkdir( $dir, 0777, true );
				
				$this->createBackUp( $dir.date("Y-m-d.H-i-s").".sql" );
			break;
			
			#delete backup file
			case 'deleteBackup':
				$filename = $post['filename'];
				$dir = $this->root.'/backup/';
				if(file_exists( $dir.$filename )) unlink( $dir.$filename );
			break;	
			
			#apply backup file
			case 'applyBackup':
				$this->applyBackUp( $post['filename'] );
			break;
		}
	}
	
	public function uploadFile( $files, $post )
	{
		return "";
	}
	
	private function applyBackUp( $filename )
	{

		$dir = $this->root.'/backup/';
		
		if(file_exists( $dir.$filename ))
		{
			
			$tables = $this->db->query( 'SHOW TABLES' );
			foreach ( $tables as $table )
			{
				$res = $this->db->query("DROP TABLE `".$table[0]."`");
			}
		
 			$res = $this->db->prepare('SET NAMES "utf8"');
			$res->execute();
			
			$query = file_get_contents( $dir.$filename );
			$res = $this->db->prepare( $query );
			$res->execute();
		}
	}
	
	private function createBackUp( $filename )
	{
		$res = $this->db->prepare('SET NAMES "utf8"');
		$res->execute();
		
		$f = fopen( $filename, 'wt' );
	
		$tables = $this->db->query( 'SHOW TABLES' );
		foreach ( $tables as $table ) {
			$sql = '-- TABLE: ' . $table[0] . PHP_EOL;
			
			$create = $this->db->query( 'SHOW CREATE TABLE `' . $table[0] . '`' )->fetch();
			$sql .= $create['Create Table'] . ';' . PHP_EOL;
			fwrite( $f, $sql );
	
			$rows = $this->db->query( 'SELECT * FROM `' . $table[0] . '`' );
			$rows->setFetchMode( PDO::FETCH_ASSOC );
			foreach ( $rows as $row ) {
				$row = array_map( array( $this->db, 'quote' ), $row );
				$sql = 'INSERT INTO `' . $table[0] . '` (`' . implode( '`, `', array_keys( $row ) ) . '`) VALUES (' . implode( ', ', $row ) . ');' . PHP_EOL;
				fwrite( $f, $sql );
			}
	
			$sql = PHP_EOL;
			$result = fwrite( $f, $sql );
		}
		fclose( $f );

	}
}
?>