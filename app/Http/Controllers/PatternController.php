<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PatternController extends Controller
{
    public function parseString(Request $request){

        $str = $request->string;
        $array_of_patterns = array();
        $longesr_pattern = null;

        for ($i = 0; $i < strlen($str); $i++){
            $num_parse = 1;
            for( $j = $i+1 ; $j < strlen($str); $j++ ){
                $num_parse = $num_parse + 1;

                $substring = substr($str, $i, $num_parse);

                if (in_array($substring, $array_of_patterns)) {
                    //pattern found a second time

                    if( !$longesr_pattern || strlen($substring) > strlen($longesr_pattern) ){
                        $longesr_pattern = $substring;
                    }
                }

                array_push($array_of_patterns, $substring);
            }
        }

        //at this point adding result to be passed back to the view
        $result = "no null";
        if($longesr_pattern){
            $result = "yes " . $longesr_pattern;
        }

        return view('/welcome',  compact('result', 'request') );
    }
}
// print_r( "<pre>" );
// print_r( $i.":".$num_parse . " - ". $substring);
// print_r( "</pre>" );