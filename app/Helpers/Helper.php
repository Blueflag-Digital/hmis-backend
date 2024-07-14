<?php

namespace App\Helpers;

class Helper
{
    public static function validPhone($phone)
    {
        $data['phoneNumber'] = "";
        $data['status'] = false;
        $data['message'] = "";
        try {
            if (!empty($phone)) {

                if (!preg_match('/^(2547||07)\d{8}$/', $phone)) {
                    throw new \Exception("{$phone} is invalid. It must start with '254' or '07', followed by 8 digits.");
                }

                if (strpos($phone, '07') === 0) {
                    $phone = '254' . substr($phone, 1);
                }
                $data['phoneNumber'] = $phone;
                $data['status'] = true;
            }
        } catch (\Throwable $th) {
            $data['message'] = $th->getMessage();
        }


        return $data;
    }
}
