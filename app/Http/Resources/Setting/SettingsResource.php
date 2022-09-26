<?php

namespace App\Http\Resources\Setting;

use App\Models\Price\Price;
use App\Models\Settings\Setting;
use App\Services\Setting\SettingService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

class SettingsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     * @throws \Throwable
     */
    public function toArray($request)
    {
        $lang = App::getLocale();

        $value = $this->value;
        $options = [];

        if ($this->type == 'select' || $this->type == 'multi-select' || $this->type == 'model_select')
            $options = Setting::getTitleOptions()[$this->title];

        if ($this->title == 'default_pricing_class') {
            foreach (Setting::getTitleOptions()[$this->title] as $key => $option) {
                $options[$key]['id'] = $option['id'];
                $options[$key]['name'] = $option['name'][$lang] ?? 'N/A';
            }
        }

        if ($this->title == 'products_required_fields') {
            if (!is_null($value))
                $value = explode(',', $this->value);
        }
        if ($this->type == 'model-select')
            $this->type = 'select';
        $value = match ($this->type) {
            'number' => (int)$value ?? null,
            'checkbox' => (bool)$value ?? false,
            'multi-select' => $value ?? null,
            'model-select' => (int)$value ?? null,
            'select' => (int)$value ?? null,
            default => $value ??  null,
        };

        return [
            'key' => $this->id,
            'title' => $this->title,
            'name' => ucwords(str_replace("_", " ", $this->title)),
            'type' => $this->type,
            'options' => ($options),
            'value' => $value
        ];
    }
}
