define(['uiComponent'], function (Component) {
    'use strict';

    return Component.extend({
        defaults: {
            "template": "Dorn_RandomProductSidebar/product"
        },

        initialize: function () {
            this._super();

            console.log(this.products);
            return this;
        }
    });
});