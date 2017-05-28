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
use App\Network\Exception\UnprocessedEntityException;
use App\Validator\DataValidator;
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

        $user->date_of_birth = $user->date_of_birth->format('m/d/y');
        $userInfo = new UserInfo($user->toArray());

        return JsonBodyResponse::okResponse($this->response, $userInfo);
    }

    public function patch()
    {
        $this->loadModel('Users');
        $user_id = $this->Auth->user('id');

        $message = DataValidator::validateMePatch($this->request);
        if (!is_null($message)) {
            throw new UnprocessedEntityException($message);
        }
        $userPatch = $this->request->getData();

        $user = $this->Users->get($user_id, ['contain' => ['LookingFor']]);
        $this->Users->LookingFor->unlink($user, $user->looking_for); // suppresion des anciennes liaisons

        debug($userPatch['looking_for']);
        $genders = $this->Users->LookingFor
            ->find('all')->where(['name IN' => $userPatch['looking_for']])->all();

        debug($genders);
        $this->Users->LookingFor->link($user, $genders);
        debug($user);

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
