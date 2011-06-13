<?php
/***************************************************************************
*                                                                          *
*            These apply everywhere and will need setting                  *
*                                                                          *
***************************************************************************/

/****************************
********* Mandatory *********
*****************************/

/**
* Ex : $c->pg_connect[] = 'dbname=davical port=5432 user=general'
* The application will attempt to
* connect to the database, successively applying connection parameters from
* the array in $c->pg_connect.
* used in the web interface but also the caldav Server
*/
$c->pg_connect[] = "dbname=davical user=davical_app";
// $c->pg_connect[] = "dbname=davical user=davical_app port=5433 host=somehost password=mypass";


/****************************
********* Desirable *********
*****************************/

/**
* default : DAViCal CalDAV Server
* Is used to specify the authentication realm of the server, as well as
* being used as a name to display in various places.
*/
$c->system_name = "DAViCal CalDAV Server @ example.org";

/**
* default is false
* If true, then TODO requested  from someone other than the admmin or owner
* of a calendar will not get any answer.  Often these todo are only relevant
* to the owner, but in some shared calendar situations they might not be in
* which case you should let this default to false.
*/
$c->hide_TODO = true;

/**
* The default is false for backward compatibility
* If true, then calendars accessed via WebDAV will only be readonly.  Any
* changes to them must be applied via CalDAV.
*
* You may want to set this to false during your initial setup to make it
* easier for people to PUT whole calendars as part of the conversion of
* their data.  After this it is recommended to turn it off so that clients
* which have been misconfigured are readily identifiable.
*/
$c->readonly_webdav_collections = true;

/***************************************************************************
*                                                                          *
*                         ADMIN web Interface                              *
*                                                                          *
***************************************************************************/
/**
* Displayed on the login page to indicate who you should ask if you have
* problems logging on. Also for the "From" of the email sent when a user
* has lost his password and click on the login page
* on "Help! I've forgotten my password"
*/
$c->admin_email ='webmaster@example.org';

/**
* default=true
* if true the admin web interface will
* have link on name to access details
* <p>The "enable_row_linking" option controls whether javascript is used
* to make the entire row clickable in browse lists in the administration
* pages.  Since this doesn't work in Konqueror you may want to set this
* to false if you expect people to be using Konqueror with the DAViCal
* administration pages.</p>
*/
$c->enable_row_linking = true;

/**
* These should be an array of style sheets with a path specified relative
* to the root directory.  Used for overriding display styles in the admin
* interface.
* e.g. : $c->local_styles = array('/css/my.css');
**/
// $c->local_styles = array();
// $c->print_styles = array();


/***************************************************************************
*                                                                          *
*                         Caldav Server                                    *
*                                                                          *
***************************************************************************/

/**
* The "collections_always_exist" value defines whether a MKCALENDAR
* command is needed to create a calendar collection before calendar
* resources can be stored in it.  You will want to leave this to the
* default (true) if people will be using Evolution or Sunbird /
* Lightning against this because that software does not support the
* creation of calendar collections.
* Default: true
*/
$c->collections_always_exist = true;

/**
* The name of a user's "home" calendar. This will be created for each
* new user.
* Default: 'home'
*/
// $c->home_calendar_name = 'home';

/**
* An array of groups / permissions which should be automatically added
* for each new user created.  This is a crude mechanism which we
* will hopefully manage to work out some better approach for in the
* future.  For now, create an array that looks something like:
*   array( 9 => 'R', 4 => 'A' )
* to create a 'read' relationship to user_no 9 and an 'all' relation
* with user_no 4.
* Default: none
*/
// $c->default_relationships = array();


/**
* An array of fields on the usr record which should be set to specific
* values when the users are created.
* Default: none
*/
// $c->template_usr = array( 'active' => true,
//                           'locale' => 'it_IT',
//                           'date_format_type' => 'E',
//                           'email_ok' => date('Y-m-d')
//                         );


/***************************************************************************
*                                                                          *
*                     External Authentication Sources                      *
*                                                                          *
***************************************************************************/

/**
* Allow specifying another way to control access of the user by authenticating
* him against other drivers such has LDAP (the default is the PgSQL DB)
* $c->authenticate_hook['call'] should be set to the name of the plugin and must
* be a valid function that will be call like this:
*   call_user_func( $c->authenticate_hook['call'], $username, $password )
*
* The login mecanism is made in 2 places:
*  - for the web interface in: index.php that calls RSCDSSession.php that extends
*    Session.php (from AWL libraries)
*  - for the caldav client in: caldav.php that calls BasicAuthSession.php
* Both Session.php and BasicAuthSession.php check against the
* authenticate_hook['call'], although for BasicAuthSession.php this will be for
* each page.  For Session.php this will only occur during login.
*
* $c->authenticate_hook['config'] should be set up with any configuration data
* needed by the authenticate call for the moment used only in awl/inc/AuthPlugins.php
* and he used to authenticate the user should be at least 'password,user_no'
* awl/inc/AuthPlugins.php is a sample file not used by showing what could be
* a hook
*/

/********************************/
/******* Other AWL hook *********/
/********************************/
// require_once('auth-functions.php');
//  $c->authenticate_hook = array(
//      'call'   => 'AuthExternalAwl',
//      'config' => array(
//           // A PgSQL database connection string for the database containing user records
//          'connection' => 'dbname=wrms host=otherhost port=5433 user=general',
//           // Which columns should be fetched from the database
//          'columns'    => "user_no, active, email_ok, joined, last_update AS updated, last_used, username, password, fullname, email",
//           // a WHERE clause to limit the records returned.
//          'where'    => "active AND org_code=7"
//      )
//  );


/********************************/
/*********** LDAP hook **********/
/********************************/
/*
 * For Active Directory go down to the next example.
 */

$c->authenticate_hook['call'] = 'LDAP_check';
$c->authenticate_hook['config'] = array(
    'host' => 'localhost', //host name of your LDAP Server
    'port' => '389',              //port

       /* For the initial bind to be anonymous leave bindDN and passDN
          commented out */
// DN to bind to this server enabling to perform request
    'bindDN'=> 'cn=admin,dc=example,dc=org',
// Password of the previous bindDN to bind to this server enabling to perform request
    'passDN'=> 'PASSWORD',

    'protocolVersion' => '3', //Version of LDAP protocol to use
    'baseDNUsers'=> 'o=hosting,dc=perversia,dc=net', //where to look at valid user
//    'filterUsers' => 'objectClass=CalDavAccount', //filter which must validate a user according to RFC4515, i.e. surrounded by brackets
    'filterUsers' => '(objectClass=CalDavAccount)(CalDavAccess=TRUE)',
//    'baseDNGroups' => 'ou=divisions,dc=tennaxia,dc=net', //not used ATM
//    'filterGroups' => 'objectClass=groupOfUniqueNames', //not used ATM
       /** /!\ "username" should be set and "updated" must be set **/
    'mapping_field' => array("username" => "mail",
                             "updated" => "modifyTimestamp",
                             "fullname" => "mail",
                             "email" => "mail"
                             ), //used to create the user based on his ldap properties
       /** used to set default value for all users, will be overcharged by ldap if defined also in mapping_field **/
//    'default_value' => array("date_format_type" => "E","locale" => "fr_FR"),
       /** foreach key set start and length in the string provided by ldap
           example for openLDAP timestamp : 20070503162215Z **/
    'format_updated'=> array('Y' => array(0,4),'m' => array(4,2),'d'=> array(6,2),'H' => array(8,2),'M'=>array(10,2),'S' => array(12,2)),
//   'startTLS' => 'no',  // Require that TLS is used for LDAP?
             // If ldap_start_tls is not working, it is probably
             // because php wants to validate the server's
             // certificate. Try adding "TLS_REQCERT never" to the
             // ldap configuration file that php uses (e.g. /etc/ldap.conf
             // or /etc/ldap/ldap.conf). Of course, this lessens security!
    'scope' => 'subtree', // Search scope to use, defaults to subtree.
//                           // Allowed values: base, onelevel, subtree.
//
    );
//
include('drivers_ldap.php');

/*
 * Use the following LDAP example if you are using Active Directory
 *
 * You will need to  change host, passDN and DOMAIN in bindDN
 * and baseDNUsers.
 */
//$c->authenticate_hook['call'] = 'LDAP_check';
//$c->authenticate_hook['config'] = array(
//    'host'              => 'ldap://ldap.example.net',
//    'bindDN'            => 'auth@DOMAIN',
//    'passDN'            => 'secret',
//    'baseDNUsers'       => 'dc=DOMAIN,dc=local',
//    'protocolVersion'   => 3,
//    'optReferrals'      => 0,
//    'filterUsers'       => '(&(objectcategory=person)(objectclass=user)(givenname=*))',
//    'mapping_field'     => array("username" => "uid",
//                                 "fullname" => "cn" ,
//                                 "email"    => "mail"),
//    'default_value'     => array("date_format_type" => "E","locale" => "en_NZ"),
//    'format_updated'    => array('Y' => array(0,4),'m' => array(4,2),'d'=> array(6,2),'H' => array(8,2),'M'=>array(10,2),'S' => array(12,2))
//    );
//include('drivers_ldap.php');


/**
* Authentication against PAM using the Squid helper script.
*/
//$c->authenticate_hook = array(
//    'call'   => 'SQUID_PAM_check',
//    'config' =>  array( 'script' => '/usr/bin/pam_auth', 'email_base' => 'example.com' )
//    );
//include('drivers_squid_pam.php');


/**
* The default locale will be "en_NZ";
* If you are in a non-English locale, you can set the default_locale
* configuration to one of the supported locales.
*
* Supported Locales (at present, see: "select * from supported_locales ;" for a full list)
*
* "de_DE", "en_NZ", "es_AR", "fr_FR", "nl_NL", "ru_RU"
*
* If you want locale support you probably know more about configuring it than me, but
* at this stage it should be noted that all translations are UTF-8, and pages are
* served as UTF-8, so you will need to ensure that the UTF-8 versions of these locales
* are supported on your system.
*
* People interested in providing new translations are directed to the Wiki:
*   http://rscds.sourceforge.net/moin/TranslatingRscds
**/
$c->default_locale = "de_DE";

/**
* Default will be $_SERVER['SERVER_NAME'];
* This is used to construct URLs which are passed in the answers to the client.  You may
* want to force this to a specific domain in responses if your system is accessed by
* multiple names, otherwise you probably won't need to change it.
*/
$c->domain_name = "example.org";

/**
* Used as a fallback for the TZID of an event where one is not supplied as part
* of a VEVENT.  The local (server) time zone will be used as a default.
*/
// $c->local_tzid;

/**
* Many people want this, but it may be a security issue for you, so it is
* disabled by default.  If you enable it, then confidential / private events
* will be visible to the 'organizer' or 'attendee' lists.  The reason that
* this becomes a security issue is that this identification needs to be based
* on the user's e-mail address.  The user's e-mail address is generally
* something which they can set, so they could change it to be the address of
* an attendee of a meeting and then would be able to read the meeting.
*
* Without this, the only person who can view/change PRIVATE or CONFIDENTIAL
* events in a calendar is someone with full administrative rights to the calendar
* usually the owner.
*
* If the only person that devious is your sysadmin then you probably already
* enabled this option...
*/
// $c->allow_get_email_visibility = false;
