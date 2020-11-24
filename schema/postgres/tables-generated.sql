-- This file is automatically generated using maintenance/generateSchemaSql.php.
-- Source: /var/www/wiki/mediawiki/extensions/LdapAuthentication/schema/tables.json
-- Do not modify this file directly.
-- See https://www.mediawiki.org/wiki/Manual:Schema_changes
CREATE TABLE ldap_domains (
  domain_id INT NOT NULL,
  domain VARCHAR(255) NOT NULL,
  user_id INT NOT NULL,
  PRIMARY KEY(domain_id)
);

CREATE INDEX user_id ON ldap_domains (user_id);
