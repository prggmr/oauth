<?php
namespace oauth;
/**
 * Copyright 2012 Nickolas Whiting
 * This file is part of proprietary software and use of this file
 * is strictly prohibited without the written consent of the owner.
 */

if (!defined('CLIENT_ID')) {
    define('CLIENT_ID', null);
}

if (!defined('CLIENT_SECRET')) {
    define('CLIENT_SECRET', null);
}

/**
 * Dead simple interface for the OAuth protocol.
 *
 * Currently github is the only tested API but others should most likely work.
 *
 * Requesting authorization.
 *
 * A
 */
class OAuth {


    /**
     * Options to use when connecting.
     */
    protected $_options = [
        'url' => 'https://github.com',
        'client_id' => CLIENT_ID,
        'client_secret' => CLIENT_SECRET,
        'authorize_uri' => 'login/oauth/authorize',
        'token_uri' => 'login/oauth/access_token'
    ]

    /**
     * Constructs a new OAuth instance.
     *
     * @param  array  $options  Options
     *
     * @return  void
     */
    public function __construct($options = [])
    {
        $this->_options = array_merge($this->_options, array $options);
    }

    /**
     * Sends the user to the site for authorization.
     *
     * @param  array  $params  Parameters to pass when requesting authorization
     *                         the client information, state and scope are 
     *                         provided and do not need to be supplied unless 
     *                         you want them to be different. 
     *
     * @return  void
     */
    public function request_authorization($params = [])
    {
        $state = $_SESSION['state'] = md5(time());
        $defaults = [
            'authorize_uri' => $this->_options['authorize_url'],
            'url' => $this->_options['url'],
            'client_id' => $this->_options['client_id'],
            'state' => $state,
            'scope' => ''
        ];
        $options = array_merge($default, $params);
        header('Location: '.sprintf('%s?client_id=%s&scope=%s&state=%s',
            $options['url'].'/'.$options['authorize_uri'],
            $options['client_id'],
            $options['scope'],
            $options['state']
        ));
    }

    /**
     * Sends the request for an access token once authorization has been recieved.
     *
     * @return  array
     */
    public function get_token($params = [])
    {
        $defaults = [
            'token_uri' => $this->_options['token_url'],
            'url' => $this->_options['url'],
            'client_id' => $this->_options['client_id'],
            'client_secret' => $this->_options['client_secret'],
            'state' => null,
            'scope' => ''
        ];
        $options = array_merge($default, $params);
        $opts = array(
            'http' => array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query([
                    'client_id' => $_options,
                    'client_secret' => GITHUB_SECRET,
                    'code' => $code,
                    'state' => $state
                ]),
            )
        );

        var_dump($opts);

        $_default_opts = stream_context_get_params(stream_context_get_default());
        $context = stream_context_create(array_merge_recursive($_default_opts['options'], $opts));
        $response = file_get_contents('https://github.com/login/oauth/access_token', false, $context);
        var_dump($response);
        $return = json_decode($response, true);
    }
}