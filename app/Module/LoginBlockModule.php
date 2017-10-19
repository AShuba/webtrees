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
declare(strict_types=1);namespace Fisharebest\Webtrees\Module;

use Fisharebest\Webtrees\Auth;
use Fisharebest\Webtrees\I18N;
use Fisharebest\Webtrees\Site;
use Fisharebest\Webtrees\View;

/**
 * Class LoginBlockModule
 */
class LoginBlockModule extends AbstractModule implements ModuleBlockInterface {
	/** {@inheritdoc} */
	public function getTitle() {
		return /* I18N: Name of a module */ I18N::translate('Sign in');
	}

	/** {@inheritdoc} */
	public function getDescription() {
		return /* I18N: Description of the “Sign in” module */ I18N::translate('An alternative way to sign in and sign out.');
	}

	/**
	 * Generate the HTML content of this block.
	 *
	 * @param int      $block_id
	 * @param bool     $template
	 * @param string[] $cfg
	 *
	 * @return string
	 */
	public function getBlock($block_id, $template = true, $cfg = []) {
		if (Auth::check()) {
			$title   = I18N::translate('Sign out');
			$content = View::make('blocks/sign-out', [
				'user' => Auth::user(),
				]);
		} else {
			$title   = I18N::translate('Sign in');
			$content = View::make('blocks/sign-in', [
				'allow_register' => (bool) Site::getPreference('USE_REGISTRATION_MODULE')
			]);
		}

		if ($template) {
			return View::make('blocks/template', [
				'block'      => str_replace('_', '-', $this->getName()),
				'id'         => $block_id,
				'config_url' => '',
				'title'      => $title,
				'content'    => $content,
			]);
		} else {
			return $content;
		}
	}

	/** {@inheritdoc} */
	public function loadAjax() {
		return false;
	}

	/** {@inheritdoc} */
	public function isUserBlock() {
		return true;
	}

	/** {@inheritdoc} */
	public function isGedcomBlock() {
		return true;
	}

	/**
	 * An HTML form to edit block settings
	 *
	 * @param int $block_id
	 */
	public function configureBlock($block_id) {
	}
}
