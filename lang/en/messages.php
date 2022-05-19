<?php

$nameValue=':name';

return [

    'success' => [
        'create' =>  'The :name was created successfully',
        'update' => 'The '.$nameValue.' was updated successfully',
        'delete' => 'The '.$nameValue.' was deleted successfully',
        'index' => ''
    ],
    
    'failed' => [
        'create' => 'The '.$nameValue.' was not created ! please try again later',
        'update' => 'The '.$nameValue.' was not updated ! please try again later',
        'delete' => 'The '.$nameValue.' was not deleted ! please try again later',
        'index' => ''
    ],
    
];