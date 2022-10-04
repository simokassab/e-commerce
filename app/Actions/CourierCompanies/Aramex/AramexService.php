<?php

namespace App\Actions\CourierCompanies\Aramex;

use App\Actions\CourierCompanies\CourierCompaniesInterface;
use App\Models\CourierCompanies\ClientCourierCompany;
use App\Models\CourierCompanies\CourierCompany;

class AramexService implements CourierCompaniesInterface
{
    private $account_number, $username, $password, $account_pin, $account_entity, $account_country_code, $version ;
    private $product_group, $product_type, $endpoint, $causer;


    /**
     * @throws \Exception
     */
    public function __construct()
    {

    }

    /*
     * get the credentials of Aramex from client_courier_companies table
     * if doesnt exist,
     */
    public function getCredentials($courier_id){
        $env = config('aramex.TEST');
        $courier = CourierCompany::find($courier_id);
        if($courier){
            $params_value = ClientCourierCompany::where('courier_company_id', $courier_id)->first();
            if($params_value->params){
                foreach(json_decode(json_encode($params_value->params), true) as $key => $array){
                    $r[] = array_merge(json_decode(json_encode($courier->params),true )[$key],$array);
                }

                foreach($r as  $key=>$val){
                    if($val['key']==='ProductGroup'){
                        $this->product_group = $val['value'];
                    }
                    if($val['key']==='ProductType'){
                        $this->product_type = $val['value'];
                    }
                    if($val['key']==='endpoint'){
                        $this->endpoint = rtrim($val['value'],'/');
                    }
                    if($val['key']==='AccountNumber'){
                        $this->account_number = $val['value'];
                    }
                    if($val['key']==='UserName'){
                        $this->username = $val['value'];
                    }
                    if($val['key']==='Password'){
                        $this->password = $val['value'];
                    }
                    if($val['key']==='AccountPin'){
                        $this->account_pin = $val['value'];
                    }
                    if($val['key']==='AccountEntity'){
                        $this->account_entity = $val['value'];
                    }
                    if($val['key']==='AccountCountryCode'){
                        $this->account_country_code = $val['value'];
                    }
                    if($val['key']==='Version'){
                        $this->version = $val['value'];
                    }
                }
            }
            else {
                $this->account_number = config("aramex.".$env)['AccountNumber'];
                $this->account_entity = config("aramex.".$env)['AccountEntity'];
                $this->account_country_code = config("aramex.".$env)['AccountCountryCode'];
                $this->account_pin = config("aramex.".$env)['AccountPin'];
                $this->username = config("aramex.".$env)['UserName'];
                $this->password = config("aramex.".$env)['Password'];
                $this->version = config("aramex.".$env)['Version'];
                $this->endpoint = config("aramex.".$env)['Endpoint'];
                $this->product_group = config("aramex.ProductGroup");
                $this->product_type = config("aramex.ProductType");
            }
        }
        else {
            $this->account_number = config("aramex.".$env)['AccountNumber'];
            $this->account_entity = config("aramex.".$env)['AccountEntity'];
            $this->account_country_code = config("aramex.".$env)['AccountCountryCode'];
            $this->account_pin = config("aramex.".$env)['AccountPin'];
            $this->username = config("aramex.".$env)['UserName'];
            $this->password = config("aramex.".$env)['Password'];
            $this->version = config("aramex.".$env)['Version'];
            $this->endpoint = config("aramex.".$env)['Endpoint'];
            $this->product_group = config("aramex.ProductGroup");
            $this->product_type = config("aramex.ProductType");
        }
    }


    public function createShipment(array $data)
    {
        // TODO: Implement createShipment() method.
    }

    public function cancelShipment(array $data)
    {
        // TODO: Implement cancelShipment() method.
    }

    public function trackShipment(array $data)
    {
        // TODO: Implement trackShipment() method.
    }

    public function getLabel(array $data)
    {
        // TODO: Implement getLabel() method.
    }
}
