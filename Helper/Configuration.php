<?php
/**
 * Created by Hidro Le.
 * Job Title: Magento Developer
 * Project Name: M2.3.0 - CE - Default Magento
 * Date: 10/06/2020
 * Time: 23:55
 */

namespace Hidro\Graylog\Helper;

class Configuration
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
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    protected function getConfig($path)
    {
        return $this->scopeConfig->getValue(self::XML_GRAYLOG_CONFIG_PREFIX . $path, \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE);
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return !!$this->getConfig('enabled');
    }
    /**
     * get config Facility
     * @return string
     */
    public function getProjectFacility()
    {
        $facility = $this->getConfig('facility');
        $baseUrl = $this->urlBuilder->getBaseUrl();
        return $facility ?: $baseUrl;
    }

    /**
     * get config host
     * @return string
     */
    public function getServerHost()
    {
        return $this->getConfig('host');
    }

    /**
     * get config port
     * @return string
     */
    public function getServerPort()
    {
        return $this->getConfig('port');
    }
    /**
     * get Transmission Control Protocol
     * @return int
     */
    public function getTransmissionProtocol()
    {
        return $this->getConfig('protocol')?:1;
    }


    public function isDisableExternal()
    {
        return !!$this->getConfig('disable_external');
    }
}
