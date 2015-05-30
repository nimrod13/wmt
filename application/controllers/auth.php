<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 *--------------------------------------------------------------------------
 * Auth class
 *--------------------------------------------------------------------------
 *
 * Deals with authentication of the users
 *
 */
class Auth extends WT_Base_Controller {

    public function __construct() {
        parent::__construct();  // construct the parent
        $this->before();        // execute before anything in this controller
    }

    private function before(){}

    public function index(){
        $this->template->build('template/login');
    }

    /* Login fct
     * Loggs in an user based on email and password
     * Email between 5 - 70 chars
     * Password bet 5 - 70 chars - this is relative because of the md5 hash on the browser.
     * @access public
     * @url /login
     * */
    public function login() {
        // if the request is of type "POST"
        if( $_SERVER['REQUEST_METHOD'] === 'POST' )
        {
            // add validation - using the library from CodeIgniter
            $this->form_validation->set_rules('personal_no', 'Numar Personal', 'required|numeric');   // sets rules
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]|max_length[70]');

            if ($this->form_validation->run() == FALSE){
                // failed show error messages
            }
            else {
                $personal_no = $this->input->post('personal_no');
                $password = $this->input->post('password');
                $password = sha1($password);    //encrypt it

                $user = new User();
                $user->where('personal_no', $personal_no)->where('password', $password)->get();
                if( $user->exists() )
                {
                    $data = array(
                        'email' => $user->email
                    );
                    $this->session->set_userdata($data);
                    redirect('/', 'location');
                }
                else {
                    $this->template->set('login_error', 'Verificati datele introduse!');
                }
            }
        }
        $this->template->build('template/login');
    }

    /* Logout fct
     *
     * Logs out an user by distroying the session
     *
     * @access public
     * @url /logout
     * */
    public function logout() {
        $this->session->sess_destroy();
        redirect('/login', 'location');
    }
} // ends class projects