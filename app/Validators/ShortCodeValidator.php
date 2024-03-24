<?php

namespace App\Validators;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * Service class responsible for validating short URL related data.
 */
class ShortCodeValidator
{
    /**
     * Validates the given data array against predefined rules for short code.
     *
     * This method uses Laravel's built-in Validator facade to ensure that the provided
     * data meets the criteria specified in the validation rules. If the validation
     * fails, it throws a ValidationException containing the validation errors.
     * 
     * @param array $data Array containing the data to validate.
     * @return array Validated data.
     * 
     * @throws ValidationException If validation fails, this exception is thrown with the validation errors.
     */
    public static function validate(array $data)
    {
        $validator = Validator::make($data, [
            'short_code' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}
