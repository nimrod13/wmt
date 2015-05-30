<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends WT_Base_Controller{

    function __construct()
    {
        parent::__construct();
        $this->before();
    }

    public function before(){}



    /* List users
     *
     * */
    public function index()
    {
        $users = new User();
        $users->order_by('lname', 'asc')->order_by('fname', 'asc')->get();
        $this->template->set('users', $users);

        // manager & operator is allowed here
        $this->template->set('title', 'Listare utilizatori');
        $this->template->build('settings/list');
    }

    public function add(){
        if( $_SERVER['REQUEST_METHOD'] === 'POST' )
        {
            // validation rules
            $this->validate_user_input();
            if ($this->form_validation->run() == FALSE)
            {
                // failed show error messages
            }
            else
            {
                // we're cool
                $user = new User();
                $password = $this->random_string( 8 );
                $user->password = sha1( md5( $password ) );
                $user->role = $this->input->post("role");
                $user->personal_no = $this->input->post("personal_no");
                $user->lname = $this->input->post("lname");
                $user->fname = $this->input->post("fname");
                $user->bank_acc = $this->input->post("bank_acc");
                $user->cnp = $this->input->post("cnp");
                $user->ci_code = $this->input->post("ci_code");
                $user->ci_address = $this->input->post("ci_address");
                $user->address = $this->input->post("address");

                $date = $this->input->post("birth_date");
                $date = new DateTime( $date );
                $user->birth_date = $date->format('Y-m-d H:i:s');

                $date = $this->input->post("employment_date");
                $date = new DateTime( $date );
                $user->employment_date = $date->format('Y-m-d H:i:s');

                $user->position = $this->input->post("position");
                $user->employment_period = $this->input->post("employment_period");

                $user->bac = $this->input->post("bac");

                $user->faculty = $this->input->post("faculty");

                $user->master = $this->input->post("master");

                $user->telephone = $this->input->post("telephone");
                $user->mobile = $this->input->post("mobile");
                $user->email = $this->input->post("email");

                $ok = $user->save();
                if( $ok )
                {
                    $this->session->set_flashdata('success', true);
                    $this->session->set_flashdata('password', $password);
                    $this->session->set_flashdata('personal_no', $user->personal_no);
                    $this->session->set_flashdata('name', $user->fname . ' ' . $user->lname);
                    redirect('/settings/add', 'location');  // redirect to clear input fields
                }
            }
        }

        $this->template->set('title', 'Adaugare utilizatori');
        $this->template->build('settings/add');
    }

    public function edit( $id = NULL ){

        if( !isset($id) || $id == NULL || !is_numeric($id)  )
        {
            show_404();
            exit();
        }

        $user = new User( $id );
        if( $_SERVER['REQUEST_METHOD'] === 'POST' )
        {
            // validation rules
            $this->validate_user_input();
            if(strcasecmp($user->email, $this->input->post('email')) == 0){
                $this->form_validation->set_rules('email', 'Email', 'required');
            }
            if(strcasecmp($user->personal_no, $this->input->post('personal_no')) == 0){
                $this->form_validation->set_rules('personal_no', 'Numar personal', 'required|numeric');
            }

            if ($this->form_validation->run() == FALSE)
            {
                // failed show error messages
            }
            else
            {

                $user->role = $this->input->post("role");
                $user->personal_no = $this->input->post("personal_no");
                $user->lname = $this->input->post("lname");
                $user->fname = $this->input->post("fname");
                $user->bank_acc = $this->input->post("bank_acc");
                $user->cnp = $this->input->post("cnp");
                $user->ci_code = $this->input->post("ci_code");
                $user->ci_address = $this->input->post("ci_address");
                $user->address = $this->input->post("address");

                $date = $this->input->post("birth_date");
                $date = new DateTime( $date );
                $user->birth_date = $date->format('Y-m-d H:i:s');

                $date = $this->input->post("employment_date");
                $date = new DateTime( $date );
                $user->employment_date = $date->format('Y-m-d H:i:s');

                $user->position = $this->input->post("position");
                $user->employment_period = $this->input->post("employment_period");

                $user->bac = $this->input->post("bac");

                $user->faculty = $this->input->post("faculty");

                $user->master = $this->input->post("master");

                $user->telephone = $this->input->post("telephone");
                $user->mobile = $this->input->post("mobile");
                $user->email = $this->input->post("email");

                $ok = $user->save();
                if( $ok )
                {
                    $this->session->set_flashdata('success', true);
                }
                else
                {
                    $this->session->set_flashdata('error', true);
                }
                redirect('/settings/edit/'.$user->id, 'location');
            }
        }

        $this->template->set('utilizator', $user);
        $this->template->set('title', 'Editare utilizator ' .$user->fname . ' ' . $user->lname);
        $this->template->build('settings/edit');
    }

    private function validate_user_input(){
        $this->form_validation->set_rules('role', 'Rol', 'required|alpha');
        $this->form_validation->set_rules('personal_no', 'Numar Personal', 'required|numeric|is_unique[user.personal_no]');
        $this->form_validation->set_rules('lname', 'Nume', 'required');
        $this->form_validation->set_rules('fname', 'Prenume', 'required');
        $this->form_validation->set_rules('bank_acc', 'Cont Banca', 'required');
        $this->form_validation->set_rules('cnp', 'CNP', 'required');
        $this->form_validation->set_rules('ci_code', 'Serie Buletin', 'required');
        $this->form_validation->set_rules('ci_address', 'Adresa Buletin', 'required');
        $this->form_validation->set_rules('address', 'Adresa Curenta', 'required');
        $this->form_validation->set_rules('birth_date', 'Data Nasterii', 'required');
        $this->form_validation->set_rules('employment_date', 'Data Angajarii', 'required');
        $this->form_validation->set_rules('position', 'Functia', 'required');
        $this->form_validation->set_rules('employment_period', 'Perioada Angajarii', 'required');
        $this->form_validation->set_rules('bac', 'Bacalaureat', 'required');
        $this->form_validation->set_rules('faculty', 'Facultate', 'required');
        $this->form_validation->set_rules('master', 'Master', 'required');
        $this->form_validation->set_rules('telephone', 'Telefon Fix', 'required');
        $this->form_validation->set_rules('mobile', 'Telefon Mobil', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[user.email]');
    }



} // ends class Settings