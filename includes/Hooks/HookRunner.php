<?php

namespace MediaWiki\Extension\LdapAuthentication\Hooks;

use MediaWiki\HookContainer\HookContainer;

/**
 * This is a hook runner class, see docs/Hooks.md in core.
 * @internal
 */
class HookRunner implements
	ChainAuthHook,
	LDAPRetrySetCreationValuesHook,
	LDAPSetCreationValuesHook,
	LDAPUpdateUserHook,
	SetUsernameAttributeFromLDAPHook
{
	private HookContainer $hookContainer;

	public function __construct( HookContainer $hookContainer ) {
		$this->hookContainer = $hookContainer;
	}

	/**
	 * @inheritDoc
	 */
	public function onChainAuth( $username, $password, &$result ) {
		return $this->hookContainer->run(
			'ChainAuth',
			[ $username, $password, &$result ]
		);
	}

	/**
	 * @inheritDoc
	 */
	public function onLDAPRetrySetCreationValues( $plugin, $username, &$values, $writeloc, &$userdn, &$result ) {
		return $this->hookContainer->run(
			'LDAPRetrySetCreationValues',
			[ $plugin, $username, &$values, $writeloc, &$userdn, &$result ]
		);
	}

	/**
	 * @inheritDoc
	 */
	public function onLDAPSetCreationValues( $plugin, $username, &$values, $writeloc, &$userdn, &$result ) {
		return $this->hookContainer->run(
			'LDAPSetCreationValues',
			[ $plugin, $username, &$values, $writeloc, &$userdn, &$result ]
		);
	}

	/**
	 * @inheritDoc
	 */
	public function onLDAPUpdateUser( &$user ) {
		return $this->hookContainer->run(
			'LDAPUpdateUser',
			[ &$user ]
		);
	}

	/**
	 * @inheritDoc
	 */
	public function onSetUsernameAttributeFromLDAP( &$hookSetUsername, $userInfo ) {
		return $this->hookContainer->run(
			'SetUsernameAttributeFromLDAP',
			[ &$hookSetUsername, $userInfo ]
		);
	}
}
