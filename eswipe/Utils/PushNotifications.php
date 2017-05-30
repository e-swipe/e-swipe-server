<?php
/**
 * Created by PhpStorm.
 * User: stardisblue
 * Date: 29/05/2017
 * Time: 23:18
 */

namespace Eswipe\Utils;


use Cake\Core\Configure;
use Cake\Http\Client;
use Cake\Log\Log;
use Eswipe\Android\MessageData;
use Eswipe\Android\Notification;
use Eswipe\Android\PushNotification;

class PushNotifications
{
    /**
     * @param $sender
     * @param $reciever
     * @param $chatId
     * @param $message
     * @return Client\Response
     */
    public static function pushNewMessage($sender, $reciever, $chatId, $message)
    {
        $notification = new Notification($sender->firstname, $message->content, "com.e_swipe.e_swipe_CHAT_ACTIVITY");
        $messageData = new MessageData($chatId);

        Log::debug('[PUSH][message] sender='.$sender->firstname.', reciever='.$reciever->id.' ('.$reciever->instance_id.')');

        return self::push($notification, $messageData, $reciever->instance_id);

    }

    /**
     * @param $notification
     * @param $messageData
     * @param $instance_id
     * @return Client\Response
     */
    private static function push($notification, $messageData, $instance_id)
    {
        $pushNotification = new PushNotification($notification, $messageData, $instance_id);

        $client = new Client();
        $response = $client->post('https://fcm.googleapis.com/fcm/send', json_encode($pushNotification),
            [
                'headers' => [
                    'Authorization' => ['key='.Configure::read('google.key')],
                ],
                'type' => 'json',
            ]);

        return $response;
    }

    /**
     * @param $matcher
     * @param $matchee
     * @param $chatId
     * @return Client\Response
     */
    public static function pushNewMatch($matcher, $matchee, $chatId)
    {
        $notification = new Notification($matcher->firstname, "New match :)", "com.e_swipe.e_swipe_CHAT_ACTIVITY");
        $messageData = new MessageData($chatId);

        Log::debug('[PUSH][message] sender='.$matcher->firstname.', reciever='.$matchee->id.' ('.$matchee->instance_id.')');

        return self::push($notification, $messageData, $matchee->instance_id);

    }

}
