<?php
/*	Project:	EQdkp-Plus
 *	Package:	EQdkp-plus
 *	Link:		http://eqdkp-plus.eu
 *
 *	Copyright (C) 2006-2016 EQdkp-Plus Developer Team
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

if(!defined('EQDKP_INC')) {
	die('Do not access this file directly.');
}

if(!class_exists('pdh_w_raid_groups_members')) {
	class pdh_w_raid_groups_members extends pdh_w_generic {

		public function add_member_to_group($member_id, $group_id, $blnLogging = true) {
			$arrSet = array(
				'group_id' => $group_id,
				'member_id'  => $member_id,
			);

			$objQuery = $this->db->prepare("INSERT INTO __groups_raid_members :p")->set($arrSet)->execute();

			if(!$objQuery) {
				return false;
			}

			$this->add_member_to_raid_events($member_id, $group_id);

			$this->pdh->enqueue_hook('raid_groups_update');
			return true;
		}

		public function add_member_to_groups($member_id, $group_array) {
			if (is_array($group_array)) {
				$memberships = $this->pdh->get('raid_groups_members', 'memberships_status', array($member_id));

				foreach($group_array as $key=>$group) {
					$group = intval($group);

					if(!$this->add_member_to_group($member_id, $group)) {
						return false;
					}
				}
				return true;
			} else {
				return false;
			}
		}

		public function add_grpleader($arrMemberIDs, $group_id){
			if (!is_array($arrMemberIDs)){
				$arrMemberIDs = array($arrMemberIDs);
			}

			$arrNames = array();
			foreach($arrMemberIDs as $member_id){
				//if char already in group?
				$blnIsInGroup = $this->pdh->get('raid_groups_members', 'membership_status', array($member_id, $group_id));
				if($blnIsInGroup <= 0){
					$this->add_member_to_group($member_id, $group_id);
				}

				$objQuery = $this->db->prepare("UPDATE __groups_raid_members :p WHERE group_id=? AND member_id=?")->set(array('grpleader' => 1))->execute($group_id, $member_id);
				if(!$objQuery) {
					return false;
				}
				$arrNames[] = $this->pdh->get('member', 'name', array($member_id));
			}

			$log_action = array(
				'{L_MEMBER}' => implode(', ', $arrNames),
			);

			$this->log_insert('action_membergroups_add_groupleader', $log_action, $group_id, $this->pdh->get('raid_groups', 'name', array($group_id)));

			$this->pdh->enqueue_hook('raid_groups_update');
			return true;
		}

		public function remove_grpleader($arrMemberIDs, $group_id){
			if (!is_array($arrMemberIDs)){
				$arrMemberIDs = array($arrMemberIDs);
			}

			$arrSet = array(
				'grpleader' => 0,
			);

			$arrNames = array();
			foreach($arrMemberIDs as $member_id){
				$objQuery = $this->db->prepare("UPDATE __groups_raid_members :p WHERE group_id=? AND member_id=?")->set($arrSet)->execute($group_id, $member_id);

				if(!$objQuery) {
					return false;
				}
				$arrNames[] = $this->pdh->get('member', 'name', array($member_id));
			}

			$log_action = array(
					'{L_USER}' => implode(', ', $arrNames),
			);

			$this->log_insert('action_membergroups_remove_groupleader', $log_action, $group_id, $this->pdh->get('raid_groups', 'name', array($group_id)));

			$this->pdh->enqueue_hook('raid_groups_update');
			return true;
		}

		public function add_members_to_group($member_array, $group_id) {
			if (is_array($member_array)) {
				foreach($member_array as $key=>$member){
					if(!$this->add_member_to_group($member, $group_id)) {
						return false;
					}
				}
				return true;
			} else {
				return false;
			}
		}

		public function delete_member_from_group($member_id, $group_id) {
			$objQuery = $this->db->prepare("DELETE FROM __groups_raid_members WHERE group_id = ? AND member_id =?")->execute($group_id, $member_id);

			if($objQuery) {
				$this->pdh->enqueue_hook('raid_groups_update');
				return true;
			}
			return false;
		}

		public function delete_members_from_group($member_array, $group_id) {
			if (is_array($member_array)) {
				$objQuery = $this->db->prepare("DELETE FROM __groups_raid_members WHERE group_id =? AND member_id :in")->in($member_array)->execute($group_id);
				$this->delete_member_from_raid_events($member_array, $group_id);
				$this->pdh->enqueue_hook('raid_groups_update');
			} else {
				return false;
			}
		}

		public function delete_all_member_from_group($group_id) {
			$objQuery = $this->db->prepare("DELETE FROM __groups_raid_members WHERE group_id =?")->execute($group_id);
			$this->pdh->enqueue_hook('raid_groups_update');
			return true;
		}

		private function add_member_to_raid_events($member_id, $group_id) {
            $objQuery = $this->db->query("SELECT * FROM __calendar_events WHERE timestamp_start >= UNIX_TIMESTAMP()");
            if($objQuery){
                while($row = $objQuery->fetchAssoc()){
                    $raidid = (int)$row['id'];

                    // Nur wenn die RaidId (EventId) deiner group_id angehört hinzufügen.
                    // TODO prüfen des Raidleiters zu dem Event, ggf join mit members und den Leader holen.
                    if($raidid){
                        continue;
                    }

                    $userid			= $this->pdh->get('user', 'userid', array($member_id));
                    $away_mode		= $this->pdh->get('calendar_raids_attendees', 'user_awaymode', array($userid, $raidid));
                    $defaultrole	= $this->pdh->get('member', 'defaultrole', array($member_id));
                    $signupstatus	= ($away_mode) ? 2 : 1;
                    $signupnote		= $this->pdh->get('$raid_groups_members', 'charSelectionMethod', array($member_id));
                    $signupnote_txt	= ($signupnote) ? $this->user_lang('raidevent_raid_note_'.$signupnote) : '';

                    $this->pdh->put('calendar_raids_attendees', 'update_status', array($raidid, $member_id, (($defaultrole) ? $defaultrole : 0), $signupstatus, $group_id, 0, $signupnote_txt));
                }
            }
        }

        private function delete_member_from_raid_events($memberids, $groupId) {
            $memberids = (is_array($memberids)) ? $memberids : array($memberids);
            $objQuery = $this->db->query("SELECT * FROM __calendar_events WHERE timestamp_start >= UNIX_TIMESTAMP()");
            if($objQuery) {
                while ($row = $objQuery->fetchAssoc()) {
                    $calenderEventId = $row['id'];
                    $query = $this->db->prepare("DELETE FROM __calendar_raid_attendees WHERE calendar_events_id=? AND raidgroup=? AND member_id :in")->in($memberids)->execute($calenderEventId, $groupId);
                }
            }
            $this->pdh->enqueue_hook('calendar_raid_attendees_update');
        }
	}
}
