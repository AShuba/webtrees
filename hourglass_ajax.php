<?php
/**
 * webtrees: online genealogy
 * Copyright (C) 2017 webtrees development team
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
declare(strict_types=1);

namespace Fisharebest\Webtrees;

use Fisharebest\Webtrees\Controller\HourglassController;

require 'includes/session.php';

$controller = new HourglassController;

header('Content-type: text/html; charset=UTF-8');

if (Filter::get('type') === 'desc') {
	$controller->dgenerations = 1;
	$controller->printDescendency($controller->root, 0, false);
} else {
	$controller->generations = 1;
	$controller->printPersonPedigree($controller->root, 0);
}
