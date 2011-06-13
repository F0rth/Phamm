<?php
/*
* Phamm functions
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

/*
* @author Alessandro De Zorzi <adezorzi@rhx.it>
*
* @param string $date_format
* @return string
**/

function phamm_date($date_format)
{
    switch ($date_format)
    {
    case "test" :
            // YYYY-MM-DD
            return date(Ymd);
        break;

    default :
        // YYYY-MM-DD
        return date(Ymd);
        break;
    }
}

/**
* Set the PHP ERROR_LEVEL
* 
* @package Phamm
* @author Alessandro De Zorzi <adezorzi@rhx.it>
* 
* @constant int ERROR_LEVEL
* @return mixed
**/

function phamm_php_error_level ()
{
    // Set PHP error level one of 0,1,2,10
    if (ERROR_LEVEL == 0)
    {
        error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    }

    elseif (ERROR_LEVEL == 1)
    {
        error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    }

    elseif (ERROR_LEVEL == 2)
    {
        error_reporting(E_ALL & ~E_NOTICE);
    }

    if (ERROR_LEVEL == 10)
    {
        error_reporting(E_ALL);
    }
}

/**
* Write a log in to file
*
* TODO see:
* Log the operations in to file
* http://www.w3.org/Daemon/User/Config/Logging.html#common-logfile-format
*
* @package Phamm
* @author Alessandro De Zorzi <adezorzi@rhx.it>
*
* @param string $pn
* @param string $user
* @param string $operation
* @param bool $result
**/

function phamm_log ($pn,$user,$operation,$result)
{
    if (PHAMM_LOG == 1)
    {
	if (!$pn)
	    $pn = 'phamm';

	if ($result)
	    $resultLabel = 'OK';
	else
	    $resultLabel = 'FAILED';

	// Set the file in Append mode
	$logFile = fopen (LOG_FILE,'a');

	// Get time and date
	$day = date('Y'.'-'.'m'.'-'.'d');
	$hour = date ('H'.':'.'i'.':'.'s');
	$IP = $_SERVER["REMOTE_ADDR"];

	// Prepare the log string
	$log= "$IP - $user [$day $hour] \"$pn : $operation\" $resultLabel\n";

	// Write the log in to file
	fwrite ($logFile,"$log");

	// Close the file
	fclose ($logFile);
    }

    return true;
}

/**
* Set Phamm var
*
* @param string $name The variable name
* @param array $values Possible values
* @param string $methods @todo
* @param int $level
* @return mixed
**/

function phamm_set_var($name, $values=null, $methods=null, $level=0)
{
    if (isset($_GET[$name]))
    {
        if ($_GET[$name] == 'NONE')
            $_SESSION["phamm"][$name] = null;
        else
            $_SESSION["phamm"][$name] = $_GET[$name];
    }
    elseif (isset($_POST[$name]))
	$_SESSION["phamm"][$name] = $_POST[$name];

    // Prevent non wanted values
    if (is_array($values) && !in_array($_SESSION["phamm"]["$name"],$values))
        return null;

    if (isset($_SESSION["phamm"][$name]))
        return $_SESSION["phamm"][$name];

    return null;
}

function phamm_print_message ($class,$message,$newline=false)
{
    switch ($class) :
	
	case "error" :
	    $msg = _('Error: ').$message;
	break;
	
	case "warning" :
	    $msg = _('Warning: ').$message;
	break;
	
	default :
	    $msg = $message;
	break;

    endswitch;

    echo '<div class="'.$class.'">'.$msg.'</div>';

    if ($newline)
        echo '<br/>';
}

function phamm_print_xhtml ($tag)
{
    echo $tag;
}

/*
* Execute a command to multiple accounts/domains
*
* @param string $command The command
* @param array $values Lists of accounts/domains
*/

function group_actions ($command,$values)
{
    // Set initial return value
    $r = false;

    $ga = explode(';',$command);

    $mode = $ga[0];

    switch ($mode) :

    case "account" :

	foreach ($values as $key => $value)
        {
            // key contains mail
            $key1 = explode ('@',$key);

            // Create right DN
            $dn = 'mail='.$key.',vd='.$key1[1].','.LDAP_BASE;
	    
	    // Pre-load values
	    $self_values = self_values ($dn, $filter="(objectClass=*)");

            // (string) needed for TRUE and FALSE not real boolean...
            $entry[$ga[1]] = (string)$ga[2];
	    
	    $is_alias = (in_array('VirtualMailAlias',($self_values[0]["objectclass"])) ? true : false);
    
	    // Delete immediate if VirtualMailAlias
	    if (isset($entry["delete"]) && $is_alias)
		$r = phamm_ldap_delete($dn,$recursive=false);
	    // Change single value
	    else
		$r = phamm_modify ($dn,$entry);
        }

    break;

case "domain" :

    foreach ($values as $key => $value)
    {
        // Create right DN
        // $dn = 'cn=postmaster,vd='.$key.','.LDAP_BASE;
        $dn = 'vd='.$key.','.LDAP_BASE;

        // (string) needed for TRUE and FALSE not real boolean...
        $entry[$ga[1]]		= (string)$ga[2];

        // Change single value
        $r = phamm_modify ($dn,$entry);
    }

    break;

case "postmaster" :

    foreach ($values as $key => $value)
    {
        // Create right DN
        // $dn = 'cn=postmaster,vd='.$key.','.LDAP_BASE;
        $dn = 'cn=postmaster,vd='.$key.','.LDAP_BASE;

        // (string) needed for TRUE and FALSE not real boolean...
        $entry[$ga[1]]		= (string)$ga[2];

        // Change single value
        $r = phamm_modify ($dn,$entry);
    }

    break;

    endswitch;

    return $r;
}


?>
