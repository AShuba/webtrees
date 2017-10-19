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
declare(strict_types=1);namespace Fisharebest\Webtrees\Controller;

use Fisharebest\Webtrees\Auth;
use Fisharebest\Webtrees\Filter;
use Fisharebest\Webtrees\I18N;
use Fisharebest\Webtrees\Menu;

/**
 * Controller for the source page
 */
class SourceController extends GedcomRecordController {
	/**
	 * get edit menu
	 */
	public function getEditMenu() {
		if (!$this->record || $this->record->isPendingDeletion()) {
			return null;
		}

		// edit menu
		$menu = new Menu(I18N::translate('Edit'), '#', 'menu-sour');

		if (Auth::isEditor($this->record->getTree())) {
			$fact = $this->record->getFirstFact('TITL');
			if ($fact) {
				// Edit existing name
				$menu->addSubmenu(new Menu(I18N::translate('Edit the source'), 'edit_interface.php?action=edit&amp;xref=' . $this->record->getXref() . '&amp;fact_id=' . $fact->getFactId() . '&amp;ged=' . $this->record->getTree()->getNameHtml(), 'menu-sour-edit'));
			} else {
				// Add new name
				$menu->addSubmenu(new Menu(I18N::translate('Edit the source'), 'edit_interface.php?action=add&amp;fact=TITL&amp;xref=' . $this->record->getXref() . '&amp;ged=' . $this->record->getTree()->getNameHtml(), 'menu-sour-edit'));
			}

			// delete
			$menu->addSubmenu(new Menu(I18N::translate('Delete'), '#', 'menu-sour-del', [
				'onclick' => "return delete_record('" . I18N::translate('Are you sure you want to delete “%s”?', strip_tags($this->record->getFullName())) . "', '" . $this->record->getXref() . "');",
			]));
		}

		// edit raw
		if (Auth::isAdmin() || Auth::isEditor($this->record->getTree()) && $this->record->getTree()->getPreference('SHOW_GEDCOM_RECORD')) {
			$menu->addSubmenu(new Menu(I18N::translate('Edit the raw GEDCOM'), 'edit_interface.php?action=editraw&amp;ged=' . $this->record->getTree()->getNameHtml() . '&amp;xref=' . $this->record->getXref(), 'menu-sour-editraw'));
		}

		return $menu;
	}
}
