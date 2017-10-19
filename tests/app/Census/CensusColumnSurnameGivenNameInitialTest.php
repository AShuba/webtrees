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

use Mockery;

/**
 * Test harness for the class CensusColumnSurnameGivenNameInitial
 */
class CensusColumnSurnameGivenNameInitialTest extends \PHPUnit_Framework_TestCase {
	/**
	 * Delete mock objects
	 */
	public function tearDown() {
		Mockery::close();
	}

	/**
	 * @covers \Fisharebest\Webtrees\Census\CensusColumnSurnameGivenNameInitial
	 * @covers \Fisharebest\Webtrees\Census\AbstractCensusColumn
	 */
	public function testOneGivenName() {
		$individual = Mockery::mock('Fisharebest\Webtrees\Individual');
		$individual->shouldReceive('getAllNames')->andReturn([['givn' => 'Joe', 'surn' => 'Sixpack']]);
		$individual->shouldReceive('getSpouseFamilies')->andReturn([]);

		$census = Mockery::mock('Fisharebest\Webtrees\Census\CensusInterface');
		$census->shouldReceive('censusDate')->andReturn('');

		$column = new CensusColumnSurnameGivenNameInitial($census, '', '');

		$this->assertSame('Sixpack, Joe', $column->generate($individual));
	}

	/**
	 * @covers \Fisharebest\Webtrees\Census\CensusColumnSurnameGivenNameInitial
	 * @covers \Fisharebest\Webtrees\Census\AbstractCensusColumn
	 */
	public function testMultipleGivenNames() {
		$individual = Mockery::mock('Fisharebest\Webtrees\Individual');
		$individual->shouldReceive('getAllNames')->andReturn([['givn' => 'Joe Fred', 'surn' => 'Sixpack']]);
		$individual->shouldReceive('getSpouseFamilies')->andReturn([]);

		$census = Mockery::mock('Fisharebest\Webtrees\Census\CensusInterface');
		$census->shouldReceive('censusDate')->andReturn('');

		$column = new CensusColumnSurnameGivenNameInitial($census, '', '');

		$this->assertSame('Sixpack, Joe F', $column->generate($individual));
	}
}
