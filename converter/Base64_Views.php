<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\modules\t2_development\converter;

use t2\core\form\Form;
use t2\core\form\Formfield_checkbox;
use t2\core\form\Formfield_text;
use t2\core\form\Formfield_textarea;
use t2\core\Html;
use t2\core\Node;
use t2\core\service\Request;
use t2\core\Views;
use t2\core\Warning;

class Base64_Views extends Views {

	/**
	 * @return Node
	 */
	public function index() {
		$html = self::input_form();
		return new Node($html);
	}

	/**
	 * @return Form
	 */
	public function input_form(){
		$form = new Form("convert");
		$form->add_field(new Formfield_textarea("source"));
		$form->add_field(new Formfield_text("file"));
		$form->add_field(new Formfield_checkbox("datauri","Data-URI"));
		return $form;
	}

	public function output_from_request() {
		$controller = new Base64_Controller();
		$file = Request::value("file");
		$datauri = Request::value("datauri");
		if(!file_exists($file)){
			#Page::add_message_error_("File not found!");
			new Warning("File not found!");
			return "";
		}
		$output = $controller->compile_file($file, $datauri);
		return Html::TEXTAREA_console($output);
	}

}