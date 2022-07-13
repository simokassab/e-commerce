<?php

return [
 //IMAGE
    // image extentions and sistring_lengthze
    'default_image_size'=>10240,
    'default_image_extentions'=>'jpeg,svg,png,jpg,gif',

    //minimum width and height for image
    'default_image_minimum_width' => 100,
    'default_image_minimum_height' => 100,

    //maximum width and height for image
    'default_image_maximum_width' => 1000,
    'default_image_maximum_height' => 1000,
//END IMAGE

//ICON
     // icon extentions and size
     'default_icon_size'=>'10240',
     'default_icon_extentions'=>'jpeg,bmp,png,jpg',

     //minimum width and height for icon
     'default_icon_minimum_width' => 100,
     'default_icon_minimum_height' => 100,

     //maximum width and height for icon
     'default_icon_maximum_width' => 1000,
     'default_icon_maximum_height' => 1000,
//END ICON

    'validation_default_entities' => 'category,product,brands',

    'validation_default_type' => 'checkbox,text,select,textarea,date',

    'default_string_length' => 250,

    'default_pagination' => 15,

    'default_cache_time' => 31557600,

    'default_minimum_tax_percentage' => 0,
    'default_maximum_tax_percentage' => 100,

    'default_minimum_price_percentage' => 0,
    'default_maximum_price_percentage' => 100,


    'validation_default_complex_behavior' => 'combine,after_other',

    'default_round_percentage'=>2,

];
