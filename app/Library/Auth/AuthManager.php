<?php

namespace App\Library\Auth;


use Illuminate\Contracts\Auth\Guard;

class AuthManager extends \Illuminate\Auth\AuthManager
{
        /**
         * Create a token based authentication guard.
         *
         * @param  string  $name
         * @param  array  $config
         * @return Guard
         */
        public function createTokenDriver($name, $config)
        {
                // The token guard implements a basic API token based guard implementation
                // that takes an API token field from the request and matches it to the
                // user in the database or another persistence layer where users are.
                $guard = new TokenGuard(
                        $this->createUserProvider($config['provider'] ?? null),
                        $this->app['request']
                );

                $this->app->refresh('request', $guard, 'setRequest');

                return $guard;
        }

    /**
     * Create a session based authentication guard.
     *
     * @param  string  $name
     * @param  array  $config
     * @return \Illuminate\Auth\SessionGuard
     */
    public function createSessionDriver($name, $config)
    {
        $provider = $this->createUserProvider($config['provider'] ?? null);

        $guard = new SessionGuard($name, $provider, $this->app['session.store']);

        // When using the remember me functionality of the authentication services we
        // will need to be set the encryption instance of the guard, which allows
        // secure, encrypted cookie values to get generated for those cookies.
        if (method_exists($guard, 'setCookieJar')) {
            $guard->setCookieJar($this->app['cookie']);
        }

        if (method_exists($guard, 'setDispatcher')) {
            $guard->setDispatcher($this->app['events']);
        }

        if (method_exists($guard, 'setRequest')) {
            $guard->setRequest($this->app->refresh('request', $guard, 'setRequest'));
        }

        return $guard;
    }
}