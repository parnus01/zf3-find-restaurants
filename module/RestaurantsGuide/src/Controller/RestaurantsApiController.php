<?php

namespace RestaurantsGuide\Controller;

use GooglePlaces\Client as GooglePlaceClient;
use Phpfastcache\Helper\Psr16Adapter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class RestaurantsApiController extends AbstractActionController
{
    const CRATERIA_TYPE = 'restaurant';
    const DEFAULT_KEYWORD = 'bangsue';
    const CACHE_TYPE = 'Files';

    private $key;
    /**
     * @var Psr16Adapter
     */
    private $_cacheManager;

    public function __construct($apiKey)
    {
        $this->key = $apiKey; /*Api Key path /config/autoload/restuarants-api.local.php */
        $this->_cacheManager = new Psr16Adapter(self::CACHE_TYPE); /*Declare Cache Driver*/
    }

    /**
     * Search Place API
     *
     */
    public function indexAction()
    {
        $keyword = strtolower($this->params()->fromQuery('keyword', self::DEFAULT_KEYWORD));
        $response = $this->_initGooglePlaceClient($keyword);
        return $this->_response($this->_reformatApiResponse($response));

    }

    /**
     * Response Handler
     * @param $data
     * @return JsonModel
     */
    protected function _response($data)
    {
        return new JsonModel($data);
    }

    /**
     * Request Google Place API Client
     *  #Validate cache life-time before call api#
     * @param $keyword
     * @return array|bool|mixed|null
     */
    protected function _initGooglePlaceClient($keyword)
    {
        try {
            $response = $this->_isCache($keyword);
            if (!$response) {
                $client = new GooglePlaceClient($this->key);
                $response = $client->placeSearch('textsearch')->setOptions([
                    'query' => $keyword,
                    'type' => self::CRATERIA_TYPE,
                    'region' => 'th'
                ])->request();
                $this->_setCache($keyword, $response);
            }
            return $response;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Reformat Response
     * @param $list
     * @return array
     */
    protected function _reformatApiResponse($list)
    {
        if (empty($list)) {
            return [];
        }
        foreach ($list as $place) {
            $response['data'][] = [
                'name' => $place['name'],
                'address' => $place['formatted_address'],
            ];
        }
        return $response ?? [];
    }

    /**
     * Get Place Detail Client
     * @param $id
     * @return
     */
    protected function _getMapInformation($id)
    {
        $client = new GooglePlaceClient($this->key);
        $response = $client->placeDetails($id)->request();
        return $response['result']['url'];
    }


    /**
     * Validate Cache life-time
     * @param $keyword
     * @return bool|mixed|null
     * @throws \Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException
     */
    protected function _isCache($keyword)
    {
        $data = false;
        if ($this->_cacheManager->has('place_data&' . $keyword)) {
            $data = $this->_cacheManager->get('place_data&' . $keyword);
        }
        return $data;
    }

    /**
     * Set Cache unique key by keyword
     * @param $keyword
     * @param $data
     * @throws \Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException
     */
    protected function _setCache($keyword, $data)
    {
        $this->_cacheManager->set('place_data&' . $keyword, $data, 300);// 5 minutes
    }
}
