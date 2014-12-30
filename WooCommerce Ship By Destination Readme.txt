"WooCommerce Ship By Destination" 
Version 1.2
Bryan Purcell
1/1/15

*** Introduction ***

"WooCommerce Ship By Destination" plugin allows WooCommerce Administrators to limit products by country on per-shipping-class. A custom error message can be entered, which is presented to users attempting to calculate shipping or check-out with invalid products in the shopping cart as determined by the Shipping Class rules. Users are alerted wherever shipping rates are calculated. Alternatively, Admins can opt to not show a custom error, and simply fall back to the built in "Sorry no shipping methods are available" shown when rates are calculated.

*** Setup ***

To configure "WooCommerce Ship By Destination", first make sure you are running the latest version of WooCommerce. Next, upload to /wp-content/plugins folder, and activate through the dashboard.

"WooCommerce Ship By Destination" Adds a few new options to the "Shipping Class" create screen and edit screen.

Choose the "Products" menu from the left Dashboard menu, and choose "Shipping Classes." New fields are located at the bottom of the Shipping Class options list, directly above the "Save" button. Create new shipping methods, or modify existing methods. Existing shipping methods will not be affected, as the defaults are set to "Allow All Countries".

*** More Information and Notes ***

Shipping Class settings override any and all other shipping method rules and settings. Any violation of the "WooCommerce Ship By Destination" rules will prevent any and all shipping methods from quoting rates to the customer, and prevent the customer from checking out while the invalid items remain in the cart and the shipping settings remain on the invalid country. 