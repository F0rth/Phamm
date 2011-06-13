<?php
/*
* Common functions
*
* @package Phamm
*/

/*
* Phamm - http://www.phamm.org - <team@phamm.org>
* Copyright (C) 2004,2008 Alessandro De Zorzi and Mirko Grava
*
* This file is part of Phamm.
*  
* This program is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* 
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/**
* Refresh the page
* 
* Initial code Copyright (C) 2004, Bruno Pelaia (PFLAdmin)
*  Redirect a client to the specified url.
*  The 'url' should be HTTP/1.1 compliant: it should start with the
*  protocol specification.
*  The 'force_meta' and 'seconds' parameter are optional.
*  The 'force_meta' behaviour is assumed if headers have been already sent.
*
*
* @param string $url The page to redirect to
* @param bool $force_meta Whenever you need an HTML-meta tag or not
* @param int $seconds The amount of seconds to wait before
**/

function refresh ($url, $force_meta = false, $seconds = 0)
{
    // Check the protocol
    if (isset($_SERVER['HTTPS']))
        $PROTOCOL = 'https';
    else
        $PROTOCOL = 'http';

    // HTTP/1.1 requires an absolute URI
    $uri = ereg ( '^http', $url ) ? $url : "$PROTOCOL://".
           $_SERVER['HTTP_HOST'];

    // Server Root Check
    if(dirname ($_SERVER['PHP_SELF']) != '' && dirname ($_SERVER['PHP_SELF']) != '/')
        $uri .= dirname($_SERVER['PHP_SELF']);

    $uri .= '/'.$url;

    if ( $force_meta || headers_sent () )
    {
        ob_start();
        ob_clean ();
        echo "<html>
        <head>
        <META HTTP-EQUIV=\"Refresh\" CONTENT=\"$seconds; url=$uri\">
        </head>
        <body>
        <p>";
	echo _("You will be automatically redirected to a new page within a few seconds.<br/> If this does not happen, please follow the link:")." ".'<a href="'.$uri.'">'._("click here").'</a>';
	echo "</p>
        </body>
        </html>";
        ob_end_flush ();
    }

    else
    {
        ob_end_clean ();
        header ( "Location: ${uri}" );
    }
    exit;
}


/**
* Return PHP array from XML file
*
* original code from
* mmustafa at vsnl dot com http://php.net/xml_parser_create
*
* @param mixed $file
* @return array $params
**/

function xml2array ($file)
{
    $xml_parser = xml_parser_create();

    if (!($fp = fopen($file, "r"))) {
        die("could not open XML input");
    }

    $data = fread($fp, filesize($file));
    fclose($fp);

    xml_parse_into_struct($xml_parser, $data, $vals, $index);
    xml_parser_free($xml_parser);

    $params = array();
    $xml_elem = array();
    $level = array();

    foreach ($vals as $xml_elem) {
        if ($xml_elem['type'] == 'open') {
            if (array_key_exists('attributes',$xml_elem))
            {
                $extra = array_values($xml_elem['attributes']);
                $level[$xml_elem['level']] = $extra[0];
            }
            else
            {
                $level[$xml_elem['level']] = $xml_elem['tag'];
            }
        }
        if ($xml_elem['type'] == 'complete')
        {
            $start_level = 1;
            $php_stmt = '$params';

            while($start_level < $xml_elem['level'])
            {
                $php_stmt .= '[$level['.$start_level.']]';
                $start_level++;
            }

            $php_stmt .= '[$xml_elem[\'tag\']] = isset($xml_elem[\'value\']) ?
                         $xml_elem[\'value\'] : "";';
            eval($php_stmt);
        }
    }

    return $params;

}


/**
* Generic LDAP Single-level search
*
* @author Alessandro De Zorzi <adezorzi@rhx.it>
*
* @todo add attrsonly, sizelimit, timelimit
*
* @param string $base_dn
* @param string $filter
* @param array $attributes
* @param array $attributes
* @param array $short Sort Attributes
**/

function phamm_list ($base_dn,$filter,$attributes=null,$sort=null)
{
    global $connect;

    // Do a LDAP search
    if ($attributes)
	$search = ldap_list($connect,$base_dn,$filter,$attributes);
    else
	$search = ldap_list($connect,$base_dn,$filter);

    // Order the results if possible
    if (version_compare(phpversion(), "4.2.0", ">="))
        ldap_sort($connect,$search,$sort);

    // Get entries
    $entries = ldap_get_entries($connect, $search);

    // Free the memory
    ldap_free_result($search);

    // Return the entry
    return $entries;
}


/**
* Generic LDAP search
*
* @author Alessandro De Zorzi <adezorzi@rhx.it>
*
* @todo add attrsonly, sizelimit, timelimit
*
* @param string $base_dn
* @param string $filter
* @param array $attributes
* @return array $entries
**/

function phamm_search ($base_dn,$filter,$attributes=null,$sort=null)
{
    global $connect;

    // Do a LDAP search
    if (isset($attributes))
        $search = ldap_search($connect,$base_dn,$filter,$attributes);
    else
        $search = ldap_search($connect,$base_dn,$filter);

    // Order the results if possible
    if (version_compare(phpversion(), "4.2.0", ">="))
        ldap_sort($connect, $search,$sort);

    // Get entries
    $entries = ldap_get_entries($connect, $search);

    // Free the memory
    ldap_free_result($search);

    // Return the entry
    return $entries;
}


/**
* Create new LDAP entry
*
* @author Alessandro De Zorzi <adezorzi@rhx.it>
*
* @param string $dn
* @param array $entry The attributes info
* @return bool $r
**/

function phamm_add ($dn,$entry)
{
    global $connect;

    $r = ldap_add ($connect, $dn, $entry);

    return $r;
}


/**
* Modify a LDAP entry
*
* @author Alessandro De Zorzi <adezorzi@rhx.it>
*
* @param string $dn
* @param array $entry The attributes info
* @return bool $r
**/

function phamm_modify ($dn,$entry)
{
    global $connect;

    $r = ldap_modify ($connect, $dn, $entry );

    return $r;
}


/**
* Check if password do not match and...
*
* @param $password1
* @param $password2
* @return bool
**/

function wrong_pw ($password1,$password2,$length=PASSWORD_MIN_LENGHT)
{
    if ( $password1 != $password2 )
        $error_msg = _("Passwords don't match!");

    elseif ( strlen($password1) < $length )
	$error_msg = _("Password too short!");

    elseif ( !check_syntax ('password',$password1) && $length > 0)
	$error_msg = _("Password contains special chars");

    if (isset($error_msg))
        return $error_msg;

    return false;
}


/**
* Hashes a password and returns the hash based on the specified enc_type. 
*
* Original function from phpLDAPadmin project.
*
* @author The phpLDAPadmin development team
*
* @param string $password_clear The password to hash in clear text.
* @constant string $enc_type Standard LDAP encryption type which must be one of
*          crypt, md5 or clear.
* @return string The hashed password.
*/

function password_hash($password_clear)
{
    $enc_type = strtolower(ENC_TYPE);

    switch($enc_type)
    {
    case 'crypt':
        $password_hash = '{CRYPT}'.crypt($password_clear);
        break;

    case 'md5':
	$password_hash = '';
        $md5_hash = md5($password_clear);
	for ( $i = 0; $i < 32; $i += 2 )
	    $password_hash .= chr( hexdec( $md5_hash{ $i + 1 } ) + hexdec( $md5_hash{ $i } ) * 16 );
	$password_hash = '{MD5}'.base64_encode($password_hash);
        break;

    case 'clear':
        $password_hash = $password_clear;
        break;

    default:
        $password_hash = '{CRYPT}'.crypt($password_clear);
        break;
    }

    return $password_hash;
}


/**
* Get the values of a DN
*
* @author Alessandro De Zorzi <adezorzi@rhx.it>
*
* @param string $dn
* @param string $filter
* @return array $results
**/

function self_values ($dn, $filter="(cn=*)")
{
    global $connect;

    $search = ldap_search($connect,$dn,$filter);

    $results = ldap_get_entries($connect, $search);

    return $results;
}


/**
* Purge empty values
*
* @param array $data
* @param array $attribute
* @return array $res
**/

function purge_empty_values ($data,$attribute=null)
{
    // Empty array
    $res = array();

    if (is_array($data))
    {
	foreach ($data as $key => $val)

	if ($val)
	{
	    if (isset($attribute))
	    {
		// multiplies
		if (isset($attribute[$key]["MULTIPLIER"]))
		    $val = ( $val * $attribute[$key]["MULTIPLIER"] );

		// Append suffix
		if (isset($attribute[$key]["SUFFIX"]))
		    $val = $val.$attribute[$key]["SUFFIX"];
	    }

	    if (is_array($val))
		$res[$key] = $val;
	    else
		$res[$key] = strip_tags($val);
	}
    }

    return $res;
}

/**
* Add new attribute (multiple)
*
* @author Alessandro De Zorzi <adezorzi@rhx.it>
*
* @param string $dn
* @param array $entry The attributes info
* @return bool $r
**/

function phamm_mod_add ($dn,$entry)
{
    global $connect;

    $r = ldap_mod_add ($connect, $dn, $entry);

    return $r;
}

/**
* Delete attribute (multiple)
*
* @author Alessandro De Zorzi <adezorzi@rhx.it>
*
* @param string $dn
* @param array $entry The attributes info
* @return bool $r
**/

function phamm_mod_del ($dn,$entry)
{
    global $connect;

    $r = ldap_mod_del ($connect, $dn, $entry);

    return $r;
}

/**
* Delete LDAP entry recursive
*
* @author gabriel at hrz dot uni-marburg dot de
* http://it2.php.net/manual/it/function.ldap-delete.php
* 
* @param $ds
* @param string $dn The DN
* @param bool $recursive
* @return bool
**/

function phamm_ldap_delete($dn,$recursive=false)
{
    global $connect;

   if($recursive == false)
   {
       return(ldap_delete($connect,$dn));
   }
   else
   {
       //searching for sub entries
       $sr=ldap_list($connect,$dn,"ObjectClass=*",array(""));
       $info = ldap_get_entries($connect, $sr);
       for($i=0;$i<$info['count'];$i++)
	   {
           //deleting recursively sub entries
           $result=myldap_delete($connect,$info[$i]['dn'],$recursive);
           if(!$result)
		   {
               //return result code, if delete fails
               return($result);
           }
       }
       return(ldap_delete($connect,$dn));
   }
}

/**
* Simple LDAP error
**/

function phamm_error ()
{
    global $connect;

    return _("LDAP Error: ").ldap_error($connect).' ('._("Code ").ldap_errno($connect).')';
}


/**
* Various syntax check (IP address, domain, email address...)
*
* @author Alessandro De Zorzi <adezorzi@rhx.it>
* @todo Check if IP 0 < number <255
*
* @param string $type The kind of data
* @param string $arg The value
* @param int $length The min length of string
* @todo name
* @return bool
**/

function check_syntax ($type,$arg,$length="0")
{
    if (strlen($arg) < $length)
    {
        return false;
    }

    // IP Address
    if ($type == 'ip')
    {
        if (!ereg ("^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})$", $arg))
        {
            return FALSE;
        }

        /*$numbers = explode('.',$arg);

        foreach ($numbers as $number)
        {
        	if ($number > 255)
        	return FALSE;
        } */

        else
        {
            return TRUE;
        }
    }


    // DOMAIN
    elseif ($type == 'domain')
    {
        if (!eregi("^([0-9a-z][0-9a-z-]+\.)+[a-z]{2,7}$", $arg))
        {
            return FALSE;
        }

        else
        {
            return TRUE;
        }
    }

    // ALIAS and ACCOUNT
    elseif ($type == 'account')
    {
        if (!eregi("^[\._a-z0-9-]+$", $arg))
        {
            return FALSE;
        }

        else
        {
            return TRUE;
        }
    }

    // Password
    elseif ($type == 'password')
    {
        if (!eregi("^[\._a-z0-9-]+$", $arg))
            return false;

        return true;
    }

    // Email
    elseif ($type == 'email')
    {
        if (!eregi("^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,4}$", $arg))
            return false;

        return true;

    }

    // Name
    elseif ($type == 'name')
    {
        return true;
    }
}


/**
* Open the LDAP connection
*
* @author Alessandro De Zorzi <adezorzi AT rhx DOT it>
**/

function phamm_ldap_connect ()
{
    // Open LDAP connection to server
    $connect = @ldap_connect(LDAP_HOST_NAME,LDAP_PORT)
               or die ("LDAP connection Failed!");
    
    ldap_set_option($connect,LDAP_OPT_PROTOCOL_VERSION,LDAP_PROTOCOL_VERSION);

    // Start TLS session
    if (LDAP_TLS == 1)
    {
	@ldap_start_tls($connect)
	    or die ("Could not start TLS. Please check your LDAP server configuration.");
    }

    return $connect;
}

?>
