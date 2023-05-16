<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

use eftec\bladeone\BladeOne; // reff : https://github.com/EFTEC/BladeOne
use voku\helper\HtmlMin; // reff : https://github.com/voku/HtmlMin

// reff : https://github.com/EFTEC/BladeOne
if (!function_exists('render')) {
	function render($fileName, $data = NULL, $bladeName = 'templates/desktop_blade')
	{
		$bladeName = isMobileDevice() ? 'templates/pwa_blade' : $bladeName;

		$views = APPPATH . 'views';
		$cache = isMobileDevice() ? APPPATH . 'cache' . DIRECTORY_SEPARATOR . 'blade_cache/mobile/' : APPPATH . 'cache' . DIRECTORY_SEPARATOR . 'blade_cache/browser/';

		$fileName = $fileName . '.php';

		if (file_exists($views . DIRECTORY_SEPARATOR . $fileName)) {

			if (!file_exists($cache)) {
				mkdir($cache, 0755, true);
			}

			loadBladeTemplate($views, $cache, $fileName, $data);
		} else {
			errorpage('404');
		}
	}
}

if (!function_exists('loadBladeTemplate')) {
	function loadBladeTemplate($views, $cache = NULL, $fileName = NULL, $data = NULL)
	{
		# Please use this settings :
		# 0 - MODE_AUTO : BladeOne reads if the compiled file has changed. If has changed,then the file is replaced.
		# 1 - MODE_SLOW : Then compiled file is always replaced. It's slow and it's useful for development.
		# 2 - MODE_FAST : The compiled file is never replaced. It's fast and it's useful for production.
		# 5 - MODE_DEBUG :  DEBUG MODE, the file is always compiled and the filename is identifiable.
		try {
			$blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);
			$blade->setAuth(currentUserID(), currentUserRoleID(), permission());
			$blade->setBaseUrl(baseUrl() . 'public/'); // with or without trail slash
			// echo $blade->run($fileName, $data);
			echo minifyHtml($blade->run($fileName, $data));
		} catch (Exception $e) {
			echo "<b> ERROR FOUND : </b> <br><br>" . $e->getMessage() . "<br><br><br>" . $e->getTraceAsString();
		}
	}
}

if (!function_exists('view')) {
	function view($fileName, $data = NULL, $bladeName = 'templates/desktop_blade')
	{
		$ci = get_instance();
		$bladeName = isMobileDevice() ? 'templates/pwa_blade' : $bladeName;

		if (file_exists(APPPATH . 'views' . DIRECTORY_SEPARATOR . $fileName . '.php')) {
			$ci->load->view($fileName, $data);
		} else {
			errorpage('404');
		}
	}
}

if (!function_exists('error')) {
	function error($code = NULL)
	{
		$ci = get_instance();
		$ci->load->view('errors/custom/error_' . $code);
	}
}

if (!function_exists('errorpage')) {
	function errorpage($code = NULL)
	{
		redirect('error/' . $code);
	}
}

if (!function_exists('minifyHtml')) {
	function minifyHtml($htmlTag)
	{
		$htmlMin = new HtmlMin();

		$htmlMin->doOptimizeViaHtmlDomParser(true);               // optimize html via "HtmlDomParser()"
		$htmlMin->doRemoveComments();                     			// remove default HTML comments (depends on "doOptimizeViaHtmlDomParser(true)")
		$htmlMin->doSumUpWhitespace();                    			// sum-up extra whitespace from the Dom (depends on "doOptimizeViaHtmlDomParser(true)")
		$htmlMin->doRemoveWhitespaceAroundTags();         			// remove whitespace around tags (depends on "doOptimizeViaHtmlDomParser(true)")
		$htmlMin->doOptimizeAttributes();                 		// optimize html attributes (depends on "doOptimizeViaHtmlDomParser(true)")
		$htmlMin->doRemoveHttpPrefixFromAttributes();         // remove optional "http:"-prefix from attributes (depends on "doOptimizeAttributes(true)")
		$htmlMin->doRemoveHttpsPrefixFromAttributes();        // remove optional "https:"-prefix from attributes (depends on "doOptimizeAttributes(true)")
		$htmlMin->doKeepHttpAndHttpsPrefixOnExternalAttributes(); // keep "http:"- and "https:"-prefix for all external links 
		$htmlMin->doMakeSameDomainsLinksRelative(['example.com']); // make some links relative, by removing the domain from attributes
		$htmlMin->doRemoveDefaultAttributes();                // remove defaults (depends on "doOptimizeAttributes(true)" | disabled by default)
		$htmlMin->doRemoveDeprecatedAnchorName();             // remove deprecated anchor-jump (depends on "doOptimizeAttributes(true)")
		$htmlMin->doRemoveDeprecatedScriptCharsetAttribute(); // remove deprecated charset-attribute - the browser will use the charset from the HTTP-Header, anyway (depends on "doOptimizeAttributes(true)")
		$htmlMin->doRemoveDeprecatedTypeFromScriptTag();      // remove deprecated script-mime-types (depends on "doOptimizeAttributes(true)")
		$htmlMin->doRemoveDeprecatedTypeFromStylesheetLink(); // remove "type=text/css" for css links (depends on "doOptimizeAttributes(true)")
		$htmlMin->doRemoveDeprecatedTypeFromStyleAndLinkTag(); // remove "type=text/css" from all links and styles
		$htmlMin->doRemoveDefaultMediaTypeFromStyleAndLinkTag(); // remove "media="all" from all links and styles
		$htmlMin->doRemoveEmptyAttributes();                  // remove some empty attributes (depends on "doOptimizeAttributes(true)")
		$htmlMin->doRemoveValueFromEmptyInput();              // remove 'value=""' from empty <input> (depends on "doOptimizeAttributes(true)")
		$htmlMin->doSortCssClassNames();                      // sort css-class-names, for better gzip results (depends on "doOptimizeAttributes(true)")
		$htmlMin->doSortHtmlAttributes();                     // sort html-attributes, for better gzip results (depends on "doOptimizeAttributes(true)")
		$htmlMin->doRemoveSpacesBetweenTags();                // remove more (aggressive) spaces in the dom (disabled by default)
		$htmlMin->doRemoveOmittedHtmlTags();

		return $htmlMin->minify($htmlTag);
	}
}
