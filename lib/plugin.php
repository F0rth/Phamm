<?php
/**
* Plugin functions
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


/*
* Generate the plugin menu
*
* @author Alessandro De Zorzi <adezorzi@rhx.it>
* @return mixed
*/

function plugins_menu ()
{
    global $plugins;
    global $pv;
    global $action;

    $tag = "<table><tr>"."\n";

    foreach ($plugins as $plugin)
    {
        if ($_SESSION["login"]["level"] >= $pv[$plugin]["MINAUTHLEVEL"])
        {
            if ($_SESSION["phamm"]["pn"] == $plugin)
                $tag .= '<td class="pluginActive">';
            else
                $tag .= '<td class="plugin">';

            $tag .= '<a href="?pn='.$plugin;
            if ($action)
                $tag .= '&amp;action='.$action;
            $tag .= '">';
            # @todo
            $tag .= strtoupper($plugin);

            $tag .= '</a>';

            $tag .= '</td>'."\n";

        }

    }

    $tag .= '</tr></table>'."\n\n";

    return $tag;
}


/**
* Load plugins info from XML files into array
*
* @package Phamm
* @author Alessandro De Zorzi <adezorzi@rhx.it>
*
* @return array $p_values
**/

function plugins_load()
{
    global $plugins;

    $pv = array();

    foreach ($plugins as $plugin)
    {
        $file = '../plugins/'.$plugin.'.xml';

        if (file_exists($file))
        {
            $xml2array = xml2array($file);
            $pv = array_merge($pv, $xml2array);
        }
    }

    return $pv;
}

?>
