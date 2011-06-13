<?php
/*
 * Phamm - http://www.phamm.org - <team AT phamm DOT org>
 * Copyright (C) 2004,2008 Alessandro De Zorzi and Mirko Grava
 * Project sponsored by RHX Studio Snc - www.rhx.it
 *  
 * Phamm is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Phamm is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/**
* The main Phamm config file
*
* @package Phamm
* @subpackage configuration
* @author Alessandro De Zorzi <adezorzi AT rhx DOT it>
**/

// *============================*
// *=== LDAP Server Settings ===*
// *============================*

// The server address (To use ldapssl change to ldaps://localhost)
define ('LDAP_HOST_NAME','localhost');

// The protocol version [2,3]
define ('LDAP_PROTOCOL_VERSION','3');

// The server port (To use ldapssl change to 636)
define ('LDAP_PORT','389');

// Set LDAP_TLS to 1 if you want to use TLS
define ('LDAP_TLS',0);

// The container
define ('SUFFIX','dc=example,dc=tld');

// The admin bind dn (could be rootdn)
define ('BINDDN','cn=admin,dc=example,dc=tld');

// The Phamm container
define ('LDAP_BASE','o=hosting,dc=example,dc=tld');


// *============================*
// *===   Layout Settings    ===*
// *============================*

// Page title
define('ORG_TITLE','Phamm');

// URL
define('ORG_URL','http://www.phamm.org');

// Logo
define('ORG_LOGO', './img/phamm_100.png');

// CSS Style
$style = 'phamm';

// Default language
define ('DEFAULT_LANGUAGE','en_GB');

// The languages available
$supported_languages = array();
$supported_languages["de_DE"] = "Deutsch";
$supported_languages["en_GB"] = "English";
$supported_languages["es_ES"] = "Español";
$supported_languages["fr_FR"] = "French";
$supported_languages["hu_HU"] = "Hungarian";
$supported_languages["it_IT"] = "Italiano";
$supported_languages["pl_PL"] = "Polish";
$supported_languages["ru_RU"] = "Russian";
$supported_languages["vi_VN"] = "Tiếng Việt"; // Vietnamese
$supported_languages["da_DK"] = "Dansk"; // Danish
// $supported_languages["ll_CC"] = "Your language here";

// This TLDs menu
$tld = array();
$tld[] = ".com";
$tld[] = ".org";
$tld[] = ".net";
// $tld[] = ".biz";
// $tld[] = ".info";
// $tld[] = ".eu";
// $tld[] = ".it";
// $tld[] = ".fr";
// $tld[] = ".de";


// *============================*
// *===   Plugins Settings   ===*
// *============================*

// The default plugin
define ('DEFAULT_PLUGIN','mail');

// This array contains the active plugins
// NOTE the display order reflect this order
$plugins = array();
$plugins[] = "mail";
$plugins[] = "alias";
//$plugins[] = "dns";
//$plugins[] = "ftp";
//$plugins[] = "proxy";
//$plugins[] = "radius";
//$plugins[] = "radius_stats";
//$plugins[] = "rates";
//$plugins[] = "person";
//$plugins[] = "jabber";
//$plugins[] = "davical";

// Account can be mail OR alias
$plugins_exclusion = array("mail","alias");


// *============================*
// *===   System Settings    ===*
// *============================*

// Phamm Version (+ indicate a CVS version)
define ('VERSION','0.5.18+cvs');

// Useful if you want hide the version [0,1]
define ('HIDE_VERSION',0);

// Useful if you wish force SSL through PHP [0,1]
define ('FORCE_SSL',0);

// Min password length
define ('PASSWORD_MIN_LENGHT',3);

// Seconds after refresh page
define ('REFRESH_TIME',1);

// A Domain administrator (example: postmaster)
define ('PHAMM_DOMAIN_ADMIN_NAME','postmaster');

// Welcome message
define ('SEND_WELCOME',1);
$welcome_msg = '../welcome_message.txt';
$welcome_subject = 'Welcome!';
$welcome_sender = 'postmaster@%domain%';
$welcome_bcc = 'postmaster@example.tld';

// *============================*
// *===  Advanced Settings   ===*
// *============================*

// Debug level [0,1]
define ('DEBUG',0);

// PHP Error Level [0,1,2,10]
define ('ERROR_LEVEL',2);

// Log level 0->don't log [0,1,2]
define ('PHAMM_LOG',0);

// Log file path
define ('LOG_FILE','/var/log/phamm.log');

// Standard LDAP encryption type [CRYPT,MD5,SSHA,CLEAR]
define ('ENC_TYPE','SSHA');

// Permit login without @domain (use it with carefull)
// define ('DEFAULT_DOMAIN','example.tld');

?>
