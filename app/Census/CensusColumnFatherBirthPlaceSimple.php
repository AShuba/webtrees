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

declare(strict_types=1);namespace Fisharebest\Webtrees\Census;

use Fisharebest\Webtrees\Individual;

/**
 * The individual's father's birth place.
 */
class CensusColumnFatherBirthPlaceSimple extends CensusColumnFatherBirthPlace implements CensusColumnInterface {
	/**
	 * Generate the likely value of this census column, based on available information.
	 *
	 * @param Individual $individual
	 * @param Individual $head
	 *
	 * @return string
	 */
	public function generate(Individual $individual, Individual $head = null) {
		return $this->lastPartOfPlace(parent::generate($individual, $head));
	}
}
