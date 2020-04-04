<?php
/*GPL
 * This file is part of the T2 module 'dev_tools';
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * dev_tools comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\modules\dev_tools;

require_once 'Start.php';

use t2\core\Html;
use t2\Start;

$page = Start::init("PAGEID_DEVTOOLS_INDEX", "Dev tools");

$page->add(Html::H1("Dev tools"));

$page->add(Html::A_button("Pick-A-Todo",Html::href_internal_relative("pick-a-todo")));
$page->add(Html::A_button("Convert TODOs",Html::href_internal_relative("convert_todos")));

$page->send_and_quit();
