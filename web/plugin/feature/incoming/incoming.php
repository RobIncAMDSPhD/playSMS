<?php

/**
 * This file is part of playSMS.
 *
 * playSMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * playSMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with playSMS.  If not, see <http://www.gnu.org/licenses/>.
 */

defined('_SECURE_') or die('Forbidden');
if (!auth_isadmin()) {
	auth_block();
};

switch (_OP_) {
	case "incoming":

		// sandbox match receiver number and sender ID
		$data = registry_search(1, 'feature', 'incoming', 'sandbox_match_sender_id');
		$sandbox_match_sender_id = (int)$data['feature']['incoming']['sandbox_match_sender_id'];
		$select_match_sender_id = _yesno('sandbox_match_sender_id', $sandbox_match_sender_id, '', '', '', 'playsms-sandbox-match-sender-id', 'form-control');
		
		// sandbox prefix
		$data = registry_search(1, 'feature', 'incoming', 'sandbox_prefix');
		$sandbox_prefix = trim(strtoupper(core_sanitize_alphanumeric($data['feature']['incoming']['sandbox_prefix'])));
		unset($params);
		$params = array(
			'size' => '100%',
			'maxlength' => 30,
			'placeholder' => _('Insert keyword') ,
		);
		$input_prefix = _input('text', 'sandbox_prefix', $sandbox_prefix, $params, 'playsms-sandbox-prefix', 'form-control');
		
		// sandbox forward to users
		$data = registry_search(1, 'feature', 'incoming', 'sandbox_forward_to');
		$sandbox_forward_to = array_unique(unserialize($data['feature']['incoming']['sandbox_forward_to']));
		unset($params);
		$params = array(
			'width' => '100%',
			'placeholder' => _('Select users')
		);
		$select_users = themes_select_users_multi('uids', $sandbox_forward_to, $params, 'playsms-route-to-users');
		
		$form_data = array(
			array(
				'id' => 'playsms-sandbox-match-sender-id',
				'label' => _('Route all sandbox SMS with matched sender ID') ,
				'input' => $select_match_sender_id,
				'help' => _('Route to user inbox if receiver number matched with user sender ID') ,
			) ,
			array(
				'id' => 'playsms-sandbox-prefix',
				'label' => _('Route all sandbox SMS to keyword') ,
				'input' => $input_prefix,
				'help' => _('A valid keyword will be inserted and prefixed to the message') ,
			) ,
			array(
				'id' => 'playsms-route-to-users',
				'label' => _('Route all sandbox SMS to users') ,
				'input' => $select_users,
				'help' => '',
			) ,
		);
		
		$tpl = array(
			'name' => 'incoming',
			'vars' => array(
				'ERROR' => _err_display() ,
				'PAGE_TITLE' => _('Route incoming SMS') ,
				'ACTION_URL' => _u('index.php?app=main&inc=feature_incoming&op=incoming_save') ,
				'HTTP_PATH_THEMES' => _HTTP_PATH_THEMES_,
				'Save' => _('Save') ,
			) ,
			'loops' => array(
				'form' => $form_data,
			) ,
		);
		_p(tpl_apply($tpl));
		break;

	case "incoming_save":
		
		// sandbox match receiver number and sender ID
		$sandbox_match_sender_id = (int)$_REQUEST['sandbox_match_sender_id'];
		$items['sandbox_match_sender_id'] = $sandbox_match_sender_id;
		
		// sandbox prefix
		$sandbox_prefix = trim(strtoupper(core_sanitize_alphanumeric($_REQUEST['sandbox_prefix'])));
		if ($sandbox_prefix && checkavailablekeyword($sandbox_prefix)) {
			$_SESSION['error_string'][] = _('Fail to insert keyword') . ' (' . _('keyword') . ': ' . $sandbox_prefix . ')';
			$sandbox_prefix = '';
		}
		$items['sandbox_prefix'] = $sandbox_prefix;
		
		// sandbox forward to users
		$sandbox_forward_to = serialize(array_unique($_REQUEST['uids']));
		$items['sandbox_forward_to'] = $sandbox_forward_to;
		
		// save to registry
		if (count($items)) {
			registry_update(1, 'feature', 'incoming', $items);
			$_SESSION['error_string'][] = _('Incoming SMS route changes has been saved');
		} else {
			$_SESSION['error_string'] = _('No route has been saved');
		}
		
		header("Location: " . _u('index.php?app=main&inc=feature_incoming&op=incoming'));
		exit();
		break;
}