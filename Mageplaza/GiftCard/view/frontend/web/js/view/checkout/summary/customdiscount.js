define(
    [
        'Magento_Checkout/js/view/summary/abstract-total',
        'Magento_Checkout/js/model/totals',
        'Magento_Checkout/js/model/quote',
        'Magento_Catalog/js/price-utils'
    ],
    function (Component,totals,quote, priceUtils) {
        "use strict";
        return Component.extend({
            defaults: {
                template: 'Mageplaza_GiftCard/checkout/summary/customdiscount'
            },
            isDisplayedCustomDiscount : function(){
                return true;
            },

            getValueDiscount : function(){
                let valueDiscount = totals.getSegment('customer_discount').value;
                return priceUtils.formatPrice(-valueDiscount, quote.getPriceFormat());
            },

            checkDiscountValue : function () {
                let valueDiscount = totals.getSegment('customer_discount').value;
                return valueDiscount > 0;
            },

            checkDiscountTitle : function () {
                return totals.getSegment('customer_discount').title;
            }

        });
    }
);