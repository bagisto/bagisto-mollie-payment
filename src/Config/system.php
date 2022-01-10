<?php

return [
    [
        'key' => 'sales.paymentmethods.molliePayment',
        'name' => 'mollie::app.admin.system.molliePayment',
        'sort' => 5,
        'fields' => [
            [
                'name' => 'title',
                'title' => 'mollie::app.admin.system.title',
                'type' => 'text',
                'validation' => 'required',
                'channel_based' => true,
                'locale_based' => true
            ],[
                'name' => 'description',
                'title' => 'mollie::app.admin.system.description',
                'type' => 'textarea',
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => true
            ],[
                'name' => 'apikey',
                'title' => 'mollie::app.admin.system.apikey',
                'type' => 'password',
                'validation' => 'required',
                'channel_based' => true,
                'locale_based' => true
            ],[
                'name' => 'active',
                'title' => 'mollie::app.admin.system.status',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'Active',
                        'value' => true
                    ], [
                        'title' => 'Inacitve',
                        'value' => false
                    ]
                ],
                'validation' => 'required'
            ],[
                'name' => 'paymentfromapplicablecountries',
                'title' => 'mollie::app.admin.system.paymentFromApplicableCountries',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'All Allowed Countries',
                        'value' => false
                    ], [
                        'title' => 'Specific Countries',
                        'value' => true
                    ]
                ],
                'validation' => 'required'
            ],[
                'name' => 'paymentfromspecificcountries',
                'title' => 'mollie::app.admin.system.paymentFromSpecificCountries',
                'info' => 'Applicable if specific countries are selected',
                'type' => 'multiselect',
                'channel_based' => true,
                'locale_based' => true,
                'repository'=>'Webkul\Mollie\Repositories\MollieRepository@getCountry'
            ],[
                'name' => 'sort',
                'title' => 'mollie::app.admin.system.sort',
                'type' => 'select',
                'options' => [
                    [
                        'title' => '1',
                        'value' => 1
                    ], [
                        'title' => '2',
                        'value' => 2
                    ], [
                        'title' => '3',
                        'value' => 3
                    ], [
                        'title' => '4',
                        'value' => 4
                    ]
                ],
            ]      
        ],
    ],
   
];