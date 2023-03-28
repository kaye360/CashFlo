<?php
/**
 * 
 * Custom Templating Engine
 * 
 * @author Josh Kaye
 * https://joshkaye.dev
 * 
 * Used to place dynamic placeholder strings in the UI. This should only
 * be called from lib\Controller\Controller class.
 * 
 * To access any $data props that were passed into a view, use {{propname}}
 * To access a CSSComponent, use class="<<cmpnt_name>>"
 * 
 */
declare(strict_types=1);
namespace lib\TemplateEngine\TemplateEngine;

use lib\TemplateEngine\CSSComponents\CSSComponents;



class TemplateEngine
{

    /**
     * 
     * @method Search and replace any view placeholders
     * 
     */
    public function apply(string $view, object $data)
    {
        // Search and replace all {{vars}} with $data->var in $view
        // If $data->var not set, set to '' and hide
        preg_match_all('/\{\{(.*?)\}\}/i', $view, $data_matches); // Find {{...}}
        foreach( $data_matches[1] as $value )
        {
            if( isset($data->value) )
            {
                $view = str_replace('{{' . $value . '}}', $data->$value, $view);
            } else {
                $view = str_replace('{{' . $value . '}}', '', $view);
            }
        }

        // Search and replace all <<css_class>> classes with CSSComponents::css_class in $view
        preg_match_all('/\<\<(.*?)\>\>/i', $view, $css_matches);  // Find <<...>>
        $css = CSSComponents::init();
        foreach( $css_matches[1] as $value )
        {
            if( property_exists($css, $value) )
            {
                $view = str_replace('<<' . $value . '>>', CSSComponents::$$value, $view);
            }
        }

        return $view;
    }
}
