<?php
/**
 * Created by Hidro Le.
 * Job Title: Magento Developer
 * Project Name: m2cedefault.local
 * Date: 8/9/20
 * Time: 17:46
 */


namespace Hidro\Graylog\Plugin\Logger;


class Monolog
{
    protected $configuration;
    public function __construct(
        \Hidro\Graylog\Helper\Configuration $configuration
    )
    {
        $this->configuration = $configuration;
    }

    /**
     * @param $subject
     * @param \Closure $callable
     * @param $handler
     */
    public function aroundPushHandler($subject, $callable, $handler){
        if(!$this->configuration->isEnabled() ||
            ($handler instanceof \Hidro\Graylog\Logger\Handler\Graylog
            || !$this->configuration->isDisableExternal())){
            return $callable($handler);
        }
        return $subject;
    }
}