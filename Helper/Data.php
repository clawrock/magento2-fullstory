<?php

namespace ClawRock\FullStory\Helper;

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
     * Min random guest ID
     */
    const RANDOM_GUEST_ID_MIN = 1;

    /**
     * Max random guest ID
     */
    const RANDOM_GUEST_ID_MAX = 1000;

    /**
     * Guest ID session key
     */
    const GUEST_ID_KEY = 'guestId';

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Magento\Framework\Math\Random
     */
    protected $mathRandom;

    /**
     * Data constructor.
     * @param Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\Math\Random $mathRandom
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Math\Random $mathRandom
    ) {
        parent::__construct($context);

        $this->customerSession = $customerSession;
        $this->mathRandom = $mathRandom;
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

        return $this->getGuestCustomerId();
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
     * @return integer
     * @throws LocalizedException
     */
    private function getGuestCustomerId()
    {
        if (!$this->customerSession->getData(self::GUEST_ID_KEY)) {
            $randomNumber = $this->mathRandom->getRandomNumber(self::RANDOM_GUEST_ID_MIN,
                self::RANDOM_GUEST_ID_MAX);

            $this->customerSession->setData(self::GUEST_ID_KEY, $randomNumber);
        }

        return $this->customerSession->getData(self::GUEST_ID_KEY);
    }
}