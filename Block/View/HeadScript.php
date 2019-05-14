<?php

namespace ClawRock\FullStory\Block\View;

use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * Class HeadScript
 * @package ClawRock\FullStory\Block\View
 */
class HeadScript extends DataObject implements ArgumentInterface
{

    /**
     * @var \ClawRock\FullStory\Helper\Data
     */
    protected $moduleHelper;

    public function __construct(
        \ClawRock\FullStory\Helper\Data $moduleHelper,
        array $data = []
    ) {
        parent::__construct($data);

        $this->moduleHelper = $moduleHelper;
    }

    /**
     * @return \ClawRock\FullStory\Helper\Data
     */
    public function getHelper() {

        return $this->moduleHelper;
    }
}