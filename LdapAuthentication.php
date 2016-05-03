<?php
# Copyright (C) 2004 Ryan Lane <http://www.mediawiki.org/wiki/User:Ryan_lane>
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License along
# with this program; if not, write to the Free Software Foundation, Inc.,
# 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
# http://www.gnu.org/copyleft/gpl.html

/**
 * LdapAuthentication plugin. LDAP Authentication and authorization integration with MediaWiki.
 *
 * @file
 * @ingroup MediaWiki
 */

#
# LdapAuthentication.php
#
# Info available at http://www.mediawiki.org/wiki/Extension:LDAP_Authentication
# Support is available at http://www.mediawiki.org/wiki/Extension_talk:LDAP_Authentication
#

if ( !defined( 'MEDIAWIKI' ) ) exit;

$wgLDAPDomainNames = array();
$wgLDAPServerNames = array();
$wgLDAPUseLocal = false;
$wgLDAPEncryptionType = array();
$wgLDAPOptions = array();
$wgLDAPPort = array();
$wgLDAPSearchStrings = array();
$wgLDAPProxyAgent = array();
$wgLDAPProxyAgentPassword = array();
$wgLDAPSearchAttributes = array();
$wgLDAPBaseDNs = array();
$wgLDAPGroupBaseDNs = array();
$wgLDAPUserBaseDNs = array();
$wgLDAPWriterDN = array();
$wgLDAPWriterPassword = array();
$wgLDAPWriteLocation = array();
$wgLDAPAddLDAPUsers = array();
$wgLDAPUpdateLDAP = array();
$wgLDAPPasswordHash = array();
$wgLDAPMailPassword = array();
$wgLDAPPreferences = array();
$wgLDAPDisableAutoCreate = array();
$wgLDAPDebug = 0;
$wgLDAPGroupUseFullDN = array();
$wgLDAPLowerCaseUsername = array();
$wgLDAPGroupUseRetrievedUsername = array();
$wgLDAPGroupObjectclass = array();
$wgLDAPGroupAttribute = array();
$wgLDAPGroupNameAttribute = array();
$wgLDAPGroupsUseMemberOf = array();
$wgLDAPUseLDAPGroups = array();
$wgLDAPLocallyManagedGroups = array();
$wgLDAPGroupsPrevail = array();
$wgLDAPRequiredGroups = array();
$wgLDAPExcludedGroups = array();
$wgLDAPGroupSearchNestedGroups = array();
$wgLDAPAuthAttribute = array();
$wgLDAPAutoAuthUsername = "";
$wgLDAPAutoAuthDomain = "";
$wgPasswordResetRoutes['domain'] = true;
$wgLDAPActiveDirectory = array();

define( "LDAPAUTHVERSION", "2.1.0" );

/**
 * Add extension information to Special:Version
 */
$wgExtensionCredits['other'][] = array(
	'path' => __FILE__,
	'name' => 'LDAP Authentication Plugin',
	'version' => LDAPAUTHVERSION,
	'author' => 'Ryan Lane',
	'descriptionmsg' => 'ldapauthentication-desc',
	'url' => 'https://www.mediawiki.org/wiki/Extension:LDAP_Authentication',
	'license-name' => 'GPL-2.0+',
);

$wgAutoloadClasses['LdapAuthenticationPlugin'] = __DIR__ . '/LdapAuthenticationPlugin.php';
$wgAutoloadClasses['LdapPrimaryAuthenticationProvider'] = __DIR__ . '/LdapPrimaryAuthenticationProvider.php';

$wgMessagesDirs['LdapAuthentication'] = __DIR__ . '/i18n';

# Schema changes
$wgHooks['LoadExtensionSchemaUpdates'][] = 'efLdapAuthenticationSchemaUpdates';

/**
 * @param $updater DatabaseUpdater
 * @return bool
 */
function efLdapAuthenticationSchemaUpdates( $updater ) {
	$base = __DIR__;
	switch ( $updater->getDB()->getType() ) {
	case 'mysql':
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
	global $wgHooks;
	global $wgAuth;
	global $wgDisableAuthManager;

	if ( class_exists( MediaWiki\Auth\AuthManager::class ) && empty( $wgDisableAuthManager ) ) {
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

	$wgAuth = LdapAuthenticationPlugin::getInstance();

	$wgAuth->printDebug( "Entering AutoAuthSetup.", NONSENSITIVE );

	# We need both authentication username and domain (bug 34787)
	if ( $wgAuth->getConf("AutoAuthUsername") !== "" && $wgAuth->getConf("AutoAuthDomain") !== "" ) {
		$wgAuth->printDebug( "wgLDAPAutoAuthUsername and wgLDAPAutoAuthDomain is not null, adding hooks.", NONSENSITIVE );
		$wgHooks['UserLoadAfterLoadFromSession'][] = 'LdapAutoAuthentication::Authenticate';

		$wgHooks['PersonalUrls'][] = 'LdapAutoAuthentication::NoLogout'; /* Disallow logout link */
	}
}
