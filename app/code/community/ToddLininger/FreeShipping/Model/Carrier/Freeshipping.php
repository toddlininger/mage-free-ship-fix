<?php
/**
 * @category     Shipping
 * @description  Simple extension to fix bug where free shipping is still available when discount brings total under free shipping threshold
 * @author       Todd Lininger, https://toddlininger.com
 * @license      http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ToddLininger_FreeShipping_Model_Carrier_Freeshipping extends Mage_Shipping_Model_Carrier_Freeshipping
{
    /**
     * FreeShipping Rates Collector
     *
     * @param Mage_Shipping_Model_Rate_Request $request
     * @return Mage_Shipping_Model_Rate_Result
     */
    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        $result = Mage::getModel('shipping/rate_result');

        $this->_updateFreeMethodQuote($request);

		if (($request->getFreeShipping())
			// BEGIN CUSTOM CODE
            // || ($request->getBaseSubtotalInclTax() >= $this->getConfigData('free_shipping_subtotal'))
			|| ($request->getPackageValueWithDiscount() >= $this->getConfigData('free_shipping_subtotal'))
			// END CUSTOM CODE
        ) {
            $method = Mage::getModel('shipping/rate_result_method');

            $method->setCarrier('freeshipping');
            $method->setCarrierTitle($this->getConfigData('title'));

            $method->setMethod('freeshipping');
            $method->setMethodTitle($this->getConfigData('name'));

            $method->setPrice('0.00');
            $method->setCost('0.00');

            $result->append($method);
        }

        return $result;
    }

}
