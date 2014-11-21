<?php
/**
 * 
 * User: semihs
 * Date: 21.11.14
 * Time: 14:07
 * 
 */
 

namespace TvkurApiClient\TvkurApiClient;

use Zend\Http\Request;

class TvkurApiClient extends AbstractClient {

    public function get($id = null, $queryParams = array()) {
        if (empty($this->getApiPath())) {
            throw new Exception\InvalidModuleException(
                'Invalid Module. you must call $tvkurApiClient->video()->get() or $tvkurApiClient->stream()->get()'
            );
        }

        if (empty($this->getAccessToken())) {
            $this->createAccessToken();
        }

        $response = $this->requestGet($id, $queryParams);
        if ($response->getStatusCode() == 403) {
            $this->createAccessToken();

            $response = $this->requestGet($id, $queryParams);
        }

        if ($response->getStatusCode() != 200) {
            throw new Exception\AuthenticationFailedStatusCodeException(
                'Api Response Failed. Body: ' . $response->getBody(),
                $response->getStatusCode()
            );
        }

        return $response;
    }

    protected function requestGet($id = null, $queryParams = array()) {
        $request = new Request();
        $request->setMethod(Request::METHOD_GET);
        $request->setUri($this->getApiPath());
        $request->getHeaders()->addHeaders(array(
            'Authorization' => $this->getTokenType() . ' ' . $this->getAccessToken(),
        ));

        $client = new \Zend\Http\Client();
        return $client->send($request);
    }
} 