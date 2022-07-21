<?php

return [
    'brands' => [
        'id' => [
            'name' => 'ID',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],
        'name' => [
            'name' => 'Name',
            'search' => 'string',
            'type' => 'string',
            'sort' => false
        ],
        'code' => [
            'name' => 'Code',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],
        'image' => [
            'name' => 'Image',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => false
        ],
    ],
    'categories' => [
        'id' => [
            'name' => 'ID',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],
        'name' => [
            'name' => 'Name',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'code' => [
            'name' => 'Code',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],
        'image' => [
            'name' => 'Image',
            'search' => '',
            'type' => 'image',
            'sort' => false
        ],
        'icon' => [
            'name' => 'Icon',
            'search' => '',
            'type' => 'image',
            'sort' => false
        ],
        'parent' => [
            'name' => 'Parent Name',
            'search' => 'string',
            'type' => 'String',
            'sort' => true
        ],
        'slug' => [
            'name' => 'Slug',
            'search' => 'string',
            'type' => 'String',
            'sort' => true
        ],
    ],
    'countries' => [
        'id' => [
            'name' => 'ID',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],
        'name' => [
            'name' => 'Name',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'iso_code_1' => [
            'name' => 'ISO code one',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'iso_code_2' => [
            'name' => 'ISO code two',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'phone_code' => [
            'name' => 'Phone code',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],
        'flag' => [
            'name' => 'Flag',
            'search' => '',
            'type' => 'image',
            'sort' => false
        ],
    ],
    'currencies' => [
        'id' => [
            'name' => 'ID',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],
        'name' =>[
            'name' => 'Name',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'code' =>[
            'name' => 'Code',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],
        'symbol' => [
            'name' => 'Symbol',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'rate' => [
            'name' => 'Rate',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'image' => [
            'name' => 'Image',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => false
        ],
    ],
    'discounts' => [
        'id' => [
            'name' => 'ID',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],
        'name' => [
            'name' => 'Name',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'start_date' =>[
            'name' => 'Start Date',
            'search' => 'date',
            'type' => 'date',
            'sort' => true
        ],
        'end_date' => [
            'name' => 'Start Date',
            'search' => 'date',
            'type' => 'date',
            'sort' => true
        ],
        'discount_percentage' => [
            'name' => 'Discount Percentage',
            'search' => '',
            'type' => 'date',
            'sort' => true
        ],
    ],
    'fields' => [
        'id' => [
            'name' => 'ID',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],
        'title' => [
            'name' => 'Title',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'type' => [
            'name' => 'Type',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'entity' => [
            'name' => 'Entity',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
    ],
    'labels' => [
        'id' => [
            'name' => 'ID',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],
        'title' => [
            'name' => 'Title',
            'search' => 'string',
            'type' => 'string',
            'sort' => true

        ],
        'entity' => [
            'name' => 'Entity',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'color' => [
            'name' => 'Color',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'image' => [
            'name' => 'Image',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => false
        ],
        'key' => [
            'name' => 'Key',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
    ],
    'languages' => [
        'id' => [
            'name' => 'ID',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],
        'name' => [
            'name' => 'Name',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'code' => [
            'name' => 'Code',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],
        'image' => [
            'name' => 'Image',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
    ],
    'prices' => [
        'id' => [
            'name' => 'ID',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],
        'name' => [
            'name' => 'Name',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'currency' => [
            'name' => 'Currency',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'original_price' => [
            'name' => 'Original Price',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'original_percent' => [
            'name' => 'Original Percentage',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
    ],
    'products' => [
        'id' => [
            'name' => 'ID',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],
        'name' => [
            'name' => 'Name',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'slug' => [
            'name' => 'Slug',
            'search' => 'string',
            'type' => 'String',
            'sort' => true
        ],
        'category' => [
            'name' => 'Category',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'code' => [
            'name' => 'Code',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],
        'sku' => [
            'name' => 'SKU',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'type' => [
            'name' => 'Type',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'unit ' => 'Unit',
        'quantity' => 'Quantity',
        'reserved_quantity' => 'Reserved Quantity',
        'minimum_quantity' => 'Minimum Quantity',
        'summary' => 'Summary',
        'specification' => 'Specification',
        'image' => 'Image',
        'brand_id' => 'Brand',
        'tax_id' => 'Tax',
        'status' => 'Status',
        'barcode' => 'Barcode',
        'height' => 'Height',
        'width' => 'Width',
        'length' => 'Length',
        'weight' => 'Weight',
        'parent_product_id ' => 'Parent',
        'is_default_child' => 'Is Default Child?',
        'products_statuses_id' => 'Product Status'
    ],
    'roles' => [
        'id' => [
            'name' => 'ID',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],
        'name' => [
            'name' => 'Name',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'parent_role' => [
            'name' => 'Parent Role',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ]
    ],
    'settings' => [
        'key' => [
            'key' => 'key',
            'name' => 'Variable Name',
            'search' => 'string',
            'type' => 'string',
            'isShow' => true
        ],
        'name' => [
            'key' => 'name',
            'name' => 'Name',
            'search' => 'string',
            'type' => 'string',
            'isShow' => true
        ],
        'options' => [
            'key' => 'options',
            'name' => 'options',
            'search' => 'string',
            'type' => 'string',
            'isShow' => true
        ],
        'value' => [
            'key' => 'Value',
            'name' => 'Values',
            'search' => 'string',
            'type' => 'string',
            'isShow' => true
        ],
    ],
    'tags' => [
        'id' => [
            'name' => 'ID',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],
        'name' => [
            'name' => 'Name',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
    ],
    'taxes' => [
        'id' => [
            'name' => 'ID',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],
        'name' => [
            'name' => 'Name',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'percentage' => [
            'name' => 'Percentage',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'complex_behavior' => [
            'name' => 'Complex Behavior',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],


    ],
    'units' => [
        'id' => [
            'name' => 'ID',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],
        'name' => [
            'name' => 'Name',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'code' => [
            'name' => 'Code',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],

    ],
    'users' => [
        'id' => [
            'name' => 'ID',
            'search' => 'integer',
            'type' => 'integer',
            'sort' => true
        ],
        'username' => [
            'name' => 'User Name',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'email' => [
            'name' => 'Email',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'first_name' => [
            'name' => 'First Name',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'last_name' => [
            'name' => 'Last Name',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ],
        'role_name' => [
            'name' => 'Role Name',
            'search' => 'string',
            'type' => 'string',
            'sort' => true
        ]
    ],

    
];