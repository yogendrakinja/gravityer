<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class SaleController extends Controller
{
    public function discount(Request $request){
        $validateUser = Validator::make(
            $request->all(),
            [
                'prices' => 'required'
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
        return response()->json([
            "rule1" => $this->applyRule1($prices),
            "rule2" => $this->applyRule2($prices),
            "rule3" => $this->applyRule3($prices)
        ],200);
    }

    // Apply rule 1 to get dicounted items and payable items
    private function applyRule1($prices){
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

    // Apply rule 2 to get dicounted items and payable items used recursively
    private function applyRule2($prices, $discountedItems = [],$payableItems = []){
        $payableItems[] = $prices[0];
        for ($i = 1; $i < count($prices); $i++) {
            if (isset($prices[$i]) && $prices[$i] < $prices[0]) {
                $discountedItems[] = $prices[$i];
                // Remove used items from the list of prices
                unset($prices[0]);
                unset($prices[$i]);
                $prices = array_values($prices);
                break;
            }else{
                // if last two items are there in prices array
                if(!isset($prices[$i+1])){
                    $payableItems[] = $prices[$i];
                    // Remove used items from the list of prices
                    unset($prices[0]);
                    unset($prices[$i]);
                }
            }
        }
        if (count($prices) == 0) {
            return [
                'Discounted Items' => $discountedItems,
                'Payable Items' => $payableItems
            ];
        }
        // New prices arrray will be returned
        return $this->applyRule2($prices, $discountedItems, $payableItems);
    }

    private function applyRule3($prices, $discountedItems = [],$payableItems = []){
        $payableItems[] = $prices[0];
        for ($i = 1; $i < count($prices); $i++) {
            if (isset($prices[$i]) && $prices[$i] < $prices[0]) {
                $discountedItems[] = $prices[$i];
                // Remove used items from the list of prices
                unset($prices[0]);
                unset($prices[$i]);
                $prices = array_values($prices);
                break;
            }else{
                // if last two items are there in prices array
                if(!isset($prices[$i+1])){
                    $payableItems[] = $prices[$i];
                    // Remove used items from the list of prices
                    unset($prices[0]);
                    unset($prices[$i]);
                }
            }
        }
        if (count($prices) == 0) {
            return [
                'Discounted Items' => $discountedItems,
                'Payable Items' => $payableItems
            ];
        }
        // New prices arrray will be returned
        return $this->applyRule3($prices, $discountedItems, $payableItems);
    }
}
