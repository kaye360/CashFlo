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

    public static $button = 'inline-block px-4 py-2 cursor-pointer';
    
    public static $button_main = 'bg-green-100';

    public static $success_prompt = 'block my-4 px-8 py-4 w-fit text-green-800 rounded border border-green-600 bg-green-100 animate-prompt';

    public static $input_error = 'flex flex-col gap-2 text-red-500';



}