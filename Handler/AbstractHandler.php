<?php

/**
 * Created by Hidro Le.
 * Job Title: Magento Developer
 * Project Name: local.magento2.com
 * Date: 07/08/2020
 * Time: 18:53
 */

namespace Hidro\Graylog\Handler;

class AbstractHandler
{
    /**
     * @var \Hidro\Graylog\Helper\Configuration
     */
    protected $_configuration;

    public function __construct(
        \Hidro\Graylog\Helper\Configuration $configuration
    ) {
        $this->_configuration = $configuration;
    }

    /**
     * Disable external handling
     * @param $handler
     * @param \Closure $next
     * @param $record
     * @return bool
     */
    public function aroundIsHandling($handler, $next, $record)
    {
        if (!$this->_configuration->isDisableExternal()) {
            return $next($record);
        }
        if ($handler instanceof \Hidro\Graylog\Logger\Handler\Graylog) {
            return true;
        }
        return false;
    }
}
