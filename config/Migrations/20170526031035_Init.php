<?php
use Migrations\AbstractMigration;

class Init extends AbstractMigration
{

    public $autoId = false;

    public function up()
    {

        $this->table('accept')
            ->addColumn('accepter_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('accepted_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['accepter_id', 'accepted_id'])
            ->addIndex(
                [
                    'accepted_id',
                ]
            )
            ->addIndex(
                [
                    'accepter_id',
                ]
            )
            ->create();

        $this->table('chat')
            ->addColumn('id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->create();

        $this->table('chats_users_messages')
            ->addColumn('id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('chat_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('user_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id', 'chat_id', 'user_id'])
            ->addColumn('content', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('created_at', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                [
                    'id',
                ],
                ['unique' => true]
            )
            ->addIndex(
                [
                    'chat_id',
                ]
            )
            ->addIndex(
                [
                    'user_id',
                ]
            )
            ->create();

        $this->table('decline')
            ->addColumn('decliner_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('declined_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['decliner_id', 'declined_id'])
            ->addIndex(
                [
                    'declined_id',
                ]
            )
            ->addIndex(
                [
                    'decliner_id',
                ]
            )
            ->create();

        $this->table('events')
            ->addColumn('id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 250,
                'null' => false,
            ])
            ->addColumn('description', 'string', [
                'default' => null,
                'limit' => 500,
                'null' => false,
            ])
            ->addColumn('date_begin', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('date_end', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('latitude', 'float', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('longitude', 'float', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('is_visible', 'integer', [
                'default' => '1',
                'limit' => 4,
                'null' => false,
            ])
            ->create();

        $this->table('events_images')
            ->addColumn('event_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('image_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['event_id', 'image_id'])
            ->addColumn('order', 'integer', [
                'default' => '0',
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addIndex(
                [
                    'event_id',
                ]
            )
            ->addIndex(
                [
                    'image_id',
                ]
            )
            ->addIndex(
                [
                    'order',
                ]
            )
            ->create();

        $this->table('events_interests')
            ->addColumn('event_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('interest_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['event_id', 'interest_id'])
            ->addIndex(
                [
                    'event_id',
                ]
            )
            ->addIndex(
                [
                    'interest_id',
                ]
            )
            ->create();

        $this->table('events_users_accept')
            ->addColumn('user_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('event_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['user_id', 'event_id'])
            ->addIndex(
                [
                    'event_id',
                ]
            )
            ->addIndex(
                [
                    'user_id',
                ]
            )
            ->create();

        $this->table('events_users_deny')
            ->addColumn('user_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('event_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['user_id', 'event_id'])
            ->addIndex(
                [
                    'event_id',
                ]
            )
            ->addIndex(
                [
                    'user_id',
                ]
            )
            ->create();

        $this->table('genders')
            ->addColumn('id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => false,
            ])
            ->addIndex(
                [
                    'name',
                ],
                ['unique' => true]
            )
            ->create();

        $this->table('images')
            ->addColumn('id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('url', 'string', [
                'default' => null,
                'limit' => 250,
                'null' => false,
            ])
            ->addColumn('uuid', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                [
                    'url',
                ],
                ['unique' => true]
            )
            ->addIndex(
                [
                    'uuid',
                ],
                ['unique' => true]
            )
            ->create();

        $this->table('images_users')
            ->addColumn('user_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('image_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['user_id', 'image_id'])
            ->addColumn('order', 'integer', [
                'default' => '0',
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addIndex(
                [
                    'image_id',
                ]
            )
            ->addIndex(
                [
                    'user_id',
                ]
            )
            ->addIndex(
                [
                    'order',
                ]
            )
            ->create();

        $this->table('interests')
            ->addColumn('id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => false,
            ])
            ->create();

        $this->table('interests_users')
            ->addColumn('user_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('interest_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['user_id', 'interest_id'])
            ->addIndex(
                [
                    'interest_id',
                ]
            )
            ->addIndex(
                [
                    'user_id',
                ]
            )
            ->create();

        $this->table('matches')
            ->addColumn('matcher_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('matched_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['matcher_id', 'matched_id'])
            ->addColumn('chat_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addIndex(
                [
                    'chat_id',
                ]
            )
            ->addIndex(
                [
                    'matched_id',
                ]
            )
            ->addIndex(
                [
                    'matcher_id',
                ]
            )
            ->create();

        $this->table('users')
            ->addColumn('id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('facebook_id', 'biginteger', [
                'default' => null,
                'limit' => 64,
                'null' => true,
            ])
            ->addColumn('email', 'string', [
                'default' => null,
                'limit' => 250,
                'null' => false,
            ])
            ->addColumn('password', 'string', [
                'default' => null,
                'limit' => 250,
                'null' => false,
            ])
            ->addColumn('instance_id', 'string', [
                'default' => null,
                'limit' => 250,
                'null' => false,
            ])
            ->addColumn('firstname', 'string', [
                'default' => null,
                'limit' => 250,
                'null' => false,
            ])
            ->addColumn('lastname', 'string', [
                'default' => null,
                'limit' => 250,
                'null' => false,
            ])
            ->addColumn('description', 'string', [
                'default' => null,
                'limit' => 500,
                'null' => false,
            ])
            ->addColumn('date_of_birth', 'date', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('latitude', 'float', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('longitude', 'float', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('is_visible', 'integer', [
                'default' => '1',
                'limit' => 4,
                'null' => false,
            ])
            ->addColumn('gender_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('min_age', 'integer', [
                'default' => '18',
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('max_age', 'integer', [
                'default' => '18',
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addIndex(
                [
                    'email',
                ],
                ['unique' => true]
            )
            ->addIndex(
                [
                    'facebook_id',
                ],
                ['unique' => true]
            )
            ->addIndex(
                [
                    'gender_id',
                ]
            )
            ->create();

        $this->table('users_genders_looking_for')
            ->addColumn('user_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('gender_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['user_id', 'gender_id'])
            ->addIndex(
                [
                    'gender_id',
                ]
            )
            ->addIndex(
                [
                    'user_id',
                ]
            )
            ->create();

        $this->table('accept')
            ->addForeignKey(
                'accepted_id',
                'users',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'CASCADE'
                ]
            )
            ->addForeignKey(
                'accepter_id',
                'users',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'CASCADE'
                ]
            )
            ->update();

        $this->table('chats_users_messages')
            ->addForeignKey(
                'chat_id',
                'chat',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'CASCADE'
                ]
            )
            ->addForeignKey(
                'user_id',
                'users',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT'
                ]
            )
            ->update();

        $this->table('decline')
            ->addForeignKey(
                'declined_id',
                'users',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'CASCADE'
                ]
            )
            ->addForeignKey(
                'decliner_id',
                'users',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'CASCADE'
                ]
            )
            ->update();

        $this->table('events_images')
            ->addForeignKey(
                'event_id',
                'events',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT'
                ]
            )
            ->addForeignKey(
                'image_id',
                'images',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT'
                ]
            )
            ->update();

        $this->table('events_interests')
            ->addForeignKey(
                'event_id',
                'events',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT'
                ]
            )
            ->addForeignKey(
                'interest_id',
                'interests',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT'
                ]
            )
            ->update();

        $this->table('events_users_accept')
            ->addForeignKey(
                'event_id',
                'events',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'CASCADE'
                ]
            )
            ->addForeignKey(
                'user_id',
                'users',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'CASCADE'
                ]
            )
            ->update();

        $this->table('events_users_deny')
            ->addForeignKey(
                'event_id',
                'events',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'CASCADE'
                ]
            )
            ->addForeignKey(
                'user_id',
                'users',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'CASCADE'
                ]
            )
            ->update();

        $this->table('images_users')
            ->addForeignKey(
                'image_id',
                'images',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT'
                ]
            )
            ->addForeignKey(
                'user_id',
                'users',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT'
                ]
            )
            ->update();

        $this->table('interests_users')
            ->addForeignKey(
                'interest_id',
                'interests',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT'
                ]
            )
            ->addForeignKey(
                'user_id',
                'users',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT'
                ]
            )
            ->update();

        $this->table('matches')
            ->addForeignKey(
                'chat_id',
                'chat',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT'
                ]
            )
            ->addForeignKey(
                'matched_id',
                'users',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'CASCADE'
                ]
            )
            ->addForeignKey(
                'matcher_id',
                'users',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'CASCADE'
                ]
            )
            ->update();

        $this->table('users')
            ->addForeignKey(
                'gender_id',
                'genders',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT'
                ]
            )
            ->update();

        $this->table('users_genders_looking_for')
            ->addForeignKey(
                'gender_id',
                'genders',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT'
                ]
            )
            ->addForeignKey(
                'user_id',
                'users',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT'
                ]
            )
            ->update();
    }

    public function down()
    {
        $this->table('accept')
            ->dropForeignKey(
                'accepted_id'
            )
            ->dropForeignKey(
                'accepter_id'
            );

        $this->table('chats_users_messages')
            ->dropForeignKey(
                'chat_id'
            )
            ->dropForeignKey(
                'user_id'
            );

        $this->table('decline')
            ->dropForeignKey(
                'declined_id'
            )
            ->dropForeignKey(
                'decliner_id'
            );

        $this->table('events_images')
            ->dropForeignKey(
                'event_id'
            )
            ->dropForeignKey(
                'image_id'
            );

        $this->table('events_interests')
            ->dropForeignKey(
                'event_id'
            )
            ->dropForeignKey(
                'interest_id'
            );

        $this->table('events_users_accept')
            ->dropForeignKey(
                'event_id'
            )
            ->dropForeignKey(
                'user_id'
            );

        $this->table('events_users_deny')
            ->dropForeignKey(
                'event_id'
            )
            ->dropForeignKey(
                'user_id'
            );

        $this->table('images_users')
            ->dropForeignKey(
                'image_id'
            )
            ->dropForeignKey(
                'user_id'
            );

        $this->table('interests_users')
            ->dropForeignKey(
                'interest_id'
            )
            ->dropForeignKey(
                'user_id'
            );

        $this->table('matches')
            ->dropForeignKey(
                'chat_id'
            )
            ->dropForeignKey(
                'matched_id'
            )
            ->dropForeignKey(
                'matcher_id'
            );

        $this->table('users')
            ->dropForeignKey(
                'gender_id'
            );

        $this->table('users_genders_looking_for')
            ->dropForeignKey(
                'gender_id'
            )
            ->dropForeignKey(
                'user_id'
            );

        $this->dropTable('accept');
        $this->dropTable('chat');
        $this->dropTable('chats_users_messages');
        $this->dropTable('decline');
        $this->dropTable('events');
        $this->dropTable('events_images');
        $this->dropTable('events_interests');
        $this->dropTable('events_users_accept');
        $this->dropTable('events_users_deny');
        $this->dropTable('genders');
        $this->dropTable('images');
        $this->dropTable('images_users');
        $this->dropTable('interests');
        $this->dropTable('interests_users');
        $this->dropTable('matches');
        $this->dropTable('users');
        $this->dropTable('users_genders_looking_for');
    }
}
