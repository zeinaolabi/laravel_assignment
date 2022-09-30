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

        //Split the given string to an array
        $stringParts = str_split($string);

        //Divide each of the characters into separate arrays (store them as lower, upper, numeric)
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

        //Store all the arrays
        sort($lowercaseString);
        sort($uppercaseString);
        sort($numString);

        //Save the numbers in string format
        $sortedNums = implode($numString);
        $leftOverLower = $lowercaseString;

        //For each character in the lowercase array, check depending on ASCII which char should be first
        for($i = 0; $i < count($lowercaseString); $i++){
            for($j = 0; $j < count($uppercaseString); $j++){
                if(ord($lowercaseString[$i])%32 < ord($uppercaseString[$j]) %32){
                    //If the lower case char should be first, add it, delete it from the array, and break the loop
                    $sortedString[] = $lowercaseString[$i];
                    unset($leftOverLower[$i]);
                    break;
                }
                elseif(ord($lowercaseString[$i])%32 > ord($uppercaseString[$j]) %32){
                    //If the upper case char should be first, add it and check the next one
                    $sortedString[] = $uppercaseString[$j];
                    array_splice($uppercaseString, $j, 1);
                }
                else{
                    //If the lowercase is the same as the uppercase letter, add it to the sorted array
                    $sortedString[] = $lowercaseString[$i];
                    //If the next lowercase char is the same as the first char, remove it from the list and break out of the loop
                    if(($i + 1) < count($lowercaseString) && ord($lowercaseString[$i+1])%32 == ord($uppercaseString[$j]) %32){
                        unset($leftOverLower[$i]);
                        break;
                    }
                    //Else add the uppercase char and remove it & the lower case from their lists
                    $sortedString[] = $uppercaseString[$j];
                    unset($leftOverLower[$i]);
                    array_splice($uppercaseString, $j, 1);
                    break;
                }
            }
        }

        //Add the leftovers chars
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

        //While the number isn't zero, get the last digit and divide by 10
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
        //Get all the numbers from the string
        preg_match_all('!\d+!', $binaryForm, $matches);

        //For each number, convert it into binary and replace it
        for($i = 0; $i < count($matches[0]); $i++){
            $binaryForm = str_replace($matches[0][$i], decbin($matches[0][$i]), $binaryForm);
        }

        return response()->json([
            "string" => $string,
            "binary_form" => $binaryForm
        ]);
    }

    function calculate($operand, $nums){
        //Get the numbers and save them into an array
        $array = explode(" ", $nums);
        $result = 0;

        //Depending on the operand, proceed with the calculations
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
