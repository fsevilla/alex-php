<?php

namespace Core;

class Response {

  const SUCCESS_CODE = 200;
  const BAD_REQUEST_ERROR_CODE = 400;
  const UNAUTHORIZED_ERROR_CODE = 401;
  const FORBIDDEN_ERROR_CODE = 403;
  const NOT_FOUND_ERROR_CODE = 404;
  const REQUEST_TIMEOUT_ERROR_CODE = 408;
  const UNPROCESSABLE_ENTITY_ERROR_CODE = 422;
  const INTERNAL_SERVER_ERROR_CODE = 500;

   public static function json($data)
   {
        header('Content-type: text/javascript');
        http_response_code(self::SUCCESS_CODE);
        echo json_encode($data);
   }

   public static function json_array($data)
   {
        header('Content-type: text/javascript');
        http_response_code(self::SUCCESS_CODE);
        if($data){
            echo json_encode(array_values($data));
        } else {
            echo "[]";
        }
   }

   public static function error($code, $message)
   {
        $data = [];
        $data['status'] = $code;
        $data['message'] = $message;
        http_response_code($code);
        echo json_encode($data);
   }

   public static function obfuscate($value, $start=5, $end=5, $chars='...')
   {
        return substr($value, 0, $start).$chars.substr($value, strlen($value)-$end);
   }

   public static function text($str)
   {
        header('Content-type: text/html');
        echo $str;
   }

   public static function ok()
   {
        self::text('');
   }

}