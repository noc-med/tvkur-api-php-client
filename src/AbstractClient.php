<?php
/**
 *
 * User: semihs
 * Date: 21.11.14
 * Time: 12:14
 *
 */

namespace TvkurApiClient;

use Zend\Http\Request;

/**
 * Class Client
 * @package TvkurClient
 */
abstract class AbstractClient
{

    /**
     * @var
     */
    private $configs;
    /**
     * @var
     */
    private $expires_in;
    /**
     * @var
     */
    private $access_token;
    /**
     * @var
     */
    private $token_type;
    /**
     * @var
     */
    private $scope;

    /**
     * @var
     */
    private $api_path;

    /**
     * @param array $options
     * @throws Exception\InvalidOptionException
     */
    function __construct(array $options)
    {
        if (!empty($options)) {
            foreach ($options as $key => $value) {
                if (!($this->$key = $value)) {
                    throw new Exception\InvalidOptionException();
                }
            }
        }
    }

    /**
     * @return mixed
     */
    public function getAccessToken()
    {
        return $this->access_token;
    }

    /**
     * @param mixed $access_token
     */
    public function setAccessToken($access_token)
    {
        $this->access_token = $access_token;
    }

    /**
     * @return array
     */
    public function getConfigs()
    {
        return $this->configs;
    }

    /**
     * @param array $configs
     */
    public function setConfigs(array $configs)
    {
        $this->configs = $configs;
    }

    /**
     * @return integer
     */
    public function getExpiresIn()
    {
        return $this->expires_in;
    }

    /**
     * @param integer $expires_in
     */
    public function setExpiresIn($expires_in)
    {
        $this->expires_in = $expires_in;
    }

    /**
     * @return mixed
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @param mixed $scope
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
    }

    /**
     * @return string("Bearer")
     */
    public function getTokenType()
    {
        return $this->token_type;
    }

    /**
     * @param string ("Bearer")
     */
    public function setTokenType($token_type)
    {
        $this->token_type = $token_type;
    }

    /**
     * @return mixed
     */
    public function getApiPath()
    {
        return $this->api_path;
    }

    /**
     * @param mixed $api_path
     */
    public function setApiPath($api_path)
    {
        $this->api_path = $this->getConfigs()['tvkur']['api_url'] . $api_path;
    }

    /**
     * @return $this
     */
    public function video()
    {
        $this->setApiPath('/video');

        return $this;
    }

    /**
     * @return $this
     */
    public function stream()
    {
        $this->setApiPath('/stream');

        return $this;
    }

    /**
     * @return $this
     */
    public function playout()
    {
        $this->setApiPath('/playout');

        return $this;
    }

    /**
     * @return $this
     *
     * @throws
     */
    public function createAccessToken()
    {
        $request = new Request();
        $request->setMethod(Request::METHOD_POST);
        $request->setUri($this->getConfigs()['tvkur']['api_url'] . '/oauth');
        $request->getHeaders()->addHeaders(array(
            'Accept' => 'application/json',
        ));

        $oAuthConfigs = $this->getConfigs()['tvkur']['authentication']['oauth'];
        $request->getPost()
            ->set('grant_type', $oAuthConfigs['grant_type'])
            ->set('client_id', $oAuthConfigs['client_id'])
            ->set('client_secret', $oAuthConfigs['client_secret']);

        $client = new \Zend\Http\Client();
        $response = $client->send($request);

        if ($response->getStatusCode() != 200) {
            throw new Exception\AuthenticationFailedStatusCodeException(
                'Tvkur api authentication failed. Body: ' . $response->getBody(),
                $response->getStatusCode()
            );
        }

        $body = json_decode($response->getBody(), true);
        if (empty($body['access_token'])) {
            throw new Exception\AuthenticationFailedAccessTokenException(
                'Tvkur api authentication failed. Access token is empty. Body: ' . $response->getBody(),
                $response->getStatusCode()
            );
        }
        $this->setAccessToken($body['access_token']);
        $this->setTokenType($body['token_type']);
        $this->setScope($body['scope']);
        $this->setExpiresIn($body['expires_in']);

        return $this;
    }
} 