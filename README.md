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
        'tvkur' => array(
            'api_url' => 'https://api.tvkur.com',
            'authentication' => array(
                'oauth' => array(
                    'grant_type' => 'client_credentials',
                    'client_id' => '61414062410',
                    'client_secret' => '18f98c0c61d091c985c1f4ebb5439158',
                    // 'username' => '',
                    // 'password' => '',
                )
            )
        )
    )


Using
-----
    $tvkurApiClient = new TvkurApiClient\TvkurApiClient(
        array(
            'configs' => $configs
            'expire_in' => $expires_in, //optional
            'acces_token' => $access_token, //optional
            'token_type' => $token_type, //optional
            'scope' => , => $scope, //optional
            'api_path' => $api_path, //optional
        )
    );
    $response = $tvkurApiClient->video()->get($id, (array) $queryParams);


or

    $tvkurApiClient = new TvkurApiClient\TvkurApiClient();
    $tvkurApiClient->setConfigs($configs);
    $response = $tvkurApiClient->video()->get($id, (array) $queryParams);
    
    
Response content


    $response->getJsonResponse(); //salt json body
    $response->getArrayResponse(); //salt array body
    
    $response->getContent(); //array video or stream array content
    $response->getLinks(); //array prev next self links
    $response->getPageCount(); //integer total page count
    $response->getPageSize(); //integer items count per page
    $response->getTotalItems(); //integer total items count

