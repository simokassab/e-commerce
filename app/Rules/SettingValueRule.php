<?php

namespace App\Rules;

use App\Models\Price\Price;
use App\Models\Settings\Setting;
use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Support\Facades\Cache;

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

    public function __construct($settings, $type, $key)
    {
        $this->settings = $settings;
        $this->type = $type;
        $this->key = $key;
    }
    public function __invoke($attribute, $value, $fail)
    {
        $settings = getSettings($this->settings->title);
        if ($settings->title == 'default_pricing_class')
            $this->type = 'model-select';

        if ($this->type != $settings->type) {
            return $fail('the value type must be the same as the setting type');
        }
        if ($this->type == 'text') {
            if (!is_string($value))
                return $fail('the :attribute must be a string');
        } elseif ($this->type == 'number') {
            if (!is_numeric($value))
                return $fail('the :attribute must be a number');
        } elseif ($this->type == 'checkbox') {
            if (!is_bool($value))
                return $fail('the :attribute must be a boolean');
        } elseif ($this->type == 'multi-select') {
            if (!is_array($value)) {
                return $fail('the :attribute must be an array');
            }
            if (!Setting::validateOptionsByTitle($settings->title, $value))
                return $fail('the :attribute must be an array of valid options');
        } elseif ($settings->title == 'default_pricing_class') {
            $this->type = 'select';
            if (!is_numeric($value))
                return $fail('the :attribute must be a number');
            else {
                $priceIds = Price::whereIs_virtual(0)->pluck('id')->toArray();
                if (!in_array($value, $priceIds)) {
                    return $fail('the :attribute must be a valid price id');
                }
            }
        }
    }
}
