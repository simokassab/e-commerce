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
        'id' => 'ID',
        'name' => 'Name',
        'code' => 'Code',
        'symbol' => 'Symbol',
        'rate' => 'Rate',
        'is_default' => 'Is Default?',
        'image' => 'image',
    ],
    'discounts' => [
        'id' => 'ID',
        'name' => 'Name',
        'start_date' => 'Start Date',
        'end_date' => 'End Date',
        'discount_percentage' => 'Discount Percentage',
    ],
    'fields' => [
        'id' => 'ID',
        'title' => 'Title',
        'type' => 'Type',
        'entity' => 'Entity',
        'is_required' => 'Is Required?',
        'value' => 'Value',
    ],
    'labels' => [
        'id' => 'ID',
        'title' => 'Title',
        'entity' => 'Entity',
        'color' => 'Color',
        'image' => 'Image',
        'key' => 'Key',
    ],
    'languages' => [
        'id' => 'ID',
        'name' => 'Name',
        'code' => 'Code',
        'is_default' => 'Is Default?',
        'image' => 'Image',
    ],
    'prices' => [
        'id' => 'ID',
        'name' => 'Name',
        'currency' => 'Currency',
        'is_virtual' => 'Is Virtual?',
        'original_price_id ' => 'Original Price',
        'original_percent ' => 'Original Percentage',
    ],
    'products' => [
        'id' => 'ID',
        'name' => 'Name',
        'slug' => 'Slug',
        'category' => 'Category',
        'code' => 'Code',
        'sku' => 'Sku',
        'type' => 'Type',
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
        'id' => 'ID',
        'name' => 'Name',
    ],
    'settings' => [
        'id' => 'ID',
        'title' => 'Title',
        'value' => 'Value',
        'is_developer' => 'Is Developer?',
    ],
    'tags' => [
        'id' => 'ID',
        'name' => 'Name',
    ],
    'taxes' => [
        'id' => 'ID',
        'name' => 'Name',
        'is_complex' => 'IS Complex?',
        'percentage' => 'Percentage',
        'complex_behavior' => 'Complex Behavior',


    ],
    'units' => [
        'id' => 'ID',
        'name' => 'Name',
        'code' => 'Code',

    ],
    'users' => [
        'id' => 'ID',
        'username ' => 'User Name',
        'email ' => 'Email',
        'first_name ' => 'First Name',
        'last_name ' => 'Last Name',
    ],

    
];