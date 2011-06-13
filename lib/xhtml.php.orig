<?php
/**
* XHTML Functions
*
* @package Phamm
**/

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

function page_start()
{
    global $action;

    $tag = '<?xml version="1.0" encoding="UTF-8"?>';

    $tag .= '<!DOCTYPE html'."\n";
    $tag .= "\t".'PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"'."\n";
    $tag .= "\t".'"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'."\n";

    $tag .= '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">'."\n";
    $tag .= '<head>'."\n";
    $tag .= "\t".'<title>'.ORG_TITLE;
    if (isset($action))
	$tag .= ' - '.str_replace('_',' ',$action);
    $tag .= '</title>'."\n";
    $tag .= "\t".'<style type="text/css" media="all">@import "./style/phamm/main.css";</style>'."\n";
    $tag .= "\t".'<script type="text/javascript" src="functions.js"></script>'."\n";
    $tag .= '</head>'."\n";
    $tag .= "<body onLoad='disableAutocomplete()'>"."\n";

    return $tag;
}

function page_end()
{
    $tag = '<hr/>';
    $tag .= '<a href="'.ORG_URL.'" target="_blank">';
    $tag .= '<img src="'.ORG_LOGO.'" ';
    $tag .= 'alt="'.ORG_TITLE.'" border="0" /></a>';

    if (HIDE_VERSION != 1)
    {
        $tag .="<font class=\"note\">Phamm";
        $tag .= sprintf(_("Ver. %s"),VERSION);
        $tag .= "</font>";
    }

    $tag .= '</body>'."\n";
    $tag .= '</html>'."\n";

    return $tag;
}

function page_exit()
{
    $tag = '</body>'."\n";
    $tag .= '</html>'."\n";

    return $tag;
}


/**
* Simple login form
**/

function login_form()
{
    if (isset($_POST["login_username"]))
        $login_username = $_POST["login_username"];
    else
        $login_username = null;

    $tag = '<a href="'.ORG_URL.'">'."\n";
    $tag .= "<img src=\"img/phamm_100.png\" alt=\"Phamm logo\" border=\"0\" /></a>"."\n";

    if (HIDE_VERSION != 1)
    {
        $tag .= "<font class=\"note\">";
        $tag .= sprintf(_("Ver. %s"),VERSION);
        $tag .= "</font>"."\n";
    }

    $tag .= "<br/>"."\n";
    $tag .= "<form method=\"post\" action=\"".$_SERVER["PHP_SELF"]."\" name=\"login\">"."\n";
    $tag .= "<input type=\"hidden\" name=\"action\" value=\"login\" />"."\n";
    $tag .= "<table class=\"data\">"."\n";
    $tag .= "<tr><td>"._("Login Name")."</td>"."\n";
    $tag .= "<td><input type=\"text\" size=\"25\" name=\"login_username\" value=\"".$login_username."\" maxlength=\"100\" /></td></tr>"."\n";
    $tag .= "<tr><td>"._("Password")."</td>"."\n";
    $tag .= "<td><input type=\"password\" size=\"25\" name=\"login_password\" value=\"\" maxlength=\"50\" /></td></tr>"."\n";
    $tag .= "</table>"."\n";

    $tag .= "<input type=\"submit\" value=\""._("Login")."\" />"."\n";
    $tag .= "</form>"."\n";

    return $tag;
}

/**
* Set the style name
**/

function style_set()
{
    $style = 'phamm';
    return $style;
}

/**
* Prepare XHTML tag CSS style
**/

function style_load($style)
{
    return true;
}

/**
* Prepare a XHTML domains list (attributes are plugin related)
*
* @param array $vds The domains
* @param array $values The attributes
* @return mixed
**/

function domains_list($vds,$values)
{
    $tag = '<form method="post" action="'.$_SERVER["PHP_SELF"].'" name="domains" id="post-checks">';
    $tag .= '<table class="data">';

    $tag .= '<thead>';
    $tag .= '<tr>';
    $tag .= '<th><input type="checkbox" name="all" onclick="checkAll();" /></th>';
    $tag .= '<th>';
    $tag .= sprintf(ngettext("Domain (%d)", "Domains (%d)", $vds["count"]), $vds["count"]);

    
    $tag .= '</th>'."\n";

    if (isset($values["ATTRIBUTES"]))
    {
        foreach ($values["ATTRIBUTES"] as $key=>$value)
        {
	    // Show only attributes with <table> value
	    if (isset($values["ATTRIBUTES"][$key]["TABLE"]))
	    {
		$label = (isset($values["ATTRIBUTES"][$key]["PRETTYNAME"]) ? $values["ATTRIBUTES"][$key]["PRETTYNAME"] : $key);
		$tag .= '<th>'.gettext($label).'</th>'."\n";
	    }
        }
    }

    $tag .= '</tr>';
    $tag .= '</thead>';

    $tag .= '<tbody>';

    foreach ($vds as $vd)
    {
        $domain = $vd["vd"][0];

        if ($domain) // @todo primo valore null?
        {
            $postmaster = phamm_search ('vd='.$domain.','.LDAP_BASE,'cn=postmaster');
            $domain_values = phamm_search ('vd='.$domain.','.LDAP_BASE,'vd='.$domain);

            $tag .= '<tr class="data">';

            // Group action checkbox
            $tag .= '<td><input type="checkbox" name="domains['.$domain.']" value="1" /></td>';

            // Domain name and link
            $tag .= '<td><a href="?action=domain_view&amp;mail=NONE&amp;domain='.$domain.'&amp;initial=" class="inside">';
            $tag .= $domain;
            $tag .= '</a></td>';

            // Other attributes (current plugin)
            if (isset($values["ATTRIBUTES"]))
            {
                foreach ($values["ATTRIBUTES"] as $key => $value)
                {
		    $reverse = (isset($values["ATTRIBUTES"][$key]["REVERSE"]) ? 1 : null);
		    $cron = (isset($values["ATTRIBUTES"][$key]["CRON"]) ? 1 : null);

		    if (isset($values["ATTRIBUTES"][$key]["TABLE"]))
		    {
			$key_lower = strtolower($key);
			$tag .= '<td>';
                    
			// Show only attributes with <table> value
			if (isset($domain_values[0][$key_lower][0]))
			{
			    // The value
			    $val = $domain_values[0][$key_lower][0];
			    
			    // The reverse values
			    $rev = ($val == 'TRUE' ? 'FALSE' : 'TRUE');

			    if (isset($values["ATTRIBUTES"][$key]["TABLENOEDIT"]))
				$tag .= $val;
			    elseif (isset($values["ATTRIBUTES"][$key]["BOOL"]))
				$tag .= tof_icon('domains['.$domain.']',$val,'group_actions&amp;command=domain;'.$key.';'.$rev,$reverse,$cron);
			    else
				$tag .= $val;
			}

			elseif (isset($postmaster[0][$key_lower][0]))
			{
			    // The value
			    $val = $postmaster[0][$key_lower][0];
			    // The reverse values
			    $rev = ($val == 'TRUE' ? 'FALSE' : 'TRUE');

			    if ($val == 'TRUE' || $val == 'FALSE')
				$tag .= tof_icon('domains['.$domain.']',$val,'group_actions&amp;command=postmaster;'.$key.';'.$rev,$reverse,$cron);
			    else
				$tag .= $val;
			}

			$tag .= '</td>';
		    }
                }
            }

            $tag .= '</tr>';
        }
    }

    $tag .= '</tbody>';
    $tag .= '</table>';

    // End </form> tag inside action_select() function

    return $tag;
}

/**
*
**/

function accounts_list($domain,$values,$initial)
{
    global $pn;
    global $pv;
    global $action;
    
    // Rapresentative ObjectClass
    $myObjectClass = (isset($pv[$pn]["ACCOUNT"]["OBJECTCLASSES"]) ? key($pv[$pn]["ACCOUNT"]["OBJECTCLASSES"]) : 'virtualMailAccount');

    if (isset($initial))
	$account_array = phamm_search ('vd='.$domain.','.LDAP_BASE,'(&(objectClass='.$myObjectClass.')(!(cn=postmaster))(!(mail=abuse@*))(mail='.$initial.'*))');
    else
	$account_array = phamm_search ('vd='.$domain.','.LDAP_BASE,'(&(objectClass='.$myObjectClass.')(!(cn=postmaster))(!(mail=abuse@*)))');

    $tag = '<form method="post" action="'.$_SERVER["PHP_SELF"].'" name="accounts" id="post-checks">';
    $tag .= '<table class="data">';

    $tag .= '<thead>';
    $tag .= '<tr>';
    $tag .= '<th><input type="checkbox" name="all" onclick="checkAll();" /></th>';
    $tag .= '<th>';
    $tag .= sprintf(ngettext("Account (%d)", "Accounts (%d)", $account_array["count"]), $account_array["count"]);
    $tag .= '</th>'."\n";
    $tag .= '<th>'._("Common Name").'</th>'."\n";

    if (count($values) > 0)
    {
        foreach ($values["ATTRIBUTES"] as $key=>$value)
        {
	    if (isset($values["ATTRIBUTES"][$key]["TABLE"]))
	    {
		$label = (isset($values["ATTRIBUTES"][$key]["PRETTYNAME"]) ? $values["ATTRIBUTES"][$key]["PRETTYNAME"] : $key);
		$tag .= '<th>'.gettext($label).'</th>'."\n";
	    }
        }
    }
    $tag .= '</tr>';
    $tag .= '</thead>';
    
    $tag .= '<tbody>';

    foreach ($account_array as $account)
    {
        $mail = $account["mail"][0];

        if (isset($mail))  // @todo empty row
        {
            $tag .= '<tr class="data">';

            // Group action checkbox
            $tag .= '<td><input type="checkbox" name="accounts['.$mail.']" value="1" /></td>';

            // Account name and link
            $tag .= '<td><a href="?action=modify_account&amp;mail='.$mail.'" class="inside">';
            $tag .= $mail;
            $tag .= '</a></td>';

	    $tag .= '<td>'.stripslashes($account["cn"][0]).'</td>';

            // Other attributes (current plugin)
            if (isset($values["ATTRIBUTES"]))
            {
                foreach ($values["ATTRIBUTES"] as $key=>$value)
                {
		    $reverse = (isset($values["ATTRIBUTES"][$key]["REVERSE"]) ? 1 : null);
		    $cron = (isset($values["ATTRIBUTES"][$key]["CRON"]) ? 1 : null);
		    if (isset($values["ATTRIBUTES"][$key]["TABLE"]))
		    {
			$key_lower = strtolower($key);
			$tag .= '<td>';

			if (isset($account[$key_lower][0]))
			{
			    $val = $account[$key_lower][0];
			    $rev = ($val == 'TRUE' ? 'FALSE' : 'TRUE');

			    if (isset($values["ATTRIBUTES"][$key]["TABLENOEDIT"]))
				$tag .= $val;
			    elseif (isset($values["ATTRIBUTES"][$key]["BOOL"]))
				$tag .= tof_icon('accounts['.$mail.']',$val,'group_actions&amp;command=account;'.$key.';'.$rev,$reverse,$cron);

			    // Print Standard Value
			    else
			    {
				if (isset($value["MULTIPLIER"]) && in_array($action,array('modify_account','domain_property','domain_view')))
				    $tag .= $account[$key_lower][0] / $value["MULTIPLIER"];
				else
				    $tag .= $val;
			    }
			}

			$tag .= '</td>'."\n";
                    }
                }
            }

            $tag .= '</tr>'."\n";

        }
    }

    $tag .= '</tbody>';
    $tag .= '</table>';

    return $tag;
}


/**
*
**/

function form_add_domain ()
{
    $tag = '<form method="post" action="'.$_SERVER["PHP_SELF"].'" name="add_domain">';
    $tag .= '<input type="hidden" name="action" value="add_domain" />';
    $tag .= '<input type="text" size="25" name="domain_new"  maxlength="50" value="';
    if (isset($_POST["domain_new"]))
        $tag .= $_POST["domain_new"];
    $tag .= '" />';
    $tag .= '<input type="submit" value="'._("Add new domain").'" />';
    $tag .= '</form>';

    return $tag;
}

/**
* Create a XHTML form for plugin attribute
*
* @author Alessandro De Zorzi <adezorzi@rhx.it>
*
* @param string $p_name The plugin name
* @param array $attributes The attributes
* @return mixed
**/

function form_template($p_name,$attributes,$myvalues,$skip_table=null)
{
    global $action;
    global $minAuthLevel;

    $tag = '';

    if (!isset($skip_table))
	$tag .= '<table class="indata">';

    if (is_array($attributes))
    foreach ($attributes as $key => $attr)
    {
        if (!isset($hidden))
	    $tag .= '<tr><td>';
        
	// Set attributes
        $name = strtolower($key);
	$hidden = (isset($attr["HIDDEN"]) ? 'type="hidden"' : null);
	$minAuthLevel = (isset($attr["MINAUTHLEVEL"]) ? $attr["MINAUTHLEVEL"] : 0);
        $size = (isset($attr["SIZE"]) ? 'size="'.$attr["SIZE"].'"' : null);

        $maxlength = (isset($attr["MAXLENGHT"]) ? strtolower($attr["MAXLENGTH"]) : null);

        $subordinated = (isset($attr["SUBORDINATED"]) ? strtoupper($attr["SUBORDINATED"]) : null);
        $subordinatedreverse = (isset($attr["SUBORDINATEDREVERSE"]) ? strtoupper($attr["SUBORDINATEDREVERSE"]) : null);
        
	// Set XHTML Tags
	$required = (isset($attr["REQUIRED"]) ? ' *' : null);

	// Set Default value
        $value = '';

        // Set standard value
        if (isset($myvalues[$p_name][$name]))
            $value = $myvalues[$p_name][$name];
        
	// Existing value
        elseif (isset($myvalues[0][$name][0]))
        {
            // If attr is multiple
            if (isset($attr["MULTIPLE"]))
                $value = $myvalues[0][$name];
            else
                $value = $myvalues[0][$name][0];
	}

	// Multiplier (Ex. Quota x 1024)
	if (isset($attr["MULTIPLIER"]) && in_array($action,array('modify_account','domain_property')))
	{
		$value = ( $value / $attr["MULTIPLIER"]);
	}

	// Action add_account
        elseif (isset($attr["DEFAULT"]) && ($action == 'add_account' || $action == 'add_domain'))
        {
            if (preg_match('/^=php\.(\w+)\((.*)\)$/',$attr["DEFAULT"],$matches))
            {
                $args = preg_split('/,/',$matches[2]);

                $function_name = $matches[1];

                switch ($function_name) :

		case "Value" :

                    foreach ($args as $arg)
                    {
                        // Global vars
                        if (preg_match('/^%(\w+)(\|.+)?(\/[lU])?%/U',$arg,$matches))
                        {
                            $var_name = $matches[1];
                            global $$var_name;
                            $value .= $$var_name;
                        }
                        // POST vars
                        elseif (preg_match('/^\+(\w+)(\|.+)?(\/[lU])?\+/U',$arg,$matches))
                        {
                            $var_name = $matches[1];
                            $value .= $_POST[$var_name];

                        }
                        else
                            $value .= $arg;
                    }

                break;

		// Call PHP Function
		default :
		    
		    if (function_exists($function_name))
		    {
			// Escape a string to be used as a shell argument
			foreach ($args as $arg)
			    $args_escaped = escapeshellarg($arg);
		    
			    $value = call_user_func_array($function_name,$args_escaped); 
		    }

                break;

                endswitch;
            }

            else
            {
		$value = $attr["DEFAULT"];
            }
        }

	// Hide values if SUBORDINATED to another
	if (isset($attr["SUBORDINATED"]))
	{
	    if (isset($myvalues[0][strtolower($subordinated)][0]))
		$hidden = ($myvalues[0][strtolower($subordinated)][0] == "TRUE" ? null : 1);
	    elseif (isset($myvalues[$p_name][strtolower($subordinated)]))
		$hidden = ($myvalues[$p_name][strtolower($subordinated)] == "TRUE" ? null : 1);
	    else
		$hidden = 1;
	}
	if (isset($attr["SUBORDINATEDREVERSE"]))
	{
	    if (isset($myvalues[0][strtolower($subordinatedreverse)][0]))
		$hidden = ($myvalues[0][strtolower($subordinatedreverse)][0] == "FALSE" ? null : 1);
	    elseif (isset($myvalues[$p_name][strtolower($subordinatedreverse)]))
		$hidden = ($myvalues[$p_name][strtolower($subordinatedreverse)] == "FALSE" ? null : 1);
	    else
		$hidden = 1;
	}
	    
        if (!isset($hidden))
        {
            $label = (isset($attr["PRETTYNAME"]) ? $attr["PRETTYNAME"] : $key);
            $tag .= gettext($label);
	    $tag .= $required;
        
	    $tag .= "</td><td>";
        }


        if (isset($attr["BOOL"]) && !isset($hidden))
	{
	    $reverse = (isset($attr["REVERSE"]) ? 1 : null);

	    $tag .= trueorfalse("values[".$p_name."]",$value,$name,$reverse);

	}
        
	// Options (select)
	elseif (isset($attr["OPTIONS"]) && !isset($hidden))
	{
	    if ($_SESSION["login"]["level"] < $minAuthLevel)
		$tag .= $value;
	    else
	    {
		$tag .= '<select name="values['.$p_name.']['.$name.']">';
		foreach ($attr["OPTIONS"] as $k => $v)
		{
		    $label = (isset($attr["PRETTYNAME"]) ? $attr["PRETTYNAME"] : $k);
		    $label = $k;
		    $selected = ($value == $v["VALUE"] ? 'selected="selected"' : '');
		
		    $tag .= '<option value="'.$v["VALUE"].'" '.$selected.'>'.$label.' ('.$v["VALUE"].')</option>'."\n";
	    }
		$tag .= '</select>'."\n\n";
	    }
	}
        
	elseif (isset($attr["DATE"]) && !isset($hidden))
	{
	    $current_date = ($value ? $value : date('Y'.'-'.'m'.'-'.'d'));

	    if ($_SESSION["login"]["level"] < $minAuthLevel)
		$tag .= $value;
	    else
		$tag .= date_input('values_date['.$p_name.']['.$name.']',$current_date,$format='ymd');
	}
        
	elseif (isset($attr["TEXTAREA"]) && !isset($hidden))
        {
	    if ($_SESSION["login"]["level"] < $minAuthLevel)
		$tag .= nl2br($value);
	    else
            {
		// Show box to add multiple values
		$tag .= '<textarea name="values['.$p_name.']['.$name.']" cols="35" rows="5">';
		$tag .= stripslashes($value);
		$tag .= "</textarea><br/>";
	    }
	}

        elseif (isset($attr["MULTIPLE"]) && !isset($hidden))
        {
            // Show box to add multiple values
            $tag .= '<textarea name="values_multi['.$p_name.']['.$name.']" cols="35" rows="5">';
	    if (isset($attr["TEXTAREA"]))
		$tag .= stripslashes($value);
	    $tag .= "</textarea><br/>";

            // Show the values
	    if (is_array($value))
	    {
		for ($i=0; $i < $value["count"]; $i++)
		{
		    $tag .= '<input type="checkbox" name="values_multi_del['.$p_name.']['.$name.'][]" value="'.$value[$i].'" />';
		    $tag .= $value[$i]."<br/>";
		}
	    }
        }

        elseif (isset($attr["SUBORDINATEDDELETE"]) && $value)
	{
	    $tag .= '<input type="checkbox" name="values_multi_del['.$p_name.']['.$name.']" value="'.$value.'" id="subordinatedelete" />';
	    $tag .= $value."<br/>";

	}

        else
	{
	    if (isset($hidden))
		$input_type = 'hidden';
	    else
		$input_type = 'text';

	    // Show Value Only if auth level > level required
	    if ($_SESSION["login"]["level"] >= $minAuthLevel)
	        $tag .= '<input type="'.$input_type.'" value="'.$value.'" name="values['.$p_name.']['.$name.']" '.$size.' />';
	    elseif (!isset($hidden))
	        $tag .= $value;
        }

	if (!isset($hidden))
	    $tag .= "</td></tr>";

    }
    
    // Exception, if last array attribute is hidden
    // XHTML Syntax required to close a TR
    if (isset($hidden))
	$tag .= "</td></tr>";
    
    if (!isset($skip_table))
	$tag .= "</table>";

    return $tag;
}

/**
* Print a form to add or modify a domain
*
* @package Phamm
* @author Alessandro De Zorzi <adezorzi@rhx.it>
*
* @param string $domain_new
* @param string $action
* @param array $values
* @return mixed
**/

function form_add_domain_2 ($domain_new, $action, $values, $values_postmaster)
{
    global $plugins;
    global $pv;
    global $domain;
    
    if (in_array($action,array('update_account','modify_account')))
	$submit_label = _("Modify domain");
    else
	$submit_label = _("Add new domain");

    $tag = '<form method="post" action="'.$_SERVER["PHP_SELF"].'" name="add">'."\n";
    $tag .= '<input type="hidden" name="action" value="'.$action.'" />'."\n";
    $tag .= '<input type="hidden" name="domain_new" value="'.$domain_new.'" />'."\n";

    $tag .= '<table class="data">'."\n";

    $tag .= '<tr><td>'._("Domain").'</td>'."\n";
    $tag .= '<td colspan="2"><b>'.$domain_new.'</b></td></tr>'."\n";

    $tag .= '<tr><td>'._("Password").'</td>'."\n";
    $tag .= '<td colspan="2"><input type="password" name="password1" value="" size="15" maxlength="15" /></td></tr>'."\n";

    $tag .= '<tr><td>'._("Confirm Password").'</td>'."\n";
    $tag .= '<td colspan="2"><input type="password" name="password2" value="" size="15" maxlength="15" /></td></tr>'."\n";
    
    foreach ($pv as $name => $p_value)
    {
        if ($p_value["ISDOMAINRELATED"])
        {
            $class = 'pluginActive';

            $tag .= '<tr valign="top" class="'.$class.'" id="'.$name.'"><td>'."\n";
            $tag .= '<input type="checkbox" name="confirm[]" value="'.$name.'" checked="checked" onclick="JavaScript:testing(this,\''.$name.'\')" />'."\n";
            $tag .= '<strong>'.$p_value["LABEL"].'</strong>'."\n";
            $tag .= '</td><td>'."\n";

	    // Load domain values
	    if (isset($p_value["DOMAIN"]["ATTRIBUTES"]))
	    {
		$tag .= form_template('domain',$p_value["DOMAIN"]["ATTRIBUTES"],$values)."\n";
	    }

            if (isset($p_value["POSTMASTER"]["ATTRIBUTES"]))
	    {
		$tag .= form_template($name,$p_value["POSTMASTER"]["ATTRIBUTES"], $values_postmaster)."\n";
	    }

            $tag .= '</td></tr>'."\n";
        }
    }

    $tag .= '</table>'."\n";
    $tag .= '<input type="submit" value="'.$submit_label.'" />'."\n";
    $tag .= '</form>'."\n";

    return $tag;
}

/**
*
**/

function form_add_account ()
{
    global $domain;

    $tag = '<form method="post" action="'.$_SERVER["PHP_SELF"].'" name="add_account">'."\n";
    $tag .= '<input type="hidden" name="action" value="add_account" />'."\n";
    $tag .= '<input type="text" size="25" name="account_new"  maxlength="50" value="'."\n";
    if (isset($_POST["account_new"]))
        $tag .= $_POST["account_new"];
    $tag .= '" />';
    $tag .= '@'.$domain;
    $tag .= '<input type="submit" value="'._("Add new account").'" />'."\n";
    $tag .= '</form>'."\n\n";

    return $tag;
}

/**
*
**/

function form_add_account_2 ($account_new,$action,$values)
{
    global $plugins;
    global $pv;
    global $pn;
    global $domain;
    global $givenName;
    global $sn;
    #global $values;
    global $plugins_exclusion;

    if (in_array($action,array('modify_account','update_account')))
	$submit_label = _("Modify account");
    else
	$submit_label = _("Add new account");

    $tag = '<form method="post" action="'.$_SERVER["PHP_SELF"].'" name="add">'."\n";
    $tag .= '<input type="hidden" name="action" value="'.$action.'" />'."\n";
    $tag .= '<input type="hidden" name="account_new" value="'.$account_new.'" />'."\n";

    $tag .= '<table class="data">'."\n";

    $tag .= '<tr><td>'._("Account").'</td>'."\n";
    $tag .= '<td colspan="2"><b>'.$account_new.'</b></td></tr>'."\n";

    $tag .= '<tr><td>'._("Password").'</td>'."\n";
    $tag .= '<td colspan="2"><input type="password" name="password1" value="" size="15" maxlength="15" /></td></tr>'."\n";

    $tag .= '<tr><td>'._("Confirm Password").'</td>'."\n";
    $tag .= '<td colspan="2"><input type="password" name="password2" value="" size="15" maxlength="15" /></td></tr>'."\n";

    $tag .= '<tr><td>'._("Name").'</td>'."\n";
    $tag .= '<td colspan="2"><input type="text" name="givenname" value="'.stripslashes($givenName).'" size="15" maxlength="50" /></td></tr>'."\n";

    $tag .= '<tr><td>'._("Surname").'</td>'."\n";
    $tag .= '<td colspan="2"><input type="text" name="sn" value="'.stripslashes($sn).'" size="15" maxlength="50" /></td></tr>'."\n";
    
    $i = 0;

    foreach ($pv as $name => $p_value)
    {
        // Skip form if plugin is defined in $plugins_exclusion (mail||alias)
	if (in_array($name,$plugins_exclusion))
	{
	    // Search if the firsh OBJECTCLASSES defined in XML is present
	    if (in_array($action,array('modify_account','update_account')))
	    {
		if (isset($values[0]["objectclass"]))
		    $show_form = (in_array(key($p_value["ACCOUNT"]["OBJECTCLASSES"]),$values[0]["objectclass"]) ? true : false);
		else
		    $show_form = isset($values[$name]);
	    }
	    else
		$show_form = ($name == $pn || !in_array($pn,$plugins_exclusion) ? true : false);
	}
	else
	    $show_form = true;

	    if ($show_form)
		if ($p_value["ISACCOUNTRELATED"])
		{
		    $tag .= '<tr valign="top" class="pluginActive" id="'.$name.'"><td>'."\n";

		    if (in_array($name,$plugins_exclusion))
			$tag .= '<input type="radio" name="confirm[]" value="'.$name.'" checked="checked" />'."\n";
		    else
			$tag .= '<input type="checkbox" name="confirm[]" value="'.$name.'" checked="checked" onClick="testing(this,\''.$name.'\')" />'."\n";

		    $tag .= '<b>'.$p_value["LABEL"].'</b>'."\n";
		    $tag .= '</td><td>'."\n";
		
		    $tag .= form_template($name,$p_value["ACCOUNT"]["ATTRIBUTES"],$values)."\n";

		    $tag .= '</td></tr>'."\n";
		}
    }

    $tag .= '</table>'."\n";
    $tag .= '<input type="submit" value="'.$submit_label.'" />'."\n";
    $tag .= '</form>'."\n";

    return $tag;
}


/**
* Print a navigation bar with letters capital
*
* @package Phamm
* @author Alessandro De Zorzi <adezorzi@rhx.it>
**/

function navigation ($initial)
{
    global $domain;
    global $account;
    global $mail;
    global $action;

    $tag = '<div id="navBar">'."\n";
    $tag .= '<div class="navigation">'."\n";
    $tag .= '<a href="main.php?domain=NONE&amp;initial=">'._("All domains")."</a>"."\n";
    if ($domain)
    {
        $tag .= " &gt; ".'<a href="main.php?action=domain_view&amp;mail=NONE&amp;domain='.$domain.'&amp;initial=">';
        $tag .= $domain.'</a>'."\n";
    }
    if ($mail && $domain)
        $tag .= " &gt; ".$mail;
    $tag .= '</div>';

    // Skip initial list
    if (in_array($action,array('add_account','insert_account','modify_account')))
	return $tag;

    $tag .= '<div class="navigation">'."\n";

    $tag .= '<a href="?action='.$action.'&amp;initial=&amp;domain='.$domain.'">'._("All").'</a> - '."\n";

    for ($i="A"; $i != "AA"; $i++)
    {
        if ($i == $initial)
            $tag .= '<span>'.$i.'</span>';
        else
        {
            $tag .= '<a href="?action='.$action.'&amp;initial='.$i;
            if ($domain)
                $tag .= '&amp;domain='.$domain;
            $tag .= '">'.$i.'</a>';
        }

        if ($i=='Q')
            $tag .= '</div><div class="navigation">'."\n";
        else
            $tag .= ' - '."\n";
    }

    for ($i='0'; $i != '10'; $i++)
    {
        if ($i == $initial)
            $tag .= '<span>'.$i.'</span>'."\n";
        else
        {
            $tag .= '<a href="?action='.$action.'&amp;initial='.$i;
            if ($domain)
                $tag .= '&amp;domain='.$domain;
            $tag .= '">'.$i.'</a>'."\n";
        }

        if ($i != '9')
            $tag .= ' - '."\n";
    }

    $tag .= '</div>'."\n";
    $tag .= '</div>'."\n\n";

    return $tag;
}

/**
* @author Alessandro De Zorzi <adezorzi@rhx.it>
*
* @return mixed
**/

function trueorfalse($table,$value,$field,$reverse)
{
    global $minAuthLevel;

    $tag = '';

    if (isset($reverse))
    {
        $false = 'TRUE';
        $true = 'FALSE';
	
	$img = ($value == 'TRUE' ? 'FALSE' : 'TRUE');
    }
    else
    {
        $false = 'FALSE';
        $true = 'TRUE';
	$img = $value;
    }

    $false_label = ($false == 'FALSE' ? _("NO") : _("YES"));
    $true_label = ($true == 'TRUE' ? _("YES") : _("NO"));
    
    $tag .= '<img src="./img/'.$img.'.png" alt="" border="0" /> ';

    if ($_SESSION["login"]["level"] >= $minAuthLevel)
    {
	$js_string = ($field == 'vacationactive' ? 'onchange="subordinatedelete.checked=true;"' : '');

	$tag .= '<select name="'.$table.'['.$field.']" '.$js_string.'>';

	$selected = ($value == 'TRUE' || $value == 'true' ? 'selected="selected"' : '');

	$tag .= '<option value="FALSE"';
	$tag .= ">".$false_label.'</option>';
	$tag .= '<option value="TRUE" '.$selected.'>';
	$tag .= $true_label.'</option>'."\n";

	$tag .= "</select>"."\n\n";
    }
    else
    {
	$tag .= ($value=='TRUE' ? $true_label : $false_label);
    }

    return $tag;
}

/*
*
*/

function action_select($dispaly)
{
    global $pv;

    $group_actions = array();
    
    // Alias require immediate cancellation
    if ($_SESSION["phamm"]["pn"] == 'alias')
	$group_actions[] = "account;delete;TRUE;Delete;0";
    
    $attribute_array = array();
    $p_active = $_SESSION["phamm"]["pn"];
    $values = ($pv[$p_active]);

    if ($dispaly == 'domain')
    {
	if (isset($values["DOMAIN"]["ATTRIBUTES"]))
	    $attribute_array = array_merge($attribute_array,$values["DOMAIN"]["ATTRIBUTES"]);
	if (isset($values["POSTMASTER"]["ATTRIBUTES"]))
	    $attribute_array = array_merge($attribute_array,$values["POSTMASTER"]["ATTRIBUTES"]);
    }
    elseif ($dispaly == 'account')
    {
	if (isset($values["ACCOUNT"]["ATTRIBUTES"]))
	    $attribute_array = array_merge($attribute_array,$values["ACCOUNT"]["ATTRIBUTES"]);
    }

    foreach ($attribute_array as $attribute_name => $attribute_values)
    {
        $is_bool = (isset($attribute_values["BOOL"]) ? $attribute_values["BOOL"] : null);
        $is_table = (isset($attribute_values["TABLE"]) ? $attribute_values["TABLE"] : null);
        $prettyname = (isset($attribute_values["PRETTYNAME"]) ? $attribute_values["PRETTYNAME"] : $attribute_name);
        $is_reverse = (isset($attribute_values["REVERSE"]) ? 1 : 0);

        if ($is_bool && $is_table)
        {
	    $group_actions[] = $dispaly.';'.$attribute_name.';TRUE;'.$prettyname.';'.$is_reverse;
	    $group_actions[] = $dispaly.';'.$attribute_name.';FALSE;'.$prettyname.';'.$is_reverse;
	}
    }
    
    // Open <form> tag inside domains_list() function
    $tag = '';
    $tag .= '<input type="hidden" name="action" value="group_actions" />'."\n";

    $tag .= '<select name="command">';
    $tag .= '<option value="">'._("...execute a command").'</option>'."\n";
    
    foreach ($group_actions as $action)
    {
	# @todo
	$label = explode(';',$action);
	$tag .= '<option value="'.$action.'">'._("Set").' '.gettext($label[3]).' ';
	if ($label[4] == 1)
	{
	    $reverse_label_2 = ($label[2] == 'TRUE' ? 'FALSE' : 'TRUE');
	    $tag .= gettext($reverse_label_2);
	}
	else
	    $tag .= gettext($label[2]);
	$tag .= '</option>'."\n";
    }

    $tag .= "</select>";
    $tag .= '<input type="submit" value="'._("Execute command").'" />'."\n";
    
    $tag .= "</form>"."\n\n";

    return $tag;
}

/*
*
*/

function tof_icon($table,$value,$tof_action,$reverse=null,$cron=null)
{
    if (isset($reverse))
	$value = ($value == 'TRUE' ? 'FALSE' : 'TRUE');

    if (isset($cron) && $value == 'TRUE')
	$value = 'waiting';

    $tag = '<div align="center">';
    $tag .= '<a href="?'.$table.'&amp;action='.$tof_action.'">';
    $tag .= '<img src="./img/'.$value.'.png" alt="'.$value.'" border="0" />';
    $tag .= '</a></div>';

    return $tag;
}

/**
* Format date input with select YYYY-MM-DD
**/

function date_input($name,$current_date,$format='ymd')
{
    $tag = '';

    if(preg_match("/-/", $current_date))
    {
	list($year,$month,$day) = split("-", $current_date);
    }
    else 
    {
	$year = substr($current_date,0,4);
	$month = substr($current_date,4,2);
	$day = substr($current_date,6,2);
    }

    // Days
    $tag .= '<select name="'.$name.'[day]">';
    for ($i=1; $i<=31; $i++)
    {
	$i_f = ($i < 10 ? '0'.$i : $i);
	$selected = ($i == $day ? 'selected="selected"' : '');
	$tag .= '<option value="'.$i_f.'" '.$selected.'>'.$i_f.'</option>';
    }
    $tag .= "</select>";

    // Months
    $tag .= '<select name="'.$name.'[month]">';
    for ($i=1; $i<=12; $i++)
    {
	$i_f = ($i < 10 ? '0'.$i : $i);
	$selected = ($i == $month ? 'selected="selected"' : '');
	$tag .= '<option value="'.$i_f.'" '.$selected.'>'.$i_f.'</option>';
    }
    $tag .= "</select>";

    // Years
    $this_year = date('Y');

    $min_year = ( $this_year - 1 );
    $max_year = ( $this_year + 4 );

    $tag .=  '<select name="'.$name.'[year]">';
    for ($i=$min_year; $i<$max_year; $i++)
    {
	$selected = ($i == $year ? 'selected="selected"' : '');
	$tag .=  '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
    }
    $tag .=  "</select>";

    return $tag;
}

/**
* Create a select with languages
**/

function locale_select()
{
    global $supported_languages;

    $tag = '<form method="get" action="'.$_SERVER["PHP_SELF"].'" name="lang">';
    $tag .= '<select name="language" onchange="document.lang.submit()">'."\n";
	
    foreach ($supported_languages as $l_k => $l_v)
    {
	$tag .= '<option value="'.$l_k.'"';
	
	if ($l_k == $_SESSION["language"])
		$tag .= ' selected="selected" ';
	$tag .= ">";
	$tag .= $l_v.'</option>'."\n";
	}

    $tag .= "</select>\n";
    $tag .= "</form>\n\n";

    return $tag;
}

function form_catch_all($domain)
{
    $tag = '<div class="message">'._("Catch-all").'</div>';

    $tag .= '<form method="post" action="'.$_SERVER["PHP_SELF"].'" name="login">'."\n";
    $tag .= '<input type="hidden" name="action" value="insert_account" />'."\n";
    $tag .= '<input type="hidden" name="account_new" value="@'.$domain.'" />'."\n";
    $tag .= '<input type="hidden" name="values[alias][accountactive]" value="TRUE" />'."\n";
    $tag .= '<input type="hidden" name="confirm[]" value="alias" />'."\n";
    $tag .= '<input type="hidden" name="givenname" value="Catch" />'."\n";
    $tag .= '<input type="hidden" name="sn" value="All" />'."\n";
    $tag .= '<input type="hidden" name="password1" value="" />'."\n";
    $tag .= '<input type="hidden" name="password2" value="" />'."\n";
    $tag .= '<textarea name="values_multi[alias][maildrop]" cols="35" rows="3">';
    $tag .= '</textarea><br/>';
    $tag .= '<input type="submit" value="'._("Add catch-all address").'" />'."\n";
    $tag .= '</form>'."\n";

    return $tag;
}


?>
