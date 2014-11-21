Tvkur Api Php SDK
=======================
Introduction
------------
This module has been written for TVKUR API

Installation
------------

For the installation uses composer composer. Add this project in your composer.json:


    "require": {
        "noc-med/tvkur-api-client": "dev-master"
    }


if you don't have the composer.phar (https://getcomposer.org/download/)

Requirements
------------

Php 5.3 or Higher versions.
zendframework/zend-http

Configuration
-------------


    $configs = array (
        'api_url' => 'https://api.tvkur.com',
        'authentication' => array(
            'oauth' => array(
                'grant_type' => 'client_credentials',
                'client_id' => 'your client id',
                'client_secret' => 'your client secret',
                // 'username' => '',
                // 'password' => '',
            )
        )
    )


Using
-----
    $tvkurApiClient = new TvkurApiClient(
        array(
            'configs' => $configs
            'expire_in' => $expires_in, //optional
            'acces_token' => $access_token, //optional
            'token_type' => $token_type, //optional
            'scope' => , => $scope, //optional
            'api_path' => $api_path, //optional
        )
    );
    $tvkurApiClient->video()->get($id, (array) $queryParams);


or

    $tvkurApiClient = new TvkurApiClient();
    $tvkurApiClient->setConfigs($configs);
    $tvkurApiClient->video()->get($id, (array) $queryParams);

