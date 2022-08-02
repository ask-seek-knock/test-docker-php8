<?php

// all possible options will be stored
$config = array();

// a unique key that identifies this application - DO NOT LEAVE THIS EMPTY!
$config['app_key'] = '1164825dd9c1356e91f25851f6e72e57';

// a secret key to be used during encryption
$config['encryption_key'] = '';


$config['url_mode'] = 0;

// plugins to load - plugins will be loaded in this exact order as in array
$config['plugins'] = array(
	'HeaderRewrite',
	'Stream',
	// ^^ do not disable any of the plugins above
	'Cookie',
	'Proxify',
	'UrlForm',
	// site specific plugins below
	'Youtube',
);

// additional curl options to go with each request
$config['curl'] = array(
	// CURLOPT_PROXY => '',
	// CURLOPT_CONNECTTIMEOUT => 5
);


// $config['replace_icon'] = 'icon_url';

// this better be here other Config::load fails
return $config;

?>
