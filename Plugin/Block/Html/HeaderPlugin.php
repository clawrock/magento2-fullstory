<?php

namespace ClawRock\FullStory\Plugin\Block\Html;

/**
 * Class HeaderPlugin
 * @package ClawRock\FullStory\Plugin\Block\Html
 */
class HeaderPlugin
{

    /**
     * @var \ClawRock\FullStory\Helper\Data
     */
    protected $moduleHelper;

    /**
     * HeaderPlugin constructor.
     * @param \ClawRock\FullStory\Helper\Data $moduleHelper
     */
    public function __construct(
        \ClawRock\FullStory\Helper\Data $moduleHelper
    ) {
        $this->moduleHelper = $moduleHelper;
    }

    /**
     * @param \Magento\Theme\Block\Html\Header $header
     * @param \Closure $proceed
     * @return mixed|string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function aroundGetWelcome(\Magento\Theme\Block\Html\Header $header, \Closure $proceed)
    {
        if ($this->moduleHelper->isModuleEnabled() && !$this->moduleHelper->isCustomerLoggedIn() && $this->moduleHelper->getFullStoryId() && $this->moduleHelper->getCustomerName()) {
            return $header->escapeHtml(sprintf(__('Welcome %s!'), $this->moduleHelper->getCustomerName()));
        }

        return $proceed();
    }
}