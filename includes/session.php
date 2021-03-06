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
namespace Fisharebest\Webtrees;

use DateTime;
use ErrorException;
use Fisharebest\Webtrees\Theme\AdministrationTheme;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use PDOException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * We set the following globals
 *
 * @global Tree    $WT_TREE
 */
global $WT_TREE;

// Identify ourself
define('WT_WEBTREES', 'webtrees');
define('WT_VERSION', '2.0.0-dev');

// External URLs
define('WT_WEBTREES_URL', 'https://www.webtrees.net/');
define('WT_BOOTSTRAP_CSS_URL', 'https://cdn.rawgit.com/twbs/bootstrap/v4-dev/dist/css/bootstrap.min.css');
define('WT_BOOTSTRAP_DATETIMEPICKER_CSS_URL', 'packages/bootstrap-datetimepicker-4.17.47/css/bootstrap-datetimepicker.min.css');
define('WT_BOOTSTRAP_DATETIMEPICKER_JS_URL', 'packages/bootstrap-datetimepicker-4.17.47/js/bootstrap-datetimepicker.min.js');
define('WT_BOOTSTRAP_JS_URL', 'https://cdn.rawgit.com/twbs/bootstrap/v4-dev/dist/js/bootstrap.min.js');
define('WT_BOOTSTRAP_RTL_CSS_URL', 'https://raw.githubusercontent.com/GhalamborM/bootstrap4-rtl/master/bootstrap-rtl.css');
define('WT_DATATABLES_JS_URL', 'packages/datatables-1.10.16/jquery.dataTables.min.js');
define('WT_DATATABLES_BOOTSTRAP_CSS_URL', 'packages/datatables-1.10.16/dataTables.bootstrap4.min.css');
define('WT_DATATABLES_BOOTSTRAP_JS_URL', 'packages/datatables-1.10.16/dataTables.bootstrap4.min.js');
define('WT_FONT_AWESOME_CSS_URL', 'packages/font-awesome-5.0.1/css/fontawesome-all.min.css');
define('WT_JQUERY_JS_URL', 'packages/jquery-3.1.1/jquery.min.js');
define('WT_MOMENT_JS_URL', 'packages/moment-2.11.2/moment-with-locales.min.js');
define('WT_POPPER_JS_URL', 'packages/popper-1.11.0/popper.min.js');
define('WT_SELECT2_JS_URL', 'packages/select2-4.0.3/js/select2.min.js');
define('WT_SELECT2_CSS_URL', 'packages/select2-4.0.3/css/select2.min.css');
define('WT_SORTABLE_JS_URL', 'packages/rubaxa-sortable-1.4.0/Sortable.min.js');
// Note: these have been patched.
define('WT_JQUERY_COLORBOX_URL', 'assets/js-2.0.0/jquery.colorbox-1.6.4.js');
define('WT_JQUERY_WHEELZOOM_URL', 'assets/js-2.0.0/jquery.wheelzoom-3.1.2.js');
define('WT_CKEDITOR_BASE_URL', 'packages/ckeditor-4.5.2-custom/');

// Location of our own scripts
define('WT_ADMIN_JS_URL', 'assets/js-2.0.0/admin.js');
define('WT_WEBTREES_JS_URL', 'assets/js-2.0.0/webtrees.js');
define('WT_FONT_AWESOME_RTL_CSS_URL', 'assets/js-2.0.0/font-awesome-rtl.css');

// Location of our modules and themes. These are used as URLs and folder paths.
define('WT_MODULES_DIR', 'modules_v3/'); // Update setup.php and build/Makefile when this changes
define('WT_THEMES_DIR', 'themes/');

// Enable debugging output on development builds
define('WT_DEBUG', strpos(WT_VERSION, 'dev') !== false);

// Required version of database tables/columns/indexes/etc.
define('WT_SCHEMA_VERSION', 38);

// Regular expressions for validating user input, etc.
define('WT_MINIMUM_PASSWORD_LENGTH', 6);
define('WT_REGEX_XREF', '[A-Za-z0-9:_-]+');
define('WT_REGEX_TAG', '[_A-Z][_A-Z0-9]*');
define('WT_REGEX_INTEGER', '-?\d+');
define('WT_REGEX_BYTES', '[0-9]+[bBkKmMgG]?');
define('WT_REGEX_PASSWORD', '.{' . WT_MINIMUM_PASSWORD_LENGTH . ',}');

// UTF8 representation of various characters
define('WT_UTF8_BOM', "\xEF\xBB\xBF"); // U+FEFF (Byte order mark)
define('WT_UTF8_LRM', "\xE2\x80\x8E"); // U+200E (Left to Right mark:  zero-width character with LTR directionality)
define('WT_UTF8_RLM', "\xE2\x80\x8F"); // U+200F (Right to Left mark:  zero-width character with RTL directionality)
define('WT_UTF8_LRO', "\xE2\x80\xAD"); // U+202D (Left to Right override: force everything following to LTR mode)
define('WT_UTF8_RLO', "\xE2\x80\xAE"); // U+202E (Right to Left override: force everything following to RTL mode)
define('WT_UTF8_LRE', "\xE2\x80\xAA"); // U+202A (Left to Right embedding: treat everything following as LTR text)
define('WT_UTF8_RLE', "\xE2\x80\xAB"); // U+202B (Right to Left embedding: treat everything following as RTL text)
define('WT_UTF8_PDF', "\xE2\x80\xAC"); // U+202C (Pop directional formatting: restore state prior to last LRO, RLO, LRE, RLE)

// Alternatives to BMD events for lists, charts, etc.
define('WT_EVENTS_BIRT', 'BIRT|CHR|BAPM|_BRTM|ADOP');
define('WT_EVENTS_DEAT', 'DEAT|BURI|CREM');
define('WT_EVENTS_MARR', 'MARR|_NMR');
define('WT_EVENTS_DIV', 'DIV|ANUL|_SEPR');

// Use these line endings when writing files on the server
define('WT_EOL', "\r\n");

// Gedcom specification/definitions
define('WT_GEDCOM_LINE_LENGTH', 255 - strlen(WT_EOL)); // Characters, not bytes

// Used in Google charts
define('WT_GOOGLE_CHART_ENCODING', 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-.');

// For performance, it is quicker to refer to files using absolute paths
define('WT_ROOT', realpath(dirname(__DIR__)) . DIRECTORY_SEPARATOR);

// Keep track of time statistics, for the summary in the footer
define('WT_START_TIME', microtime(true));

// We want to know about all PHP errors during development, and fewer in production.
if (WT_DEBUG) {
	error_reporting(E_ALL | E_STRICT | E_NOTICE | E_DEPRECATED);
} else {
	error_reporting(E_ALL);
}

require WT_ROOT . 'vendor/autoload.php';

// Initialise the DebugBar for development.
// Use `composer install --dev` on a development build to enable.
// Note that you may need to increase the size of the fcgi buffers on nginx.
// e.g. add these lines to your fastcgi_params file:
// fastcgi_buffers 16 16m;
// fastcgi_buffer_size 32m;
DebugBar::init(WT_DEBUG && class_exists('\\DebugBar\\StandardDebugBar'));

// PHP requires a time zone to be set. We'll set a better one later on.
date_default_timezone_set('UTC');

// Calculate the base URL, so we can generate absolute URLs.
$request     = Request::createFromGlobals();
$request_uri = $request->getSchemeAndHttpHost() . $request->getRequestUri();

// Remove any PHP script name and parameters.
$base_uri = preg_replace('/[^\/]+\.php(\?.*)?$/', '', $request_uri);
define('WT_BASE_URL', $base_uri);

// What is the name of the requested script.
define('WT_SCRIPT_NAME', basename(Filter::server('SCRIPT_NAME')));

// Convert PHP errors into exceptions
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
	// Ignore errors thar are silenced with '@'
	if (error_reporting() & $errno) {
		throw new ErrorException($errfile . ':' . $errline . ' ' . $errstr, $errno);
	}
});

set_exception_handler(function (Throwable $ex) {
	$message = $ex->getFile() . ':' . $ex->getLine() . ' ' . $ex->getMessage() . PHP_EOL;

	foreach ($ex->getTrace() as $level => $frame) {
		$frame += ['args' => [], 'file' => 'unknown', 'line' => 'unknown'];
		array_walk($frame['args'], function (&$arg) {
			switch (gettype($arg)) {
				case 'boolean':
				case 'integer':
				case 'double':
				case 'null':
					$arg = var_export($arg, true);
					break;
				case 'string':
					if (mb_strlen($arg) > 30) {
						$arg = mb_substr($arg, 0, 30) . '…';
					}
					$arg = var_export($arg, true);
					break;
				case 'object':
					$reflection = new \ReflectionClass($arg);
					if (is_object($arg) && method_exists($arg, '__toString')) {
						$arg = '[' . $reflection->getShortName() . ' ' . (string) $arg . ']';
					} else {
						$arg = '[' . $reflection->getShortName() . ']';
					}
					break;
				default:
					$arg = '[' . gettype($arg) . ']';
					break;
			}
		});
		$frame['file'] = str_replace(dirname(__DIR__), '', $frame['file']);
		$message .= '#' . $level . ' ' . $frame['file'] . ':' . $frame['line'] . ' ';
		if ($level > 0) {
			$message .= $frame['function'] . '(' . implode(', ', $frame['args']) . ')' . PHP_EOL;
		} else {
			$message .= get_class($ex) . '("' . $ex->getMessage() . '")' . PHP_EOL;
		}
	}

	echo $message;

	Log::addErrorLog($message);
});

DebugBar::startMeasure('init database');

// Load our configuration file, so we can connect to the database
if (file_exists(WT_ROOT . 'data/config.ini.php')) {
	// Down for maintenance?
	if (file_exists(WT_ROOT . 'data/offline.txt')) {
		header('Location: site-offline.php');
		exit;
	}
} else {
	// No config file. Set one up.
	$url      = Html::url('setup.php', ['route' => 'setup']);
	$response = new RedirectResponse($url);
	$response->send();
	exit;
}

// Connect to the database
try {
	// Read the connection settings and create the database
	Database::createInstance(parse_ini_file(WT_ROOT . 'data/config.ini.php'));

	// Update the database schema, if necessary.
	Database::updateSchema('\Fisharebest\Webtrees\Schema', 'WT_SCHEMA_VERSION', WT_SCHEMA_VERSION);
} catch (PDOException $ex) {
	DebugBar::addThrowable($ex);

	define('WT_DATA_DIR', 'data/');
	I18N::init();
	if ($ex->getCode() === 1045) {
		// Error during connection?
		$content = View::make('errors/database-connection', ['error' => $ex->getMessage()]);
	} else {
		// Error in a migration script?
		$content = View::make('errors/database-error', ['error' => $ex->getMessage()]);
	}
	$html     = View::make('layouts/error', ['content' => $content]);
	$response = new Response($html, 503);
	$response->prepare($request)->send();
	exit;
} catch (Throwable $ex) {
	DebugBar::addThrowable($ex);

	define('WT_DATA_DIR', 'data/');
	I18N::init();
	$content = View::make('errors/database-connection', ['error' => $ex->getMessage()]);
	$html     = View::make('layouts/error', ['content' => $content]);
	$response = new Response($html, 503);
	$response->prepare($request)->send();
	exit;
}

DebugBar::stopMeasure('init database');

// The config.ini.php file must always be in a fixed location.
// Other user files can be stored elsewhere...
define('WT_DATA_DIR', realpath(Site::getPreference('INDEX_DIRECTORY', 'data/')) . DIRECTORY_SEPARATOR);

// Some broken servers block access to their own temp folder using open_basedir...
$data_dir = new Filesystem(new Local(WT_DATA_DIR));
$data_dir->createDir('tmp');
putenv('TMPDIR=' . WT_DATA_DIR . 'tmp');

// Request more resources - if we can/want to
if (!ini_get('safe_mode')) {
	$memory_limit = Site::getPreference('MEMORY_LIMIT');
	if ($memory_limit !== '' && strpos(ini_get('disable_functions'), 'ini_set') === false) {
		ini_set('memory_limit', $memory_limit);
	}
	$max_execution_time = Site::getPreference('MAX_EXECUTION_TIME');
	if ($max_execution_time !== '' && strpos(ini_get('disable_functions'), 'set_time_limit') === false) {
		set_time_limit($max_execution_time);
	}
}

// Sessions
Session::setSaveHandler();
Session::start([
	'gc_maxlifetime' => Site::getPreference('SESSION_TIME'),
	'cookie_path'    => implode('/', array_map('rawurlencode', explode('/', parse_url(WT_BASE_URL, PHP_URL_PATH)))),
]);

// A new session, so prevent session fixation attacks by choosing a new PHPSESSID.
if (!Session::get('initiated')) {
	Session::regenerate(true);
	Session::put('initiated', true);
}

DebugBar::startMeasure('init tree');

// Set the tree for the page; (1) the request, (2) the session, (3) the site default, (4) any tree
foreach ([Filter::post('ged'), Filter::get('ged'), Session::get('GEDCOM'), Site::getPreference('DEFAULT_GEDCOM')] as $tree_name) {
	$WT_TREE = Tree::findByName($tree_name);
	if ($WT_TREE) {
		Session::put('GEDCOM', $tree_name);
		break;
	}
}
// No chosen tree? Use any one.
if (!$WT_TREE) {
	foreach (Tree::getAll() as $WT_TREE) {
		break;
	}
}

DebugBar::stopMeasure('init tree');

DebugBar::startMeasure('init i18n');

// With no parameters, init() looks to the environment to choose a language
define('WT_LOCALE', I18N::init());
Session::put('locale', WT_LOCALE);

DebugBar::stopMeasure('init i18n');

// Note that the database/webservers may not be synchronised, so use DB time throughout.
define('WT_TIMESTAMP', (int) Database::prepare("SELECT UNIX_TIMESTAMP()")->fetchOne());

// Users get their own time-zone. Visitors get the site time-zone.
if (Auth::check()) {
	date_default_timezone_set(Auth::user()->getPreference('TIMEZONE', 'UTC'));
} else {
	date_default_timezone_set(Site::getPreference('TIMEZONE', 'UTC'));
}
define('WT_TIMESTAMP_OFFSET', date_offset_get(new DateTime('now')));

define('WT_CLIENT_JD', 2440588 + (int) ((WT_TIMESTAMP + WT_TIMESTAMP_OFFSET) / 86400));

// The login URL must be an absolute URL, and can be user-defined
if (Site::getPreference('LOGIN_URL') !== '') {
	define('WT_LOGIN_URL', Site::getPreference('LOGIN_URL'));
} else {
	define('WT_LOGIN_URL', WT_BASE_URL . 'login.php');
}

// If there is no current tree and we need one, then redirect somewhere
if (WT_SCRIPT_NAME != 'admin_trees_manage.php' && WT_SCRIPT_NAME != 'admin_pgv_to_wt.php' && WT_SCRIPT_NAME != 'login.php' && WT_SCRIPT_NAME != 'logout.php' && WT_SCRIPT_NAME != 'import.php' && WT_SCRIPT_NAME != 'help_text.php' && WT_SCRIPT_NAME != 'action.php') {
	if (!$WT_TREE || !$WT_TREE->getPreference('imported')) {
		if (Auth::isAdmin()) {
			header('Location: admin_trees_manage.php');
		} else {
			// We're not an administrator, so we can only log in if there is a tree.
			if (Auth::id()) {
				Auth::logout();
				FlashMessages::addMessage(
					I18N::translate('This user account does not have access to any tree.')
				);
			}
			header('Location: ' . Html::url(WT_LOGIN_URL, ['url' => $request->getRequestUri()]));
		}
		exit;
	}
}

// Update the last-login time no more than once a minute
if (WT_TIMESTAMP - Session::get('activity_time') >= 60) {
	if (Session::get('masquerade') === null) {
		Auth::user()->setPreference('sessiontime', WT_TIMESTAMP);
	}
	Session::put('activity_time', WT_TIMESTAMP);
}

DebugBar::startMeasure('init theme');

// Set the theme
if (substr(WT_SCRIPT_NAME, 0, 5) === 'admin' || WT_SCRIPT_NAME === 'module.php' && substr(Filter::get('mod_action'), 0, 5) === 'admin') {
	// Administration scripts begin with “admin” and use a special administration theme
	Theme::theme(new AdministrationTheme)->init($WT_TREE);
} else {
	// Last theme used?
	$theme_id = Session::get('theme_id');
	// Default for tree
	if (!array_key_exists($theme_id, Theme::themeNames()) && $WT_TREE) {
		$theme_id = $WT_TREE->getPreference('THEME_DIR');
	}
	// Default for site
	if (!array_key_exists($theme_id, Theme::themeNames())) {
		$theme_id = Site::getPreference('THEME_DIR');
	}
	// Default
	if (!array_key_exists($theme_id, Theme::themeNames())) {
		$theme_id = 'webtrees';
	}
	foreach (Theme::installedThemes() as $theme) {
		if ($theme->themeId() === $theme_id) {
			Theme::theme($theme)->init($WT_TREE);
			// Remember this setting
			if (Site::getPreference('ALLOW_USER_THEMES') === '1') {
				Session::put('theme_id', $theme_id);
			}
			break;
		}
	}
}

DebugBar::stopMeasure('init theme');

