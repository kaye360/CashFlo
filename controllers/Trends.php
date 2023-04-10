<?php
/**
 * 
 * Trends Controller
 * 
 * @author Josh Kaye
 * https://joshkaye.dev
 * 
 * Used to control routes related to Trends
 * 
 */
declare(strict_types=1);
namespace controllers\TrendsController;

use lib\Auth\Auth;
use lib\Controller\Controller;
use lib\Router\Route\Route;
use stdClass;

class TrendsController extends Controller {

    /**
     * 
     * @method Trends index
     * 
     */
    public function index()
    {
        return $this->view('trends/index');
    }

    /**
     * 
     * @method Budget Trends index
     * 
     */
    public function budgets_index()
    {
        $budgetModel   = $this->model('Budget');
        $data          = new stdClass();
        $data->budgets = $budgetModel->get_all(Auth::user_id() );

        return $this->view('trends/budgets', $data);
    }

    /**
     * 
     * @method Single Budget Trend
     * 
     */
    public function budgets_single()
    {
        $budgetModel      = $this->model('Budget');
        $transactionModel = $this->model('Transaction');

        $data         = new stdClass();
        $data->budget = $budgetModel->get( (int) Route::params()->id );

        if ( !$data->budget )
        {
            header('Location: /error/404'); 
            die();
        }

        $data->title         = 'Budget Trends: ' . ucwords($data->budget->name);
        $data->h1            = 'Budget Trends: ' . ucwords($data->budget->name);
        $data->transactions  = $transactionModel->get_single_budget_trend( $data->budget->name );

        // Authorize 
        Auth::authorize( $data->budget->user_id );

        if ( $data->transactions->monthly_net_totals )
        {
            $monthly_max     = (float) max($data->transactions->monthly_net_totals);
            $monthly_min     = (float) min($data->transactions->monthly_net_totals);
        } else {
            $monthly_max     = (float) 1;
            $monthly_min     = (float) 1;
        }

        $monthly_diff        = ($monthly_max - $monthly_min);
        $data->monthly_ratio = $monthly_diff / 100;

        if ( $data->monthly_ratio === (float) 0 )
        {
            $data->monthly_ratio = $monthly_max < 0
                ? 50 / -$monthly_max
                : 50 / $monthly_max;
        }

        return $this->view('trends/budget', $data);
    }
    
    /**
     * 
     * @method Monthly Spending Trends index
     * 
     */
    public function monthly_index()
    {
        return $this->view('trends/monthly');
    }

    /**
     * 
     * @method Single Monthly Spending Trends
     * 
     */
    public function monthly_single()
    {
        return $this->view('trends/month');
    }



}