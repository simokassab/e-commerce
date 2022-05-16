<?php

namespace App\Models\Language;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MainModel;

class Language extends MainModel
{
    use HasFactory;
    protected $guard_name = 'sanctum';

}
