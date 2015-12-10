<?php

class Kasterweb_Cleaner_Adminhtml_CacheController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
	{
		$this->loadLayout()
			->_title($this->__('Cache Management'))
			->_setActiveMenu('cleaner/cache')
			->renderLayout();
	}

	public function mageTablesAction()
	{
		$database = Mage::getSingleton('cleaner/magentoDatabase');
		$truncatedTables = $database->truncate();
		$this->_getSession()->addSuccess(
			Mage::helper('cleaner')->__('%s tables have been truncated.', count($truncatedTables))
		);
		$this->_redirect('*/*');
	}

	public function mageDirAction()
	{
		$directories = Mage::getModel('cleaner/magentoDirectories');
		try {
			$truncatedTables = $directories->truncate();
			$this->_getSession()->addSuccess(
				Mage::helper('cleaner')->__('%s directories have been truncated.', count($truncatedTables))
			);
		} catch (Exception $e) {
			$this->_getSession()->addError($e->getMessage());
		}
		$this->_redirect('*/*');
	}

	public function redisAction()
	{
		$redis = Mage::getModel('cleaner/redis');
		try {
			$redis->truncate();
			$this->_getSession()->addSuccess(
				Mage::helper('cleaner')->__('The redis has been truncated.')
			);
		} catch (InvalidArgumentException $e) {
			$this->_getSession()->addError(
				Mage::helper('cleaner')->__($e->getMessage())
			);
		}
		$this->_redirect('*/*');
	}

	public function memcachedAction()
	{
		$memcached = Mage::getModel('cleaner/memcached');
		try {
			$memcached->truncate();
			$this->_getSession()->addSuccess(
				Mage::helper('cleaner')->__('The memcached has been truncated.')
			);
		} catch (InvalidArgumentException $e) {
			$this->_getSession()->addError(
				Mage::helper('cleaner')->__($e->getMessage())
			);
		}
		$this->_redirect('*/*');
	}

	public function cdnAction()
	{
		$cdn = Mage::getModel('cleaner/cdn');
		try {
			$cdn->truncate($this->getRequest()->getParam('media_path'));
			$this->_getSession()->addSuccess(
				Mage::helper('cleaner')->__('The CDN has been enqueued to be truncated.')
			);
		} catch (CdnException $e) {
			$this->_getSession()->addError(
				Mage::helper('cleaner')->__('The CDN has not been enqueued because: %s', $e->getMessage())
			);
		} catch (InvalidArgumentException $e) {
			$this->_getSession()->addError(
				Mage::helper('cleaner')->__($e->getMessage())
			);
		}
		$this->_redirect('*/*');
	}
}
