<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class SettingTypeRule implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $type, $fail)
    {
        if(!in_array($type, ['number', 'text', 'checkbox','select', 'multi-select'])) {
            $fail('the :attribute field is invalid');
        }
        if($type == 'select' || $type == 'text'){
            $fail('string');
        }
        if($type == 'number'){
            $fail('numeric');
        }
        if($type == 'checkbox'){
            $fail('boolean');
        }
        if($type == 'multi-select'){
            $fail('array');
        }
    }
}
