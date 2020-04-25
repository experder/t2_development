<?php
/*GPL
 * This file is part of the T2 module 'dev_tools';
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * dev_tools comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\modules\t2_development;

require_once 'Start.php';

use t2\core\Page;
use t2\dev\Debug;
use t2\Start;

$page = Start::init_("PAGEID_DEVTOOLS_PICKATODO");

//Get all TODOs:
$file = file(ROOT_DIR."/dev/notes.md", FILE_IGNORE_NEW_LINES);

$todos = array();
$cat = null;
$bucket = array();
$on = false;

foreach ($file as $line){
	if($line=="Current TODOs"){
		$on = true;
	}
	if($on){
		if(preg_match("/^\\#\\#\\# (.*)\$/", $line, $matches)){
			if($cat){
				$todos[$cat] = $bucket;
			}
			$cat = $matches[1];
			$bucket = array();
		}
		if(preg_match("/^\\* (.*)\$/", $line, $matches)){
			$todo = $matches[1];
			//Unescape markdown:
			$todo = preg_replace("/\\\\(.)/","$1",$todo);
			$bucket[] = $todo;
		}
	}
}
if($cat){
	$todos[$cat] = $bucket;
}

//Values:
$values = array();
foreach ($todos as $cat=>$vals){
	$c = count($vals);
	if ($cat == 'High') {
		$c /= 2;
	}
	if ($cat == 'Medium') {
		$c /= 3;
	}
	if ($cat == 'Low') {
		$c /= 6;
	}
	if ($cat == 'Deprecated') {
		$c = 0;
	}
	$values[$cat] = $c;
}
Debug::out($values, "Values");

//Find max:
$max_cat = null;
$max_val = -1;
foreach ($values as $cat=>$c){
	if($c>$max_val){
		$max_cat=$cat;
		$max_val=$c;
	}
}

//Roll the dice:
$winner_array = $todos[$max_cat];
$random_number = rand(0, count($winner_array)-1);
$todo = $winner_array[$random_number];

Page::add_message_info_("<b>".htmlentities($todo)."</b><br><i style='display:block;text-align: right'>from the category \"$max_cat\"</i>");

$page->send_and_quit();
