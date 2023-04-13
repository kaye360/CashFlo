<?php
/**
 * 
 * User Input Handling Class
 * 
 * @author Josh Kaye
 * https://joshkaye.dev
 * 
 */
declare(strict_types=1);
namespace lib\InputHandler\Validator;

use InvalidArgumentException;
use lib\InputHandler\Rules\Rules;
use stdClass;

class Validator {


    /**
     * 
     * @method Validate user input with defined rules
     * 
     * @param input (array) 
     * [
     *      'input1' => [rule list],    // aligns with $_POST['input1']
     *      'input2' => [rule_list],    // aligns with $_POST['input2']
     * ]
     * 
     * Some rules in [rule_list] may have params
     * ['max:15']   // max 15 characters
     * ['min:6']    // min 6 characters
     * 
     * @return validator object
     * {
     *      success: true|false
     *      errors: {
     *          input1: {is_unique, has_too_few, username_password_validate}    // aligns with $_POST['input1']
     *          input2: {password_confirm, required}                            // aligns with $_POST['input2']
     *      }
     * }
     * 
     * Each @var errors->input->abc is (bool)
     * Each error can be handled in the UI separately or as a group.
     * Each input with have a @var has_errors (bool) property 
     * 
     */
    public static function validate(array $inputs) : object
    {
        if( !is_array($inputs) ) {
            throw new InvalidArgumentException('InputHandler::validate arguments must be an array');
        }

        $validator          = new stdClass();
        $validator->errors  = new stdClass();
        $validator->success = true;

        // Loop thru each <input />
        foreach( $inputs as $input => $rules )
        {
            // Error container for current $input
            $validator->errors->$input            = new stdClass();
            $validator->errors->$input->has_error = false;


            /**
             * Default Rules
             */
            

            // Has forbidden characters
            // Allow for has_spaces exception
            $has_forbidden_chars = self::is_rule('has_spaces', $rules)
                ? Rules::has_forbidden_chars($_POST[$input], ['has_spaces'])
                : Rules::has_forbidden_chars($_POST[$input]);

            if( $has_forbidden_chars ) 
            {
                $validator->errors->$input->has_forbidden_chars = true;
                $validator->errors->$input->has_error           = true;
                $validator->success                             = false;

            } else {

                $validator->errors->$input->has_forbidden_chars = false;
            }


            /**
             * Optional Rules
             */


            // Required
            if( self::is_rule('required', $rules) ) 
            {
                if( empty( $_POST[$input])) 
                {
                    $validator->errors->$input->required  = true;
                    $validator->errors->$input->has_error = true;
                    $validator->success                   = false;

                } else {

                    $validator->errors->$input->required  = false;
                }
            }


            // Unique
            // Note: This currently only works for username in table users
            if( self::is_rule('unique', $rules) ) 
            {
                if( Rules::is_not_unique($_POST[$input]) ) 
                {
                    $validator->errors->$input->unique    = true;
                    $validator->errors->$input->has_error = true;
                    $validator->success                   = false;

                } else {

                    $validator->errors->$input->unique    = false;
                }
            }


            // Max character length
            if( self::is_rule('max', $rules) ) 
            {
                $param = self::get_rule_param('max', $rules);

                if( 
                    is_numeric($param) && 
                    Rules::has_too_many_chars( $_POST[$input], $param )
                ) {
                    $validator->errors->$input->max       = true;
                    $validator->errors->$input->has_error = true;
                    $validator->success                   = false;

                } else {

                    $validator->errors->$input->max       = false;
                }
            }
            

            // Min character length
            if( self::is_rule('min', $rules) ) 
            {
                $param = self::get_rule_param('min', $rules);

                if( 
                    is_numeric($param) && 
                    Rules::has_too_few_chars( $_POST[$input], $param )
                ) {
                    $validator->errors->$input->min       = true;
                    $validator->errors->$input->has_error = true;
                    $validator->success                   = false;

                } else {

                    $validator->errors->$input->min       = false;
                }
            }


            // Username, password verification
            if( self::is_rule('user_pass_verify', $rules) ) 
            {
                if( Rules::is_invalid_username_password(
                    username: $_POST['username'],
                    password: $_POST['password']
                )) {
                    $validator->errors->$input->user_pass_verify = true;
                    $validator->errors->$input->has_error        = true;
                    $validator->success                          = false;

                } else {

                    $validator->errors->$input->user_pass_verify = false;
                }
            }
            

            // Password, confirm password verification
            if( self::is_rule('confirm_password', $rules) ) 
            {
                if( Rules::is_invalid_confirm_password(
                    password: $_POST['confirm_password_1'],
                    confirm_password: $_POST['confirm_password_2']
                )) {
                    $validator->errors->$input->confirm_password = true;
                    $validator->errors->$input->has_error        = true;
                    $validator->success                          = false;

                } else {

                    $validator->errors->$input->confirm_password = false;
                }
            }

            // Check if number
            if( self::is_rule('number', $rules) )
            {
                if( !is_numeric($_POST[$input]) )
                {
                    $validator->errors->$input->number    = true;
                    $validator->errors->$input->has_error = true;
                    $validator->success                   = false;

                } else {

                    $validator->errors->$input->number    = false;
                }
            }

            // Checki if valid date
            if( self::is_rule('date', $rules) )
            {
                if( Rules::is_invalid_date($_POST[$input]) )
                {
                    $validator->errors->$input->date    = true;
                    $validator->errors->$input->has_error = true;
                    $validator->success                   = false;
                } else {
                    $validator->errors->$input->date    = false;
                }
            }

        }

        return $validator;
    }

    
    
    /**
     * 
     * Helper methods
     * 
     */


    /**
     * 
     * @method Check if rule is set on an input rule list
     * Uses str_contains instead of in_array to account for params
     * @example min:6 or max:15 etc.
     * 
     */
    private static function is_rule(string $rule, array $input_rule_list) : bool
    {
        foreach($input_rule_list as $input_rule)
        {
            if( str_contains($input_rule, $rule) ) return true;
        }

        return false;
    }

    /**
     * 
     * @method Get rule param
     * 
     */
    private static function get_rule_param(string $rule, array $input_rule_list) : ?int
    {
        foreach($input_rule_list as $input_rule)
        {
            if( str_contains($input_rule, $rule) ) 
            {
                $input_rule_to_array = explode(':', $input_rule);
                $param               = $input_rule_to_array[1] ?? null;

                if( !empty($param) ) return (int) $param;
            }
        }

        return null;
    }
}