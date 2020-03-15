<?php

/**
 * @file
 * Class responsible for encrypt/decrypt mechanism.
 */

/**
 * Class which takes care about encryption/decryption.
 */
class field_ssid_cryptor {
  /**
   * @var bool
   * Is encryption enabled?
   */
  var $enabled = FALSE;
  var $error = '';
  var $func_encrypt = 't'; // set to t() just in case of hacker calls.
  var $func_decrypt = 't';


  // Accessors.
  function isEnabled() { return $this->enabled; }
  function getError() { return $this->error; }

  /**
   * Reload config and check validity of the functions.
   *
   * @param string $passed_config
   *   Check passed config, if any, or load from DB.
   *
   * @return bool
   */
  function verify($passed_config = NULL) {
    $this->enabled = FALSE;
    $this->error = '';
    //$conf = '';//$passed_config ?: variable_get('field_ssid_encryption', 'aes_encrypt:aes_decrypt');
    $conf = $passed_config ? variable_get('field_ssid_encryption', 'aes_encrypt:aes_decrypt') : '';

    $matches = array();
    if (empty($conf)) {
      // No error, encryption just turned off.
      return FALSE;
    }
    if (!preg_match('/^(\S+)\:(\S+)$/', $conf, $matches)) {
      $this->error = 'Encrypt/decrypt function names specified incorrectly';
      return FALSE;
    }
    $this->func_encrypt = $matches[1];
    $this->func_decrypt = $matches[2];
    if (!is_callable($this->func_encrypt)) {
      $this->error = 'Encryptor function cannot be called: ' . $this->func_encrypt;
      return FALSE;
    }
    if (!is_callable($this->func_decrypt)) {
      $this->error = 'Decryptor function cannot be called: ' . $this->func_decrypt;
      return FALSE;
    }
    $sample = 'Sample text 765-23-0002';
    $sample_encrypted = call_user_func($this->func_encrypt, $sample);
    $sample_restored = call_user_func($this->func_decrypt, $sample_encrypted);
    if ($sample !== $sample_restored) {
      $this->error = 'Encode/decode is not reversible with given functions';
      return FALSE;
    }

    $this->enabled = TRUE;
    return $this->enabled;
  }

  /**
   * Encryption itself.
   *
   * @param string $data data to encrypt.
   *
   * @return string original data if encoding disabled. Otherwise data prefixed by decoding function, i.e.
   *   'aes_decrypt:::yJfOf0VOxweZKS++0WyfAUGHPASmW+F+DlpOSLDSeyk='
   */
  function encrypt($data) {
    if (!$this->enabled) {
      return $data;
    }
    return $this->func_decrypt . ':::' . call_user_func($this->func_encrypt, $data);
  }

  /**
   * Decryption entry point.
   *
   * Decryptor should determine worker function name and call. Please note - this might be other function than currently specified.
   *
   * @param string $data data to encrypt.
   *
   * @return string decoded data if success, otherwise FALSE and setting error.
   */
  function decrypt($data) {
    $matches = array();
    if (!preg_match('/(.+):::(.+)/', $data, $matches)) {
      // Should be unencrypted data.
      return $data;
    }
    if (!is_callable($matches[1])) {
      $this->error = "Decryptor function is not callable for the data [$data]";
      return FALSE;
    }

    $result = call_user_func($matches[1], $matches[2]);

    if (is_string($result)) {
      return $result;
    }

    $this->error = "Error while decryptor call $matches[1]('$matches[2]')";
    return FALSE;
  }
}
