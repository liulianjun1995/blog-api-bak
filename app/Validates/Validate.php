<?php


namespace App\Validates;


use Illuminate\Support\Facades\Validator;

class Validate
{
    /**
     * 验证
     * @param $request
     * @param $rules
     * @param $message
     * @return bool|string
     */
    protected function validate($request, $rules, $message)
    {
        $validator = Validator::make($request, $rules, $message);

        if ($validator->fails()) {
            return $validator->errors()->first();
        }

        return true;
    }
}
