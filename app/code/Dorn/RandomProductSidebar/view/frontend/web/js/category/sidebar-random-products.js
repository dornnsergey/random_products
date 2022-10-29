define(['ko', 'jquery', 'uiComponent', 'urlBuilder'], function (ko, $, Component, urlBuilder) {
    'use strict';

    return Component.extend({
        defaults: {
            "template": "Dorn_RandomProductSidebar/product"
        },

        products: ko.observableArray([]),

        initialize: function () {
            this._super();

            $.ajax({
                url: urlBuilder.build("/data/ui/randomproducts"),
                success: function (result) {
                   this.products(result);
                }
            });

            return this;
        }
    });
});