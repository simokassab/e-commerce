<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

trait AdditionalField
{
    public function storeAdditionalFields($request, $product)
    {
        //TODO: to be organized all addional fields here

        if (!$request->has('fields') && count($request['fields']) == 0)
            return $this;

        $data = [];
        foreach ($request->fields as $index => $field) {
            if (!in_array($field['type'], $this->fieldTypes))
                throw new Exception('Invalid fields type');

            throw_if(!array_key_exists('value', $field), new Exception('Invalid value'));
            if ($field['type'] == 'select') {
                $data[] = [
                    'product_id' => $product->id,
                    'field_id' => (int)$field['field_id'],
                    'field_value_id' =>  (int)$field['value'],
                    'value' => null,
                ];
            } elseif ($field['type'] == 'checkbox') {
                $data[] = [
                    'product_id' => $product->id,
                    'field_id' => (int)$field['field_id'],
                    'field_value_id' =>  null,
                    'value' => (bool)$field['value'],
                ];
            } elseif (($field['type']) == 'date') {
                $data[] = [
                    'product_id' => $product->id,
                    'field_id' => (int)$field['field_id'],
                    'field_value_id' =>  null,
                    'value' => Carbon::parse($field['value'])->format('Y-m-d'),
                ];
            } elseif (($field['type']) == 'text' || gettype($field['type']) == 'textarea') {
                $data[] = [
                    'product_id' => $product->id,
                    'field_id' => (int)$field['field_id'],
                    'field_value_id' =>  null,
                    'value' => json_encode($field['value']),
                ];
            } else {
                continue;
            }
        }
        DB::beginTransaction();
        try {
            static::where('product_id', $product->id)->delete();
            static::insert($data);
            DB::commit();
            return $this;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
