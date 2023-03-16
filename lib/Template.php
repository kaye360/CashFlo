<?php
namespace Template;

/**
 * 
 * Custom Templating Engine
 * 
 * @author Josh Kaye
 * https://joshkaye.dev
 * 
 */



class Template 
{



    /**
     * 
     * Search and replace any instance of {{var}} with $data->var
     * in a template file
     * 
     */
    public static function apply(object $data)
    {
        $view = ob_get_contents();

        foreach( $data as $key => $value ) {
            $view = preg_replace_callback('!\{\{' . $key . '\}\}!', function() use($value) {
                return $value;
            }, $view);
        }

        ob_end_clean();
        echo $view;
    }
}
