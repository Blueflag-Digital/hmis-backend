<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\CountryCurrency;
use App\Models\Currency;
use Illuminate\Http\Request;

class CurrenciesController extends Controller
{
    public function currencies(){

        try {
            if(!$currencies = Currency::get()->map(function($currency){
                return [
                    'id' =>$currency->id,
                    'code' =>$currency->code,
                    'symbol' =>$currency->symbol,
                    'country' => $currency->name
                ];
            })){
                throw new \Exception("Currencies is not found", 1);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status'=>false,
                'data' => $th->getMessage()
            ]);

        }

        return response()->json([
                'status'=>true,
                'data' => $currencies
            ]);

    }

    public function updateHospCurrency(Request $request){
        try {
            if(!$currency = Currency::find($request->currencyId)){
                throw new \Exception("This currency is not found", 1);
            }
            if(!$updatedCurrency = CountryCurrency::where('hospital_id',$request->user()->getHospital()->id)->first()){
                throw new \Exception("Currency is not found", 1);
            }
            if(!$updatedCurrency->update([
                'currency_id' =>$currency->id,

            ])){
                throw new \Exception("Failed to update currency", 1);
            }

            $currency = $this->getHospitalCurrency($request->user());
            return response()->json([
                'status'=>true,
                'data' => 'Currency updated successfully',
                'currency' =>$currency
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' =>false,
                'data' => $th->getMessage()
            ]);
        }
    }

    public function getHospitalCurrency($user){
        return Helper::getHospCurrency($user);
    }
}
