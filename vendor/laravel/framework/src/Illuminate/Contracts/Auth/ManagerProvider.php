<?php

namespace Illuminate\Contracts\Auth;

interface ManagerProvider
{
    /**
     * Retrieve a manager by their unique identifier.
     *
     * @param  mixed  $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier);

    /**
     * Retrieve a manager by their unique identifier and "remember me" token.
     *
     * @param  mixed  $identifier
     * @param  string  $token
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token);

    /**
     * Update the "remember me" token for the given manager in storage.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $manager
     * @param  string  $token
     * @return void
     */
    public function updateRememberToken(Authenticatable $manager, $token);

    /**
     * Retrieve a manager by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials);

    /**
     * Validate a manager against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $manager
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $manager, array $credentials);
}
