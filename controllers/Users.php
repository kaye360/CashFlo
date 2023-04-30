<?php
/**
 * 
 * Users Controller
 * 
 * @author Josh Kaye
 * https://joshkaye.dev
 * 
 * Used to control routes related to Users
 * 
 */
declare(strict_types=1);
namespace controllers\UsersController;

use lib\Auth\Auth;
use lib\Controller\Controller;
use lib\InputHandler\Sanitizer\Sanitizer;
use lib\InputHandler\Validator\Validator;
use lib\Redirect\Redirect\Redirect;
use lib\Router\Route\Route;
use lib\utils\Helpers\Helpers;
use lib\utils\Prompt\Prompt;
use stdClass;



class UsersController extends Controller {

    private $userModel;



    public function __construct()
    {
        $this->userModel = $this->model('User');
    }

    /**
     * 
     * @method Sign up form
     * 
     */
    public function new() : void
    {
        $this->view('users/signup');
    }

    /**
     * 
     * @method Sign up a user 
     * 
     */
    public function create() : void
    {
        $validator = Validator::validate([
            'username' =>           ['required', 'unique', 'max:15', 'min:6'],
            'confirm_password_1' => ['required', 'min:6', 'confirm_password'],
            'confirm_password_2' => ['required']
        ]);
            
        $data                   = new stdClass() ;
        $data->username         = Sanitizer::sanitize( $_POST['username'] );
        $data->password         = trim($_POST['confirm_password_1']);
        $data->confirm_password = trim($_POST['confirm_password_2']);
        $data->errors           = $validator->errors;
        $data->errors->query    = false;
        $data->success          = $validator->success;

        if( $data->success ) 
        {
            $new_user = $this->userModel->create(data: $data);
            
            if( $new_user->error ) 
            {
                $data->success       = false;
                $data->errors->query = true;
                
            } else {
                $data->success = true;
                Redirect::to('/signin')->prompt('success', 'Account created. You may now sign in.')->redirect();
            }
            Prompt::set('error', 'Something went wrong. Please try again.');
        }

        $this->view('users/signup', $data);
    }
    
    /**
     * 
     * @method Sign in form
     * 
     */
    public function sign_in() : void
    {
        $this->view('users/signin');
    }

    /**
     * 
     * @method Sign in a user
     * 
     */
    public function authenticate() : void
    {
        $validator = Validator::validate([
            'password' => ['user_pass_verify']
        ]);
        
        $data           = new stdClass();
        $data->username = Sanitizer::sanitize( $_POST['username'] );
        $data->password = Sanitizer::sanitize( $_POST['password'] );
        $data->errors   = $validator->errors;
        $data->success  = $validator->success;
        
        if( $data->success) 
        {
            $this->userModel->destroy_session();

            $session = Helpers::make_UUID();

            $this->userModel->update_session(
                session:  $session, 
                username: $data->username
            );

            setcookie('session', $session, strtotime( '+30 days' ));
            Redirect::to('/dashboard')->prompt('success', 'Signed in successfully')->redirect();

        } else {
            Prompt::set('error', 'Sign in unsuccessful');
        }

        $this->view('users/signin', $data);
    }

    /**
     * 
     * @method Sign out a user form and sign out user
     * GET and POST route
     * 
     */
    public function sign_out() : void
    {
        $data          = new stdClass();
        $data->success = false;

        if( $_SERVER['REQUEST_METHOD'] === 'POST' ) 
        {
            $this->userModel->destroy_session();
            Redirect::to('/')->prompt('success', 'Sign out successful')->redirect();
        }
        
        $this->view('users/signout', $data);
    }    

    
    
    /**
    * 
    * @return Dashboard Page
    * 
    */
   public function dashboard() : void
   {
        $current_month = date('Y-m');

        $transactionModel = $this->model('Transaction');

        $budgetModel = $this->model('Budget');
        $budgets     = $budgetModel->get_all();

        $recent_transactions = $transactionModel->get_all();

        $current_month_tranactions  = $transactionModel->get_monthly_transaction_trend();
        $current_month_net_total    = $current_month_tranactions->monthly_net_totals[ $current_month ] ?? 0;
        $current_month_net_income   = $current_month_tranactions->monthly_net_income[ $current_month ] ?? 0;
        $current_month_net_spending = $current_month_tranactions->monthly_net_spending[ $current_month ] ?? 0;
        
        $ratio = Helpers::calc_graph_ratio([
            $current_month_net_total,
            $current_month_net_income, 
            $current_month_net_spending
        ]);

        $data = new stdClass();
        $data->recent_transactions        = $recent_transactions->list;
        $data->ratio                      = $ratio;
        $data->current_month_net_total    = $current_month_net_total;
        $data->current_month_net_income   = $current_month_net_income;
        $data->current_month_net_spending = $current_month_net_spending;
        $data->budgets                    = $budgets;

        $this->view('users/dashboard', $data);
   }

    /**
     * 
     * @method User settings form
     * 
     */
    public function settings() : void
    {
        $data = new stdClass();
        $data->settings = Auth::settings();
        $data->success = true;

        $this->view('users/settings', $data);
    }

    /**
     * 
     * @method Update user settings 
     * 
     */
    public function update_settings() : void
    {
        $validator = Validator::validate([
            'confirm_password_1'    => !$_POST['confirm_password_1'] && !$_POST['confirm_password_2'] 
                ? []
                : ['confirm_password', 'min:6'],
            'transactions_per_page' => ['number']
        ]);

        $data                        = new stdClass();
        $data->success               = false;
        $data->confirm_password_1    = Sanitizer::sanitize( $_POST['confirm_password_1'] );
        $data->confirm_password_2    = Sanitizer::sanitize( $_POST['confirm_password_2'] );
        $data->transactions_per_page = Sanitizer::sanitize( $_POST['transactions_per_page'] );
        $data->errors                = $validator->errors;
        $data->success               = $validator->success; 

        if( $data->success )
        {
            $settings                        = new stdClass();
            $settings->password              = $data->confirm_password_1;
            $settings->transactions_per_page = $data->transactions_per_page;
            
            $this->userModel->update_settings($settings);

            Redirect::to('/settings')->prompt('success', 'Settings saved.')->redirect();
            
        } else {
            Prompt::set('error', 'Something went wrong.');
            
        }

        $this->view('users/settings', $data);
    }

    /**
     * 
     * @method update a single users setting
     * Used when this setting is changed from outside the settings page
     * 
     */
    public function update_setting()
    {
        $referer = $_SERVER['HTTP_REFERER'];

        if( Route::params()->setting === 'transactions_per_page' )
        {
            $value = Sanitizer::sanitize( Route::params()->value );
            $this->userModel->update_setting('transactions_per_page', (int) $value );
            Redirect::to($referer)->redirect();
        }
        Redirect::to('/error/404')->redirect();
    }

}