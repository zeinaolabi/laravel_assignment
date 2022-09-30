<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use IntlChar;

class exercisesController extends Controller
{
    function sortString($string){
        $lowercaseString = [];
        $uppercaseString = [];
        $numString = [];
        $sortedString = [];
        $stringParts = str_split($string);
        for($i = 0; $i < count($stringParts); $i++){
            if(IntlChar::islower($stringParts[$i])){
                $lowercaseString[] = $stringParts[$i];
            }
            elseif(IntlChar::isupper($stringParts[$i])){
                $uppercaseString[] = $stringParts[$i];
            }
            elseif(is_numeric($stringParts[$i])){
                $numString[] = $stringParts[$i];
            }
        }
        sort($lowercaseString);
        sort($uppercaseString);
        sort($numString);

        $sortedNums = implode($numString);

        for($i = 0; $i < count($lowercaseString); $i++){
            for($j = 0; $j < count($uppercaseString); $j++){
                if(ord($lowercaseString[$i])%32 < ord($uppercaseString[$j]) %32){
                    $sortedString[] = $lowercaseString[$i];
                    array_splice($lowercaseString, $i, 1);
                    break;
                }
                elseif(ord($lowercaseString[$i])%32 > ord($uppercaseString[$j]) %32){
                    $sortedString[] = $uppercaseString[$j];
                    array_splice($uppercaseString, $j, 1);
                }
                else{
                    $sortedString[] = $lowercaseString[$i];
                    if(($i + 1) < count($lowercaseString) && ord($lowercaseString[$i+1])%32 == ord($uppercaseString[$j]) %32){
                        array_splice($lowercaseString, $i, 1);
                        break;
                    }
                    $sortedString[] = $uppercaseString[$j];
                    array_splice($lowercaseString, $i, 1);
                    array_splice($uppercaseString, $j, 1);
                    break;
                }
            }
        }

        for($i = 0; $i < count($lowercaseString); $i++){
            $sortedString[] = $lowercaseString[$i];
        }

        for($i = 0; $i < count($uppercaseString); $i++){
            $sortedString[] = $uppercaseString[$i];
        }

        echo implode($sortedString).$sortedNums;


    }
}
