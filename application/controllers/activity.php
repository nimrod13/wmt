<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Activity extends WT_Base_Controller {

    function __construct() {
        parent::__construct();
        $this->before();
    }

    public function before() {}

    public function index() {
        $clients = new Client();
        $clients->order_by('name', 'asc')->get();
        $this->template->set('clients', $clients);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $batch = new Batch();

            $job_date = date('Y-m-d', time());
            $user_id = $this->user->id;
            $client_id = $this->input->post('job_id');
            $batch->where('job_date', $job_date)->where('user_id', $user_id)->where('client_id', $client_id)->get();
            //verificam daca s-a mai lucrat in ziua curenta pentru jobul/clientul respectiv
            if ($batch->exists()) {
                $batch->total_time += $this->input->post('total_time'); // add to the total time
                $batch->no_bills += $this->input->post('bills_no');
            } else {
                $batch->job_date = date('Y-m-d', time());
                $batch->user_id = $this->user->id;
                $batch->client_id = $this->input->post('job_id');
                $batch->total_time = $this->input->post('total_time');
                $batch->no_bills = $this->input->post('bills_no');
            }

            $batch->obs = $this->input->post('obs');
            $batch->job_start = date('Y-m-d H:i:s', $this->input->post('start_time'));
            $batch->job_stop = date('Y-m-d H:i:s', $this->input->post('finish_time'));

            $ok = $batch->save();
            if ($ok) {
                $this->template->set('success', true);
            } else {
                $this->template->set('error', true);
            }
        }

        // manager & operator is allowed here
        $this->template->set('title', 'Activitate zilnica');
        $this->template->build('activity/daily');
    }

//ends daily activity


    /* Lists all activities for this user
     *
     * */

    public function list_activities() {
        $user_id = $this->user->id;
        $batches = new Batch();
        $batches->where('user_id', $user_id)->order_by('job_date', 'asc')->get();
        $this->template->set('batches', $batches);

        $this->template->set('title', 'Listare acitivitate zilnica');
        $this->template->build('activity/list');
    }

    /* Edit daily activity
     *
     * */

    public function edit_daily_activity($id = NULL) {
        if (isset($id) && $id != NULL && is_numeric($id)) {
            $batch = new Batch($id);
            if ($batch->exists() && $batch->user_id == $this->user->id) {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $this->form_validation->set_rules('bills_no', 'Numar facturi', 'required|numeric');
                    if ($this->form_validation->run() == FALSE) {
                        // failed show error messages
                    } else {
                        $bills_no = $this->input->post('bills_no');
                        $obs = $this->input->post('obs');
                        $batch->no_bills = $bills_no;
                        $batch->obs = $obs;

                        $ok = $batch->save();
                        if ($ok) {
                            $this->template->set('success', true);
                        } else {
                            $this->template->set('error', true);
                        }
                    }
                }

                $this->template->set('batch', $batch);  //trimite batch-ul in view
                $this->template->set('title', 'Actualizare acitivitate zilnica');
                $this->template->build('activity/edit');        //constr tema
            }
        }
    }

//ends edit daily activity


    /*
     * Interface for offline activities
     * */

    public function offline() {
        $clients = new Client();
        $clients->order_by('name', 'asc')->get();
        $this->template->set('clients', $clients);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->form_validation->set_rules('job', 'Job', 'required|numeric');
            $this->form_validation->set_rules('start_time', 'Ora inceperii', 'required');
            $this->form_validation->set_rules('finish_time', 'Ora incheierii', 'required');
            $this->form_validation->set_rules('total_time', 'Timp total', 'required|numeric');
            $this->form_validation->set_rules('bills_no', 'Numar facturi', 'required|numeric');
            $this->form_validation->set_rules('work_date', 'Data', 'required');

            if ($this->form_validation->run() == FALSE) {
                // failed show error messages
            } else {
                $batch = new Batch();

                $time = strtotime( $this->input->post('work_date') );
                $job_date = date('Y-m-d', $time);
                
                $user_id = $this->user->id;
                $client_id = $this->input->post('job');

                // look for existing job activity at this date, for this user, for this client with this activity.
                $batch->where('job_date', $job_date)->where('user_id', $user_id)->where('client_id', $client_id)->get();
                if ($batch->exists()) {
                    $batch->no_bills += $this->input->post('bills_no');
                    $batch->total_time += $this->input->post('total_time');
                } else {
                    $batch->job_date = $job_date;
                    $batch->user_id = $this->user->id;
                    $batch->client_id = $client_id;

                    $batch->no_bills = $this->input->post('bills_no');
                    $batch->total_time = $this->input->post('total_time');
                }

                $batch->obs = $this->input->post('obs');
                $batch->job_start = $this->input->post('start_time');
                $batch->job_stop = $this->input->post('finish_time');

                $ok = $batch->save();
                if ($ok) {
                    $this->template->set('success', true);
                } else {
                    $this->template->set('error', true);
                }
            }
        }

        // manager & operator is allowed here
        $this->template->set('title', 'Activitate Offline');
        $this->template->build('activity/offline');
    } // ends offline activity

    public function errors() {
        $clients = new Client();
        $clients->order_by('name', 'asc')->get();
        $this->template->set('clients', $clients);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->form_validation->set_rules('work_date', 'Data', 'required');
            $this->form_validation->set_rules('job', 'Job', 'required|numeric');
            if ($this->form_validation->run() == FALSE) {
                // failed show error messages
            } else {
                // we're cool
                $work_date = $this->input->post('work_date');
                $this->template->set('work_date', $work_date);

                $job_id = $this->input->post('job');

                $date = new DateTime($work_date);
                $work_date = $date->format('Y-m-d');

                // if everything is good set batches in template
                $client = new Client($job_id);

                $batch = new Batch();
                $batch->where('job_date', $work_date)->where('client_id', $job_id)->get(); // where ..date and job id
                $this->template->set('batches', $batch);
                $this->template->set('job', $client);
            }
        }
        $this->template->set('title', 'Introducere erori');
        $this->template->build('manager/errors');
    } // ends errors

    public function update_no_of_errors() {
        $response = array('status' => 0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $no_errors = $this->input->post('no_errors');
            $user_id = $this->input->post('user_id');
            $job_id = $this->input->post('job_id');
            $job_date = $this->input->post('job_date');
            $date = new DateTime($job_date);
            $job_date = $date->format('Y-m-d');

            $batch = new Batch();
            $batch->where('user_id', $user_id)->where('client_id', $job_id)->where('job_date', $job_date)->get();

            if ($batch->exists()) {
                $batch->errors += $no_errors;
                $batch->save();
                $response = array(
                    'status' => 1
                );
            }
        }

        echo json_encode($response);
    }

    public function get_start_time() {
        $response = array('status' => 0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $response = array(
                'status' => 1,
                'time' => time()
            );
        }

        echo json_encode($response);
    }

    public function get_finish_time() {
        $response = array('status' => 0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $start_time = $this->input->post('start_time');
            $now = time();
            $response = array(
                'status' => 1,
                'finish_time' => $now,
                'difference' => $now - $start_time
            );
        }

        echo json_encode($response);
    }

}