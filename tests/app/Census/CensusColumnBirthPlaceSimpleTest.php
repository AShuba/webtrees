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
namespace Fisharebest\Webtrees\Census;

use Mockery;

/**
 * Test harness for the class CensusColumnBirthPlaceSimple
 */
class CensusColumnBirthPlaceSimpleTest extends \PHPUnit\Framework\TestCase {
	/**
	 * Delete mock objects
	 */
	public function tearDown() {
		Mockery::close();
	}

	/**
	 * Get place mock.
	 *
	 * @param string $place Gedcom Place
	 *
	 * @return \Fisharebest\Webtrees\Place
	 */
	private function getPlaceMock($place) {
		$placeMock = Mockery::mock('\Fisharebest\Webtrees\Place');
		$placeMock->shouldReceive('getGedcomName')->andReturn($place);

		return $placeMock;
	}

	/**
	 * @covers \Fisharebest\Webtrees\Census\CensusColumnBirthPlaceSimple
	 * @covers \Fisharebest\Webtrees\Census\AbstractCensusColumn
	 */
	public function testForeignCountry() {
		$individual = Mockery::mock('Fisharebest\Webtrees\Individual');
		$individual->shouldReceive('getBirthPlace')->andReturn($this->getPlaceMock('Westminster, London, England'));

		$census = Mockery::mock('Fisharebest\Webtrees\Census\CensusInterface');
		$census->shouldReceive('censusPlace')->andReturn('United States');

		$column = new CensusColumnBirthPlaceSimple($census, '', '');

		$this->assertSame('England', $column->generate($individual));
	}

	/**
	 * @covers \Fisharebest\Webtrees\Census\CensusColumnBirthPlaceSimple
	 * @covers \Fisharebest\Webtrees\Census\AbstractCensusColumn
	 */
	public function testJustCountry() {
		$individual = Mockery::mock('Fisharebest\Webtrees\Individual');
		$individual->shouldReceive('getBirthPlace')->andReturn($this->getPlaceMock('United States'));

		$census = Mockery::mock('Fisharebest\Webtrees\Census\CensusInterface');
		$census->shouldReceive('censusPlace')->andReturn('United States');

		$column = new CensusColumnBirthPlaceSimple($census, '', '');

		$this->assertSame('', $column->generate($individual));
	}

	/**
	 * @covers \Fisharebest\Webtrees\Census\CensusColumnBirthPlaceSimple
	 * @covers \Fisharebest\Webtrees\Census\AbstractCensusColumn
	 */
	public function testKnownState() {
		$individual = Mockery::mock('Fisharebest\Webtrees\Individual');
		$individual->shouldReceive('getBirthPlace')->andReturn($this->getPlaceMock('Maryland, United States'));

		$census = Mockery::mock('Fisharebest\Webtrees\Census\CensusInterface');
		$census->shouldReceive('censusPlace')->andReturn('United States');

		$column = new CensusColumnBirthPlaceSimple($census, '', '');

		$this->assertSame('Maryland', $column->generate($individual));
	}

	/**
	 * @covers \Fisharebest\Webtrees\Census\CensusColumnBirthPlaceSimple
	 * @covers \Fisharebest\Webtrees\Census\AbstractCensusColumn
	 */
	public function testKnownStateAndTown() {
		$individual = Mockery::mock('Fisharebest\Webtrees\Individual');
		$individual->shouldReceive('getBirthPlace')->andReturn($this->getPlaceMock('Miami, Florida, United States'));

		$census = Mockery::mock('Fisharebest\Webtrees\Census\CensusInterface');
		$census->shouldReceive('censusPlace')->andReturn('United States');

		$column = new CensusColumnBirthPlaceSimple($census, '', '');

		$this->assertSame('Florida', $column->generate($individual));
	}
}
