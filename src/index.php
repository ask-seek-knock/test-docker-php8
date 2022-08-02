<?php

define('PROXY_START', microtime(true));

require("vendor/autoload.php");

use Proxy\Config;
use Proxy\Http\Request;
use Proxy\Proxy;

function require_auth() {
	$AUTH_USER = 'pt2';
	$AUTH_PASS = 'pt3';
	$has_supplied_credentials = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));
	$is_not_authenticated = (
		!$has_supplied_credentials ||
		$_SERVER['PHP_AUTH_USER'] != $AUTH_USER ||
		$_SERVER['PHP_AUTH_PW']   != $AUTH_PASS
	);
	if ($is_not_authenticated) {
        header('HTTP/1.1 401 Authorization Required');
		header('WWW-Authenticate: Basic realm="Access denied"');
		exit;
	}
}

function _is_peer($ip) {
    $ips = array('219.144.25', '240e:358:5', '2409:8a70:', '2409:8a55:', '112.46.68.', '2406:840:f', '124.89.86.', '111.20.101', '124.23.133.', '127.0.0.1', );
    return in_array($ip, $ips);
}

if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
    if (!_is_peer(substr($_SERVER['HTTP_CF_CONNECTING_IP'], 0, 10))) {
        echo 'cf ip';
        //header('HTTP/1.1 451 Forbidden');
        exit;
    }
} else {
    if (!_is_peer(substr($_SERVER['REMOTE_ADDR'], 0, 10))) {
        //header('HTTP/1.1 404 Forbidden');
        echo 'remote ip';
        exit;
    }
}

if (!strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox')) {
        //header('HTTP/1.1 400 Bad Request');
    echo 'user agent';
    exit;
}

//require_auth();
// start the session
if (Config::get('session_enable')) {
    session_start();
}

// load config...
Config::load('./config.php');

// custom config file to be written to by a bash script or something
Config::load('./custom_config.php');

if (!Config::get('app_key')) {
    die("app_key inside config.php cannot be empty!");
}

if (!function_exists('curl_version')) {
    die("cURL extension is not loaded!");
}


if (Config::get('session_enable')) {
    session_write_close();
}

// form submit in progress...
if (isset($_POST['url'])) {

    $url = $_POST['url'];
    $url = add_http($url);

    header("HTTP/1.1 302 Found");
    header('Location: ' . proxify_url($url));
    exit;

} elseif (!isset($_GET['q'])) {

    // must be at homepage - should we redirect somewhere else?
    if (Config::get('index_redirect')) {

        // redirect to...
        header("HTTP/1.1 302 Found");
        header("Location: " . Config::get('index_redirect'));

    } else {
        echo render_template("./templates/main.php", array('version' => Proxy::VERSION));
    }

    exit;
}

// decode q parameter to get the real URL
$url = url_decrypt($_GET['q']);

$proxy = new Proxy();

// load plugins
foreach (Config::get('plugins', array()) as $plugin) {

    $plugin_class = $plugin . 'Plugin';

    if (file_exists('./plugins/' . $plugin_class . '.php')) {

        // use user plugin from /plugins/
        require_once('./plugins/' . $plugin_class . '.php');

    } elseif (class_exists('\\Proxy\\Plugin\\' . $plugin_class)) {

        // does the native plugin from php-proxy package with such name exist?
        $plugin_class = '\\Proxy\\Plugin\\' . $plugin_class;
    }

    // otherwise plugin_class better be loaded already through composer.json and match namespace exactly \\Vendor\\Plugin\\SuperPlugin
    // $proxy->getEventDispatcher()->addSubscriber(new $plugin_class());

    $proxy->addSubscriber(new $plugin_class());
}

try {

    // request sent to index.php
    $request = Request::createFromGlobals();

    // remove all GET parameters such as ?q=
    $request->get->clear();

    // forward it to some other URL
    $response = $proxy->forward($request, $url);

    // if that was a streaming response, then everything was already sent and script will be killed before it even reaches this line
    $response->send();

} catch (Exception $ex) {

    // if the site is on server2.proxy.com then you may wish to redirect it back to proxy.com
    if (Config::get("error_redirect")) {

        $url = render_string(Config::get("error_redirect"), array(
            'error_msg' => rawurlencode($ex->getMessage())
        ));

        // Cannot modify header information - headers already sent
        header("HTTP/1.1 302 Found");
        header("Location: {$url}");

    } else {

        echo render_template("./templates/main.php", array(
            'url' => $url,
            'error_msg' => $ex->getMessage(),
            'version' => Proxy::VERSION
        ));

    }
}
