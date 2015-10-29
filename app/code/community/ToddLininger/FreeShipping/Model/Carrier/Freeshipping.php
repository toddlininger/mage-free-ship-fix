<?php
/**
 * @category     Shipping
 * @description  Simple extension to fix bug where free shipping is still available when discount brings total under free shipping threshold
 * @author       Todd Lininger, https://toddlininger.com
 * @license      http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @thanks       Thanks to Nikhil Ben Kuruvilla for superior upgrade-proof code: https://github.com/nikhilben/magento-free-shipping-and-discount-coupon
 */
class ToddLininger_FreeShipping_Model_Carrier_Freeshipping extends Mage_Shipping_Model_Carrier_Freeshipping
{
	/**
	 * The original free shipping class will use the discounted package value.
	 *
	 * The package_value_with_discount value already is in the base currency
	 * even if there is no "base" in the property name, no need to convert it.
	 *
	 * @param Mage_Shipping_Model_Rate_Request $request
	 * @return Mage_Shipping_Model_Rate_Result
	 */
	public function collectRates(Mage_Shipping_Model_Rate_Request $request)
	{
		$baseSubtotal = $request->getBaseSubtotalInclTax();
		$request->setBaseSubtotalInclTax($request->getPackageValueWithDiscount());
		$result = parent::collectRates($request);
		$request->setBaseSubtotalInclTax($baseSubtotal);
		return $result;
	}

}
