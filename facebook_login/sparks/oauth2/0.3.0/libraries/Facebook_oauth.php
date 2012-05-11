<?php
/**
 * Copyright 2011 Facebook, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

require_once "base_facebook.php";

/**
 * Extends the BaseFacebook class with the intent of using
 * PHP sessions to store user ids and access tokens.
 */


/**
 * Facebook OAuth 2 class
 */
class Facebook_oauth extends BaseFacebook
{
  /* Verify SSL Cert. */
  public $verifypeer = FALSE;
  /* Decode returned json data. */
  public $decode_JSON = TRUE;
  /* Set connect timeout. */
  public $connecttimeout = 30;
  /* Set timeout default. */
  public $timeout = 30;
  /* Set the useragent. */
  public $useragent = "FacebookOAuth v0.0.4 | http://github.com/Zae/FacebookOAuth";
  /* HTTP Proxy settings (will only take effect if you set 'behind_proxy' to true) */
  public $proxy_settings = array(
    'behind_proxy' => false,
    'host' => '',
    'port' => '',
    'user' => '',
    'pass' => '',
    'type' => CURLPROXY_HTTP,
    'auth' => CURLAUTH_BASIC
  );
  /* Contains the last HTTP status code returned. */
  public $http_code;
  /* Contains the last HTTP headers returned. */
  public $http_info = array();
  /* Contains the last API call. */
  public $url;
  /* Contains last http_headers */
  public $http_header = array();
  
  /* Variables used internally by the class and subclasses */
  protected $client_id, $client_secret, $access_token;
  protected $callback_url;
  
  protected static $METHOD_GET = "GET";
  protected static $METHOD_POST = "POST";
  protected static $METHOD_DELETE = "DELETE";
  
  /* Set API URLS */
  const AuthorizeUrl = 'https://graph.facebook.com/oauth/authorize';
  const AccessTokenUrl = 'https://graph.facebook.com/oauth/access_token';
  const GraphUrl = 'https://graph.facebook.com/';


  public function __construct($config) {
    if (!session_id()) {
      session_start();
    }
    $this->client_id      = $config['appId'];
    $this->client_secret  = $config['secret'];
    $this->callback_url   = $config['callback_url'];
    parent::__construct($config);
  }

  protected static $kSupportedKeys =
    array('state', 'code', 'access_token', 'user_id');

  /**
   * Provides the implementations of the inherited abstract
   * methods.  The implementation uses PHP sessions to maintain
   * a store for authorization codes, user ids, CSRF states, and
   * access tokens.
   */
  protected function setPersistentData($key, $value) {
    if (!in_array($key, self::$kSupportedKeys)) {
      self::errorLog('Unsupported key passed to setPersistentData.');
      return;
    }

    $session_var_name = $this->constructSessionVariableName($key);
    $_SESSION[$session_var_name] = $value;
  }

  protected function getPersistentData($key, $default = false) {
    if (!in_array($key, self::$kSupportedKeys)) {
      self::errorLog('Unsupported key passed to getPersistentData.');
      return $default;
    }

    $session_var_name = $this->constructSessionVariableName($key);
    return isset($_SESSION[$session_var_name]) ?
      $_SESSION[$session_var_name] : $default;
  }

  protected function clearPersistentData($key) {
    if (!in_array($key, self::$kSupportedKeys)) {
      self::errorLog('Unsupported key passed to clearPersistentData.');
      return;
    }

    $session_var_name = $this->constructSessionVariableName($key);
    unset($_SESSION[$session_var_name]);
  }

  protected function clearAllPersistentData() {
    foreach (self::$kSupportedKeys as $key) {
      $this->clearPersistentData($key);
    }
  }

  protected function constructSessionVariableName($key) {
    return implode('_', array('fb',
                              $this->getAppId(),
                              $key));
  }
  
  /* Get the authorize URL @returns a string */
  public function getAuthorizeUrl($scope=NULL)
  {
    $params = array();
    $params["client_id"] = $this->client_id;
    
    if(!empty($this->callback_url))
    {
      $params["redirect_uri"] = $this->callback_url;
    }
    
    if(is_array($scope))
    {
      $params["scope"] = implode(",", $scope);
    }
    elseif ($scope != NULL)
    {
      $params["scope"] = $scope;
    }
    
    return self::AuthorizeUrl."?".OAuthUtils::build_http_query($params);
  }
  
  /* GET wrapper for http. */
  public function get($location, $fields = NULL, $introspection = FALSE){
  $params = array();
  if(!empty($this->access_token)){
    $params["access_token"] = $this->access_token;
  }
  if(!empty($fields)){
    $params["fields"] = $fields;
  }
  if($introspection){
    $params["metadata"] = 1;
  }
  $url = self::GraphUrl.OAuthUtils::urlencode_rfc3986($location)."?".OAuthUtils::build_http_query($params);
  $response = $this->http($url, self::$METHOD_GET);
  return $this->decode_JSON ? json_decode($response) : $response;
  }
  
    /* GET IDS wrapper for http. @ids comma separated list of ids */
  public function get_ids($ids)
  {
    $params = array();
    
    if(is_array($ids))
    {
      $params["ids"] = implode(",", $ids);
    }
    else
    {
      $params["ids"] = $ids;
    }
    
    if(!empty($this->access_token))
    {
      $params["access_token"] = $this->access_token;
    }
    
    $url    = self::GraphUrl."?".OAuthUtils::build_http_query($params);
    $response   = $this->http($url, self::$METHOD_GET);
    
    return $this->decode_JSON ? json_decode($response) : $response;
  }
  
  /* POST wrapper for http.*/
  public function post($location, $postfields = array())
  {
    $url = self::GraphUrl.OAuthUtils::urlencode_rfc3986($location);
    if(!empty($this->access_token))
    {
        $postfields["access_token"] = $this->access_token;
    }
    
    $response = $this->http($url, self::$METHOD_POST, $postfields);
    
    return $this->decode_JSON ? json_decode($response) : $response;
  }
  
  /* DELETE wrapper for http. */
  public function delete($location, $postfields = array())
  {
    $url = self::GraphUrl.OAuthUtils::urlencode_rfc3986($location);
    $postfields = array();
    
    if(!empty($this->access_token))
    {
        $postfields["access_token"] = $this->access_token;
    }
    
    $response = $this->http($url, self::$METHOD_DELETE, $postfields);
    return $this->decode_JSON ? json_decode($response) : $response;
  }

  /**
   * Make an HTTP request
   *
   * @return API results
   */
  private function http($url, $method = "GET", $postfields=NULL){
    $this->http_info = array();
    $handle = curl_init();
    /* Curl settings */
    curl_setopt($handle, CURLOPT_HEADER, FALSE);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
    //curl_setopt($handle, CURLOPT_PROTOCOLS, "CURLPROTO_HTTPS");
    curl_setopt($handle, CURLOPT_HTTPHEADER, array('Expect:'));
    curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, $this->verifypeer);
    curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);
    curl_setopt($handle, CURLOPT_TIMEOUT, $this->timeout);
    curl_setopt($handle, CURLOPT_USERAGENT, $this->useragent);
    curl_setopt($handle, CURLOPT_HEADERFUNCTION, array($this, 'getHeader'));
    
    if ($this->proxy_settings['behind_proxy']){
      curl_setopt($ci, CURLOPT_PROXY, $this->proxy_settings['host']);
      curl_setopt($ci, CURLOPT_PROXYPORT, $this->proxy_settings['port']);
      curl_setopt($ci, CURLOPT_PROXYUSERPWD, "{$this->proxy_settings['user']}:{$this->proxy_settings['pass']}");
      curl_setopt($ci, CURLOPT_PROXYTYPE, $this->proxy_settings['type']);
      curl_setopt($ci, CURLOPT_PROXYAUTH, $this->proxy_settings['auth']);
    }
    
    switch($method){
      case self::$METHOD_POST:
        curl_setopt($handle, CURLOPT_POST, TRUE);
        if (!empty($postfields)) {
          curl_setopt($handle, CURLOPT_POSTFIELDS, $postfields);
        }
        break;
      case self::$METHOD_DELETE:
        curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'DELETE');
        if (!empty($postfields)){
          $url .= "?".OAuthUtils::build_http_query($postfields);
        }
        break;
    }
    curl_setopt($handle, CURLOPT_URL, $url);
    $response = curl_exec($handle);
    $this->http_code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
    $this->http_info = array_merge($this->http_info, curl_getinfo($handle));
    $this->url = $url;
    curl_close($handle);
    return $response;
  }
  
  /**
   * Get the header info to store.
   */
  function getHeader($ch, $header) {
    $i = strpos($header, ':');
    if (!empty($i)) {
      $key = str_replace('-', '_', strtolower(substr($header, 0, $i)));
      $value = trim(substr($header, $i + 2));
      $this->http_header[$key] = $value;
    }
    return strlen($header);
  }
}

/**
 *  OAuthUtils
 *  Copied and adapted from http://oauth.googlecode.com/svn/code/php/
 */
class OAuthUtils {
  public static function urlencode_rfc3986($input) {
    if (is_array($input)) {
      return array_map(array('OAuthUtils', 'urlencode_rfc3986'), $input);
    } else if (is_scalar($input)) {
      return str_replace(
        '+',
        ' ',
        str_replace('%7E', '~', rawurlencode($input))
      );
    } else {
      return '';
    }
  }
  public static function build_http_query($params) {
    if (!$params) return '';
    // Urlencode both keys and values
    $keys = OAuthUtils::urlencode_rfc3986(array_keys($params));
    $values = OAuthUtils::urlencode_rfc3986(array_values($params));
    $params = array_combine($keys, $values);
    
    $pairs = array();
    foreach ($params as $parameter => $value) {
      if (is_array($value)) {
        foreach ($value as $duplicate_value) {
          $pairs[] = $parameter . '=' . $duplicate_value;
        }
      } else {
        $pairs[] = $parameter . '=' . $value;
      }
    }
    // For each parameter, the name is separated from the corresponding value by an '=' character (ASCII code 61)
    // Each name-value pair is separated by an '&' character (ASCII code 38)
    return implode('&', $pairs);
  }
}

