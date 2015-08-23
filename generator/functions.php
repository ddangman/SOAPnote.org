<?php
session_start() ;
define('APP_URL', 'http://www.soapnote.org/generator');
define('TEMPLATE_PATH', dirname(__FILE__) . '/template');
define('DATA_PATH', dirname(__FILE__) . '/data');
define('DATA_URL', APP_URL . '/data');

$shortcode_tags = array('text' => 'shortcode_text_function',
                                                'date' => 'shortcode_date_function',
                                                'textarea' => 'shortcode_textarea_function',
                                                'checkbox' => 'shortcode_checkbox_function',
                                                'radio' => 'shortcode_radio_function',
                                                'mark' => 'shortcode_mark_function',
                                                'link' => 'shortcode_link_function',
                                                'var' => 'shortcode_var_function',
                                                'comment' => 'shortcode_comment_function',
                                                'html' => 'shortcode_html_function',
                                                'select' => 'shortcode_select_function',
                                                'calc' => 'shortcode_calc_function',
                                                'conditional' => 'shortcode_conditional_function') ;

$conditions = $condition_fields = array();

function generateFormFile($title, $content,$fileName) {
        global $conditions, $condition_fields;
        $header = str_replace('##DATA_URL##',DATA_URL,file_get_contents(TEMPLATE_PATH . '/header.php'));
        $header = str_replace('##FORM_TITLE##',$title,$header);
        $footer = str_replace('##DATA_URL##',DATA_URL,file_get_contents(TEMPLATE_PATH . '/footer.php'));
        
        $content  = str_replace(array("\r\n","\n"),"<br>",$content);
        $content = do_shortcode($content);
        /*
        $regex =  get_shortcode_regex( );
        preg_match_all("/$regex/is",$content,$matched);

         foreach($matched[0] as $key => $item) {
                $shortcodeName = $matched[2][$key];
                $itemText = $matched[3][$key];
                $itemAtts = shortcode_parse_atts($itemText) ;
                $itemFunc = $shortcode_tags[$shortcodeName];
                $itemContent = $matched[5][$key];
                $itemHtml = call_user_func($itemFunc, $itemAtts,$itemContent);
                
                $content = str_replace($item,$itemHtml,$content);
         }
         */
         $conditionalContent = '';
         if (!empty($conditions) && sizeof($conditions) > 0) { 
			$conditionalContent .= '<script type="text/javascript">' . "\n";
			$conditionalContent .= "if (typeof vars == 'undefined') vars = new Array();" . "\n";
			$conditionalContent .= 'vars[1] = {};' . "\n";
			$conditionalContent .= "if (typeof conditions == 'undefined') conditions = new Array();" . "\n";
			$conditionalContent .= "conditions[1] = new Array();" . "\n";
			foreach ($conditions as $show => $condition) 
                        { 
				$conditionalContent .= 'conditions[1].push({logic:"' . $condition . '", show:"' .  $show . '"});' . "\n";
			}
			$fields = '';
			foreach ($condition_fields as $condition_field) {
				if ($fields != '') $fields .= ', ';
				$fields .= 'input[name="'.$condition_field.'"], select[name="'.$condition_field.'"]';
			}
			
                      
			$conditionalContent .= "jQuery(document).ready(function($) {
				$('#form_1').find('$fields').change(function() {
					if ($(this).attr('type') == 'checkbox') {
						vars[1][$(this).attr('name')] = new Array();
						$(this).parents('.checkbox').find(':checked').each(function() {
							vars[1][$(this).attr('name')].push($(this).val());
						});
					}
					else vars[1][$(this).attr('name')] = $(this).val();
					$.each(conditions[1], function(index, condition) { 
						$.each(vars[1], function(index, value) { 
							condition.logic = condition.logic.replace(new RegExp('\\\('+index+'\\\)', 'g'), 'vars[1][\''+index+'\']');
						});
						
						try {
							if (eval(condition.logic) === true) $('#form_1 [name=\"'+condition.show+'\"]').removeClass('hidden');
							else $('#form_1 [name='+condition.show+']').addClass('hidden');
							}
						catch(err) {
							$('#form_1 [name='+condition.show+']').addClass('hidden');
						}
					});
				});
				$('#form_1').find('$fields').change();
			});
			</script>" . "\n";
		}
        
        $footer = str_replace('##CONDITIONAL_CONTENT##',$conditionalContent,$footer);
        $content = $header . $content . $footer;
        file_put_contents(DATA_PATH.'/html/'.$fileName . '.html', $content);
        return DATA_URL .'/html/'.$fileName . '.html';
}

function generateTextFile($content,$fileName) {
        file_put_contents(DATA_PATH.'/text/'.$fileName .'.txt', $content);
        return DATA_URL .'/text/'.$fileName . '.txt';
}

function getHtml($data, $zipFilePath) {
	$zip = new ZipArchive();
	if (file_exists($zipFilePath)) {
		unlink($zipFilePath);
	}
	$res = $zip->open($zipFilePath, ZipArchive::CREATE);
	if ($res === true) {
                $data = str_replace(array(APP_URL . '/data/html/css/',APP_URL . '/data/html/js/'),'',$data);
                $zip->addFromString('style.css', file_get_contents(APP_URL . '/data/html/css/style.css'));
                $zip->addFromString('jquery.min.js', file_get_contents(APP_URL . '/data/html/js/jquery.min.js'));
                $zip->addFromString('jquery-ui.min.js', file_get_contents(APP_URL . '/data/html/js/jquery-ui.min.js'));
                $zip->addFromString('form.js', file_get_contents(APP_URL . '/data/html/js/form.js'));
		$zip->addFromString("soapnote.html" , $data);
	}
	$zip->close();
}

function getUserId($reset=false) {
        
        if(empty($_SESSION['userId']) || $reset == true) {
                $_SESSION['userId'] = uniqid();
        } 
        
        return $_SESSION['userId'] ;
}



	// textbox shortcode : text
	function shortcode_text_function( $atts ) {
		extract( shortcode_atts( array(
			'name' => '',
			'memo' => '',
			'help' => '',
			'size' => '15',
			'default' => ''
		), $atts ) );

		if ($atts[0] == '*') $required = ' required="required"';

		if (empty($name)) $name = uniqid('text_');
		
		if (!empty($memo)) $memo = '<span class="memo exclude">'.$memo.'</span>';

		if (!empty($help)) $help = '<span class="help exclude"><span class="help_popup hidden">'.$help.'</span></span>';

		return "<input type=\"text\" value=\"{$default}\" size=\"$size\" name=\"{$name}\" />".$memo.$help;
	}


	// date shortcode : date
	function shortcode_date_function( $atts ) {

		extract( shortcode_atts( array(
			'name' => '',
			'memo' => '',
			'help' => '',
			'default' => ''
		), $atts ) );

		if (empty($name)) $name = uniqid('date_');

		if (!empty($memo)) $memo = '<span class="memo exclude">'.$memo.'</span>';

		if ($default == 'today') {
			$class = 'date_today';
			$default = '';
		}
		else $class = '';
		
		if (!empty($help)) $help = '<span class="help exclude"><span class="help_popup hidden">'.$help.'</span></span>';

		return "<input type=\"text\" class=\"date {$class}\" value=\"{$default}\" size=\"10\" name=\"{$name}\" />".$memo.$help;
	}
	
	// textarea shortcode
	
	function shortcode_textarea_function( $atts ) {
		extract( shortcode_atts( array(
			'name' => '',
			'memo' => '',
			'help' => '',
			'cols' => '60',
			'rows' => '5',
			'default' => ''
		), $atts ) );


		if ($atts[0] == '*') $required = ' required="required"';
		if (empty($name)) $name = uniqid('textarea_');

		if (!empty($memo)) $memo = '<span class="memo exclude">'.$memo.'</span>';

		if (!empty($help)) $help = '<span class="help exclude"><span class="help_popup hidden">'.$help.'</span></span>';

		return "<textarea cols=\"$cols\" rows=\"$rows\" name=\"{$name}\">$default</textarea>".$memo.$help;
	}


	// select shortcode
	function shortcode_select_function( $atts ) {
                $test = shortcode_atts( array(
			'name' => '',
			'memo' => '',
			'help' => '',
			'value' => ''
		), $atts );
                
		extract( shortcode_atts( array(
			'name' => '',
			'memo' => '',
			'help' => '',
			'value' => ''
		), $atts ) );

		if (empty($name)) $name = uniqid('select_');

		if (!empty($memo)) $memo = '<span class="memo exclude">'.$memo.'</span>';

		$html = "<select name=\"{$name}\">";

		$value = explode("|",$value);

		foreach ($value as $choice) {
			$choice_array = explode("=",$choice);
			$choice_text = $choice_array[0];

			$html .= "<option value=\"{$choice}\">{$choice_text}</option>";
		}


		$html .= "</select>";

		if (!empty($help)) $help = '<span class="help exclude"><span class="help_popup hidden">'.$help.'</span></span>';

		return $html.$memo.$help;
	}
	

	// checkbox shortcode
	function shortcode_checkbox_function( $atts ) {
		$varname = '';

		extract( shortcode_atts( array(
			'name' => '',
			'memo' => '',
			'help' => '',
			'value' => ''
		), $atts ) );

		if ($atts[0] == '*') $required = ' required="required"';
		if (empty($name)) $name = uniqid('checkbox_');

		if (!empty($memo)) $memo = '<span class="memo exclude">'.$memo.'</span>';

		$value = explode("|",$value);

		$html = "<span class=\"checkbox\" name=\"{$name}\">";

		foreach ($value as $choice) {
			$choice_array = explode("=",$choice);
			$choice_text = $choice_array[0];

			$html .= " <label><input type=\"checkbox\" name=\"{$name}\" value=\"{$choice}\" /> {$choice_text}</label>";
		}

		if (!empty($help)) $help = '<span class="help exclude"><span class="help_popup hidden">'.$help.'</span></span>';

		$html .= $help . "</span>";



		return $html.$memo;
	}
	
	// radio shortcode
	function shortcode_radio_function( $atts ) {

		extract( shortcode_atts( array(
			'name' => '',
			'memo' => '',
			'help' => '',
			'value' => ''
		), $atts ) );

		if ($atts[0] == '*') $required = ' required="required"';
		if (empty($name)) $name = uniqid('radio_');

		if (!empty($memo)) $memo = '<span class="memo exclude">'.$memo.'</span>';

		$value = explode("|",$value);

		$html = "<span class=\"radio\" name=\"{$name}\">";

		foreach ($value as $choice) {
			$choice_array = explode("=",$choice);
			$choice_text = $choice_array[0];

			$html .= "<label> <input type=\"radio\" name=\"{$name}\" value=\"{$choice}\" /> {$choice_text}</label>";
		}

		if (!empty($help)) $help = '<span class="help exclude"><span class="help_popup hidden">'.$help.'</span></span>';

		$html .= $help . "</span>";
		
		return $html.$memo;
	}
	
	// html shortcode
	function shortcode_html_function( $atts, $content = null ) {

		$html = "<span class=\"exclude\">$content</span>";
		return $html;
	}

	function shortcode_comment_function( $atts ) {

		extract( shortcode_atts( array(
			'memo' => ''
		), $atts ) );
		$html = "<span class=\"memo exclude\">$memo</span>";
		return $html;
	}
	


	// mark shortcode
	function shortcode_mark_function( $atts ) {
		extract( shortcode_atts( array(
			'name' => '',
			'memo' => ''
		), $atts ) );

		if (empty($memo)) $memo = $name;
		if (empty($name)) return '';

		$html = "<a id=\"$name\" class=\"memo exclude\">$memo</a>";
		return $html;
	}

	// link shortcode
	function shortcode_link_function( $atts ) {
		extract( shortcode_atts( array(
			'url' => '',
			'mark' => '',
			'memo' => ''
		), $atts ) );

		if (!empty($url)) $html = "<a href=\"$url\" target=\"_blank\" class=\"link exclude\">$memo</a>";
		else if (!empty($mark)) $html = "<a href=\"#$mark\" class=\"link exclude\">$memo</a>";
		else $html = '';

		return $html;
	}
	
	// var shortcode
	function shortcode_var_function( $atts ) {
		extract( shortcode_atts( array(
			'name' => '',
			'memo' => ''
		), $atts ) );

		if (empty($memo)) $memo = $name;
		if (empty($name)) return '';

		$html = "<span class=\"var\" name=\"{$name}\">{$memo}</span>";
		return $html;
	}

	// calc shortcode
	function shortcode_calc_function( $atts ) {
		extract( shortcode_atts( array(
			'value' => '',
			'memo' => '',
			'show' => 'true'
		), $atts ) );
		if (empty($value)) return '';

		if ($show == 'false') $classes = "calc hidefo";
		else $classes = "calc";

		$html = "<span class=\"{$classes}\">{$memo}<span class=\"calc_value\">{$value}</span></span>";
		return $html;
	}
        
        function shortcode_conditional_function( $atts, $content ) {
		global $conditions, $condition_fields;
		extract( shortcode_atts( array(
			'name' => '',
			'field' => '',
			'condition' => ''
		), $atts ) );

		if (empty($name)) $name = uniqid('conditional_');
		
		if (!empty($condition)) {
			$condition = str_replace('&#038;','&',$condition);
			// add to list of conditional logics
			$conditions[$name] = $condition;

			if (!empty($field)) {
				$fields = explode('|', $field);
				foreach ($fields as $field) {
					$field = trim($field);
					if (!in_array($field, $condition_fields)) $condition_fields[] = $field;
				} 		
			}	
		}

		return "<span name=\"{$name}\" class=\"hidden\">".do_shortcode($content)."</span>";
	}

function shortcode_parse_atts($text) {
    $atts = array();
    $pattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
    $text = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);
    if ( preg_match_all($pattern, $text, $match, PREG_SET_ORDER) ) {
        foreach ($match as $m) {
            if (!empty($m[1]))
                $atts[strtolower($m[1])] = stripcslashes($m[2]);
            elseif (!empty($m[3]))
                $atts[strtolower($m[3])] = stripcslashes($m[4]);
            elseif (!empty($m[5]))
                $atts[strtolower($m[5])] = stripcslashes($m[6]);
            elseif (isset($m[7]) && strlen($m[7]))
                $atts[] = stripcslashes($m[7]);
            elseif (isset($m[8]))
                $atts[] = stripcslashes($m[8]);
        }
    } else {
        $atts = ltrim($text);
    }
    return $atts;
}

function get_shortcode_regex() {
    global $shortcode_tags;
    $tagnames = array_keys($shortcode_tags);
    $tagregexp = join( '|', array_map('preg_quote', $tagnames) );
 
    // WARNING! Do not change this regex without changing do_shortcode_tag() and strip_shortcode_tag()
    // Also, see shortcode_unautop() and shortcode.js.
    return
          '\\['                              // Opening bracket
        . '(\\[?)'                           // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
        . "($tagregexp)"                     // 2: Shortcode name
        . '(?![\\w-])'                       // Not followed by word character or hyphen
        . '('                                // 3: Unroll the loop: Inside the opening shortcode tag
        .     '[^\\]\\/]*'                   // Not a closing bracket or forward slash
        .     '(?:'
        .         '\\/(?!\\])'               // A forward slash not followed by a closing bracket
        .         '[^\\]\\/]*'               // Not a closing bracket or forward slash
        .     ')*?'
        . ')'
        . '(?:'
        .     '(\\/)'                        // 4: Self closing tag ...
        .     '\\]'                          // ... and closing bracket
        . '|'
        .     '\\]'                          // Closing bracket
        .     '(?:'
        .         '('                        // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
        .             '[^\\[]*+'             // Not an opening bracket
        .             '(?:'
        .                 '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
        .                 '[^\\[]*+'         // Not an opening bracket
        .             ')*+'
        .         ')'
        .         '\\[\\/\\2\\]'             // Closing shortcode tag
        .     ')?'
        . ')'
        . '(\\]?)';                          // 6: Optional second closing brocket for escaping shortcodes: [[tag]]
}

function do_shortcode ($content) {
        global $shortcode_tags;
        
        $regex =  get_shortcode_regex( );
        preg_match_all("/$regex/is",$content,$matched);

         foreach($matched[0] as $key => $item) {
                $shortcodeName = $matched[2][$key];
                $itemText = $matched[3][$key];
                $itemAtts = shortcode_parse_atts($itemText) ;
                $itemFunc = $shortcode_tags[$shortcodeName];
                $itemContent = $matched[5][$key];
                $itemHtml = call_user_func($itemFunc, $itemAtts,$itemContent);
                
                $content = str_replace($item,$itemHtml,$content);
         }
        
        return $content;
}

function shortcode_atts( $pairs, $atts, $shortcode = '' ) {
    $atts = (array)$atts;
    $out = array();
    foreach($pairs as $name => $default) {
        if ( array_key_exists($name, $atts) )
            $out[$name] = $atts[$name];
        else
            $out[$name] = $default;
    }
    return $out;
}

function do_shortcode_tag( $m ) {
    global $shortcode_tags;
 
    // allow [[foo]] syntax for escaping a tag
    if ( $m[1] == '[' && $m[6] == ']' ) {
        return substr($m[0], 1, -1);
    }
 
    $tag = $m[2];
    $attr = shortcode_parse_atts( $m[3] );
 
    if ( isset( $m[5] ) ) {
        // enclosing tag - extra parameter
        return $m[1] . call_user_func( $shortcode_tags[$tag], $attr, $m[5], $tag ) . $m[6];
    } else {
        // self-closing tag
        return $m[1] . call_user_func( $shortcode_tags[$tag], $attr, null,  $tag ) . $m[6];
    }
}

function strip_shortcode_tag( $m ) {
	// allow [[foo]] syntax for escaping a tag
	if ( $m[1] == '[' && $m[6] == ']' ) {
		return substr($m[0], 1, -1);
	}

	return $m[1] . $m[6];
}