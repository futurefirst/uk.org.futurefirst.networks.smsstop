<?php

require_once 'smsstop.civix.php';

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function smsstop_civicrm_config(&$config) {
  _smsstop_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @param $files array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function smsstop_civicrm_xmlMenu(&$files) {
  _smsstop_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function smsstop_civicrm_install() {
  _smsstop_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function smsstop_civicrm_uninstall() {
  _smsstop_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function smsstop_civicrm_enable() {
  _smsstop_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function smsstop_civicrm_disable() {
  _smsstop_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed
 *   Based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function smsstop_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _smsstop_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function smsstop_civicrm_managed(&$entities) {
  _smsstop_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function smsstop_civicrm_caseTypes(&$caseTypes) {
  _smsstop_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function smsstop_civicrm_angularModules(&$angularModules) {
_smsstop_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function smsstop_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _smsstop_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Functions below this ship commented out. Uncomment as required.
 *

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function smsstop_civicrm_preProcess($formName, &$form) {

}

*/

/**
 * Implements hook_civicrm_pre().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_pre
 */
function smsstop_civicrm_pre($op, $objectName, $id, &$params) {
  // Must be an Inbound SMS activity, being created, beginning with a keyword
  if ($op != 'create' || $objectName != 'Activity') {
    return;
  }

  $body = strtoupper(trim($params['details']));
  if (!preg_match("/^(STOP|END|CANCEL|UNSUBSCRIBE|QUIT)\b/", $body)) {
    return;
  }

  $activityTypeID = CRM_Core_OptionGroup::getValue('activity_type', 'Inbound SMS', 'name');
  if ($params['activity_type_id'] != $activityTypeID) {
    return;
  }

  // Get contact IDs
  // Note: Personally I think CRM_SMS_Provider::processInbound does these the
  // wrong way round, as it doesn't match inbound emails. So handle FF as a special case.
  $domain = civicrm_api('Domain', 'get', array('version' => 3, 'sequential' => 1));
  if (
    $domain['values'][0]['name'] == 'Future First' &&
    strpos($domain['values'][0]['domain_email'], '@futurefirst.org.uk') !== FALSE
  ) {
    $remoteCid = $params['source_contact_id'];
    $ourCid    = $params['target_contact_id'] ?: $params['source_contact_id'];
  }
  else {
    $remoteCid = $params['target_contact_id'];
    $ourCid    = $params['source_contact_id'] ?: $params['target_contact_id'];
  }

  CRM_Core_Error::debug_log_message("SMS STOP message received from cid $remoteCid");

  // Get default SMS provider and our template
  $providerId = CRM_Core_DAO::singleValueQuery("
    SELECT `id`
      FROM `civicrm_sms_provider`
     WHERE `is_default` IS TRUE
       AND `is_active`  IS TRUE
  ORDER BY `id`         ASC
     LIMIT 1
  ");
  $templateId = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_MessageTemplate', 'SMS STOP acknowledgement', 'id', 'msg_title');

  // Send an acknowledgement
  // Note: from_contact_id is required if we're not logged in
  // (which is the case during an SMS callback), as the Outbound SMS
  // being created needs a source contact.
  $ack_result = civicrm_api('Sms', 'Send', array(
    'version'         => 3,
    'contact_id'      => $remoteCid,
    'from_contact_id' => $ourCid,
    'template_id'     => $templateId,
    'provider_id'     => $providerId,
  ));
  if (civicrm_error($ack_result)) {
    CRM_Core_Error::debug_log_message("Error sending SMS STOP acknowledgement to cid $remoteCid: " . $ack_result['error_message']);
  }

  // Set them as 'do not SMS'
  $set_result = civicrm_api('Contact', 'create', array(
    'version'    => 3,
    'id'         => $remoteCid,
    'do_not_sms' => 1,
  ));
  if (civicrm_error($set_result)) {
    CRM_Core_Error::debug_log_message("Error setting 'do not SMS' on cid $remoteCid: " . $set_result['error_message']);
  }
}
