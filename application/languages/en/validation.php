<?php 

return array(

	/*
	|--------------------------------------------------------------------------
	| Validation Error Messages
	|--------------------------------------------------------------------------
	*/

	"accepted"   => "The :attribute must be accepted.",
	"active_url" => "The :attribute does not exist.",
	"alpha"      => "The :attribute may only contain letters.",
	"alpha_dash" => "The :attribute may only contain letters, numbers, dashes, and underscores.",
	"alpha_num"  => "The :attribute may only contain letters and numbers.",
	"between"    => [
	    "numeric" => "The :attribute must be between :min and :max.",
    ],
	"confirmed"  => "The :attribute confirmation does not match.",
	"email"      => "The :attribute format is invalid.",
	"image"      => "The :attribute must be an image.",
	"in"         => "The selected :attribute is invalid.",
	"integer"    => "The :attribute must be an integer.",
	"max"        => "The :attribute must be less than :max.",
	"mimes"      => "The :attribute must be a file of type: :values.",
	"min"        => [
	    "numeric" => "The :attribute must be at least :min.",
    ],
	"not_in"     => "The selected :attribute is invalid.",
	"numeric"    => "The :attribute must be a number.",
	"required"   => "The :attribute field is required.",
	"size"       => "The :attribute must be :size.",
	"unique"     => "The :attribute has already been taken.",
	"url"        => "The :attribute field is not a valid url.",

    'not_null' => ":attribute must not be null.",
	'boolean' => 'The :attribute must be true or false.',
    'list' => 'The :attribute must be a list.',
    'length' => 'The :attribute has the wrong number of elements.',
    'max_length' => 'The :attribute has more elements than what is allowed.',
    'alpha_dash_dot' => 'The :attribute may only contain letters, numbers, dashes, underscores and dots.',
    'datetime' => 'The :attribute must be an ISO 8601 datetime.',
    'country' => 'The :attribute must be an ISO 3166-1 alpha 2 country code.',
	'store_language_required' => 'The :attribute must contain the language ":lang".',
	'all_nonempty' => 'All elements of :attribute must be nonempty strings.',
	'all_integers' => 'All elements of :attribute must be integers.',
	'all_existing_categories' => 'All elements of :attribute must be existing categories.',
    'multiple_in' => "The :attribute is invalid.",
    'category_exists' => 'The category does not exist.',
    'category_isnt_descendant' => 'Setting this parent creates a loop in the hierarchy.',
    'src_attachment_filename' => 'If src is not provided, attachment and filename must be present.',
    'absent' => 'The :attribute must not be present.',
    'has_language' => 'Must include language to use this filter.',
    'unique_store' => 'The :attribute must be unique.',
    'unique_store_soft_delete' => 'The :attribute must be unique.',
    'not_localhost' => 'The :url field cannot point to localhost.',
    'not_empty' => 'The :attribute parameter could not be empty',
    'input_code' => 'The code is required. Must be unique and can contain only alfanumeric characters',
    'input_value' => 'The value is not required if the type is shipping, positive if the type is absolute or between 0 and 100 is the type es percentage',
    'input_type' => 'The type must be percentage, absolute or shipping. Only for the last one the value is not mandatory.',
    'input_categories'  => 'The parameter categories must be a list of ids (integers) and must belong to the store',
    'undefined_handle' => 'The seo_handle field is required',
    'used_handle' => 'The seo_handle is already in use',
	'php_property_name' => 'Invalid characters in the value, it must start with a letter and followed only by: a-z A-Z 0-9 or _',

	'requires' => 'The :attribute requires another field, most likely the language.',
	'integer_comma_list' => 'The :attribute must be a comma-delimited list of integers.',


    /*
    |--------------------------------------------------------------------------
    | The following words are appended to the "size" messages when applicable,
    | such as when validating string lengths or the size of file uploads.
    |--------------------------------------------------------------------------
    */

	"characters" => "characters",
	"kilobytes"  => "kilobytes",

);
