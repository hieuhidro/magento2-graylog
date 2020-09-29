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

class MagentoGrayLog extends \Monolog\Logger
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
        $name = '',
        $facility = '',
        array $defaultContext = array(),
        array $handlers = [],
        array $processors = [])
    {
        $this->objectManager = $objectManager;
        $this->defaultContext = $defaultContext;
        $this->graylogBuilder = $graylogBuilder;
        $this->configuration = $configuration;
        parent::__construct($name, $handlers, $processors);
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
            $facility = $this->configuration->getProjectFacility();
            $logger = $this->graylogBuilder->build($host, $port, $protocol, $facility, $this->defaultContext);
        }
        if (null === $logger) {
            $logger = $this->getMonolog();
        }
        return $logger;
    }
    public function addRecord($level, $message, array $context = [])
    {
        $message = $message instanceof \Exception ? $message->getMessage() : $message;
        try {
            $this->getLogger()->log($level, $message, $context);
        } catch (\Exception $e) {
            /**
             * Use default magneto log if existing exception.
             */
            $this->getMonolog()->addRecord($level, $message, $context);
        }
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
