<?php
namespace App\Validators;

/**
 * Validate that a string is a valid kenyan mobile number
 *
 * @author jameskmb
 */
class PhoneValidator {
    
    public function validate($field, $value, $params) {
        return preg_match('/^((0)|(\+254))7[\d]{8}$/', $value);
    }
    
    /**
     * 
     * @return array
     */
    public static function message() {
        return ['kmobile' => 'A Valid Mobile Kenyan Number has no spaces and looks like: +254798765432 or 0712345678'];
    }
}
