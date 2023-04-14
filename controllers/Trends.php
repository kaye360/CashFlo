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
use lib\utils\Helpers\Helpers;
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

        // Authorize 
        Auth::authorize( $data->budget->user_id );

        if ( !$data->budget )
        {
            header('Location: /error/404'); 
            die();
        }

        $data->title         = 'Budget Trends: ' . ucwords($data->budget->name);
        $data->h1            = 'Budget Trends: ' . ucwords($data->budget->name);
        $data->transactions  = $transactionModel->get_monthly_transaction_trend( $data->budget->name );

        // Generate Graph values
        $data->monthly_ratio = Helpers::budget_trend_bar_graph( $data->transactions->monthly_net_totals );

        return $this->view('trends/budget', $data);
    }
    
    /**
     * 
     * @method Monthly Spending Trends index
     * 
     */
    public function monthly_index()
    {
        $transactionModel = $this->model('Transaction');

        $data = new stdClass();
        $data->transactions = $transactionModel->get_monthly_transaction_trend();

        return $this->view('trends/monthly', $data);
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