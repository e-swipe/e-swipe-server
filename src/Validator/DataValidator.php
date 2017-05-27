<?php
/**
 * Created by PhpStorm.
 * User: stardisblue
 * Date: 27/05/2017
 * Time: 16:19
 */

namespace App\Validator;


use Cake\Http\ServerRequest;
use Cake\Utility\Hash;
use Cake\Validation\Validator;

class DataValidator
{
    public static function validateLoginFacebook(ServerRequest $request)
    {
        $check = array_merge($request->getQueryParams(), $request->getData());
        $validator = self::facebookUserValidator();
        $validator->requirePresence('facebook_auth')
            ->maxLength('facebook_auth', 250);
        $validator->requirePresence('instance_id')
            ->maxLength('instance_id', 250);;

        $errors = $validator->errors($check);

        if (!empty($errors)) {
            list($key, $value) = each($errors);
            $combine = Hash::extract($value, '{s}');
            return $key . ': ' . $combine[0];
        }

        return null;
    }

    public static function facebookUserValidator()
    {
        $validator = new Validator();
        $validator->requirePresence('email')
            ->email('email');

        $validator->requirePresence('first_name')
            ->maxLength('first_name', 250);

        $validator->requirePresence('last_name')
            ->maxLength('last_name', 250);

        $validator->requirePresence('gender')
            ->maxLength('gender', 250);

        $validator->requirePresence('date_of_birth')
            ->date('date_of_birth','mdy');

        return $validator;
    }

}
