<?php
/**
 * Created by Hidro Le.
 * Job Title: Magento Developer
 * Project Name: M2.3.0 - CE - Default Magento
 * Date: 10/06/2020
 * Time: 23:37
 */

namespace Hidro\Graylog\Logger;
use Psr\Log\LoggerInterface;
use Psr\Log\InvalidArgumentException;
use Exception;
use Gelf\Transport\UdpTransport;

class GrayLog implements LoggerInterface
{
    /**
     * @var \Gelf\Logger
     */
    protected $_handler;
    /**
     * @var string
     */
    protected $facility;

    protected $objectManager;
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        $facility = 'magento-graylog'
    )
    {
        $this->facility = $facility;
        $this->objectManager = $objectManager;
    }

    /**
     * @return \Gelf\Logger
     */
    protected function getHandler(){
        if(!$this->_handler){
            $loggerBuilder = $this->objectManager->get(\Hidro\Graylog\Logger\GrayLog\LoggerBuilder::class);
            $this->_handler = $loggerBuilder->prepareHandler($this->facility);
            if(null === $this->_handler){
                $this->_handler = $this->objectManager->get(\Magento\Framework\Logger\Monolog::class);
            }
        }
        return $this->_handler;
    }

    public function emergency($message, array $context = array())
    {
        $this->getHandler()->emergency($message, $context);
    }

    public function alert($message, array $context = array())
    {
        $this->getHandler()->alert($message, $context);
    }

    public function critical($message, array $context = array())
    {
        $this->getHandler()->critical($message, $context);
    }

    public function error($message, array $context = array())
    {
        $this->getHandler()->error($message, $context);
    }

    public function warning($message, array $context = array())
    {
        $this->getHandler()->warning($message, $context);
    }

    public function notice($message, array $context = array())
    {
        $this->getHandler()->notice($message, $context);
    }

    public function info($message, array $context = array())
    {
        $this->getHandler()->info($message, $context);
    }

    public function debug($message, array $context = array())
    {
        $this->getHandler()->debug($message, $context);
    }

    public function log($level, $message, array $context = array())
    {
        $this->getHandler()->log($message, $context);
    }
}