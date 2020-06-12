<?php
/**
 * Created by Hidro Le.
 * Job Title: Magento Developer
 * Project Name: M2.3.0 - CE - Default Magento
 * Date: 10/06/2020
 * Time: 23:55
 */

namespace Hidro\Graylog\Logger\GrayLog;

use Gelf\Publisher;
use Gelf\Transport\UdpTransport;
use Gelf\Logger;

class LoggerBuilder
{

    const XML_GRAYLOG_CONFIG_PREFIX = 'system/graylog/';

    /**
     * Object manager
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $_objectManager;

    /**
     * @var \Gelf\Publisher
     */
    protected $_publisher;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * LoggerBuilder constructor.
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\ObjectManagerInterface          $objectManager
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\ObjectManagerInterface $objectManager
    )
    {
        $this->_objectManager = $objectManager;
        $this->scopeConfig = $scopeConfig;
        $this->urlBuilder = $urlBuilder;
    }

    protected function getConfig($path){
        return $this->scopeConfig->getValue(self::XML_GRAYLOG_CONFIG_PREFIX . $path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return bool
     */
    protected function isEnabled()
    {
        return !!$this->getConfig('enabled');
    }
    /**
     * get config Facility
     * @return string
     */
    protected function getProjectFacility()
    {
        $facility = $this->getConfig('facility');
        $baseUrl = $this->urlBuilder->getBaseUrl();
        return $facility?:$baseUrl;
    }

    /**
     * get config host
     * @return string
     */
    protected function getServerHost(){
        return $this->getConfig('host');
    }

    /**
     * get config port
     * @return string
     */
    protected function getServerPort(){
        return $this->getConfig('port');
    }

    protected function getUdpTransport(){
        $host = $this->getServerHost();
        $port = $this->getServerPort();
        if($host && $port) {
            return $this->_objectManager->create(UdpTransport::class, [
                'host' => $this->getServerHost(),
                'port' => $this->getServerPort(),
                UdpTransport::CHUNK_SIZE_LAN
            ]);
        }
        return null;
    }

    protected function getPublisher(){
        if(!$this->_publisher) {
            $transport = $this->getUdpTransport();
            if($transport) {
                /**
                 * @var $publisher Publisher
                 */
                $publisher = $this->_objectManager->get(Publisher::class);
                $publisher->addTransport($transport);
                $this->_publisher = $publisher;
            }
        }
        return $this->_publisher;
    }

    /**
     * @param $facility
     * @return Logger
     */
    public function prepareHandler($facility = ''){
        $handler = null;
        if($this->isEnabled()) {
            $publisher = $this->getPublisher();
            if($publisher) {
                if(!$facility){
                    $facility = $this->getProjectFacility();
                }
                $handler = $this->_objectManager->create(Logger::class, [
                    'publisher' => $publisher,
                    'facility' => $facility
                ]);
            }
        }
        return $handler;
    }
}