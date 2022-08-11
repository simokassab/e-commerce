<?php

return [
 //IMAGE
    // image extentions and sistring_lengthze
    'default_image_size' => 200,
    'default_image_extentions' => 'jpeg,svg,png,jpg,gif',

    //maximum width and height for image
    'default_image_maximum_width' => 1000,
    'default_image_maximum_height' => 1000,
//END IMAGE

//ICON
     // icon extentions and size
     'default_icon_size' => 200,
     'default_icon_extentions' => 'jpeg,svg,png,jpg,gif',

     //maximum width and height for icon
     'default_icon_maximum_width' => 1000,
     'default_icon_maximum_height' => 1000,
//END ICON

    'validation_default_entities' => 'category,product,brand',
    'validation_default_type' => 'checkbox,text,select,textarea,date',

    'default_string_length' => 250,
    'default_string_length_2' => 125,

    'default_pagination' => 15,
    'fields_types' => 'checkbox','text','select','textarea','date',

    'default_minimum_tax_percentage' => 0,
    'default_maximum_tax_percentage' => 100,

    'validation_default_complex_behavior' => 'combine,after_other',

    'validation_default_types' => 'normal,bundle,service,variable,variable_child',

    'validation_default_status' => 'draft,pending_review,published',

];
