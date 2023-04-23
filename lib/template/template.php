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
namespace lib\Template\Template;

use lib\Template\CSSComponents\CSSComponents;



class Template
{

    /**
     * 
     * @method Search and replace any view placeholders
     * 
     * 
     */
    public function apply(string $view, object $data)
    {
        // Search and replace all {{vars}} with $data->var in $view
        // If $data->var not set, set to '' and hide
        preg_match_all('/\{\{(.*?)\}\}/i', $view, $matches); // Find {{...}}

        $ui_placeholders = array_unique($matches[1]);

        unset($matches);

        foreach( $ui_placeholders as $value )
        {
            if( isset($data->$value) )
            {
                $view = str_replace('{{' . $value . '}}', (string) $data->$value, $view);
            } else {
                $view = str_replace('{{' . $value . '}}', '', $view);
            }
        }

        return $view;
    }
}
