<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jobs extends WT_Base_Controller{

    function __construct(){
        parent::__construct();
        $this->before();
    }

    public function before(){}

    // List jobs
    public function index(){
        $clients = new Client();
        $clients->order_by('name', 'asc')->get();
        $this->template->set('clients', $clients);

        // manager
        $this->template->set('title', 'Listare joburi');
        $this->template->build('jobs/list');
    }

    public function add(){
        if( $_SERVER['REQUEST_METHOD'] === 'POST' ){
            // validation rules
            $this->form_validation->set_rules('title', 'Titlu job', 'required|is_unique[client.name]');
            if ($this->form_validation->run() == FALSE){
                // failed show error messages
            }
            else{
                // it's ok
                $client = new Client();
                $client->name = $this->input->post("title");

                $ok = $client->save();
                if( $ok )
                {
                    // CodeIgniter flash messages. Session
                    $this->session->set_flashdata('success', true);
                    $this->session->set_flashdata('title', $title);
                    redirect('/jobs/add', 'location'); // redirect to clear input data
                }
            }
        }
        $this->template->set('title', 'Adaugare job');
        $this->template->build('jobs/add');
    }

    public function edit( $id = NULL ){

        if(!isset($id) || $id == NULL || !is_numeric($id)) {
            show_404();
            exit();
        }

        $client = new Client( $id );
        if( $_SERVER['REQUEST_METHOD'] === 'POST'){
            // validation rules
            $this->form_validation->set_rules('title', 'Titlu job', 'required|is_unique[client.name]');
            if(strcasecmp($client->name, $this->input->post('title')) == 0){
                $this->form_validation->set_rules('title', 'Titlu job', 'required');
            }
            if ($this->form_validation->run() == FALSE){
                // failed show error messages
            }
            else{
                $client->name = $this->input->post("title");

                $ok = $client->save();
                if( $ok ){
                    // success set flash message in session
                    $this->session->set_flashdata('success', true);
                }
                else{
                     // error 
                    $this->session->set_flashdata('error', true);
                }    
                // redirect to refresh data
                redirect('/jobs/edit/'.$client->id, 'location');
            }
        }
        $this->template->set('client', $client);
        $this->template->set('title', 'Editare client ' .$client->name);
        $this->template->build('jobs/edit');
    }
}