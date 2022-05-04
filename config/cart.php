<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default tax rate
    |--------------------------------------------------------------------------
    |
    | This default tax rate will be used when you make a class implement the
    | Taxable interface and use the HasTax trait.
    |
    */
    // Thue mac dinh ban dau de 21
    // 'tax' => 21,
    // Sua lai thue de 0
    'tax' => 0,

    /*
    |--------------------------------------------------------------------------
    | Shoppingcart database settings
    |--------------------------------------------------------------------------
    |
    | Here you can set the connection that the shoppingcart should use when
    | storing and restoring a cart.
    |
    */

    'database' => [
        'connection' => null,

        'table' => 'shoppingcart',
    ],

    /*
    |--------------------------------------------------------------------------
    | Destroy the cart on user logout
    |--------------------------------------------------------------------------
    |
    | When this option is set to 'true' the cart will automatically
    | destroy all cart instances when the user logs out.
    |
    */

    'destroy_on_logout' => false,

    /*
    |--------------------------------------------------------------------------
    | Default number format
    |--------------------------------------------------------------------------
    |
    | This defaults will be used for the formated numbers if you don't
    | set them in the method call.
    |
    */
// Mac dinh ban dau lam tron sau 2 so
    // 'format' => [
    //     'decimals' => 2,

    //     'decimal_point' => '.',

    //     'thousand_seperator' => ',',
    // ],

    // format lai lam tron sau phan le 0 so(0 dong)
    'format' => [
        'decimals' => 0,

        'decimal_point' => '.',

        'thousand_seperator' => ',',
    ],
];
