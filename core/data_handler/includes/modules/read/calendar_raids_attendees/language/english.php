<?php
/*	Project:	EQdkp-Plus
 *	Package:	EQdkp-plus
 *	Link:		http://eqdkp-plus.eu
 *
 *	Copyright (C) 2006-2015 EQdkp-Plus Developer Team
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU Affero General Public License as published
 *	by the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU Affero General Public License for more details.
 *
 *	You should have received a copy of the GNU Affero General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

$module_lang = array(
	'html_status'						=> '',
	'html_calstat_lastraid'				=> 'RCS - Last Raid',
	'html_calstat_raids_confirmed'		=> 'Attended',
	'html_calstat_raids_signedin'		=> 'Signedin',
	'html_calstat_raids_signedoff'		=> 'Signedoff',
	'html_calstat_raids_backup'			=> 'Backup',
);

$preset_lang = array(
	'raidattendees_status'				=> 'Calendar-Raid-Attendee Status',
	'raidcalstats_lastraid'				=> 'Calendar-Stats-Last Raid',
	'raidcalstats_raids_confirmed_90'	=> 'Calendar-Stats-Raids confirmed (90 days)',
	'raidcalstats_raids_signedin_90'	=> 'Calendar-Stats-Raids signedin (90 days)',
	'raidcalstats_raids_signedoff_90'	=> 'Calendar-Stats-Raids signedoff (90 days)',
	'raidcalstats_raids_backup_90'		=> 'Calendar-Stats-Raids backup (90 days)',
	'raidcalstats_raids_confirmed_60'	=> 'Calendar-Stats-Raids confirmed (60 days)',
	'raidcalstats_raids_signedin_60'	=> 'Calendar-Stats-Raids signedin (60 days)',
	'raidcalstats_raids_signedoff_60'	=> 'Calendar-Stats-Raids signedoff (60 days)',
	'raidcalstats_raids_backup_60'		=> 'Calendar-Stats-Raids backup (60 days)',
	'raidcalstats_raids_confirmed_30'	=> 'Calendar-Stats-Raids confirmed (30 days)',
	'raidcalstats_raids_signedin_30'	=> 'Calendar-Stats-Raids signedin (30 days)',
	'raidcalstats_raids_signedoff_30'	=> 'Calendar-Stats-Raids signedoff (30 days)',
	'raidcalstats_raids_backup_30'		=> 'Calendar-Stats-Raids backup (30 days)',
);
?>