<?php

return [
    'brands' => [
        'id' => [
            ''
        ],
        'name' => 'الإسم',
        'code' => 'الشفرة',
        'image' => 'الصورة',
    ],
    
    'categories' => [
        'id' => 'الرقم',
        'name' => 'الإسم',
        'code' => 'الشفرة',
        'image' => 'الصورة',
        'icon' => 'أيقونة',
        'parent' => 'إسم الأب',
        'slug' => 'الشفرة الخاصة',
    ],
    'countries' => [
        'id' => 'الرقم',
        'name' => 'الإسم',
        'iso_code_1' => '1 الرمز الوحيد',
        'iso_code_2' => 'الرمز الوحيد 2',
        'phone_code' => 'رمز الهاتف',
        'flag' => 'علم',
    ],
    'currencies' => [
        'id' => 'الرقم',
        'name' => 'الإسم',
        'code' => 'الشفرة',
        'symbol' => 'الرمز',
        'rate' => 'القيمة',
        'is_default' => 'هو الافتراضي؟',
        'image' => 'الصورة',
    ],
    'discounts' => [
        'id' => 'الرقم',
        'name' => 'الإسم',
        'start_date' => 'تاريخ البدء',
        'end_date' => 'تاريخ الانتهاء',
        'discount_percentage' => 'نسبة الخصم',
    ],
    'fields' => [
        'id' => 'الرقم',
        'title' => 'العنوان',
        'type' => 'النوع',
        'entity' => 'نوع الإدخال',
        'is_required' => 'هو إلزامي؟',
        'value' => 'القيمة',
    ],
    'labels' => [
        'id' => 'الرقم',
        'title' => 'العنوان',
        'entity' => 'نوع الإدخال',
        'color' => 'اللون',
        'image' => 'الصورة',
        'key' => 'المفتاح',
    ],
    'languages' => [
        'id' => 'الرقم',
        'name' => 'الإسم',
        'code' => 'الشفرة',
        'is_default' => 'هو الافتراضي؟',
        'image' => 'الصورة',
    ],
    'prices' => [
        'id' => 'الرقم',
        'name' => 'الإسم',
        'currency' => 'العملة',
        'is_virtual' => 'افتراضي؟',
        'original_price_id ' => 'السعر الأصلي',
        'original_percent ' => 'نسبة الخصم الأصلية',
    ],
    'products' => [
        'id' => 'الرقم',
        'name' => 'الإسم',
        'slug' => 'الشفرة الخاصة',
        'category' => 'الفئة',
        'code' => 'الشفرة',
        'sku' => 'وحدة المنتج',
        'type' => 'النوع',
        'unit ' => 'وحدة',
        'quantity' => 'الكمية',
        'reserved_quantity' => 'الكمية المحجوزة',
        'minimum_quantity' => 'الكمية الأدنى',
        'summary' => 'الملخص',
        'specification' => 'تخصيص',
        'image' => 'الصورة',
        'brand_id' => 'ماركة',
        'tax_id' => 'ضريبة',
        'status' => 'الحالة',
        'barcode' => 'الرمز الشريطي',
        'height' => 'ارتفاع',
        'width' => 'العرض',
        'length' => 'الطول',
        'weight' => 'الوزن',
        'parent_product_id ' => 'الأب',
        'is_default_child' => 'هو الطفل الافتراضي؟',
        'products_statuses_id' => 'حالة المنتج'
    ],
    'roles' => [
        'id' => 'الرقم',
        'name' => 'الإسم',
    ],
    'settings' => [
        'id' => 'الرقم',
        'title' => 'العنوان',
        'value' => 'القيمة',
        'is_developer' => 'هو مطور؟',
    ],
    'tags' => [
        'id' => 'الرقم',
        'name' => 'الإسم',
    ],
    'taxes' => [
        'id' => 'الرقم',
        'name' => 'الإسم',
        'is_complex' => 'هو معقد؟',
        'percentage' => 'نسبة الضريبة',
        'complex_behavior' => 'نوع الضريبة',


    ],
    'units' => [
        'id' => 'الرقم',
        'name' => 'الإسم',
        'code' => 'الشفرة',

    ],
    'users' => [
        'id' => 'الرقم',
        'username ' => 'اسم المستخدم',
        'email ' => 'البريد الإلكتروني',
        'first_name ' => 'الاسم الأول',
        'last_name ' => 'الاسم الأخير',
    ],



];