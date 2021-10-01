<?php
// https://developers.whmcs.com/hooks-reference/domain/#predomainregister
// https://developers.whmcs.com/domain-registrars/module-parameters/
// https://developers.whmcs.com/api-reference/updateclientdomain/

/**
 * Automatically Enable Domain Prior to Registering domain
 *
 * @package     WHMCS
 * @copyright   meramsey
 * @link        https://github.com/meramsey
 * @author      Michael Ramsey <mike@hackerdise.me>
 */

if (!defined("WHMCS"))
    die("This file cannot be accessed directly");

function enable_domain_privacy($vars) {
	//logActivity('enable_domain_privacy hook called');
	if (isset($vars['domainid'])) {
		$domain_id = $vars['domainid'];
		$domainName = $vars['params']['sld'] . '.' . $vars['params']['tld'];
	} else {
		return;
	}
	// logActivity("enable_domain_privacy hook provided with domainid: $domain_id for domain: $domainName");
	$command = 'UpdateClientDomain';
	$postData = array(
		'domainid' => $domain_id,
		'idprotection' => true,
	);
	
  $adminUsername = ''; // Optional for WHMCS 7.2 and later
	$results = localAPI($command, $postData, $adminUsername);
  $info = "For domainid: $domain_id" ;
	if ($results['result'] == 'success') {
		$msg = 'Domain id protection set successfully! ' . $info;
	} else {
		$msg = "An Error Occurred while updating Domain id protection: " . $results['message'] . ' ' . $info ;
	}
	logActivity($msg);

}
add_hook('PreRegistrarRegisterDomain',1,"enable_domain_privacy");
