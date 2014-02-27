<?
final class Security
{    
    // just init vars
    private $XSSHashe = '';
    private $CurrentKey;
    
    // never allowed, string replacement 
	private $NeverAllowedStr = array
    (
					'document.cookie'	=> '[removed]',
					'document.write'	=> '[removed]',
					'.parentNode'		=> '[removed]',
					'.innerHTML'		=> '[removed]',
					'window.location'	=> '[removed]',
					'-moz-binding'		=> '[removed]',
					'<!--'				=> '<!--',
					'-->'				=> '-->',
					'<![CDATA['			=> '<![CDATA['
	);

	// never allowed, regex replacement 
	private $NeverAllowedRegex = array
    (
					"javascript\s*:"			=> '[removed]',
					"expression\s*(\(|&\#40;)"	=> '[removed]', // CSS and IE
					"vbscript\s*:"				=> '[removed]', // IE, surprise!
					"Redirect\s+302"			=> '[removed]'
	);
    
    private $NougthyTags = 'alert|applet|audio|basefont|base|behavior|bgsound|blink|body|embed|expression|form|frameset|frame|h  ead|html|ilayer|iframe|input|isindex|layer|link|meta|object|plaintext|style|script|textarea|title|vid  eo|xml|xss';
    
   /**
    * clean incomming data
    * @access public
    * @param mix, bool
    * @return mix
    * @todo rename stupid named vars and others and beatify the code
    */
   function SafeData($str, $is_image = false, $denyOnlyNoughty = false )
   {
        // safe data recursively
		if (is_array($str))
		{
			while (list($key) = each($str))
			{
			        unset( $this->CurrentKey );
                                $this->CurrentKey = $key;
				$str[$key] = $this->SafeData($str[$key]);
			}
			return $str;
		}
        // case no tags
        if ( !$denyOnlyNoughty && !$is_image)
        {
           return strip_tags($str);  
        } 
        // remove invisible characters
		$str = $this->ric($str);              
        
        // start regexp
		$str = preg_replace('|\&([a-z\_0-9\-]+)\=([a-z\_0-9\-]+)|i', $this->XSSHashe()."\\1=\\2", $str);

		$str = preg_replace('#(&\#?[0-9a-z]{2,})([\x00-\x20])*;?#i', "\\1;\\2", $str);

		$str = preg_replace('#(&\#x?)([0-9A-F]+);?#i',"\\1\\2;",$str);

		$str = str_replace($this->XSSHashe(), '&', $str);

		$str = rawurldecode($str);

		$str = preg_replace_callback("/[a-z]+=([\'\"]).*?\\1/si", array($this, 'conv_attr'), $str);

		$str = preg_replace_callback("/<\w+.*?(?=>|<|$)/si", array($this, 'html_edc'), $str);

        // remove invisible characters 
		$str = $this->ric($str);

		if (strpos($str, "\t") !== false)
		{
			$str = str_replace("\t", ' ', $str);
		}

		$converted_string = $str;
        
		foreach ($this->NeverAllowedStr as $key => $val)
		{
			$str = str_replace($key, $val, $str);
		}

		foreach ($this->NeverAllowedRegex as $key => $val)
		{
			$str = preg_replace("#".$key."#i", $val, $str);
		}

		if ($is_image === true)
		{
			$str = preg_replace('/<\?(php)/i', "<?\\1", $str);
		}
		else
		{
			$str = str_replace(array('<?', '?'.'>'),  array('<?', '?>'), $str);
		}

		$words = array('javascript', 'expression', 'vbscript', 'script', 'applet', 'alert', 'document', 'write', 'cookie', 'window');
		foreach ($words as $word)
		{
			$temp = '';

			for ($i = 0, $wordlen = strlen($word); $i < $wordlen; $i++)
			{
				$temp .= substr($word, $i, 1)."\s*";
			}

			$str = preg_replace_callback('#('.substr($temp, 0, -3).')(\W)#is', array($this, 'comp_ex_w'), $str);
		}

		do
		{
			$original = $str;

			if (preg_match("/<a/i", $str))
			{
				$str = preg_replace_callback("#<a\s+([^>]*?)(>|$)#si", array($this, 'js_lr'), $str);
			}

			if (preg_match("/<img/i", $str))
			{
				$str = preg_replace_callback("#<img\s+([^>]*?)(\s?/?>|$)#si", array($this, 'js_imgr'), $str);
			}

			if (preg_match("/script/i", $str) || preg_match("/xss/i", $str))
			{
				$str = preg_replace("#<(/*)(script|xss)(.*?)\>#si", '[removed]', $str);
			}
		}
		while($original != $str);

		unset($original);

		$event_handlers = array('[^a-z_\-]on\w*','xmlns');

		if ($is_image === true)
		{
			unset($event_handlers[array_search('xmlns', $event_handlers)]);
		}

		$str = preg_replace("#<([^><]+?)(".implode('|', $event_handlers).")(\s*=\s*[^><]*)([><]*)#i", "<\\1\\4", $str);

		$naughty = $this->NougthyTags;
		$str = preg_replace_callback('#<(/*\s*)('.$naughty.')([^><]*)([><]*)#is', array($this, 'san_html'), $str);

		$str = preg_replace('#(alert|cmd|passthru|eval|exec|expression|system|fopen|fsockopen|file|file_get_contents|readfile|un  link)(\s*)\((.*?)\)#si', "\\1\\2(\\3)", $str);

		foreach ($this->NeverAllowedStr as $key => $val)
		{
			$str = str_replace($key, $val, $str);
		}

		foreach ($this->NeverAllowedRegex as $key => $val)
		{
			$str = preg_replace("#".$key."#i", $val, $str);
		}
		if ($is_image === true)
		{
			if ($str == $converted_string)
			{
				return true;
			}
			else
			{
				return true;
			}
		}
		return $str;
   }
   
   // remove invisible characters from a string
   function ric($str, $url_encoded = true)
	{
		$non_displayables = array();
		
		// every control character except newline (dec 10)
		// carriage return (dec 13), and horizontal tab (dec 09)
		
		if ($url_encoded)
		{
			$non_displayables[] = '/%0[0-8bcef]/';	// url encoded 00-08, 11, 12, 14, 15
			$non_displayables[] = '/%1[0-9a-f]/';	// url encoded 16-31
		}
		
		$non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';	// 00-08, 11, 12, 14-31, 127

		do
		{
			$str = preg_replace($non_displayables, '', $str, -1, $count);
		}
		while ($count);

		return $str;
	}
    
    // random hash for protecting url
    public function XSSHashe()
	{
		if ($this->XSSHashe == '')
		{
			if (phpversion() >= 4.2)
			{
				mt_srand();
			}
			else
			{
				mt_srand(hexdec(substr(md5(microtime()), -8)) & 0x7fffffff);
			}

			$this->XSSHashe = md5(time() + mt_rand(0, 1999999999));
		}

		return $this->XSSHashe;
	}
    
    // remove some js
    function js_lr($match)
    {
        return preg_replace("#<a.+?href=.*?(alert\(|alert&\#40;|javascript\:|window\.|document\.|\.cookie|<script|<xss).*?\>.*?</a>#si", "", $match[0]);
    }
    
    // remove js
    function js_imgr($match)
    {
        return preg_replace("#<img.+?src=.*?(alert\(|alert&\#40;|javascript\:|window\.|document\.|\.cookie|<script|<xss).*?\>#si", "", $match[0]);
    }
 
   // remove extra whitespaces 
    function comp_ex_w($matches)
    {
        return preg_replace('/\s+/s', '', $matches[1]) . $matches[2];
    }
   // convert atributes
    function conv_attr($match)
    {
        return str_replace(array('>', '<', '\\'), array('>', '<', '\\\\'), $match[0]);
    }
    // extended html entities decode
    function html_edc($match)
    {
        return $this->html_e_d($match[0]);
    }
    // native decode html entities
    function html_e_d($str, $charset='UTF-8')
    {
        if (stristr($str, '&') === FALSE) {
            return $str;
        }

        if (
            function_exists('html_entity_decode') &&
              (
                strtolower($charset) != 'utf-8' ||
                version_compare(phpversion(), '5.0.0', '>=')
            )
        ) {
            $str = html_entity_decode($str, ENT_COMPAT, $charset);
            $str = preg_replace('~&#x([0-9a-f]{2,5})~ei', 'chr(hexdec("\\1"))', $str);
            return preg_replace('~&#([0-9]{2,4})~e', 'chr(\\1)', $str);
        }
        // Numeric Entities
        $str = preg_replace('~&#x([0-9a-f]{2,5});{0,1}~ei', 'chr(hexdec("\\1"))', $str);
        $str = preg_replace('~&#([0-9]{2,4});{0,1}~e', 'chr(\\1)', $str);
        // Literal Entities - Slightly slow so we do another check
        if (stristr($str, '&') === FALSE) {
            $str = strtr($str, array_flip(get_html_translation_table(HTML_ENTITIES)));
        }
        return $str;
    }
    // sanitize naugthy html
    function san_html($matches)
    {
        $str = '<' . $matches[1] . $matches[2] . $matches[3];
        $str .= str_replace(array('>', '<'), array('>', '<'), $matches[4]);
        return $str;
    }
}
?>