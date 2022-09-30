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

        $leftOverLower = $lowercaseString;

        for($i = 0; $i < count($lowercaseString); $i++){
            for($j = 0; $j < count($uppercaseString); $j++){
                if(ord($lowercaseString[$i])%32 < ord($uppercaseString[$j]) %32){
                    $sortedString[] = $lowercaseString[$i];
                    unset($leftOverLower[$i]);
                    break;
                }
                elseif(ord($lowercaseString[$i])%32 > ord($uppercaseString[$j]) %32){
                    $sortedString[] = $uppercaseString[$j];
                    array_splice($uppercaseString, $j, 1);
                }
                else{
                    $sortedString[] = $lowercaseString[$i];
                    if(($i + 1) < count($lowercaseString) && ord($lowercaseString[$i+1])%32 == ord($uppercaseString[$j]) %32){
                        unset($leftOverLower[$i]);
                        break;
                    }
                    $sortedString[] = $uppercaseString[$j];
                    unset($leftOverLower[$i]);
                    array_splice($uppercaseString, $j, 1);
                    break;
                }
            }
        }

        foreach ($leftOverLower as $leftover) {
            $sortedString[] = $leftover;
        }

        for($i = 0; $i < count($uppercaseString); $i++){
            $sortedString[] = $uppercaseString[$i];
        }

        return response()->json([
            "string" => $string,
            "sorted_string" => implode($sortedString).$sortedNums
        ]);
    }

    function numPlace($num){
        $numArray = [];
        $numClone = $num;
        $counter = 0;

        while($numClone != 0){
            $numArray[] = $numClone%10 * pow(10, $counter);
            $numClone = (int) ($numClone/10);
            $counter++;
        }

        return response()->json([
            "number" => $num,
            "digits" => array_reverse($numArray)
        ]);
    }

    function getBinaryForm($string){
        $binaryForm = $string;
        preg_match_all('!\d+!', $binaryForm, $matches);

        for($i = 0; $i < count($matches[0]); $i++){
            $binaryForm = str_replace($matches[0][$i], decbin($matches[0][$i]), $binaryForm);
        }

        return response()->json([
            "string" => $string,
            "binary_form" => $binaryForm
        ]);
    }

    function calculate($operand, $nums){
        $array = explode(" ", $nums);
        $result = 0;
        switch($operand){
            case "*":
                $result = 1;
                for($i = 0; $i < count($array); $i++){
                    $result *= $array[$i];
                }
                break;
            case "+":
                for($i = 0; $i < count($array); $i++){
                    $result += $array[$i];
                }
                break;
            case "-":
                for($i = 0; $i < count($array); $i++){
                    $result -= $array[$i];
                }
                break;
            case "/":
                $result = 1;
                for($i = 0; $i < count($array); $i++){
                    $result /= $array[$i];
                }
                break;
        }

        return response()->json([
            "operator" => $operand,
            "numbers" => $array,
            "solution" => $result
        ]);
    }
}
