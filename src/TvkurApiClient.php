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
        $this->setResponse($response);

        return $this;
    }

    /**
     * @param null $id
     * @param array $queryParams
     * @return mixed
     */
    protected function requestGet($id = null, $queryParams = array()) {
        $uri = $this->getApiPath();
        if (!empty($id)) {
            $uri .= '/' . $id;
        }
        if (!empty($queryParams)) {
            $uri .= '?' . http_build_query($queryParams);
        }

        $request = new Request();
        $request->setMethod(Request::METHOD_GET);
        $request->setUri($uri);
        $request->getHeaders()->addHeaders(array(
            'Accept' => 'application/json',
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

    public function getJsonResponse() {
        return $this->getResponse()->getBody();
    }

    public function getArrayResponse() {
        return json_decode($this->getJsonResponse(), true);
    }

    public function getContent() {
        return $this->getArrayResponse()['_embedded'];
    }

    public function getLinks() {
        return $this->getArrayResponse()['_links'];
    }

    public function getPageCount() {
        return $this->getArrayResponse()['page_count'];
    }

    public function getPageSize() {
        return $this->getArrayResponse()['page_size'];
    }

    public function getTotalItems() {
        return $this->getArrayResponse()['total_items'];
    }
} 