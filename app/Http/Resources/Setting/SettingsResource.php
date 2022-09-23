<?php

namespace App\Http\Resources\Setting;

use App\Models\Price\Price;
use App\Models\Settings\Setting;
use App\Services\Setting\SettingService;
use Illuminate\Http\Resources\Json\JsonResource;
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

        $value = null;
        $options = [];

        if (in_array($this->title, Setting::$titles) && ($this->type == 'select' || $this->type == 'multi-select' || $this->type == 'model_select'))
            $optionsArray = Setting::getTitleOptions()[$this->title];

        if ($this->title == 'default_pricing_class') {
            foreach ($optionsArray as $key => $option) {
                $options[$key]['id'] = $option['id'];
                $options[$key]['name'] = $option['name']['en'];
            }
        }

        if ($this->title == 'products_required_fields') {
            $value = explode(',', $this->value);
        }

        if ($this->type == 'model-select')
            $this->type = 'select';

        $value = match ($this->type) {
            'number' => (int)$value ?? 0,
            'checkbox' => (bool)$value ?? false,
            'multi-select' => $value ?? [],
            'model-select' => (int)$value ?? null,
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
