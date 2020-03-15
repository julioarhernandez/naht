<?php
/**
 * @file
 * Administration page for the module.
 *
 */

/**
 * Administration settings/operation form.
 */
function field_ssid_admin_settings($form, &$form_state) {

  $form['field_ssid_intro'] = array(
    '#markup' => "Do not change anything on the page unless you're know exactly what are you doing. Be careful changing encryption mechanism when you already have a data in the database.",
  );

  $form['field_ssid_mask'] = array(
    '#title' => t('Mask of SSID'),
    '#type' => 'textfield',
    '#default_value' => variable_get('field_ssid_mask', '\d\d\d\-\d\d\-\d\d\d\d'),
    '#description' => t('Mask for SSID field in PCRE format. Initially field has been created for United States Social Security ID, but definitely can be used for similar purposes for different documents and countries.'),
  );

  $form['field_ssid_encryption'] = array(
    '#title' => t('Encryption functions'),
    '#type' => 'textfield',
    '#default_value' => variable_get('field_ssid_encryption', "aes_encrypt:aes_decrypt"),
    '#description' => t('SSID can be stored in encrypted form, if these functions are available. Out of the box it supports the AES module, with default setting "aes_encrypt:aes_decrypt". Clear this field to turn off encryption.'),
  );

  return system_settings_form($form);
}

/**
 * Validation handler for settings form.
 */
function field_ssid_admin_settings_validate($form, &$form_state) {
  /* @var $cryptor field_ssid_cryptor */
  $cryptor = field_ssid_get_cryptor();
  if (!$cryptor->verify($form_state['values']['field_ssid_encryption'])) {
    form_set_error('field_ssid_encryption', t('Incorrect settings: %error', array('%error' => $cryptor->getError())));
  }
}
