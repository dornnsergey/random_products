define(
    [
        'ko',
        'Magento_Checkout/js/view/payment/default',
        'Magento_Customer/js/model/customer'
    ],
    function (ko, Component, customer) {
        'use strict';


        return Component.extend({
            defaults: {
                template: 'Dorn_Loyalty/payment/coins'
            },

            currentCoins: 0,

            initialize: function () {
                this._super();

                if (customer.isLoggedIn() && customer.customerData.custom_attributes) {
                    this.currentCoins = Math.round(customer.customerData.custom_attributes.coins.value * 100) / 100;
                }
            }
        });
    }
);