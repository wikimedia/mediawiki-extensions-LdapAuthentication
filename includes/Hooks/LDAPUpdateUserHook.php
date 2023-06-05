<?php

namespace MediaWiki\Extension\LdapAuthentication\Hooks;

use User;

/**
 * This is a hook handler interface, see docs/Hooks.md in core.
 * Use the hook name "LDAPUpdateUser" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface LDAPUpdateUserHook {
	/**
	 * @param User &$user
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLDAPUpdateUser( &$user );
}
