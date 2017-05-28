<?php

namespace App\Auth;

use Cake\Auth\BaseAuthenticate;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\Network\Exception\UnauthorizedException;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class ApiAuthenticate extends BaseAuthenticate
{
    /**
     * Default config for this object.
     *
     * - `fields` The fields to use to identify a user by.
     * - `sessionModel` The alias for users table, defaults to Sessions.
     * - `finder` The finder method to use to fetch user record. Defaults to 'all'.
     *   You can set finder name as string or an array where key is finder name and value
     *   is an array passed to `Table::find()` options.
     *   E.g. ['finderName' => ['some_finder_option' => 'some_value']]
     * - Options `scope` and `contain` have been deprecated since 3.1. Use custom
     *   finder instead to modify the query to fetch user record.
     * @var array
     */
    protected $_defaultConfig = [
        'fields' => [
            'token' => 'uuid'
        ],
        'sessionModel' => 'Sessions',
        'contain' => 'Users',
        'fk_alias' => 'user',
        'finder' => 'all'
    ];

    /**
     * Authenticate a user using HTTP auth. Will use the configured User model and attempt a
     * login using HTTP auth.
     *
     * @param \Cake\Http\ServerRequest $request The request to authenticate with.
     * @param \Cake\Http\Response $response The response to add headers to.
     * @return mixed Either false on failure, or an array of user data on success.
     */
    public function authenticate(ServerRequest $request, Response $response)
    {
        return $this->getUser($request);
    }

    /**
     * Get a user based on information in the request. Used by cookie-less auth for stateless clients.
     *
     * @param \Cake\Http\ServerRequest $request Request object.
     * @return mixed Either false or an array of user information
     */
    public function getUser(ServerRequest $request)
    {
        $auth = $request->getHeaderLine('auth');

        $validator = new Validator();
        $validator->uuid('auth');
        $errors = $validator->errors(['auth' => $auth]);
        if ($errors) {
            return false;
        }

        return $this->_findAuth($auth);
    }

    /**
     * Find a user record using the username and password provided.
     *
     * Input passwords will be hashed even when a user doesn't exist. This
     * helps mitigate timing attacks that are attempting to find valid usernames.
     *
     * @param  string $token the authentification id
     * @return array|bool Either false on failure, or an array of user data.
     * @internal param string $username The username/identifier.
     * @internal param null|string $password The password, if not provided password checking is skipped
     *   and result of find is returned.
     */
    protected function _findAuth($token)
    {
        $result = $this->_query($token)->first()->user;

        if (empty($result)) {
            return false;
        }

        return $result->toArray();
    }

    /**
     * Get query object for fetching user from database.
     *
     * @param string $token The identifier.
     * @return \Cake\ORM\Query
     */
    protected function _query($token)
    {
        $config = $this->_config;
        $table = TableRegistry::get($config['sessionModel']);

        $options = [
            'conditions' => [$table->aliasField($config['fields']['token']) => $token]
        ];

        if (!empty($config['contain'])) {
            $options['contain'] = $config['contain'];
        }

        $finder = $config['finder'];
        if (is_array($finder)) {
            $options += current($finder);
            $finder = key($finder);
        }

        if (!isset($options['token'])) {
            $options['token'] = $token;
        }

        return $table->find($finder, $options);
    }

    /**
     * Handles an unauthenticated access attempt by sending appropriate login headers
     *
     * @param \Cake\Http\ServerRequest $request A request object.
     * @param \Cake\Http\Response $response A response object.
     * @return void
     * @throws \Cake\Network\Exception\UnauthorizedException
     */
    public function unauthenticated(ServerRequest $request, Response $response)
    {
        throw new UnauthorizedException();
    }

    /**
     * Generate the login headers
     *
     * @param \Cake\Http\ServerRequest $request Request object.
     * @return string Headers for logging in.
     */
    public function loginHeaders(ServerRequest $request)
    {
    }

}