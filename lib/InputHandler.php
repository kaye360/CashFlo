<?php
/**
 * 
 * User Input Handling Class
 * Used for validating, filtering, and sanitizing user input data
 * 
 * @author Josh Kaye
 * https://joshkaye.dev
 * 
 */
namespace lib\InputHandler;

use lib\Database\Database;
use stdClass;



class InputHandler {


    /**
     * 
     */
    public static function sanitize(array $inputs) 
    {

    }

    /**
     * 
     * @method Validate user input with defined rules
     * 
     * @param input (array) 
     * - must be in the form of ASSOC array
     * - inputs must align with $_POST keys
     * [
     *      'input1' => [rule list],
     *      'input2' => [rule_list],
     * ]
     * 
     * Some rules in [rule_list] may have params
     * ['max:15'] = max 15 characters
     * ['min:6'] = min 6 characters
     * 
     * @return validtor (object)
     * {
     *      success: true|false
     *      errors: {
     *          group1: [is_unique, has_too_few, username_password_validate]
     *          group2: [password_confirm, required]
     *      }
     * }
     * 
     * Each error can be handled in the UI separately
     * 
     */
    public static function validate(array $inputs)
    {
        if( !is_array($inputs) ) return;

        $validator = new stdClass();
        $validator->errors = new stdClass();
        $validator->success = true;

        foreach( $inputs as $input => $rules )
        {
            // Error container for current $input
            $validator->errors->$input = new stdClass();


            /**
             * Default Rules
             */
            

            // Has forbidden chars
            if( self::has_forbidden_chars($_POST[$input]) ) {

                $validator->errors->$input->has_forbidden_chars = true;
                $validator->success = false;
            } else {
                $validator->errors->$input->has_forbidden_chars = false;
            }


            /**
             * Optional Rules
             */


            // Required
            if( self::is_rule('required', $rules) ) {

                if( empty( $_POST[$input])) {
                    $validator->errors->$input->required = true;
                    $validator->success = false;
                } else {
                    $validator->errors->$input->required = false;
                }
            }


            // Unique
            // Note: This currently only works for username in table users
            if( self::is_rule('unique', $rules) ) {

                if( self::is_not_unique($_POST[$input]) ) {
                    $validator->errors->$input->unique = true;
                    $validator->success = false;
                } else {
                    $validator->errors->$input->unique = false;
                }
            }


            // Max character length
            if( self::is_rule('max', $rules) ) {

                $param = self::get_rule_param('max', $rules);

                if( 
                    is_numeric($param) && 
                    self::has_too_many_chars( $_POST[$input], $param )
                ) {
                    $validator->errors->$input->max = true;
                    $validator->success = false;
                } else {
                    $validator->errors->$input->max = false;
                }
            }
            

            // Min character length
            if( self::is_rule('min', $rules) ) {

                $param = self::get_rule_param('min', $rules);

                if( 
                    is_numeric($param) && 
                    self::has_too_few_chars( $_POST[$input], $param )
                ) {
                    $validator->errors->$input->min = true;
                    $validator->success = false;
                } else {
                    $validator->errors->$input->min = false;
                }
            }


            // Username, password verification
            if( self::is_rule('user_pass_verify', $rules) ) {

                if( self::is_invalid_username_password(
                    username: $_POST['username'],
                    password: $_POST['password']
                ) ) {
                    $validator->errors->$input->user_pass_verify = true;
                    $validator->success = false;
                } else {
                    $validator->errors->$input->user_pass_verify = false;
                }
            }
            

            // Password, confirm password verification
            if( self::is_rule('confirm_password', $rules) ) {

                if( self::is_invalid_confirm_password(
                    password: $_POST['password'],
                    confirm_password: $_POST['confirm_password']
                ) ) {
                    $validator->errors->$input->confirm_password = true;
                    $validator->success = false;
                } else {
                    $validator->errors->$input->confirm_password = false;
                }
            }

        }

        return $validator;
    }



    /**
     * 
     * Rule Methods
     * 
     */



    /** 
     * 
    * @method checks if array of strings has forbidden characters
    * 
    * @return bool
    * 
    */
    private static function has_forbidden_chars(string $input) 
    {
        if( empty($input) ) return false;
        return !preg_match('/^[a-zA-Z0-9_\-]+$/', $input);
    }


    /**
    * 
    * @method checks if string too long
    * 
    * @return bool
    * 
    */
    private static function has_too_many_chars(string $string, int $limit)
    {
        return strlen($string) > $limit;
    }



    /**
    * 
    * @method checks if string too short
    * 
    * @return bool
    * 
    */
    private static function has_too_few_chars(string $string, int $limit)
    {
        return strlen($string) < $limit;
    }



    /**
     * 
    * @method is value of column already taken. For unique columns
    * 
    * @return bool
    * 
    */
    private static function is_not_unique( string $username ) 
    {

        $db = new Database();
        $user_count = $db->table('users')
           ->select('username')
           ->where("username = '$username' ")
           ->count();

        return $user_count >= 1;
    }



    /**
     * 
     * @method invalid username and password combination
     * 
     * @return bool
     * 
     */
    private static function is_invalid_username_password( string $username, string $password )
    {
        $db = new Database();
        $user = $db->select('username, password')
                   ->table('users')
                   ->where("username = '$username' ")
                   ->single();

        return !( $user['success'] && password_verify($password, $user['data']['password']) );
    }



    /**
     * 
     * @method invalid confirmed password
     * 
     * @return bool
     * 
     */
    private static function is_invalid_confirm_password(string $password, string $confirm_password)
    {
        return $password !== $confirm_password;
    }


    /**
     * 
     * Helper methods
     * 
     */



    /**
     * 
     * @method Check if rule is set on an input rule list
     * 
     */
    private static function is_rule(string $rule, array $input_rule_list)
    {
        foreach($input_rule_list as $input_rule)
        {
            if( str_contains($input_rule, $rule) ) {
                return true;
            }
        }

        return false;
    }


    /**
     * 
     * @method Get rule param
     * 
     */
    private static function get_rule_param(string $rule, array $input_rule_list)
    {
        foreach($input_rule_list as $input_rule)
        {
            if( str_contains($input_rule, $rule) ) {
                $input_rule_to_array = explode(':', $input_rule);

                if( !empty($input_rule_to_array[1]) ) {
                    return $input_rule_to_array[1];
                }
            }
        }

        return null;
    }
}
