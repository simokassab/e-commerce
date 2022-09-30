<?php

namespace App\Observers\Fields;

use App\Models\Field\FieldValue;
use mysql_xdevapi\Exception;

class FieldsValesObserver
{
    /**
     * Handle the FieldsValue "created" event.
     *
     * @param  \App\Models\FieldValue  $fieldsValue
     * @return void
     */
    public function created(FieldValue $fieldsValue)
    {
        //
    }

    /**
     * Handle the FieldsValue "updated" event.
     *
     * @param  \App\Models\FieldValue  $fieldsValue
     * @return void
     */
    public function updated(FieldValue $fieldsValue)
    {
        //
    }

    /**
     * Handle the FieldsValue "deleted" event.
     *
     * @param  \App\Models\FieldValue  $fieldsValue
     * @return void
     */
    public function deleted(FieldValue $fieldsValue)
    {
    }

    /**
     * Handle the FieldsValue "deleted" event.
     *
     * @param \App\Models\FieldValue $fieldsValue
     * @return void
     * @throws \Exception
     */
    public function deleting(FieldValue $fieldsValue)
    {

        if($fieldsValue->fieldCategorie->count() != 0){
            throw new \Exception('the field value is already in use in category entities');
        }

        if($fieldsValue->fieldBrand->count() != 0){
            throw new \Exception('the field value is already in use in brand entities');
        }

        if($fieldsValue->fieldProduct->count() != 0){
            throw new \Exception('the field value is already in use in product entities');
        }
    }


    /**
     * Handle the FieldsValue "restored" event.
     *
     * @param  \App\Models\FieldValue  $fieldsValue
     * @return void
     */
    public function restored(FieldValue $fieldsValue)
    {
        //
    }

    /**
     * Handle the FieldsValue "force deleted" event.
     *
     * @param  \App\Models\FieldValue  $fieldsValue
     * @return void
     */
    public function forceDeleted(FieldValue $fieldsValue)
    {
        //
    }
}
