<?php

$name_value=':name';

return [

    'success' => [
        'create' =>  'تم إضافة '.$name_value.' بنجاح',
        'update' => 'تم تعديل '.$name_value.' بنجاح',
        'delete' => 'تم حذف '.$name_value.' بنجاح',
        'index' => ''
    ],

    'failed' => [
        'create' => 'لم يتم إضافة '.$name_value.' | حاول مرة أخرى',
        'update' => 'لم يتم تعديل '.$name_value.'  | حاول مرة أخرى',
        'delete' => 'لم يتم حذف '.$name_value.'  | حاول مرة أخرى',
        'index' => ''
    ],
   
];