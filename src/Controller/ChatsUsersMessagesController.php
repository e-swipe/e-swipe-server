<?php

namespace App\Controller;

use App\Controller\AppController;
use App\Model\Entity\ChatsUsersMessage;

/**
 * ChatsUsersMessages Controller
 *
 * @property \App\Model\Table\ChatsUsersMessagesTable $ChatsUsersMessages
 *
 * @method ChatsUsersMessage[] paginate($object = null, array $settings = [])
 */
class ChatsUsersMessagesController extends ApiV1Controller
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Chat', 'Users']
        ];
        $chatsUsersMessages = $this->paginate($this->ChatsUsersMessages);

        $this->set(compact('chatsUsersMessages'));
        $this->set('_serialize', ['chatsUsersMessages']);
    }

    /**
     * View method
     *
     * @param string|null $id Chats Users Message id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $chatsUsersMessage = $this->ChatsUsersMessages->get($id, [
            'contain' => ['Chat', 'Users']
        ]);

        $this->set('chatsUsersMessage', $chatsUsersMessage);
        $this->set('_serialize', ['chatsUsersMessage']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $chatsUsersMessage = $this->ChatsUsersMessages->newEntity();
        if ($this->request->is('post')) {
            $chatsUsersMessage = $this->ChatsUsersMessages->patchEntity($chatsUsersMessage, $this->request->getData());
            if ($this->ChatsUsersMessages->save($chatsUsersMessage)) {
                $this->Flash->success(__('The chats users message has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The chats users message could not be saved. Please, try again.'));
        }
        $chat = $this->ChatsUsersMessages->Chat->find('list', ['limit' => 200]);
        $users = $this->ChatsUsersMessages->Users->find('list', ['limit' => 200]);
        $this->set(compact('chatsUsersMessage', 'chat', 'users'));
        $this->set('_serialize', ['chatsUsersMessage']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Chats Users Message id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $chatsUsersMessage = $this->ChatsUsersMessages->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $chatsUsersMessage = $this->ChatsUsersMessages->patchEntity($chatsUsersMessage, $this->request->getData());
            if ($this->ChatsUsersMessages->save($chatsUsersMessage)) {
                $this->Flash->success(__('The chats users message has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The chats users message could not be saved. Please, try again.'));
        }
        $chat = $this->ChatsUsersMessages->Chat->find('list', ['limit' => 200]);
        $users = $this->ChatsUsersMessages->Users->find('list', ['limit' => 200]);
        $this->set(compact('chatsUsersMessage', 'chat', 'users'));
        $this->set('_serialize', ['chatsUsersMessage']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Chats Users Message id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $chatsUsersMessage = $this->ChatsUsersMessages->get($id);
        if ($this->ChatsUsersMessages->delete($chatsUsersMessage)) {
            $this->Flash->success(__('The chats users message has been deleted.'));
        } else {
            $this->Flash->error(__('The chats users message could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
