<?php

namespace MediaWiki\Extension\LdapAuthentication\Hooks;

use LdapAuthenticationPlugin;

/**
 * This is a hook handler interface, see docs/Hooks.md in core.
 * Use the hook name "LDAPSetCreationValues" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface LDAPSetCreationValuesHook {
	/**
	 * @param LdapAuthenticationPlugin $plugin
	 * @param string $username
	 * @param array &$values
	 * @param string|null $writeloc
	 * @param string &$userdn
	 * @param bool &$result
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLDAPSetCreationValues( $plugin, $username, &$values, $writeloc, &$userdn, &$result );
}
