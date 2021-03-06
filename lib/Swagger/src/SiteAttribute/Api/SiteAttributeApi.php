<?php
/**
 * SiteAttributeApi
 * PHP version 5
 *
 * @category Class
 * @package  Swagger\Client
 * @author   http://github.com/swagger-api/swagger-codegen
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache License v2
 * @link     https://github.com/swagger-api/swagger-codegen
 */

/**
 * site-attribute
 *
 * No description provided (generated by Swagger Codegen https://github.com/swagger-api/swagger-codegen)
 *
 * OpenAPI spec version: 0.1.0-SNAPSHOT
 * 
 * Generated by: https://github.com/swagger-api/swagger-codegen.git
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen
 * Do not edit the class manually.
 */

namespace Swagger\Client\SiteAttribute\Api;

use \Swagger\Client\Configuration;
use \Swagger\Client\ApiClient;
use \Swagger\Client\ApiException;
use \Swagger\Client\ObjectSerializer;

/**
 * SiteAttributeApi Class Doc Comment
 *
 * @category Class
 * @package  Swagger\Client
 * @author   http://github.com/swagger-api/swagger-codegen
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache License v2
 * @link     https://github.com/swagger-api/swagger-codegen
 */
class SiteAttributeApi
{

    /**
     * API Client
     *
     * @var \Swagger\Client\ApiClient instance of the ApiClient
     */
    protected $apiClient;

    /**
     * Constructor
     *
     * @param \Swagger\Client\ApiClient|null $apiClient The api client to use
     */
    public function __construct(\Swagger\Client\ApiClient $apiClient = null)
    {
        if ($apiClient == null) {
            $apiClient = new ApiClient();
            $apiClient->getConfig()->setHost('https://localhost/site-attribute');
        }

        $this->apiClient = $apiClient;
    }

    /**
     * Get API client
     *
     * @return \Swagger\Client\ApiClient get the API client
     */
    public function getApiClient()
    {
        return $this->apiClient;
    }

    /**
     * Set the API client
     *
     * @param \Swagger\Client\ApiClient $apiClient set the API client
     *
     * @return SiteAttributeApi
     */
    public function setApiClient(\Swagger\Client\ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
        return $this;
    }

    /**
     * Operation deleteAttribute
     *
     * Deletes attribute for this site
     *
     * @param int $site_id id of the site to be modified (required)
     * @param string $attr_name Name of attribute to be deleted for specified site (required)
     * @return void
     * @throws \Swagger\Client\ApiException on non-2xx response
     */
    public function deleteAttribute($site_id, $attr_name)
    {
        list($response) = $this->deleteAttributeWithHttpInfo($site_id, $attr_name);
        return $response;
    }

    /**
     * Operation deleteAttributeWithHttpInfo
     *
     * Deletes attribute for this site
     *
     * @param int $site_id id of the site to be modified (required)
     * @param string $attr_name Name of attribute to be deleted for specified site (required)
     * @return array of null, HTTP status code, HTTP response headers (array of strings)
     * @throws \Swagger\Client\ApiException on non-2xx response
     */
    public function deleteAttributeWithHttpInfo($site_id, $attr_name)
    {
        // verify the required parameter 'site_id' is set
        if ($site_id === null) {
            throw new \InvalidArgumentException('Missing the required parameter $site_id when calling deleteAttribute');
        }
        // verify the required parameter 'attr_name' is set
        if ($attr_name === null) {
            throw new \InvalidArgumentException('Missing the required parameter $attr_name when calling deleteAttribute');
        }
        // parse inputs
        $resourcePath = "/site/{siteId}/attr/{attrName}";
        $httpBody = '';
        $queryParams = array();
        $headerParams = array();
        $formParams = array();
        $_header_accept = $this->apiClient->selectHeaderAccept(array('*_/_*'));
        if (!is_null($_header_accept)) {
            $headerParams['Accept'] = $_header_accept;
        }
        $headerParams['Content-Type'] = $this->apiClient->selectHeaderContentType(array());

        // path params
        if ($site_id !== null) {
            $resourcePath = str_replace(
                "{" . "siteId" . "}",
                $this->apiClient->getSerializer()->toPathValue($site_id),
                $resourcePath
            );
        }
        // path params
        if ($attr_name !== null) {
            $resourcePath = str_replace(
                "{" . "attrName" . "}",
                $this->apiClient->getSerializer()->toPathValue($attr_name),
                $resourcePath
            );
        }
        // default format to json
        $resourcePath = str_replace("{format}", "json", $resourcePath);

        
        // for model (json/xml)
        if (isset($_tempBody)) {
            $httpBody = $_tempBody; // $_tempBody is the method argument, if present
        } elseif (count($formParams) > 0) {
            $httpBody = $formParams; // for HTTP post (form)
        }
        // this endpoint requires API key authentication
        $apiKey = $this->apiClient->getApiKeyWithPrefix('X-Wikia-AccessToken');
        if (strlen($apiKey) !== 0) {
            $headerParams['X-Wikia-AccessToken'] = $apiKey;
        }
        // this endpoint requires API key authentication
        $apiKey = $this->apiClient->getApiKeyWithPrefix('X-Wikia-UserId');
        if (strlen($apiKey) !== 0) {
            $headerParams['X-Wikia-UserId'] = $apiKey;
        }
        // make the API Call
        try {
            list($response, $statusCode, $httpHeader) = $this->apiClient->callApi(
                $resourcePath,
                'DELETE',
                $queryParams,
                $httpBody,
                $headerParams,
                null,
                '/site/{siteId}/attr/{attrName}'
            );

            return array(null, $statusCode, $httpHeader);
        } catch (ApiException $e) {
            switch ($e->getCode()) {
            }

            throw $e;
        }
    }

    /**
     * Operation getAllAttributes
     *
     * Returns all available attributes for the specified siteId
     *
     * @param int $site_id The ID of the site (required)
     * @return \Swagger\Client\SiteAttribute\Models\AllSiteAttributesHalResponse
     * @throws \Swagger\Client\ApiException on non-2xx response
     */
    public function getAllAttributes($site_id)
    {
        list($response) = $this->getAllAttributesWithHttpInfo($site_id);
        return $response;
    }

    /**
     * Operation getAllAttributesWithHttpInfo
     *
     * Returns all available attributes for the specified siteId
     *
     * @param int $site_id The ID of the site (required)
     * @return array of \Swagger\Client\SiteAttribute\Models\AllSiteAttributesHalResponse, HTTP status code, HTTP response headers (array of strings)
     * @throws \Swagger\Client\ApiException on non-2xx response
     */
    public function getAllAttributesWithHttpInfo($site_id)
    {
        // verify the required parameter 'site_id' is set
        if ($site_id === null) {
            throw new \InvalidArgumentException('Missing the required parameter $site_id when calling getAllAttributes');
        }
        // parse inputs
        $resourcePath = "/site/{siteId}/attr";
        $httpBody = '';
        $queryParams = array();
        $headerParams = array();
        $formParams = array();
        $_header_accept = $this->apiClient->selectHeaderAccept(array('application/hal+json; charset=UTF-8'));
        if (!is_null($_header_accept)) {
            $headerParams['Accept'] = $_header_accept;
        }
        $headerParams['Content-Type'] = $this->apiClient->selectHeaderContentType(array());

        // path params
        if ($site_id !== null) {
            $resourcePath = str_replace(
                "{" . "siteId" . "}",
                $this->apiClient->getSerializer()->toPathValue($site_id),
                $resourcePath
            );
        }
        // default format to json
        $resourcePath = str_replace("{format}", "json", $resourcePath);

        
        // for model (json/xml)
        if (isset($_tempBody)) {
            $httpBody = $_tempBody; // $_tempBody is the method argument, if present
        } elseif (count($formParams) > 0) {
            $httpBody = $formParams; // for HTTP post (form)
        }
        // this endpoint requires API key authentication
        $apiKey = $this->apiClient->getApiKeyWithPrefix('X-Wikia-AccessToken');
        if (strlen($apiKey) !== 0) {
            $headerParams['X-Wikia-AccessToken'] = $apiKey;
        }
        // this endpoint requires API key authentication
        $apiKey = $this->apiClient->getApiKeyWithPrefix('X-Wikia-UserId');
        if (strlen($apiKey) !== 0) {
            $headerParams['X-Wikia-UserId'] = $apiKey;
        }
        // make the API Call
        try {
            list($response, $statusCode, $httpHeader) = $this->apiClient->callApi(
                $resourcePath,
                'GET',
                $queryParams,
                $httpBody,
                $headerParams,
                '\Swagger\Client\SiteAttribute\Models\AllSiteAttributesHalResponse',
                '/site/{siteId}/attr'
            );

            return array($this->apiClient->getSerializer()->deserialize($response, '\Swagger\Client\SiteAttribute\Models\AllSiteAttributesHalResponse', $httpHeader), $statusCode, $httpHeader);
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = $this->apiClient->getSerializer()->deserialize($e->getResponseBody(), '\Swagger\Client\SiteAttribute\Models\AllSiteAttributesHalResponse', $e->getResponseHeaders());
                    $e->setResponseObject($data);
                    break;
            }

            throw $e;
        }
    }

    /**
     * Operation getAttribute
     *
     * Returns specific attribute for specified site
     *
     * @param int $site_id The siteId of the site (required)
     * @param string $attr_name The name of the attribute to be retrieved (required)
     * @return \Swagger\Client\SiteAttribute\Models\SiteAttributeHalResponse
     * @throws \Swagger\Client\ApiException on non-2xx response
     */
    public function getAttribute($site_id, $attr_name)
    {
        list($response) = $this->getAttributeWithHttpInfo($site_id, $attr_name);
        return $response;
    }

    /**
     * Operation getAttributeWithHttpInfo
     *
     * Returns specific attribute for specified site
     *
     * @param int $site_id The siteId of the site (required)
     * @param string $attr_name The name of the attribute to be retrieved (required)
     * @return array of \Swagger\Client\SiteAttribute\Models\SiteAttributeHalResponse, HTTP status code, HTTP response headers (array of strings)
     * @throws \Swagger\Client\ApiException on non-2xx response
     */
    public function getAttributeWithHttpInfo($site_id, $attr_name)
    {
        // verify the required parameter 'site_id' is set
        if ($site_id === null) {
            throw new \InvalidArgumentException('Missing the required parameter $site_id when calling getAttribute');
        }
        // verify the required parameter 'attr_name' is set
        if ($attr_name === null) {
            throw new \InvalidArgumentException('Missing the required parameter $attr_name when calling getAttribute');
        }
        // parse inputs
        $resourcePath = "/site/{siteId}/attr/{attrName}";
        $httpBody = '';
        $queryParams = array();
        $headerParams = array();
        $formParams = array();
        $_header_accept = $this->apiClient->selectHeaderAccept(array('application/hal+json; charset=UTF-8'));
        if (!is_null($_header_accept)) {
            $headerParams['Accept'] = $_header_accept;
        }
        $headerParams['Content-Type'] = $this->apiClient->selectHeaderContentType(array());

        // path params
        if ($site_id !== null) {
            $resourcePath = str_replace(
                "{" . "siteId" . "}",
                $this->apiClient->getSerializer()->toPathValue($site_id),
                $resourcePath
            );
        }
        // path params
        if ($attr_name !== null) {
            $resourcePath = str_replace(
                "{" . "attrName" . "}",
                $this->apiClient->getSerializer()->toPathValue($attr_name),
                $resourcePath
            );
        }
        // default format to json
        $resourcePath = str_replace("{format}", "json", $resourcePath);

        
        // for model (json/xml)
        if (isset($_tempBody)) {
            $httpBody = $_tempBody; // $_tempBody is the method argument, if present
        } elseif (count($formParams) > 0) {
            $httpBody = $formParams; // for HTTP post (form)
        }
        // this endpoint requires API key authentication
        $apiKey = $this->apiClient->getApiKeyWithPrefix('X-Wikia-AccessToken');
        if (strlen($apiKey) !== 0) {
            $headerParams['X-Wikia-AccessToken'] = $apiKey;
        }
        // this endpoint requires API key authentication
        $apiKey = $this->apiClient->getApiKeyWithPrefix('X-Wikia-UserId');
        if (strlen($apiKey) !== 0) {
            $headerParams['X-Wikia-UserId'] = $apiKey;
        }
        // make the API Call
        try {
            list($response, $statusCode, $httpHeader) = $this->apiClient->callApi(
                $resourcePath,
                'GET',
                $queryParams,
                $httpBody,
                $headerParams,
                '\Swagger\Client\SiteAttribute\Models\SiteAttributeHalResponse',
                '/site/{siteId}/attr/{attrName}'
            );

            return array($this->apiClient->getSerializer()->deserialize($response, '\Swagger\Client\SiteAttribute\Models\SiteAttributeHalResponse', $httpHeader), $statusCode, $httpHeader);
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = $this->apiClient->getSerializer()->deserialize($e->getResponseBody(), '\Swagger\Client\SiteAttribute\Models\SiteAttributeHalResponse', $e->getResponseHeaders());
                    $e->setResponseObject($data);
                    break;
            }

            throw $e;
        }
    }

    /**
     * Operation saveAttribute
     *
     * Saves an attribute for a specified site
     *
     * @param int $site_id The id of the site (required)
     * @param string $attr_name The name of the attribute to be saved (required)
     * @param \SplFileObject $data  (optional)
     * @return \Swagger\Client\SiteAttribute\Models\SiteAttributeHalResponse
     * @throws \Swagger\Client\ApiException on non-2xx response
     */
    public function saveAttribute($site_id, $attr_name, $data = null)
    {
        list($response) = $this->saveAttributeWithHttpInfo($site_id, $attr_name, $data);
        return $response;
    }

    /**
     * Operation saveAttributeWithHttpInfo
     *
     * Saves an attribute for a specified site
     *
     * @param int $site_id The id of the site (required)
     * @param string $attr_name The name of the attribute to be saved (required)
     * @param \SplFileObject $data  (optional)
     * @return array of \Swagger\Client\SiteAttribute\Models\SiteAttributeHalResponse, HTTP status code, HTTP response headers (array of strings)
     * @throws \Swagger\Client\ApiException on non-2xx response
     */
    public function saveAttributeWithHttpInfo($site_id, $attr_name, $data = null)
    {
        // verify the required parameter 'site_id' is set
        if ($site_id === null) {
            throw new \InvalidArgumentException('Missing the required parameter $site_id when calling saveAttribute');
        }
        // verify the required parameter 'attr_name' is set
        if ($attr_name === null) {
            throw new \InvalidArgumentException('Missing the required parameter $attr_name when calling saveAttribute');
        }
        // parse inputs
        $resourcePath = "/site/{siteId}/attr/{attrName}";
        $httpBody = '';
        $queryParams = array();
        $headerParams = array();
        $formParams = array();
        $_header_accept = $this->apiClient->selectHeaderAccept(array('application/hal+json; charset=UTF-8'));
        if (!is_null($_header_accept)) {
            $headerParams['Accept'] = $_header_accept;
        }
        $headerParams['Content-Type'] = $this->apiClient->selectHeaderContentType(array('multipart/form-data'));

        // path params
        if ($site_id !== null) {
            $resourcePath = str_replace(
                "{" . "siteId" . "}",
                $this->apiClient->getSerializer()->toPathValue($site_id),
                $resourcePath
            );
        }
        // path params
        if ($attr_name !== null) {
            $resourcePath = str_replace(
                "{" . "attrName" . "}",
                $this->apiClient->getSerializer()->toPathValue($attr_name),
                $resourcePath
            );
        }
        // default format to json
        $resourcePath = str_replace("{format}", "json", $resourcePath);

        // form params
        if ($data !== null) {
            // PHP 5.5 introduced a CurlFile object that deprecates the old @filename syntax
            // See: https://wiki.php.net/rfc/curl-file-upload
            if (function_exists('curl_file_create')) {
                $formParams['data'] = curl_file_create($this->apiClient->getSerializer()->toFormValue($data));
            } else {
                $formParams['data'] = '@' . $this->apiClient->getSerializer()->toFormValue($data);
            }
        }
        
        // for model (json/xml)
        if (isset($_tempBody)) {
            $httpBody = $_tempBody; // $_tempBody is the method argument, if present
        } elseif (count($formParams) > 0) {
            $httpBody = $formParams; // for HTTP post (form)
        }
        // this endpoint requires API key authentication
        $apiKey = $this->apiClient->getApiKeyWithPrefix('X-Wikia-AccessToken');
        if (strlen($apiKey) !== 0) {
            $headerParams['X-Wikia-AccessToken'] = $apiKey;
        }
        // this endpoint requires API key authentication
        $apiKey = $this->apiClient->getApiKeyWithPrefix('X-Wikia-UserId');
        if (strlen($apiKey) !== 0) {
            $headerParams['X-Wikia-UserId'] = $apiKey;
        }
        // make the API Call
        try {
            list($response, $statusCode, $httpHeader) = $this->apiClient->callApi(
                $resourcePath,
                'PUT',
                $queryParams,
                $httpBody,
                $headerParams,
                '\Swagger\Client\SiteAttribute\Models\SiteAttributeHalResponse',
                '/site/{siteId}/attr/{attrName}'
            );

            return array($this->apiClient->getSerializer()->deserialize($response, '\Swagger\Client\SiteAttribute\Models\SiteAttributeHalResponse', $httpHeader), $statusCode, $httpHeader);
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = $this->apiClient->getSerializer()->deserialize($e->getResponseBody(), '\Swagger\Client\SiteAttribute\Models\SiteAttributeHalResponse', $e->getResponseHeaders());
                    $e->setResponseObject($data);
                    break;
            }

            throw $e;
        }
    }

}
