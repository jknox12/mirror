<?php namespace Laravel\Session\Drivers;

use Laravel\Crypter;

class Cookie implements Driver {

	/**
	 * The name of the cookie used to store the session payload.
	 *
	 * @var string
	 */
	const payload = 'api_payload';

	/**
	 * Load a session from storage by a given ID.
	 *
	 * If no session is found for the ID, null will be returned.
	 *
	 * @param  string  $id
	 * @return array
	 */
	public function load($id)
	{
		if (\Laravel\Cookie::has(Cookie::payload))
		{
			$cookie = Crypter::decrypt(\Laravel\Cookie::get(Cookie::payload));

			return unserialize($cookie);
		}
	}

	/**
	 * Save a given session to storage.
	 *
	 * @param  array  $session
	 * @param  array  $config
	 * @param  bool   $exists
	 * @return void
	 */
	public function save($session, $config, $exists)
	{
		extract($config, EXTR_SKIP);

		$payload = Crypter::encrypt(serialize($session));

		$success = \Laravel\Cookie::put(Cookie::payload, $payload, $lifetime, $path, $domain);
	}

	/**
	 * Delete a session from storage by a given ID.
	 *
	 * @param  string  $id
	 * @return void
	 */
	public function delete($id)
	{
		\Laravel\Cookie::forget(Cookie::payload);
	}

}
