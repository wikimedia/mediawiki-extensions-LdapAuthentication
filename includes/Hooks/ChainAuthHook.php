<?php

namespace MediaWiki\Extension\LdapAuthentication\Hooks;

/**
 * This is a hook handler interface, see docs/Hooks.md in core.
 * Use the hook name "ChainAuth" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface ChainAuthHook {
	/**
	 * @param string $username
	 * @param string $password
	 * @param bool &$result
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onChainAuth( $username, $password, &$result );
}
