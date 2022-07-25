<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class SettingRule implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        dd($attribute);
if($value->type=='multi-select'){
            if(!is_array($value->value)){
                $fail('The value must be an array');
            }
}
if($value->type=='select' || $value->type=='text'){
            if(!is_string($value->value)){
                $fail('The value must be a string');
            }
}
if($value->type=='number'){
            if(!is_numeric($value->value)){
                $fail('The value must be a number');
            }
}
if($value->type=='checkbox'){
            if(!is_bool($value->value)){
                $fail('The value must be a boolean');
            }

}
    }
}
