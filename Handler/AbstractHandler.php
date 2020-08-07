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
     * @var \Hidro\Graylog\Helper\Data
     */
    protected $_data;

    public function __construct(
        \Hidro\Graylog\Helper\Data $data
    ) {
        $this->_data = $data;
    }

    /**
     * @param $handler
     * @param \Closure $next
     * @param $record
     * @return bool
     */
    public function aroundIsHandling($handler, $next, $record)
    {
        if (!$this->_data->isDisableExternal()) {
            return $next($record);
        }
        if ($handler instanceof \Hidro\Graylog\Logger\Handler\Graylog) {
            return true;
        }
        return false;
    }
}
