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

// TODO : https://book.cakephp.org/3.0/en/core-libraries/validation.html#creating-reusable-validators
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

            var_dump($errors);

            return $key.': '.$combine[0];
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
            ->range(
                'looking_for_age_min',
                [18, 100],
                null,
                function ($context) {
                    return $context['data']['looking_for_age_min'] != 0;
                }
            );
        $validator->allowEmpty('looking_for_age_max')
            ->range(
                'looking_for_age_max',
                [18, 100],
                null,
                function ($context) {
                    return $context['data']['looking_for_age_max'] != 0;
                }
            );
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

    public static function validateGetChats(ServerRequest $request)
    {
        $validator = new Validator();
        $validator->allowEmpty('offset')->integer('offset')->greaterThanOrEqual('offset', 0);
        $validator->allowEmpty('limit')->integer('limit')->range('limit', [10, 200]);

        return self::toStringValidationErrors($validator, $request->getQueryParams());
    }

    public static function validateChatGet(ServerRequest $request)
    {
        return self::toStringValidationErrors(self::chatValidator(), $request->getQueryParams());
    }

    private static function chatValidator()
    {
        $validator = new Validator();
        $validator->allowEmpty('offset')->integer('offset')->greaterThanOrEqual('offset', 0);
        $validator->allowEmpty('limit')->integer('limit')->range('limit', [10, 200]);
        $validator->allowEmpty('since')->dateTime('since', ['ymd'], null, function ($context) {
            return $context['data']['since'] != 0;
        });

        return $validator;
    }

    public static function validateChatAddMessage(ServerRequest $request)
    {
        return self::toStringValidationErrors(self::messageValidator(), $request->getData());
    }

    private static function messageValidator()
    {
        $validator = new Validator();
        $validator->requirePresence('content');
        $validator->requirePresence('date')->dateTime('date', ['ymd'], null, function ($context) {
            return $context['data']['date'] != 0;
        });

        return $validator;
    }

    public static function validateEvents(ServerRequest $response)
    {
        return self::toStringValidationErrors(self::eventValidator(), $response->getQueryParams());
    }

    /**
     * @return Validator
     */
    public static function eventValidator()
    {
        $validator = new Validator();
        $validator->requirePresence('latitude')->latitude('latitude');
        $validator->requirePresence('longitude')->longitude('longitude');
        $validator->allowEmpty('radius')->integer('radius')->range('radius', [10, 200]);
        $validator->allowEmpty('offset')->integer('offset')->greaterThanOrEqual('offset', 0);
        $validator->allowEmpty('limit')->integer('limit')->range('limit', [10, 200]);

        return $validator;
    }

}
