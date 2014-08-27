<?php
/*
* Project:		EQdkp-Plus
* License:		Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
* Link:			http://creativecommons.org/licenses/by-nc-sa/3.0/
* -----------------------------------------------------------------------
* Began:		2009
* Date:			$Date$
* -----------------------------------------------------------------------
* @author		$Author$
* @copyright	2006-2011 EQdkp-Plus Developer Team
* @link			http://eqdkp-plus.com
* @package		eqdkpplus
* @version		$Rev$
*
* $Id$
*/

//tbody not allowed withoud thead, 

define('EQDKP_INC', true);
define('IN_ADMIN', true);
$eqdkp_root_path = './../';
include_once($eqdkp_root_path . 'common.php');

class Manage_Media extends page_generic {
	public function __construct(){
		$this->user->check_auth('a_files_man');
		$handler = array();
		parent::__construct(false, $handler);
		$this->process();
	}

	public function display(){
		$this->core->set_vars(array(
			'page_title'		=> $this->user->lang('manage_media'),
			'template_file'		=> 'admin/manage_media.html',
			'display'			=> true)
		);
	}
}
registry::register('Manage_Media');
?>