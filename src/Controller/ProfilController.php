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
use Cake\I18n\FrozenDate;
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

        // TODO : optimize
        // https://api.cakephp.org/3.4/class-Cake.ORM.Query.html#_contain
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
        $userPatch = array_filter($this->request->getData(), function ($key) {
            return $key !== null;
        });

        $user = $this->Users->get($user_id, ['contain' => ['LookingFor']]);

        //TODO : faire ca en plus joli :)
        if (isset($userPatch['looking_for']) && !empty($userPatch['looking_for'])) {
            $this->Users->LookingFor->unlink($user, $user->looking_for); // suppresion des anciennes liaisons

            $genders = $this->Users->LookingFor
                ->find('all')->where(['name IN' => $userPatch['looking_for']])->toArray();

            $this->Users->LookingFor->link($user, $genders);// reassociation de la liaison :)
        }

        if (isset($userPatch['first_name'])) {
            $user->firstname = $userPatch['first_name'];
        }

        if (isset($userPatch['last_name'])) {
            $user->lastname = $userPatch['last_name'];
        }

        if (isset($userPatch['date_of_birth'])) {
            $user->date_of_birth = FrozenDate::parseDate($userPatch['date_of_birth']);
        }

        if (isset($userPatch['description'])) {
            $user->description = $userPatch['description'];
        }

        if (isset($userPatch['gender'])) {
            $user->gender = $this->Users->Genders->findByName($userPatch['gender'])->first();;
        }

        if (isset($userPatch['looking_for_age_min'])) {
            $user->min_age = $userPatch['looking_for_age_min'];
        }

        if (isset($userPatch['looking_for_age_max'])) {
            $user->max_age = $userPatch['looking_for_age_max'];
        }

        if (isset($userPatch['is_visible'])) {
            $user->is_visible = $userPatch['is_visible'];
        }

        $this->Users->save($user);
        return $this->response->withStatus(204);

    }

    public function changePassword()
    {

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
