<?php

namespace Code\Entity;


use Code\DB\Entity;

class Category extends Entity
{
	protected $table = 'categories';
	static $filters = [
		'name' => FILTER_UNSAFE_RAW,
		'description' => FILTER_UNSAFE_RAW,
	];
}