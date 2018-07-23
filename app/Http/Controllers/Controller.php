<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function makeResponse($result, $message, $code=100)
    {
        return [
            'data'    => $result,
            'message' => $message,
            'code'    => $code,
        ];
    }

    public function sendResponse($result, $message, $code=100)
    {
    	return response()->json($this->makeResponse($result, $message, $code));
    }

    public static function encodeSpecialChar($string)
	{
	    $replace  = str_replace(' ', '-', $string);
	    // $replace  = str_replace(".", "-",$replace);
	    $replace  = str_replace("&", "-",$replace);
	    $replace  = str_replace(" ", "-",$replace);
	    $replace  = str_replace("  ", "-",$replace);
	    $replace  = str_replace("   ", "-",$replace);
	    $replace  = str_replace("$", "-",$replace);
	    $replace  = str_replace("+", "-",$replace);
	    $replace  = str_replace("! ", "-",$replace);
	    $replace  = str_replace("@", "-",$replace);
	    $replace  = str_replace("#", "-",$replace);
	    $replace  = str_replace("$", "-",$replace);
	    $replace  = str_replace("%", "-",$replace);
	    $replace  = str_replace("^", "-",$replace);
	    $replace  = str_replace("&", "-",$replace);
	    $replace  = str_replace("*", "-",$replace);
	    $replace  = str_replace("(", "-",$replace);
	    $replace  = str_replace(")", "-",$replace);
	    $replace  = str_replace("/", "-",$replace);
	    $replace  = str_replace("+", "-",$replace);
	    $replace  = preg_replace('/[^A-Za-z0-9\-]/', '', $replace);
	    $replace  = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $replace);
	    $replace = preg_replace('/-+/', '-', $replace);
	    return $replace;
	}
}
