<?php

/**
 * Class Api.
 *
 * This class contains all the necessary
 * functions to communicate with Instapage.
 *
 * @package Drupal\instapage
 */
class InstapageApi {

  const ENDPOINT = 'http://app.instapage.com';
  const METHOD = 'POST';

  /**
   * Sends out an API call and returns the results.
   *
   * @param string $action
   * @param array $headers
   * @param array $params
   *
   * @return array|bool
   */
  public function createRequest($action = '', $headers = array(), $params = array()) {
    $headers['integration'] = 'drupal';
    $headers['Content-Type'] = 'application/x-www-form-urlencoded';

    $params = http_build_query($params);

    $options = array(
      'method' => self::METHOD,
      'headers' => $headers,
      'data' => $params,
    );
    $url = self::ENDPOINT . '/api/plugin/page' . $action;

    $request = drupal_http_request($url, $options);
    if ($request->code == 200) {
      $headers = $request->headers;
      return array(
        'body' => (string) $request->data,
        'status' => $request->status_message,
        'code' => $request->code,
        'headers' => $headers,
      );
    }
    return FALSE;
  }

  /**
   * Save a user in config and register him through the API.
   *
   * @param $email
   * @param $token
   */
  public function registerUser($email, $token) {
    variable_set('instapage_user_id', $email);
    variable_set('instapage_plugin_hash', $token);
    $this->connectKeys($token);
  }

  /**
   * Verify the user email and password.
   *
   * @param $email
   * @param $password
   *
   * @return array
   */
  public function authenticate($email, $password) {
    $reponse = $this->createRequest('', array(), array(
      'email' => $email,
      'password' => $password,
    ));
    if ($reponse && $reponse['code'] == 200) {
      $decoded = json_decode($reponse['body']);
      return array('status' => 200, 'content' => $decoded->data->usertoken);
    }
    return array('error' => TRUE, 'content' => t('Login failed.'));
  }

  /**
   * Callback for getting the account keys.
   *
   * @param $token
   *
   * @return array
   */
  public function getAccountKeys($token) {
    $reponse = $this->createRequest('/get-account-keys', array('usertoken' => $token), array('ping' => TRUE));
    if ($reponse && $reponse['code'] == 200) {
      $decoded = json_decode($reponse['body']);
      return array('status' => 200, 'content' => $decoded->data->accountkeys);
    }
    return array('error' => TRUE, 'content' => t('Login failed.'));
  }

  /**
   * Callback for getting a list of all pages.
   *
   * @param $token
   *
   * @return array|mixed
   */
  public function getPageList($token) {
    $encoded = $this->getEncodedKeys($token);
    if ($encoded) {
      $response = $this->createRequest('/list', array('accountkeys' => $encoded), array('ping' => TRUE));
      $decoded = json_decode($response['body']);
      $data = array();

      // Fetch available subaccounts from the API.
      $subAccounts = $this->getSubAccounts($token);
      if (!empty($decoded->data)) {
        foreach ($decoded->data as $item) {
          $data[$item->id] = $item->title;

          // If possible add the subaccount label in brackets.
          if (isset($item->subaccount) && array_key_exists($item->subaccount, $subAccounts)) {
            $data[$item->id] .= ' (' . $subAccounts[$item->subaccount] . ')';
          }
        }
      }
      // Save page labels in config.
      variable_set('instapage_page_labels', $data);
      return $decoded;
    }
    return array('error' => TRUE, 'content' => t('Login failed.'));
  }

  /**
   * Returns encoded account keys.
   *
   * @param $token
   *
   * @return bool|string
   */
  public function getEncodedKeys($token) {
    $keys = $this->getAccountKeys($token);
    if (isset($keys['status']) && $keys['status'] == 200) {
      return base64_encode(json_encode($keys['content']));
    }
    return FALSE;
  }

  /**
   * Callback to edit a page.
   *
   * @param $page_id
   * @param $path
   * @param $token
   */
  public function editPage($page_id, $path, $token, $publish = 1) {
    $encoded = $this->getEncodedKeys($token);
    if ($encoded) {
      $headers = array(
        'accountkeys' => $encoded,
      );
      $params = array(
        'page' => $page_id,
        'url' => $path,
        'publish' => $publish,
      );
      $this->createRequest('/edit', $headers, $params);

      // Get existing page paths from config.
      $pages = variable_get('instapage_pages', array());

      // Publishing a page.
      if ($publish) {
        $pages[$page_id] = $path;
      }
      else {
        // When unpublishing a page remove it from config.
        if (array_key_exists($page_id, $pages)) {
          unset($pages[$page_id]);
        }
      }
      // Save new page paths to config.
      variable_set('instapage_pages', $pages);
    }
  }

  /**
   * API call to connect current domain to Drupal publishing on Instapage.
   *
   * @param $token
   */
  public function connectKeys($token) {
    $encoded = $this->getEncodedKeys($token);
    if ($encoded) {
      $domain = (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME']);
      $headers = array(
        'accountkeys' => $encoded,
      );
      $params = array(
        'accountkeys' => $encoded,
        'status' => 'connect',
        'domain' => $domain,
      );
      $this->createRequest('/connection-status', $headers, $params);
    }
  }

  /**
   * Fetch the subaccounts from the API.
   *
   * @param $token
   *
   * @return array
   */
  public function getSubAccounts($token) {
    $encoded = $this->getEncodedKeys($token);
    if ($encoded) {
      $headers = array(
        'accountkeys' => $encoded,
      );
      $reponse = $this->createRequest('/get-sub-accounts-list', $headers);
      if ($reponse && $reponse['code'] == 200) {
        $decode = json_decode($reponse['body']);
        $accounts = array();
        // Create array of subaccounts and return it.
        foreach ($decode->data as $item) {
          $accounts[$item->id] = $item->name;
        }
        return $accounts;
      }
    }
    return array();
  }
}
