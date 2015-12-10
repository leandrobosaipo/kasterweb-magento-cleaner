<?php

class Kasterweb_Cleaner_Model_MagentoDirectories extends Mage_Core_Model_Abstract
{
    /**
     * Directories to truncate
     *
     * @var array
     */
    protected $directories = array(
        'downloader/.cache',
        'downloader/pearlib/cache',
        'downloader/pearlib/download',
        'includes/src',
        'var/cache',
        'var/locks',
        'var/log',
        'var/report',
        'var/session',
        'var/tmp',
    );

    protected function _construct()
    {
        $this->_init('cleaner/magentoDirectories');
    }

    public function truncate()
    {
        $truncatedDirectories = array();
        foreach ($this->directories as $dir) {
            array_walk(glob($dir . '/**/*'), function($item) {
                if (is_file($item)) {
                    unlink($item);
                } else if (is_dir($item)) {
                    rmdir($item);
                }
            });
            $truncatedDirectories[] = $dir;
        }
        return $truncatedDirectories;
    }
}
