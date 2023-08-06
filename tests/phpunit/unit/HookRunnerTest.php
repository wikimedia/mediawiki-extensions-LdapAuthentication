<?php

namespace MediaWiki\Extension\LdapAuthentication\Tests\Unit;

use MediaWiki\Extension\LdapAuthentication\Hooks\HookRunner;
use MediaWiki\Tests\HookContainer\HookRunnerTestBase;

/**
 * @covers \MediaWiki\Extension\LdapAuthentication\Hooks\HookRunner
 */
class HookRunnerTest extends HookRunnerTestBase {

	public static function provideHookRunners() {
		yield HookRunner::class => [ HookRunner::class ];
	}
}
