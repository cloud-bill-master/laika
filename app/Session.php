<?php
/**
 * APP: Laika
 * Author: Showket Ahmed
 * APP Link: https://cloudbillmaster.com
 * Email: riyadtayf@gmail.com
 * Version: 1.0.0
 * Provider: Cloud Bill Master Ltd.
 */

// Namespace
namespace app\core;

// Forbidden Access
defined('ROOTPATH') || http_response_code(403).die('403 Forbidden Access!');

use Lang;
use app\model\Session as SessionModel;
use app\helper\function\Functions;

class Session
{
	// Session For
	private string $sessionFor = "APP";

    // Start Session
	private function start()
	{
		(new SessionModel)->start();
	}

	// Destroy Session
	public function end()
	{
		$this->start();
		session_unset();
		session_destroy();
		return true;
	}

	/** put data into the session **/
	public function set(string $key, mixed $value, string $for = "APP"):void
	{
		$this->sessionFor = ucwords($for);
		// Start
		$this->start();
		// Set Session Value
		$_SESSION[$this->sessionFor][$key] = $value;
	}

	// Get Session Value
	public function get(string $key, string $for = "APP"):mixed
	{
		$this->sessionFor = ucwords($for);
		// Start
		$this->start();
		// Get Session Data
		return $_SESSION[$this->sessionFor][$key] ?? '';
	}

	// Unset Session
	public function pop(string $key, string $for = "APP"):void
	{
		$this->sessionFor = ucwords($for);
		// Start
		$this->start();
		if(isset($_SESSION[$this->sessionFor][$key]))
		{
			unset($_SESSION[$this->sessionFor][$key]);
		}
	}

	// Destroy Session and Logout
	public function logout()
	{
		$staff_id = Functions::staffId();
		// Destroy Session
		$this->end();
		// Set Successfull Message
		setMessage(Lang::$loggedOutSuccess);

		Model::conn()->table('admins')->where(['admin_id' => $staff_id])->update(['admin_token'=>NULL]);
		// Redirect to Login
		redirect(getUri('login'));
	}
}