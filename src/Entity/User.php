<?php

namespace Code\Entity;


use Code\DB\Entity;

class User extends Entity
{
	protected $table = 'users';
	static $filters = [
		'first_name' => FILTER_UNSAFE_RAW,
		'last_name' => FILTER_UNSAFE_RAW,
		'email' => FILTER_UNSAFE_RAW,
		'password' => FILTER_UNSAFE_RAW,
		'password_confirm' => FILTER_UNSAFE_RAW,
	];
}