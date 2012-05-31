<?php

/*
	Site configuration file
*/

error_reporting(-1);
ini_set('display_errors', 1);


/**
 * If the framework hasn't been installed yet, set to false.
 */
$mvc->config['installed'] = true;

/**
 * The current timezone of the system.
 */
$mvc->config['timezone'] = 'Europe/Stockholm';

/**
 * The character encoding the system uses.
 */
$mvc->config['character_encoding'] = 'iso-8859-1';

/**
 * The website language.
 */
$mvc->config['language'] = 'en';

/**
 * The available controllers:
 * 	enabled			= Is the controller available?
 * 	class			= The class connected to the controller.
 */
$mvc->config['controllers'] = array(
	'acp' => array(
		'enabled' => true,
		'class' => 'CCAdminControlPanel',
		),
	'blog' => array(
		'enabled' => true,
		'class' => 'CCBlog',
		),
	'configure' => array(
		'enabled' => true,
		'class' => 'CCConfigure',
		),
	'content' => array(
		'enabled' => true,
		'class' => 'CCContent',
		),
	'guestbook' => array(
		'enabled' => true,
		'class' => 'CCGuestbook',
		),
	'index' => array(
		'enabled' => false,
		'class' => 'CCIndex',
		),
	'me' => array(
		'enabled' => false,
		'class' => 'CCMe',
		),
	'modules' => array(
		'enabled' => true,
		'class' => 'CCModules',
		),
	'ngaming' => array(
		'enabled' => true,
		'class' => 'CCNGaming',
		),
	'news' => array(
		'enabled' => true,
		'class' => 'CCNewsfeed',
		),
	'page' => array(
		'enabled' => true,
		'class' => 'CCPage',
		),
	'prospects' => array(
		'enabled' => true,
		'class' => 'CCProspects',
		),
	'source' => array(
		'enabled' => true,
		'class' => 'CCSource',
		),
	'theme' => array(
		'enabled' => true,
		'class' => 'CCTheme',
		),
	'user' => array(
		'enabled' => true,
		'class' => 'CCUser',
		),
	);


/**
 * The extra routes:
 * 	enabled			= Is the route available?
 * 	url				= The url it uses.
 */
$mvc->config['routing'] = array(
	'index' => array(
		'enabled' => true,
		'url' => 'news',
		),
	'BBCode' => array(
		'enabled' => true,
		'url' => 'page/view/1',
		),
	);


/**
 * The theme configuration:
 * 	path			= The local path to the theme.
 * 	parent			= The local path to the parent theme.
 * 	template_file	= The template-file used.
 * 	regions			= Regions available to output in.
 * 	data			= Website data.
 * 	themes			= List of available themes.
 */
$mvc->config['theme'] = array(
	'path' => '/site/themes/ngaming-red',
	'parent' => 'themes/grid',
	'template_file' => 'index.tpl.php',
	'regions' => array(
		'flash',
		'featured-first',
		'featured-middle',
		'featured-last',
		'primary',
		'sidebar',
		'triptych-first',
		'triptych-middle',
		'triptych-last',
		'footer-column-one',
		'footer-column-two',
		'footer-column-three',
		'footer-column-four',
		'footer',
		),
	'data' => array(
		'header' => 'NGaming',
		'slogan' => 'The killer clan',
		'favicon' => '/logo.png',
		'logo' => '/site/data/logo.png',
		'logo_width' => 80,
		'logo_height' => 80,
		'footer' => '&copy;NocturnalGaming by Marcus Olsson',
		),
	'themes' => array(
		'core' => array(
			'name' => 'core',
			'file' => 'default.tpl.php',
			'path' => '/themes/core',
			'parent' => 'themes/core',
			),
		'metal' => array(
			'name' => 'metal',
			'file' => 'default.tpl.php',
			'path' => '/themes/metal',
			'parent' => 'themes/metal',
			),
		'grid' => array(
			'name' => 'grid',
			'file' => 'index.tpl.php',
			'path' => '/themes/grid',
			'parent' => 'themes/grid',
			),
		'ngaming' => array(
			'name' => 'ngaming',
			'file' => 'index.tpl.php',
			'path' => '/site/themes/ngaming',
			'parent' => 'themes/grid',
			),
		'ngaming-red' => array(
			'name' => 'ngaming-red',
			'file' => 'index.tpl.php',
			'path' => '/site/themes/ngaming-red',
			'parent' => 'themes/grid',
			),
		),
	);


/**
 * Set a base_url to use another than the default calculated.
 */
$mvc->config['base_url'] = '';

/**
 * The url-type that will be created with all the create_url()-functions.
 * 	0				= default: index.php/controller/method/arg1/arg2/arg3
 * 	1				= clean: controller/method/arg1/arg2/arg3
 * 	2				= q-string: index.php?q=controller/method/arg1/arg2/arg3
 */
$mvc->config['url_type'] = 1;

/**
 * Database settings
 * 	active		- Is the connection active?
 * 	dsn			- Unavaliable
 * 	host		- hostname
 * 	user		- username for db
 * 	password	- password for user
 * 	db			- database to access
 * 	dbDriver	- Driver for sql
 * 		- available drivers:
 * 			Mysqli
 */
$mvc->config['database'] = array(
	'active' => true,
	'dsn' => '',
	'host' => '',
	'user' => '',
	'password' => '',
	'db' => '',
	'dbDriver' => 'Mysqli',
	'drivers' => array(
		'Mysqli',
		),
	);


/**
 * Debug information messages:
 * 	false = don't show.
 * 	true = show.
 */
$mvc->config['debugEnabled'] = true;
$mvc->config['debug'] = array(
	'mvc' => false,
	'db-num-queries' => true,
	'db-queries' => true,
	);


/**
 * The content of the navbar.
 */
$mvc->config['navbar'] = array(
	'news' => array(
		'text' => 'News',
		'url' => 'news',
		),
	'prospects' => array(
		'text' => 'Prospects',
		'url' => 'prospects',
		),
	'ngaming' => array(
		'text' => 'Us',
		'url' => 'ngaming',
		),
	'source' => array(
		'text' => 'Source',
		'url' => 'source',
		),
	);


/**
 * The session-variables
 */
$mvc->config['session_key'] = 'NGaming';
$mvc->config['session_name'] = 'localhost';

/**
 * CMUser-Admin
 * 	name		- Admin name
 * 	password	- Admin password
 */
$mvc->config['CMUser-Admin'] = array(
	'name' => 'root',
	'password' => 'root',
	'email' => 'test@example.com',
	'acronym' => 'root',
	);


/**
 * CMUser-Groups
 * 	Do not touch unless you know what you are doing.
 */
$mvc->config['CMUser-Groups'] = array(
	'admin' => array(
		'acronym' => 'admin',
		'name' => 'The Administrator Group',
		),
	'user' => array(
		'acronym' => 'user',
		'name' => 'The User Group',
		),
	'prospect' => array(
		'acronym' => 'prospect',
		'name' => 'The Prospect Group',
		),
	);


/**
 * Allow or disallow creation of new user accounts.
 */
$mvc->config['create_new_users'] = true;

/**
 * The content settings.
 * 	types			= The types of content.
 * 	filter			= The filter.
 */
$mvc->config['content'] = array(
	'types' => array(
		'page' => 'page',
		'post' => 'post',
		),
	'filter' => array(
		'plain' => 'plain',
		'html' => 'html',
		'bbcode' => 'bbcode',
		),
	);


/**
 * Mainly comments for the config file.
 * 	config.comments.php			<- The one updated by the creator(do not edit, please ^^)
 * 	site.config.comments.php	<- The user comment file
 */
$mvc->config['commentfiles'] = array(
	'/config.comments.php',
	'/site.config.comments.php',
	);