<?php

namespace Shura\Asset\Controllers;


trait AssetResponse
{
    function validationError($message='The submitted data is invalid', $code = 200, $data = null){
        $validate = (object)[
            'error'=>(object) ['status'=>'ERROR','code'=>$code, 'message'=>$message],
            'data'=>$data ?? $message
        ];
        return response()->json($validate,$code);
    }
    function jsonResponse($data,$message=""){
        $response = (object)[
            'error'=>(object) ['status'=>'OK','code'=>200,'message'=>$message],
            'data'=>$data
        ];
        return response()->json($response);
    }
}
