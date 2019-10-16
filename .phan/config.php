<?php

$cfg = require __DIR__ . '/../vendor/mediawiki/mediawiki-phan-config/src/config.php';

$cfg['file_list'][] = 'LdapAuthentication.php';
$cfg['file_list'][] = 'includes/LdapAuthenticationPlugin.php';
$cfg['file_list'][] = 'includes/LdapAutoAuthentication.php';
$cfg['file_list'][] = 'includes/LdapPrimaryAuthenticationProvider.php';

// Most is in global scope, because this extension does not using extension.json yet
$cfg['exclude_analysis_directory_list'][] = 'LdapAuthentication.php';

return $cfg;
