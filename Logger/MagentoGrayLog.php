<?php
/**
 * Created by Hidro Le.
 * Job Title: Magento Developer
 * Project Name: M2.3.0 - CE - Default Magento
 * Date: 10/06/2020
 * Time: 23:37
 */

namespace Hidro\Graylog\Logger;

use Hidro\Graylog\Helper\Configuration;

class MagentoGrayLog extends \Psr\Log\AbstractLogger
{

    /**
     * @var \Magento\Framework\Logger\Monolog
     */
    protected $_monolog;

    /**
     * @var
     */
    protected $objectManager;

    /**
     * @var array
     */
    protected $defaultContext;

    protected $graylogBuilder;

    protected $configuration;

    public function __construct(
        Configuration $configuration,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Hidro\Graylog\Logger\GraylogBuilder $graylogBuilder,
        $facility = '',
        array $defaultContext = array()
    )
    {
        $this->objectManager = $objectManager;
        $this->defaultContext = $defaultContext;
        $this->graylogBuilder = $graylogBuilder;
        $this->configuration = $configuration;
    }

    /**
     * @return \Magento\Framework\Logger\Monolog
     */
    protected function getMonolog()
    {
        if (!$this->_monolog) {
            $this->_monolog = $this->objectManager->get(\Magento\Framework\Logger\Monolog::class);
        }
        return $this->_monolog;
    }


    /**
     * @return \Psr\Log\LoggerInterface
     */
    protected function getLogger()
    {
        $logger = null;
        //If isn't enabled return Monolog
        if($this->configuration->isEnabled()) {
            $host = $this->configuration->getServerHost();
            $port = $this->configuration->getServerPort();
            $protocol = $this->configuration->getTransmissionProtocol();
            $facility = $this->configuration->getTransmissionProtocol();
            $logger = $this->graylogBuilder->build($host, $port, $protocol, $facility, $this->defaultContext);
        }
        if (null === $logger) {
            $logger = $this->getMonolog();
        }
        return $logger;
    }

    public function log($level, $rawMessage, array $context = array())
    {
        try {
            $this->getLogger()->log($level, $rawMessage, $context);
        } catch (\Exception $e) {
            /**
             * Use default magneto log if existing exception.
             */
            $this->getMonolog()->log($level, $rawMessage, $context);
        }
    }
}
