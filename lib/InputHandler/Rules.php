<?php
/**
 * 
 *  User Input Handling Rules
 * 
 * @author Josh Kaye
 * https://joshkaye.dev
 * 
 */
declare(strict_types=1);
namespace lib\InputHandler\Rules;



use lib\Database\Database;



class Rules {


    /** 
     * 
     * @method check if string has forbidden characters
     * 
     */
    public static function has_forbidden_chars(string $input, array $exceptions = []) : bool
    {
        if( empty($input) ) return false;
        
        if( in_array('has_spaces', $exceptions) )
        {
            return !preg_match('/^[a-zA-Z0-9 ._\-]+$/', $input);
        }
        
        
        return !preg_match('/^[a-zA-Z0-9._\-]+$/', $input);
    }

    /**
     * 
     * @method check if string too long
     * 
     */
    public static function has_too_many_chars(string $string, int $limit) : bool
    {
        return strlen($string) > $limit;
    }

    /**
     * 
     * @method check if string too short
     * 
     */
    public static function has_too_few_chars(string $string, int $limit) : bool
    {
        return strlen($string) < $limit;
    }

    /**
     * 
     * @method check if username already taken
     * 
     */
    public static function is_not_unique( string $username ) : bool
    {
        $db         = new Database();
        $user_count = $db
            ->table('users')
            ->select('username')
            ->where("username = '$username' ")
            ->count();

        return $user_count >= 1;
    }

    /**
     * 
     * @method invalid username and password combination
     * 
     */
    public static function is_invalid_username_password(string $username, string $password ) : bool
    {
        $db   = new Database();
        $user = $db
            ->select('username, password, salt')
            ->table('users')
            ->where("username = '$username' ")
            ->single();

        if( !$user ) return true;

        $salted_password = $password . $user->salt;

        return !( password_verify($salted_password, $user->password) );
    }

    /**
     * 
     * @method invalid password/confirmed password
     * 
     */
    public static function is_invalid_confirm_password(string $password, string $confirm_password) : bool
    {
        return $password !== $confirm_password;
    }

    /**
     * 
     * @method check if input is a valid date
     * 
     */
    public static function is_invalid_date( string $date ) : bool
    {
        return !strtotime( $date ) ? true : false;
    }

}