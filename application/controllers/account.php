<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/* * --------------------------------------------------------------------------
 * Auth class
 * --------------------------------------------------------------------------
 *
 * Deals with authentication of the users
 *
 */

class Account extends WT_Base_Controller {

    public function __construct() {
        parent::__construct();  // construct the parent
        $this->before();        // execute before anything in this controller
    }

    private function before() {}

    /*
     * Latest uploaded projects
     *
     * Lists all the projects uploaded in the last 24h
     *
     * @link /projects
     * @access public
     * */

    public function index() {
        exit('bye');
        //$this->template->build('login');
    }

    /* Change password
     *
     * @access public
     * @url /account/change-password
     * */

    public function change_password() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // add validation
            $this->form_validation->set_rules('old_pass', 'Parola veche', 'required');
            $this->form_validation->set_rules('pass', 'Parola noua', 'required|matches[re_pass]|min_length[6]|max_length[20]');
            $this->form_validation->set_rules('re_pass', 'Parola noua (confirmare)', 'required');

            if ($this->form_validation->run() == FALSE) {
                // failed show error messages
            } else {
                // it's ok
                $old_pass = $this->input->post('old_pass');
                $hash_old_pass = sha1(md5($old_pass));
                if ($this->user->password === $hash_old_pass) {
                    // do stuff, everything is fine
                    $re_pass = $this->input->post('re_pass');
                    $pass = $this->input->post('pass');
                    if ($pass === $re_pass) {
                        $password = sha1(md5($pass));    // just make sha1 of it :)
                        $this->user->password = $password;
                        $ok = $this->user->save();
                        if ($ok) {
                            $this->template->set('success', true);
                        } else {
                            $this->template->set('error', true);
                            $this->template->set('er_message', 'Datele nu au putut fi salvate in baza de date!');
                        }
                    } else {
                        $this->template->set('error', true);
                        $this->template->set('er_message', 'Cele doua parola noi nu se potrivesc!');
                    }
                } else {
                    $this->template->set('error', true);
                    $this->template->set('er_message', 'Parola veche introdusa nu se potriveste!');
                }
            }
        }
        $this->template->build('account/change_password');
    }
}