<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\modules\dev_tools\converter;

require_once '../../../tethys/Start.php';
use t2\Start;
$page = Start::init_("PAGEID_CONVERTER_BASE64");
require_once HDDROOT_PROJECT.'/modules/dev_tools/converter/Base64_Views.php';
require_once HDDROOT_PROJECT.'/modules/dev_tools/converter/Base64_Controller.php';

use t2\core\service\Request;

$views = new Base64_Views($page);

$page->add($views->index());

if(Request::cmd("convert")){
	$page->add($views->output_from_request());
}

$page->send_and_quit();