<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\modules\dev_tools\converter;

class Base64_Controller {

	public function compile_file($file, $datauri) {
		$result = base64_encode(file_get_contents($file));
		if($datauri){
			$extension = pathinfo($file, PATHINFO_EXTENSION);
			$result="url(data:image/$extension;base64,$result)";
		}
		return $result;
	}
}