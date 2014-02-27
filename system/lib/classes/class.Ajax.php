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
		
		if(!@is_array($this->post['post']) ) @parse_str( $this->post['post'], $this->post['post']);

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


	public function modAjax()
	{
		if(!file_exists( $this->root.'/mod/'.$this->post['mod_name'].'/front/mod.php'))
		{
			return;
		}
	
		
		if( isset($this->post['mod_name']))
		{
			include_once $this->root."/mod/".$this->post['mod_name']."/front/mod.php";
			eval( "\$mod = new Mod".ucfirst($this->post['mod_name'])."();" );
			$mod->template->template_dir = $this->root.'/mod/'.$this->post['mod_name'].'/front/tpl/';
			$mod->template->compile_dir = $this->root.'/mod/'.$this->post['mod_name'].'/front/tpl_compile/';
			$mod->template->cache_dir = $this->root.'/mod/'.$this->post['mod_name'].'/front/cache';
			if( isset( $this->post['xsrviceaction'] ) && $this->post['xsrviceaction']=='xsrviceactionupload' )
			{
				$mod->upload( $this->files , $this->post );
			}else{

				$mod->ajax( $this->post['post'] );
			}
			return;
		}
	}
	
	public function plugAjax()
	{
			
		if(!file_exists( $this->root.'/plug/'.$this->post['plug_name'].'/front/plug.php'))
		{
			return;
		}
	
		if( isset($this->post['plug_name']) )
		{
			include_once $this->root."/plug/".$this->post['plug_name']."/front/plug.php";
			eval( "\$plug = new Plug".ucfirst($this->post['plug_name'])."();" );
			$plug->template->template_dir = $this->root.'/plug/'.$this->post['plug_name'].'/front/tpl/';
			$plug->template->compile_dir = $this->root.'/plug/'.$this->post['plug_name'].'/front/tpl_compile/';
			$plug->template->cache_dir = $this->root.'/plug/'.$this->post['plug_name'].'/front/cache';
			if( isset( $this->post['xsrviceaction'] ) && $this->post['xsrviceaction']=='xsrviceactionupload' )
			{
				$plug->upload( $this->files , $this->post );
			}else{
				$plug->ajax( $this->post['post'] );
			}
			return;
		}
	}
	
}
?>