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
        $validator = self::facebookUserValidator();
        $validator->requirePresence('facebook_auth')
            ->maxLength('facebook_auth', 250);
        $validator->requirePresence('instance_id')
            ->maxLength('instance_id', 250);;

        $check = array_merge($request->getQueryParams(), $request->getData());
        return self::toStringValidationErrors($validator, $check);
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
            ->date('date_of_birth', 'mdy');

        return $validator;
    }

    private static function toStringValidationErrors(Validator $validator, array $data)
    {
        $errors = $validator->errors($data);
        if (!empty($errors)) {
            list($key, $value) = each($errors);
            $combine = Hash::extract($value, '{s}');
            return $key . ': ' . $combine[0];
        }

        return null;
    }

    public static function validateMePatch(ServerRequest $request)
    {
        return self::toStringValidationErrors(self::userPatchValidator(), $request->getData());
    }

    public static function userPatchValidator()
    {
        $validator = new Validator();

        $validator->allowEmpty('first_name')
            ->maxLength('first_name', 250);

        $validator->allowEmpty('last_name')->maxLength('last_name', 250);

        $validator->allowEmpty('gender')->maxLength('gender', 250);
        $validator->allowEmpty('description')->maxLength('description', 500);
        $validator->allowEmpty('looking_for')->isArray('looking_for');
        $validator->allowEmpty('looking_for_age_min')
            ->range('looking_for_age_min', [18, 100], null, function ($context) {
                return $context['data']['looking_for_age_min'] != 0;
            });
        $validator->allowEmpty('looking_for_age_max')
            ->range('looking_for_age_max', [18, 100], null, function ($context) {
                return $context['data']['looking_for_age_max'] != 0;
            });
        $validator->allowEmpty('is_visible')->boolean('is_visible');
        return $validator;
    }

    public static function validateProfiles(ServerRequest $request)
    {
        $validator = new Validator();
        $validator->requirePresence('latitude')->latitude('latitude');
        $validator->requirePresence('longitude')->longitude('longitude');
        $validator->allowEmpty('radius')->integer('radius')->range('radius', [10, 200]);
        return self::toStringValidationErrors($validator, $request->getQueryParams());
    }

}
