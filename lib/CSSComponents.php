<?php
/**
 * 
 * Tailwind components
 * 
 * @author Josh Kaye
 * https://joshkaye.dev
 * 
 * Used to reuse the same list of Tailwind classes
 * Initialized in bootstrap.php as constant 'CSS'. Components can be used in
 * the UI in the following way 
 * 
 * @example
 * <a href="#" class="<?php CSS->button_main();?>">Link</a>
 * 
 */
namespace lib\CSSComponents;

class CSSComponents {

    /**
     * 
     * @var Instance Singleton var
     * Used to determine if Auth class has been instantiated or not
     * 
     */
    private static $instance = null;

    /**
     * 
     * @method Initialize Auth singleton
     * 
     */
    public static function init()
    {
        if (!self::$instance) self::$instance = new CSSComponents();
        return self::$instance;
    }
    
    /**
     * 
     * CSS components
     * 
     */

    public static function button_main()
    {
        echo 'inline-block px-4 py-2 bg-green-100';
    }
}