<?php
// This file declares various managed database records.
// The records will be automatically inserted, updated, or deleted from the
// database as appropriate. For more details, see "hook_civicrm_managed" at:
// http://wiki.civicrm.org/confluence/display/CRMDOC44/Hook+Reference

return array(
  array(
    'name'   => 'smsstop:SMS_STOP_acknowledgement',
    'entity' => 'MessageTemplate',
    'params' => array(
      'version'   => 3,
      'msg_title' => 'SMS STOP acknowledgement',
      'msg_text'  => "Hi {contact.first_name}, we're sorry that you have chosen to unsubscribe from our messages. Your choice has been recorded.",
    ),
  ),
);
