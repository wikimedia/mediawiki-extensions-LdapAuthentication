<?php

namespace MediaWiki\Extension\LdapAuthentication\Hooks;

/**
 * This is a hook handler interface, see docs/Hooks.md in core.
 * Use the hook name "SetUsernameAttributeFromLDAP" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface SetUsernameAttributeFromLDAPHook {
	/**
	 * @param string &$hookSetUsername
	 * @param array|null $userInfo
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSetUsernameAttributeFromLDAP( &$hookSetUsername, $userInfo );
}
