<?php

namespace ClawRock\FullStory\Helper;

use Magento\Customer\Model\VisitorFactory;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Data
 * @package ClawRock\FullStory\Helper
 */
class Data extends AbstractHelper
{

    /**
     * Is module enabled config
     */
    const CONFIG_MODULE_IS_ENABLED = 'clawrock_fullstory/general/enabled';

    /**
     * FullStory script ID config
     */
    const CONFIG_MODULE_SCRIPT_ID = "clawrock_fullstory/general/fs_org";

    /**
     * Start Guest ID. Create next ID as sum this number with customer_visitor ID
     */
    const FIXED_GUEST_ID = 100000;

    /**
     * Guest ID session key
     */
    const GUEST_ID_KEY = 'guestId';

    /**
     * @var \Magento\Customer\Model\Session\Proxy
     */
    protected $customerSession;

    /**
     * @var VisitorFactory
     */
    protected $visitorFactory;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Data constructor.
     * @param Context $context
     * @param \Magento\Customer\Model\Session\Proxy $customerSession
     * @param VisitorFactory $visitorFactory
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Customer\Model\Session\Proxy $customerSession,
        \Magento\Customer\Model\VisitorFactory $visitorFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($context);

        $this->customerSession = $customerSession;
        $this->visitorFactory = $visitorFactory;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return mixed
     */
    public function getFullStoryId()
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

        return $this->scopeConfig->getValue(self::CONFIG_MODULE_SCRIPT_ID, $storeScope);
    }

    /**
     * @return bool
     */
    public function isCustomerLoggedIn()
    {
        return $this->customerSession->isLoggedIn();
    }

    /**
     * @return int|null
     * @throws LocalizedException
     */
    public function getCustomerId()
    {
        if ($this->isCustomerLoggedIn()) {
            return $this->customerSession->getId();
        }

        return 'Guest' . $this->getGuestCustomerId();
    }

    /**
     * @return string
     * @throws LocalizedException
     */
    public function getCustomerName()
    {
        if ($this->isCustomerLoggedIn()) {
            return $this->customerSession->getCustomer()->getName();
        }

        return 'Guest' . $this->getGuestCustomerId();
    }

    /**
     * @return null|string
     */
    public function getCustomerEmail()
    {
        if ($this->isCustomerLoggedIn()) {
            return $this->customerSession->getCustomer()->getEmail();
        }

        return null;
    }

    /**
     * @return array
     * @throws LocalizedException
     */
    public function getUserData()
    {
        return array_filter([
            'displayName' => $this->getCustomerName(),
            'email' => $this->getCustomerEmail()
        ]);
    }

    /**
     * @return bool
     */
    public function isModuleEnabled()
    {
        return (bool)$this->scopeConfig->getValue(self::CONFIG_MODULE_IS_ENABLED);
    }

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    private function getGuestCustomerId()
    {
        //if guest ID is empty then get or create it
        if (!$this->customerSession->getData(self::GUEST_ID_KEY)) {
            $sessionId = $this->customerSession->getSessionId();
            $visitor = $this->visitorFactory->create();
            //check if ID with current session exists in visitor table
            $existsVisitor = $visitor->getCollection()
                ->addFieldToFilter('session_id', $sessionId)
                ->getFirstItem();
            $visitorId = $existsVisitor->getId();
            //if not then save new record and get ID
            if (!$visitorId) {
                $visitor->setData('session_id', $sessionId);
                $visitor->getResource()->save($visitor);
                $visitorId = $visitor->getId();
            }
            //save guest id to session
            $this->customerSession->setData(self::GUEST_ID_KEY, self::FIXED_GUEST_ID + $visitorId);
        }

        return $this->customerSession->getData(self::GUEST_ID_KEY);
    }
}