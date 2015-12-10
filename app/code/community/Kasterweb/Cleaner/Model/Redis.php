<?php

class Kasterweb_Cleaner_Model_Redis extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('cleaner/redis');
    }

    public function truncate()
    {
        $socketPath = Mage::getStoreConfig('cleaner/redis/socket_path');
        if (empty($socketPath)) {
            throw new InvalidArgumentException('Empty socket path. Please set it in System > Configuration > Kasterweb > Cleaner > Redis -> Socket path');
        }
        return shell_exec(sprintf('redis-cli -s %s flushall', $socketPath));
    }
}
