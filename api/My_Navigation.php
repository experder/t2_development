<?php
/*GPL
 * This file is part of the T2 module 'dev_tools';
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * dev_tools comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\modules\dev_tools\api;

use t2\api\Navigation;
use t2\core\Html;

class My_Navigation extends Navigation {

	public function __construct() {
		parent::__construct('PAGEID_DEVTOOLS_INDEX',"Dev Tools",Html::href_internal_module("dev_tools", "index"),null);
	}

	public function getChildren() {
		if($this->children===null){
			$this->children=array(
				new Navigation('PAGEID_DEVTOOLS_PICKATODO',"Pick-A-Todo",Html::href_internal_module("dev_tools", "pick-a-todo")),
				new Navigation('PAGEID_DEVTOOLS_CONVERTTODOS',"Convert TODOs",Html::href_internal_module('dev_tools',"convert_todos")),
				new Navigation('PAGEID_CONVERTER_BASE64',"Convert BASE64",Html::href_internal_module('dev_tools',"converter/base64")),
			);
		}
		return $this->children;
	}

}