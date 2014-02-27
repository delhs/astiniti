<?
@session_start();

class Ajax{

	private $post;
	private $files;
	private $root = '';

	
	
	public function __construct( $post, $files=null )
	{

		$this->root = $_SERVER['DOCUMENT_ROOT'];
		$this->post = $post;
		$this->files = $files;
		
		if( !@is_array($this->post['post']) ) @parse_str( $this->post['post'], $this->post['post']);

		if( isset( $this->post['mod_name'] ) )
		{
			$this->modAjax();
			
		}elseif( isset( $this->post['plug_name'] ) )
		{	
			$this->plugAjax();
		}else{
			$this->adminAjax();
		}
		
	}
	
	public function modAjax(){
	
		if(!file_exists( $this->root.'/mod/'.$this->post['mod_name'].'/back/mod.php'))
		{
			return;
		}
	
		if( isset($this->post['mod_name']) && isset($this->post['post']))
		{
			include_once $this->root."/mod/".$this->post['mod_name']."/back/mod.php";
			eval( "\$mod = new Mod".ucfirst($this->post['mod_name'])."();" );
			@$mod->modId = $this->post['modId'];
			
			$mod->template->template_dir = $this->root.'/mod/'.$this->post['mod_name'].'/back/tpl/';
			$mod->template->compile_dir = $this->root.'/mod/'.$this->post['mod_name'].'/back/tpl_compile/';
			$mod->template->cache_dir = $this->root.'/mod/'.$this->post['mod_name'].'/back/cache';
			if( isset( $this->post['xsrviceaction'] ) && $this->post['xsrviceaction']=='xsrviceactionupload' )
			{
				$mod->upload( $this->files , $this->post );
			}else{
				$mod->ajax( $this->post['post'] );
			}	
			
			
			return;
		}
	}	
	
	public function plugAjax(){
	
		if(!file_exists( $this->root.'/plug/'.$this->post['plug_name'].'/back/plug.php'))
		{
			return;
		}
	
		if( isset($this->post['plug_name']) && isset($this->post['post']))
		{

			include_once $this->root."/plug/".$this->post['plug_name']."/back/plug.php";
			eval( "\$plug = new Plug".ucfirst($this->post['plug_name'])."();" );
			
			$plug->template->template_dir = $this->root.'/plug/'.$this->post['plug_name'].'/back/tpl/';
			$plug->template->compile_dir = $this->root.'/plug/'.$this->post['plug_name'].'/back/tpl_compile/';
			$plug->template->cache_dir = $this->root.'/plug/'.$this->post['plug_name'].'/back/cache';	
			if( isset( $this->post['xsrviceaction'] ) && $this->post['xsrviceaction']=='xsrviceactionupload' )
			{
				$plug->upload( $this->files , $this->post );
			}else{
				$plug->ajax( $this->post['post'] );
			}			
			
			return;
		}
	}

	
	public function adminAjax(){

		if(!file_exists( $this->root.'/system/admin/panels/class.panel.'.$this->post['panel_name'].'.php'))
		{
			return;
		}

		eval( "include_once '".$this->root."/system/admin/panels/class.panel.".$this->post['panel_name'].".php';" );
		eval( "\$panel = new ".$this->post['panel_name']."();" );
		if( isset( $this->post['xsrviceaction'] ) && $this->post['xsrviceaction']=='xsrviceactionupload' )
		{
			$panel->upload( $this->files , $this->post );
		}else{
			$panel->save( $this->post['post'] );
		}		
		return;
	}

}
?>