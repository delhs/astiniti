<?php /* Smarty version 2.6.27, created on 2014-01-31 15:29:55
         compiled from error.404.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'error.404.tpl', 4, false),)), $this); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?php echo ((is_array($_tmp=$this->_tpl_vars['projectName'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</title> 
		<meta name="robots" content="noindex"/>
		<meta name="robots" content="noarchive"/>
		<meta http-equiv="Content-Type" content="text/html" charset="utf-8" />
		<link rel="icon" href="/img/favicon.ico" type="image/x-icon" /> 
		<link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon" /> 
		
		<style type="text/css">
		<?php echo '
			body{
				background:#D6D6D6;
			}
			.wrapper{
				position: fixed;
				width: 500px;
				height: 200px;
				top: 50%;
				left: 50%;				
				margin: -100px 0 0 -250px;
				background: #FCFCFC;
				border: 3px solid #DADADA;
				-webkit-box-shadow: 0px 0px 20px 0px rgba(0, 0, 0, 0.16);
				-moz-box-shadow: 0px 0px 20px 0px rgba(0, 0, 0, 0.16);
				box-shadow: 0px 0px 20px 0px rgba(0, 0, 0, 0.16);
				-webkit-border-radius: 6px;
				-moz-border-radius: 6px;
				border-radius: 6px;
			}
			.wrapper p{
				position: relative;
				text-align: center;
				font-size: 20px;
				margin-top: 75px;			
			}
			.wrapper p>b{
				font-size: 46px;
				display: block;
			}
		'; ?>

		</style>
		
	</head>

	<body>
		<div class="wrapper">
			<p><b>404</b> Запрашиваемый вами раздел не найден</p>
		</div>
	</body>
</html>