<?php
/*GPL
 * This file is part of the T2 module 'dev_tools';
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * dev_tools comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\modules\dev_tools\api;

use t2\api\Update_database;

class My_Update_database extends Update_database {

	protected $module = 'dev_tools';
	protected $start_ver = 1;

	/**
	 * @inheritdoc
	 */
	protected function do_update() {

		$core_config = DB_CORE_PREFIX . '_config';

		$this->q(1, "INSERT INTO `$core_config` (`idstring`, `module`, `userid`, `content`) VALUES ('API_DIR', 'dev_tools', NULL, 'api');");

		#$this->q(,"");

	}

}