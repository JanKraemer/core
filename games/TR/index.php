<?php
/******************************
 * EQdkp
 * Copyright 2002-2003
 * Licensed under the GNU GPL.  See COPYING for full terms.
 * ------------------
 * DAoC.php
 * Began: Fri May 13 2005
 *
 * $Id$
 *
 ******************************/

if ( !defined('EQDKP_INC') )
{
    header('HTTP/1.0 404 Not Found');
    exit;
}

class Manage_Game
{
  var $gamename = 'TR';
  var $maxlevel = 50;

   function do_it($db,$table_prefix,$install,$lang)
   {
    if($lang == 'de')
    {
      $classes = array(
        array('Unknown', 'Motor Assist',0,50),
        array('Soldat', 'Reflexionspanzerung',5,14),
        array('Spezialist', 'Hazmatpanzerung',5,14),
        array('Kommandosoldat', 'Gravitationspanzerung',15,29),
        array('Aufklärer', 'Tarnpanzerung',15,29),
        array('Pionier', 'Mech-Panzerung',15,29),
        array('Biotechniker', 'Organische Panzerung',15,29),
        array('Grenadier', 'Gravitationspanzerung',30,50),
        array('Gardist', 'Gravitationspanzerung',30,50),
        array('Spion', 'Tarnpanzerung',30,50),
        array('Scharfschütze', 'Tarnpanzerung',30,50),
        array('Saboteur', 'Mech-Panzerung',30,50),
        array('Ingenieur', 'Mech-Panzerung',30,50),
        array('Mikrobiologe', 'Organische Panzerung',30,50),
        array('Xenobiologe', 'Organische Panzerung',30,50)
      );

      $races = array(
        'Unknown',
        'Mensch'
      );

      $factions = array(
        'AFS'
      );
    }else{
      $classes = array(
        array('Unknown', 'Plate',0,50),
        array('Recruit', 'Motor Assist',0,4),
        array('Soldier', 'Reflective Armor',5,14),
        array('Specialist', 'Hazmat Suit',5,14),
        array('Commando', 'Graviton Armor',15,29),
        array('Ranger', 'Stealth Armor',15,29),
        array('Sapper', 'Mech Body Armor',15,29),
        array('Biotechnician', 'Bio Suit',15,29),
        array('Grenadier', 'Graviton Armor',30,50),
        array('Guardian', 'Graviton Body Armor',30,50),
        array('Spy', 'Stealth Body Armor',30,50),
        array('Sniper', 'Stealth Body Armor',30,50),
        array('Demolitionist', 'Mech Body Armor',30,50),
        array('Engineer', 'Mech Body Armor',30,50),
        array('Medic', 'Bio Body Armor',30,50),
        array('Exobiologist', 'Bio Body Armor',30,50)
      );

      $races = array(
        'Unknown',
        'Human'
      );

      $factions = array(
        'AFS'
      );
    }
    
    // The Class colors
    $classColorsCSS = array(
    );
    
    //lets do some tweak on the templates dependent on the game
    $aq =  array();
    array_push($aq, "UPDATE ". $table_prefix ."style_config SET logo_path='logo_plus.gif' WHERE logo_path='bc_header3.gif' ;");
    array_push($aq, "UPDATE ". $table_prefix ."style_config SET logo_path='/logo/logo_plus.gif' WHERE logo_path='/logo/logo_wow.gif' ;");
    array_push($aq, "UPDATE ". $table_prefix ."style_config SET logo_path='logo_plus.gif' WHERE logo_path='logo_wow.gif' ;" );

    //Do this SQL Query NOT if the Eqdkp is installed -> only @ the first install
    if($install)
    {
    	array_push($aq, "UPDATE ". $table_prefix ."config SET config_value = 32 WHERE config_name='default_style' ;");
    	array_push($aq, "UPDATE ". $table_prefix ."users SET user_style = '32' ;");
    }

    //Itemstats
    array_push($aq, "UPDATE ". $table_prefix ."plus_config SET config_value = '0' WHERE config_name = 'pk_itemstats' ;");
    array_push($aq, "UPDATE ". $table_prefix ."plus_config SET config_value = '0' WHERE config_name = 'pk_is_autosearch' ;");


    // this is the fix stuff.. instert the new information
    // into the database. moved it to a new class, its easier to
    // handle
    $gmanager = new GameManagerPlus($table_prefix, $db);
    $gmanager->ChangeGame($this->gamename, $classes, $races, $factions, $classColorsCSS, $this->maxlevel,$aq,$lang);

     if (!$install)
     {
  	   $redir = "admin/settings.php";
  	   redirect($redir);
  	 }
   }
}

?>
