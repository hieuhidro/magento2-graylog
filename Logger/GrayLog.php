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
     * @var \Magento\Framework\Logger\Monolog
     */
    protected $_monolog;

    protected $objectManager;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager
    )
    {
        $this->objectManager = $objectManager;
        $this->_monolog = $this->objectManager->get(\Magento\Framework\Logger\Monolog::class);
    }

    /**
     * @return \Gelf\Logger
     */
    protected function getHandler(){
        if(!$this->_handler){
            /**
             * @var $loggerBuilder \Hidro\Graylog\Logger\GrayLog\LoggerBuilder
             */
            $loggerBuilder = $this->objectManager->get(\Hidro\Graylog\Logger\GrayLog\LoggerBuilder::class);
            $this->_handler = $loggerBuilder->prepareHandler();
            if(null === $this->_handler){
                $this->_handler = $this->_monolog;
            }
        }
        return $this->_handler;
    }

    public function emergency($message, array $context = array())
    {
        try {
            $this->getHandler()->emergency($message, $context);
        }catch (\Exception $e){
            $this->_monolog->emergency($message, $context);
        }
    }

    public function alert($message, array $context = array())
    {
        try {
            $this->getHandler()->alert($message, $context);
        }catch (\Exception $e){
            $this->_monolog->alert($message, $context);
        }
    }

    public function critical($message, array $context = array())
    {
        try {
            $this->getHandler()->critical($message, $context);
        }catch (\Exception $e){
            $this->_monolog->critical($message, $context);
        }
    }

    public function error($message, array $context = array())
    {
        try {
            $this->getHandler()->error($message, $context);
        }catch (\Exception $e){
            $this->_monolog->error($message, $context);
        }
    }

    public function warning($message, array $context = array())
    {
        try {
            $this->getHandler()->warning($message, $context);
        }catch (\Exception $e){
            $this->_monolog->warning($message, $context);
        }
    }

    public function notice($message, array $context = array())
    {
        try {
            $this->getHandler()->notice($message, $context);
        }catch (\Exception $e){
            $this->_monolog->notice($message, $context);
        }
    }

    public function info($message, array $context = array())
    {
        try {
            $this->getHandler()->info($message, $context);
        }catch (\Exception $e){
            $this->_monolog->info($message, $context);
        }
    }

    public function debug($message, array $context = array())
    {
        try {
            $this->getHandler()->debug($message, $context);
        }catch (\Exception $e){
            $this->_monolog->debug($message, $context);
        }
    }

    public function log($level, $message, array $context = array())
    {
        try {
            $this->getHandler()->log($level, $message, $context);
        }catch (\Exception $e){
            $this->_monolog->log($level, $message, $context);
        }
    }
}