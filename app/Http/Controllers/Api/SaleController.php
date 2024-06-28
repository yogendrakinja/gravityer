<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

class SaleController extends Controller
{
    public function discount(Request $request)
    {
        $validateUser = Validator::make(
            $request->all(),
            [
                'prices' => 'required|array',
                "rule" => 'required|in:1,2,3' 
            ]
        );

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validateUser->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $prices = $request->prices;
        rsort($prices);
        $rule = "applyRule$request->rule";
        return response()->json(['data' => $this->$rule($prices)], Response::HTTP_OK);
    }

    // Apply rule 1 to get dicounted items and payable items
    private function applyRule1($prices)
    {
        $discountedItems = [];
        $payableItems = [];

        for ($i = 0; $i < count($prices); $i += 2) {
            $payableItems[] = $prices[$i];
            if (isset($prices[$i + 1]) && $prices[$i + 1] <= $prices[$i]) {
                $discountedItems[] = $prices[$i + 1];
            } elseif (isset($prices[$i + 1])) {
                $payableItems[] = $prices[$i + 1];
            }
        }

        return [
            'Discounted Items' => $discountedItems,
            'Payable Items' => $payableItems
        ];
    }

    // Apply rule 2 to get dicounted items and payable items
    private function applyRule2($prices)
    {
        $discountedItems = $payableItems = [];
        for ($i = 0; $i < count($prices); $i++) {
            if (!array_key_exists($i, $payableItems) && !array_key_exists($i, $discountedItems)) {
                $payableItems[$i] = $prices[$i];
                for ($j = $i+1; $j < count($prices); $j++) {
                    if (!array_key_exists($j, $payableItems) && !array_key_exists($j, $discountedItems) && $prices[$i] > $prices[$j]) {
                        $discountedItems[$j] = $prices[$j];
                        break;
                    }
                }
            }
        }
        return [
            "discountedItems" => $discountedItems,
            "payableItems" => $payableItems,
        ];
    }

 // Apply rule 3 to get dicounted items and payable items
    private function applyRule3($prices)
    {
        $discountedItems = $payableItems = [];
        for ($i = 0; $i < count($prices); $i++) {
            if(!array_key_exists($i, $payableItems) && !array_key_exists($i, $discountedItems)){
                $payableItems[$i] = $prices[$i];
                for($j=$i+1; $j < count($prices); $j++){
                    if(!array_key_exists($j, $payableItems) && !array_key_exists($j, $discountedItems) && $prices[$i] > $prices[$j]){
                        $discountedItems[$j] = $prices[$j];
                        break;
                    }
                }
            }
        }
        return [
            "discountedItems" => $discountedItems,
            "payableItems" => $payableItems,
        ];
    }
}
