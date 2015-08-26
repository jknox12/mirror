<?php

class Validator extends Laravel\Validator {

	/**
	 * Validate that an attribute has a matching required attribute.
	 */
	protected function validate_requires($attribute, $value, $arguments){
		$required = $arguments[0];

		return isset($this->attributes[$required]) and $this->attributes[$required] != null;
	}

	/**
	 * Validate that an attribute has a matching required attribute.
	 */
	protected function validate_boolean($attribute, $value, $arguments){
		return $value === "true" || $value === "false";
	}

	/**
	 * Validate that an attribute is a list.
	 */
	protected function validate_list($attribute, $value, $arguments){
		return is_array($value);
	}

	/**
	 * Validate that a list only contains integers.
	 */
	protected function validate_all_integers($attribute, $value, $arguments){
		if (!is_array($value)){
			return false;
		}

		foreach ($value as $elem){
			if (!is_numeric($elem)){
				return false;
			}
		}

		return true;
	}

	/**
	 * Validate that an attribute is a comma-delimited list of integers.
	 */
	protected function validate_integer_comma_list($attribute, $value, $arguments){
		$list = explode(',', $value);

		foreach ($list as $elem){
			if (!is_numeric($elem)){
				return false;
			}
		}

		return true;
	}
}
