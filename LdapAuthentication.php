<?php
/**
 * Copyright (C) 2004 Ryan Lane <http://www.mediawiki.org/wiki/User:Ryan_lane>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 */

/**
 * LdapAuthentication plugin. LDAP Authentication and authorization integration with MediaWiki.
 *
 * @file
 * @ingroup MediaWiki
 */

/**
 * LdapAuthentication.php
 *
 * Info available at https://www.mediawiki.org/wiki/Extension:LDAP_Authentication
 * Support is available at https://www.mediawiki.org/wiki/Extension_talk:LDAP_Authentication
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	exit;
}

$wgLDAPDomainNames = [];
$wgLDAPServerNames = [];
$wgLDAPUseLocal = false;
$wgLDAPEncryptionType = [];
$wgLDAPOptions = [];
$wgLDAPPort = [];
$wgLDAPSearchStrings = [];
$wgLDAPProxyAgent = [];
$wgLDAPProxyAgentPassword = [];
$wgLDAPSearchAttributes = [];
$wgLDAPBaseDNs = [];
$wgLDAPGroupBaseDNs = [];
$wgLDAPUserBaseDNs = [];
$wgLDAPWriterDN = [];
$wgLDAPWriterPassword = [];
$wgLDAPWriteLocation = [];
$wgLDAPAddLDAPUsers = [];
$wgLDAPUpdateLDAP = [];
$wgLDAPPasswordHash = [];
$wgLDAPMailPassword = [];
$wgLDAPPreferences = [];
$wgLDAPDisableAutoCreate = [];
$wgLDAPDebug = 0;
$wgLDAPGroupUseFullDN = [];
$wgLDAPLowerCaseUsername = [];
$wgLDAPGroupUseRetrievedUsername = [];
$wgLDAPGroupObjectclass = [];
$wgLDAPGroupAttribute = [];
$wgLDAPGroupNameAttribute = [];
$wgLDAPGroupsUseMemberOf = [];
$wgLDAPUseLDAPGroups = [];
$wgLDAPLocallyManagedGroups = [];
$wgLDAPGroupsPrevail = [];
$wgLDAPRequiredGroups = [];
$wgLDAPExcludedGroups = [];
$wgLDAPGroupSearchNestedGroups = [];
$wgLDAPAuthAttribute = [];
$wgLDAPAutoAuthUsername = "";
$wgLDAPAutoAuthDomain = "";
$wgPasswordResetRoutes['domain'] = true;
$wgLDAPActiveDirectory = [];
$wgLDAPGroupSearchPosixPrimaryGroup = false;

define( "LDAPAUTHVERSION", "2.1.0" );

/**
 * Add extension information to Special:Version
 */
$wgExtensionCredits['other'][] = [
	'path' => __FILE__,
	'name' => 'LDAP Authentication Plugin',
	'version' => LDAPAUTHVERSION,
	'author' => 'Ryan Lane',
	'descriptionmsg' => 'ldapauthentication-desc',
	'url' => 'https://www.mediawiki.org/wiki/Extension:LDAP_Authentication',
	'license-name' => 'GPL-2.0-or-later',
];

$wgAutoloadClasses['LdapAuthenticationPlugin'] = __DIR__ . '/LdapAuthenticationPlugin.php';
$wgAutoloadClasses['LdapPrimaryAuthenticationProvider'] =
	__DIR__ . '/LdapPrimaryAuthenticationProvider.php';

$wgMessagesDirs['LdapAuthentication'] = __DIR__ . '/i18n';

# Schema changes
$wgHooks['LoadExtensionSchemaUpdates'][] = 'efLdapAuthenticationSchemaUpdates';

/**
 * @param DatabaseUpdater $updater
 * @return bool
 */
function efLdapAuthenticationSchemaUpdates( $updater ) {
	$base = __DIR__;
	switch ( $updater->getDB()->getType() ) {
	case 'mysql':
	case 'sqlite':
		$updater->addExtensionTable( 'ldap_domains', "$base/schema/ldap-mysql.sql" );
		break;
	case 'postgres':
		$updater->addExtensionTable( 'ldap_domains', "$base/schema/ldap-postgres.sql" );
		break;
	}
	return true;
}

// constants for search base
define( "GROUPDN", 0 );
define( "USERDN", 1 );
define( "DEFAULTDN", 2 );

// constants for error reporting
define( "NONSENSITIVE", 1 );
define( "SENSITIVE", 2 );
define( "HIGHLYSENSITIVE", 3 );

// The auto-auth code was originally derived from the SSL Authentication plugin
// http://www.mediawiki.org/wiki/SSL_authentication

/**
 * Sets up the auto-authentication piece of the LDAP plugin.
 *
 * @access public
 */
function AutoAuthSetup() {
	/**
	 * @todo If you want to make AutoAuthSetup() work in an AuthManager
	 *  world, what you need to do is figure out how to do it with a
	 *  SessionProvider instead of the hackiness below. You'll probably
	 *  want an ImmutableSessionProviderWithCookie subclass where
	 *  provideSessionInfo() does the first part of
	 *  LdapAutoAuthentication::Authenticate() (stop before the $localId
	 *  bit).
	 */
	throw new BadFunctionCallException( 'AutoAuthSetup() is not supported with AuthManager.' );
}
