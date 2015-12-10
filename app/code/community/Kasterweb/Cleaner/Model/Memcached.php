<?php

class Kasterweb_Cleaner_Model_Memcached extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('cleaner/memcached');
    }

    public function truncate()
    {
        $returns = array();
        $socketsPaths = array_filter(explode(PHP_EOL, Mage::getStoreConfig('cleaner/memcached/sockets_paths')));
        if (empty($socketsPaths)) {
            throw new InvalidArgumentException('Any socket path was found. Please set it in System > Configuration > Kasterweb > Cleaner > Memcached -> Socket path');
        }
        foreach ($socketsPaths as $socketPath) {
            $returns[] = shell_exec(sprintf('echo "flush_all" | nc -U %s', $socketPath));
        }
        return $returns;
    }
}
