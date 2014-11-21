<?php
/**
 *
 * User: semihs
 * Date: 21.11.14
 * Time: 14:07
 *
 */


namespace TvkurApiClient;

use Zend\Http\Request;

/**
 * Class TvkurApiClient
 * @package TvkurApiClient
 */
class TvkurApiClient extends AbstractClient {

    /**
     * @param null $id
     * @param array $queryParams
     * @return mixed
     * @throws Exception\AuthenticationFailedAccessTokenException
     * @throws Exception\AuthenticationFailedStatusException
     * @throws Exception\InvalidModuleException
     */
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
            throw new Exception\AuthenticationFailedStatusException(
                'Api Response Failed. Body: ' . $response->getBody(),
                $response->getStatusCode()
            );
        }

        return $response;
    }

    /**
     * @param null $id
     * @param array $queryParams
     * @return mixed
     */
    protected function requestGet($id = null, $queryParams = array()) {
        $request = new Request();
        $request->setMethod(Request::METHOD_GET);
        $request->setUri($this->getApiPath());
        $request->getHeaders()->addHeaders(array(
            'Authorization' => $this->getTokenType() . ' ' . $this->getAccessToken(),
        ));

        $client = new \Zend\Http\Client(null, array(
            'adapter'   => 'Zend\Http\Client\Adapter\Curl',
            'curloptions' => array(
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
            ),
        ));
        return $client->send($request);
    }
} 