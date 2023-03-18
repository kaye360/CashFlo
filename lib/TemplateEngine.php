<?php
namespace lib\TemplateEngine;

/**
 * 
 * Custom Templating Engine
 * 
 * @author Josh Kaye
 * https://joshkaye.dev
 * 
 */



class TemplateEngine
{



    /**
     * 
     * Search and replace any instance of {{var}} with $data->var
     * in a template file
     * 
     */
    public static function apply(string $view, object $data)
    {

        foreach( $data as $key => $value ) {
            if( !is_string($value) ) continue;
            $view = str_replace('{{' . $key . '}}', $value, $view);
        }

        return $view;
    }
}
