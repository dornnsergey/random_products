define(['jquery', 'uiComponent'], function ($, Component) {
    'use strict';

    return Component.extend({
        defaults: {
            "template": "Dorn_RandomProductSidebar/product"
        },

        hasProducts: function () {
            return this.products.length;
        },

        initialize: function () {
            this._super();

            $.ajax({
                url: "/data/ui/randomproducts",
                async: false,
                success: result => this.products = result
            });

            return this;
        }
    });
});