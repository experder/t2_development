<?php
/*GPL
 * This file is part of the T2 module 'dev_tools';
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * dev_tools comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

foreach (array(

			 //dev_tools is the main project and T2 is submodule tethys
			 'tethys/Start.php',

			 //dev_tools is a submodule in a project where T2 is another submodule named 'tethys'
			 //(as recommended on https://github.com/experder/T2/blob/master/help/install.md)
			 '../../tethys/Start.php',//dev_tools is sub of 'modules' / T2 is sub of project root

			 //OTHERS:
			 //(please create a config file in dev_tools root folder that points to T2-Start)
			 //Example content: require_once '../T2/Start.php';
			 'config.php',

		 ) as $file) {
	if (file_exists($file)) {
		/** @noinspection PhpIncludeInspection */
		require_once $file;
		break;
	}
}
