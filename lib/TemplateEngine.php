<?php
/**
 * 
 * Custom Templating Engine
 * 
 * @author Josh Kaye
 * https://joshkaye.dev
 * 
 * Used to place dynamic strings in the UI
 * Any {{string}} will show the value of $data->string, if
 * $data->string is set.
 * 
 */
namespace lib\TemplateEngine;


class TemplateEngine
{

    /**
     * 
     * @method Replace any instance of {{var}} with $data->var
     * in a template file
     * 
     */
    public static function apply(string $view, object $data)
    {
        foreach( $data as $key => $value ) 
        {
            if( !is_string($value) && !is_null($value) ) continue;
            if( is_null($value) ) $value = '';
            $view = str_replace('{{' . $key . '}}', $value, $view);
        }
        return $view;
    }
}
