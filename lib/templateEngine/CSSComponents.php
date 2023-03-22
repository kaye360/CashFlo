<?php
/**
 * 
 * Tailwind components
 * 
 * @author Josh Kaye
 * https://joshkaye.dev
 * 
 * Used to reuse the same list of Tailwind classes
 * 
 * Initialized in lib\templateEngine\TemplateEngine
 * Define static vars as strings with tailwind classes
 * Call these vars in the UI with <<var>>
 * @example <button class="<<button_main>>">
 * 
 */
namespace lib\TemplateEngine\CSSComponents;

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

    public static $button_main = 'inline-block px-4 py-2 bg-green-100';



}