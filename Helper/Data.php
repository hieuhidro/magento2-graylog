<?php

/**
 * Created by Hidro Le.
 * Job Title: Magento Developer
 * Project Name: local.magento2.com
 * Date: 07/08/2020
 * Time: 19:09
 */

namespace Hidro\Graylog\Helper;

use Magento\Store\Model\ScopeInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_CONFIG_DISABLE_EXTERNAL = 'system/graylog/disable_external';

    public function isDisableExternal($store = null)
    {
        return $this->scopeConfig->isSetFlag(self::XML_CONFIG_DISABLE_EXTERNAL, ScopeInterface::SCOPE_STORE, $store);
    }
}
