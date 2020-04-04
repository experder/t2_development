<?php
/*GPL
 * This file is part of the T2 module 'dev_tools';
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * dev_tools comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\modules\dev_tools;

require_once 'Start.php';

use t2\core\form\Form;
use t2\core\form\Formfield_textarea;
use t2\core\Html;
use t2\core\service\Request;
use t2\core\service\Strings;
use t2\Start;

$page = Start::init_("PAGEID_DEVTOOLS_CONVERTTODOS");

$page->add(Html::H1("Format TODOs"));

$form = new Form("do_process", "", "Process");
$form->add_field(new Formfield_textarea("input", ""));

$page->add($form);
if(Request::cmd("do_process")){
	$out = process();
	$page->add(Html::TEXTAREA_console($out));
}

$page->send_and_quit();
//==================================================================================
function process(){
	$input = Request::value("input");
	$output = array(
		1=>array(),//High
		2=>array(),//Medium
		3=>array(),//Low
		4=>array(),//Deprecated
		5=>array(),//Unknown(default)
		6=>array(),//Feature requests
	);

	$current_file = false;
	$count_per_file = array();

	foreach (explode("\n",$input) as $in){
		$in = trim($in);//Von Windows-Zeilenumbrüchen bleibt ein \r.
		if($in){//Leere Zeilen rausfiltern
			if(!preg_match("/^Found [0-9]+ TODO items in [0-9]+ files\$/",$in)){//Titelzeile
				if(preg_match("/^[\\w\\.]+$/", $in)){//Überschrift
					if ($current_file!==false && $count_per_file[$current_file]==0){//Letzte Überschrift war leer
						$output[1][] = $current_file;
					}
					$current_file = $in;
					$count_per_file[$current_file]=0;
				}else{
					$in0 = $in;
					$prio = 5;
					if($current_file!==false){
						/**
						 * "(123, 45) T0D0"
						 * https://github.com/experder/T2/blob/master/help/dev_regex.md
						 */
						if (preg_match("/^\\(([0-9]{1,5}), [0-9]{1,3}\\) (.+)/", $in, $matches)){//ZEILE
							$zeile = $matches[1];
							$todo = $matches[2];
							$todo_w_prio_regex = "/(\\W)TODO\\(([123F])\\)/i";
							if(preg_match($todo_w_prio_regex,$todo,$matches_i)){
								$prio = $matches_i[2];
								if ($prio == "F") {
									$prio = 6;
								}
								$todo=preg_replace($todo_w_prio_regex, "$1TODO", $todo);
							}
							if(preg_match("/\\(TODO\\:(.*)\\)/i",$todo,$matches_i)
								||preg_match("/\\/\\*TODO\\:(.*?)\\*\\//i",$todo,$matches_i)
								||preg_match("/\\/\\/TODO\\:(.*)/i",$todo,$matches_i)
								||preg_match("/ TODO\\:(.*)/i",$todo,$matches_i)
							){
								$todo_inner = trim($matches_i[1]);
								if($todo_inner){
									$todo = $todo_inner;
								}
							}
							$in = "$todo ($current_file:$zeile)";
							$count_per_file[$current_file]++;
						}
					}
					if(preg_match("/\\@deprecated /", $in0)){
						$output[4][] = $in;
					}else{
						$output[$prio][] = $in;
					}
				}
			}
		}
	}
	if ($current_file!==false && $count_per_file[$current_file]==0){//Letzte Zeile war Überschrift
		$output[1][] = $current_file;
	}

	$output_escaped = array();
	foreach ($output as $key => $value){
		$array = array();
		foreach ($value as $val){
			$array[] = Strings::escape_markdown($val);
		}
		$output_escaped[$key] = $array;
	}
	$output = $output_escaped;

	$out_html=array();
	if($output[6]){
		$out_html[]="### Feature requests\n"
		. "* ".implode("\n* ", $output[6]);
	}
	if($output[1]){
		$out_html[]="### High\n"
		. "* ".implode("\n* ", $output[1]);
	}
	if($output[5]){
		$out_html[]="### (Unknown)\n"
			. "* ".implode("\n* ", $output[5]);
	}
	if($output[2]){
		$out_html[]="### Medium\n"
		. "* ".implode("\n* ", $output[2]);
	}
	if($output[3]){
		$out_html[]="### Low\n"
		. "* ".implode("\n* ", $output[3]);
	}
	if($output[4]){
		$out_html[]="### Deprecated\n"
		."* ".implode("\n* ", $output[4]);
	}
	$out = implode("\n\n",$out_html);

	return $out;
}
