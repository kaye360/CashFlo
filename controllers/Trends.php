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
        $budgetModel = $this->model('Budget');

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

        $data                = new stdClass();
        $data->budget        = $budgetModel->get( (int) Route::params()->id );
        $data->transactions  = $transactionModel->get_single_budget_trend( $data->budget->name );
        $monthly_max         = max($data->transactions->monthly_net_totals);
        $monthly_min         = min($data->transactions->monthly_net_totals);
        $data->monthly_ratio = ($monthly_max - $monthly_min) / 100;

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