<?php

namespace App\Library\Auth;


use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class TokenGuard extends \Illuminate\Auth\TokenGuard
{

    protected $uid;

    /**
     * Create a new authentication guard.
     *
     * @param  \Illuminate\Contracts\Auth\UserProvider $provider
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    public function __construct(UserProvider $provider, Request $request)
    {
        parent::__construct($provider,$request);
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     * @throws \Exception
     */
    public function user()
    {
        // If we've already retrieved the user for the current request we can just
        // return it back immediately. We do not want to fetch the user data on
        // every call to this method because that would be tremendously slow.
        if (!is_null($this->user)) {
            return $this->user;
        }

        $user = null;

        $token = $this->getTokenForRequest();

        if (!empty($token)) {
            $this->uid = $this->_validateToken($this->getTokenForRequest());
            $user = $this->provider->retrieveById(
                $this->uid
            );
        }

        return $this->user = $user;
    }


    /**
     * Validate a user's credentials.
     *
     * @param  array $credentials
     * @return bool
     * @throws \Exception
     */
    public function validate(array $credentials = [])
    {
        $this->uid = $this->_validateToken($this->getTokenForRequest());
        if($this->provider->retrieveById($this->uid)) {
            return true;
        }

        return false;
    }


    /**
     * @param string $token
     * @return int
     * @throws AuthenticationException
     */
    private function _validateToken(string $token): int
    {
        try {
            $uid = intval(Crypt::decrypt($token));
        } catch (DecryptException $e) {
            throw new AuthenticationException('Unauthenticated.');
        }
        return $uid;
    }
}