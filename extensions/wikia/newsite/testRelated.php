<?php

/**
 * test for related hosts
 */

ini_set( "include_path", dirname(__FILE__)."/../../../maintenance/" );
require_once( "commandLine.inc" );
require_once( "$IP/extensions/wikia/newsite/NeueWebsiteJob.php" );

$job = new NeueWebsiteJob(
	Title::newFromText( "Eloy.wikia", NS_USER ),
	array( "domain" => "kofeina.net", "key" => "kofeina.net", "test" => true ) );

$job->run();

$job = new NeueWebsiteJob(
	Title::newFromText( "Eloy.wikia", NS_USER ),
	array( "domain" => "en.wikipedia.org", "key" => "en.wikipedia.org", "test" => true ) );

$job->run();
