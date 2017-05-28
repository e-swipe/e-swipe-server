<?php
/**
 * Created by PhpStorm.
 * User: stardisblue
 * Date: 27/05/2017
 * Time: 22:11
 */

namespace App\Controller;


use App\Http\JsonBodyResponse;
use App\Model\Table\UsersTable;
use Eswipe\Model\UserInfo;

/**
 * @property UsersTable Users
 */
class ProfilController extends ApiV1Controller
{

    /**
     * @property bool|object Users
     */
    public function profil()
    {
        $this->loadModel('Users');
        $user_id = $this->Auth->user('id');

        $user = $this->Users->get($user_id, [
            'contain' => ['LookingFor', 'Genders', 'Images', 'Events' => ['Images']]
        ]);

        $user->date_of_birth = $user->date_of_birth->('m/d/y');
        $userInfo = new UserInfo($user->toArray());

        return JsonBodyResponse::okResponse($this->response, $userInfo);
    }

    public function patch()
    {
        //TODO
    }

    public function changePassword()
    {
        //TODO
    }

    public function addPhoto()
    {
        //TODO :hexadecimales
    }

    public function deletePhoto()
    {
        //TODO : delete photo
    }

    public function updatePhotosOrder()
    {
        //TODO: updatePhotosOrder
    }
}
