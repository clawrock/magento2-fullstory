# Magento 2 - Fullstory Script module
Module for installation [fullstory recording script](https://help.fullstory.com/using/recording-snippet?target=_blank) and identify users with [FS.identify](https://help.fullstory.com/develop-js/identify?target=_blank) function. 
The script sends `customer email`, `full name` and `customer ID` for logged users. 
For guests, it generates and sends incremental `ID` with `Guest` prefix based on `visitor_id` from table `customer_visitor`, e.g `Guest100234`. 
The guest ID is added to header welcome message.
Configuration allows you to enable/disable the module and change script ID. 

## Installation (using composer)
1. `composer require clawrock/magento2-fullstory`
2. Register module `php bin/magento setup:upgrade`
3. Compile code using `php bin/magento setup:di:compile`
4. Clean cache using `php bin/magento cache:clean`

## Installation (manually)
1. Clone the repository to `app/code/ClawRock/FullStory/`
2. Register module `php bin/magento setup:upgrade`
3. Compile code using `php bin/magento setup:di:compile`
4. Clean cache using `php bin/magento cache:clean`

## Configuration
1. Go to Stores -> Configuration -> ClawRock -> FullStory
2. Enable module
3. Provide fullstory ID
4. Save
5. Clean cache

If you disable module in configuration then block with fullstory script will not be added to the header section.
