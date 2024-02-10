<?php

$cfg = require __DIR__ . '/../vendor/mediawiki/mediawiki-phan-config/src/config.php';

// TODO Create a ldap phan stub and remove this line (to bump to 8.1)
$cfg['target_php_version'] = '7.4';

$cfg['file_list'][] = 'includes/LdapAuthenticationPlugin.php';
$cfg['file_list'][] = 'includes/LdapPrimaryAuthenticationProvider.php';

return $cfg;
