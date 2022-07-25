<?php

namespace App\Rules;

use App\Models\Settings\Setting;
use Illuminate\Contracts\Validation\InvokableRule;

class SettingValueRule implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */

    public function __construct($type, $key)
    {
        $this->type = $type;
        $this->key = $key;
    }
    public function __invoke($attribute, $value, $fail)
    {
        $type = Setting::find($this->key)->type;
        if ($this->type == 'select' || $this->type == 'text') {
            if ($this->type == $type) {
                if (!is_string($value)) {
                    $fail('the :attribute must be a string');
                }
            } else {
                $fail('the value type must be the same as the setting type');
            }
        } elseif ($this->type == 'number') {
            if ($this->type == $type) {
                if (!is_numeric($value)) {
                    $fail('the :attribute must be a number');
                }
            } else {
                $fail('the value type must be the same as the setting type');
            }
        } elseif ($this->type == 'checkbox') {
            if ($this->type == $type) {
                if (!is_bool($value)) {
                    $fail('the :attribute must be a boolean');
                }
            } else {
                $fail('the value type must be the same as the setting type');
            }
        } elseif ($this->type == 'multi-select') {
            if ($this->type == $type) {
                if(!in_array(Setting::$titlesOptions,$value)){
                    $fail('no data');
                }
                if (!is_array($value)) {
                    $fail('the :attribute must be an array');
                }
            } else {
                $fail('the value type must be the same as the setting type');
            }
        }
    }
}
