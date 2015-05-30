<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class WT_Base_Controller extends CI_Controller {

    public $user = null;

    public function __construct() {
        parent::__construct();
        $this->before();
    }

    /*
     * Before anything
     *
     * Verify is the user is logged in
     * Set this user globally in view
     *
     * Build the theme
     *
     * @access private
     */

    private function before() {
        // verify the login
        $logged_in = $this->user_is_logged();

        if (!$logged_in && current_url() !== '/login') {
            redirect('/login', 'location');
        }

        $this->template->set('user', $this->user);  // we go global from here
        // IF the user is OPERATOR prevent access to anything except /login, /, /logout, /activity, /activity/index
        $url = current_url();
        if (isset($this->user) && $this->user->role == 'OPERATOR' && !( $url == '/login' || $url == '/' || $url == '/activity' || $url == '/activity/index' || $url == '/logout' || $url == '/get_finish_time' || $url == '/get_start_time' || $url == '/account/change-password' || $url == '/activity/offline' || $url == '/activity/list' || substr($url, 0, 14) == '/activity/edit')) {
            redirect('/', 'location');
        }

        // Setting the template
        $this->template->set_theme('presentation');        // the theme in APPATH.themes
        $this->template->set_layout('yeti');    // the layout for this theme
        // the order in which you add these counts
        $this->template->set_partial('head', 'template/head');
        $this->template->set_partial('header', 'template/header');
        $this->template->set_partial('footer', 'template/footer');
    }

    /* Verifies if a user is logged in
     * Checks the session to verify if email is set.
     * If a valid user is retrieved from the db using the email, then it's ok
     * @access private
     */

    private function user_is_logged() {
        $email = $this->session->userdata('email');
        if (isset($email) && $email != null) {
            $user = new User();
            $user = $user->where('email', $email)->get();
            if ($user->exists()) {
                $this->user = $user;
                return true;
            }
        }
        return false;
    }

    /* Generate a random string
     * @param $length - the length of the string
     * */

    public function random_string($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
}