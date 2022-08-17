<?php

namespace App\Models\Orders;

use App\Models\MainModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class OrderProduct extends MainModel
{

    use HasFactory,HasTranslations;

    protected array $translatable = [];
}
