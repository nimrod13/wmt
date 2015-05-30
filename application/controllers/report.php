<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Report extends WT_Base_Controller {

    function __construct() {
        parent::__construct();
        $this->before();
    }

    public function before() {}

    /* Generate table reports by user filters
     *
     * @link /report/user
     * */

    public function user() {
        $users = new User();
        $users->order_by('lname', 'asc')->order_by('fname', 'asc')->get();
        $this->template->set('users', $users);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->form_validation->set_rules('user', 'Utilizator', 'required|numeric');
            if ($this->form_validation->run() == FALSE) {
                // failed show error messages
            } else {
                $user_id = $this->input->post('user');
                $usr = new User($user_id);
                if (!$usr->exists()) {
                    $this->template->set('error', true);
                    $this->template->set('message', 'Utilizatorul selectat nu a fost gasit in baza de date!');
                } else {
                    $date_from = $this->input->post('date_from');
                    $date_to = $this->input->post('date_to');
                    $month_year = $this->input->post('month_year');
                    $year = $this->input->post('year');

                    $period = '';

                    $batches = new Batch();
                    $batches->where('user_id', $user_id);
                    // from date to date
                    if (isset($date_from) && !empty($date_from) && isset($date_to) && !empty($date_to)) {
                        $period = $date_from . ' - ' . $date_to;

                        $date = new DateTime($date_from);
                        $date_from = $date->format('Y-m-d');
                        $date = new DateTime($date_to);
                        $date_to = $date->format('Y-m-d');

                        $batches->where('job_date >=', $date_from)->where('job_date <=', $date_to);
                    }
                    // month and year
                    elseif (isset($month_year) && !empty($month_year)) {
                        $period = $month_year;
                        $month_year = substr($month_year, 3, 6) . '-' . substr($month_year, 0, 2);
                        $batches->like('job_date', $month_year);
                    }
                    //only year
                    elseif ($year) {
                        $period = $year;
                        $batches->like('job_date', $year);
                    }

                    $batches->order_by('job_date', 'asc')->get();

                    // group batches by job date and send them in view
                    $this->work_batches_by_user($batches, $usr, $period);
                }
            }
        }

        // manager
        $this->template->set('title', 'Rapoarte tabelare per utilizator');
        $this->template->build('report/user_by_table');
    }

    /* Generate table reports by job filters
     *
     * @link /report/job
     * */

    public function job() {
        $clients = new Client();
        $clients->order_by('name', 'asc')->get();
        $this->template->set('clients', $clients);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->form_validation->set_rules('job', 'Job', 'required|numeric');
            if ($this->form_validation->run() == FALSE) {
                // failed show error messages
            } else {
                $job_id = $this->input->post('job');
                $job = new Client($job_id);
                if (!$job->exists()) {
                    $this->template->set('error', true);
                    $this->template->set('message', 'Jobul selectat nu a fost gasit in baza de date!');
                } else {
                    $date_from = $this->input->post('date_from');
                    $date_to = $this->input->post('date_to');
                    $month_year = $this->input->post('month_year');
                    $year = $this->input->post('year');

                    $period = '';

                    $batches = new Batch();
                    $batches->where('client_id', $job_id);
                    // from date to date
                    if (isset($date_from) && !empty($date_from) && isset($date_to) && !empty($date_to)) {
                        $period = $date_from . ' - ' . $date_to;
                        $date = new DateTime($date_from);
                        $date_from = $date->format('Y-m-d');
                        $date = new DateTime($date_to);
                        $date_to = $date->format('Y-m-d');
                        $batches->where('job_date >=', $date_from)->where('job_date <=', $date_to); //->or_where('job_date =', $date_from)->or_where('job_date =', $date_to);
                    }
                    // month and year
                    elseif (isset($month_year) && !empty($month_year)) {

                        $period = $month_year;
                        $month_year = substr($month_year, 3, 6) . '-' . substr($month_year, 0, 2);
                        $batches->like('job_date', $month_year);
                    }
                    //only year
                    elseif ($year) {

                        $period = $year;
                        $batches->like('job_date', $year);
                    }

                    $batches->order_by('job_date', 'asc')->get();

                    $this->work_batches_by_job($batches, $job, $period);
                }
            }
        }

        // manager
        $this->template->set('title', 'Rapoarte tabelare per job');
        $this->template->build('report/job_by_table');
    }

    /*
     * Returns json with batches matching job_date
     *
     * @param post('job_date')
     * @link /get-batches-by-job-date
     * */

    public function batches_by_job_date() {
        $response = array('status' => 0, 'batches' => null);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $job_date = $this->input->post('job_date');
            $date = new DateTime($job_date);
            $job_date = $date->format('Y-m-d');
            $batches = new Batch();

            $job_id = $this->input->post('job_id');
            $user_id = $this->input->post('user_id');
            if (isset($job_id) && is_numeric($job_id)) {
                $batches->where('client_id', $job_id);
            } else if (isset($user_id) && is_numeric($user_id)) {
                $batches->where('user_id', $user_id);
            }

            $batches->where('job_date', $job_date)->order_by('job_date', 'asc')->get();
            $batches_arr = array();
            foreach ($batches as $batch) {
                $client = new Client($batch->client_id);
                $usr = new User($batch->user_id);
                $batches_arr[] = array(
                    'id' => $batch->id,
                    'job_name' => $client->name,
                    'user_name' => $usr->fname . ' ' . $usr->lname,
                    'job_start' => $batch->job_start,
                    'job_stop' => $batch->job_stop,
                    'job_date' => $batch->job_date,
                    'total_time' => $batch->total_time,
                    'no_bills' => $batch->no_bills,
                    'obs' => $batch->obs,
                    'li' => $batch->li,
                    'client_id' => $batch->client_id,
                    'user_id' => $batch->user_id,
                    'errors' => $batch->errors
                );
            }

            $response['status'] = 1;
            $response['batches'] = $batches_arr;
        }
        echo json_encode($response);
    }

    private function work_batches_by_user($batches, $usr, $period) {            //grouping by job date
        $total_no_bills = 0;
        $total_no_errors = 0;
        $total_time = 0;
        $job_dates = array();
        foreach ($batches as $batch) {
            $total_no_bills += $batch->no_bills;
            $total_no_errors += $batch->errors;
            $job_dates[$batch->job_date] = 0;
            $total_time += $batch->total_time;
        }

        $error_percentage = 0;
        $bills_per_hour = 0;
        if ($total_no_bills != 0 && $total_no_errors != 0) {
            $error_percentage = number_format(($total_no_errors / $total_no_bills) * 100, 2);
            $bills_per_hour = number_format($total_no_bills / 60, 2);
        }

        $this->template->set('total_time', $total_time);
        $this->template->set('job_dates', $job_dates);
        $this->template->set('bills_per_hour', $bills_per_hour);
        $this->template->set('error_percentage', $error_percentage);
        $this->template->set('total_no_bills', $total_no_bills);
        $this->template->set('total_no_errors', $total_no_errors);
        $this->template->set('usr', $usr);
        $this->template->set('period', $period);
        $this->template->set('batches', $batches);
    }

    /* Generate chart reports by user filters
     *
     * @link /report/global-user
     * */
    public function global_user() {
        $users = new User();
        $users->order_by('lname', 'asc')->order_by('fname', 'asc')->get();
        $this->template->set('users', $users);

        $this->form_validation->set_rules('user', 'Utilizator', 'required|numeric');
        $this->form_validation->set_rules('job', 'Job', 'required|numeric');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->form_validation->run() == FALSE) {
                // failed show error messages
            } else {
                $user_id = $this->input->post('user');
                $usr = new User($user_id);
                if (!$usr->exists()) {
                    $this->template->set('error', true);
                    $this->template->set('message', 'Utilizatorul selectat nu a fost gasit in baza de date!');
                } else {
                    $client_id = $this->input->post('job');

                    if ($client_id == -1) {
                        $client_exists = false;
                        $client = false;
                    } else {
                        $client = new Client($client_id);
                        $client_exists = !$client->exists();
                    }
                    
                    if ($client_exists) {
                        $this->template->set('error', true);
                        $this->template->set('message', 'Job-ul selectat nu a fost gasit in baza de date!');
                    } else {
                        // we're cool; we've got client and user
                        $date_from = $this->input->post('date_from');
                        $date_to = $this->input->post('date_to');
                        $month_year = $this->input->post('month_year');
                        $year = $this->input->post('year');

                        $period = '';

                        $batches = new Batch();
                        if ($client_id == -1) {
                            // filter only on user
                            $batches->where('user_id', $user_id);
                        } else {
                            // filter with user and client
                            $batches->where('user_id', $user_id)->where('client_id', $client_id);
                        }

                        // from date to date
                        if (isset($date_from) && !empty($date_from) && isset($date_to) && !empty($date_to)) {
                            $this->template->set('method', 'days');
                            $period = $date_from . ' - ' . $date_to;

                            $date = new DateTime($date_from);
                            $date_from = $date->format('Y-m-d');
                            $date = new DateTime($date_to);
                            $date_to = $date->format('Y-m-d');

                            $batches->where('job_date >=', $date_from)->where('job_date <=', $date_to);
                        }
                        // month and year
                        elseif (isset($month_year) && !empty($month_year)) {
                            $this->template->set('method', 'days');
                            $period = $month_year;
                            $month_year = substr($month_year, 3, 6) . '-' . substr($month_year, 0, 2);
                            $batches->like('job_date', $month_year);
                        }
                        //only year
                        elseif ($year) {
                            $this->template->set('method', 'months');
                            $period = $year;
                            $batches->like('job_date', $year);
                        }

                        $batches->order_by('job_date', 'asc')->get();

                        $this->template->set('job', $client);
                        $this->template->set('usr', $usr);
                        $this->template->set('period', $period);
                        $this->template->set('batches', $batches);
                    }
                }
            }
        }

        // manager
        $this->template->set('title', 'Rapoarte grafice per utilizator');
        $this->template->build('report/user_by_graphic');
    }

    private function work_batches_by_job($batches, $job, $period) {
        $total_time = 0;
        $total_no_bills = 0;
        $total_no_errors = 0;
        $total_no_li = 0;
        $job_dates = array();
        foreach ($batches as $batch) {
            $total_no_bills += $batch->no_bills;
            $total_no_errors += $batch->errors;
            $total_no_li += $batch->li;
            $job_dates[$batch->job_date] = 0;
            $total_time += $batch->total_time;
        }

        $error_percentage = 0;
        $bills_per_hour = 0;
        if ($total_no_bills != 0 && $total_no_errors != 0) {
            $error_percentage = number_format(($total_no_errors / $total_no_bills) * 100, 2);
            $bills_per_hour = number_format($total_no_bills / 60, 2);
        }

        $this->template->set('total_time', $total_time);
        $this->template->set('job_dates', $job_dates);
        $this->template->set('bills_per_hour', $bills_per_hour);
        $this->template->set('error_percentage', $error_percentage);
        $this->template->set('total_no_bills', $total_no_bills);
        $this->template->set('total_no_errors', $total_no_errors);
        $this->template->set('total_no_li', $total_no_li);
        $this->template->set('job', $job);
        $this->template->set('period', $period);
        $this->template->set('batches', $batches);
    }

    /* Generate chart reports by job filters
     *
     * @link /report/global-job
     * */

    public function global_job() {
        $clients = new Client();
        $clients->order_by('name', 'asc')->get();
        $this->template->set('clients', $clients);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->form_validation->set_rules('job', 'Job', 'required|numeric');
            if ($this->form_validation->run() == FALSE) {
                // failed show error messages
            } else {
                $job_id = $this->input->post('job');
                $job = new Client($job_id);
                if (!$job->exists()) {
                    $this->template->set('error', true);
                    $this->template->set('message', 'Jobul selectat nu a fost gasit in baza de date!');
                } else {
                    $date_from = $this->input->post('date_from');
                    $date_to = $this->input->post('date_to');
                    $month_year = $this->input->post('month_year');
                    $year = $this->input->post('year');

                    $period = '';

                    $batches = new Batch();
                    $batches->where('client_id', $job_id);
                    // from date to date
                    if (isset($date_from) && !empty($date_from) && isset($date_to) && !empty($date_to)) {
                        $this->template->set('current_date_from', $date_from);
                        $this->template->set('current_date_to', $date_to);

                        $period = $date_from . ' - ' . $date_to;

                        $date = new DateTime($date_from);
                        $date_from = $date->format('Y-m-d');
                        $date = new DateTime($date_to);
                        $date_to = $date->format('Y-m-d');

                        $batches->where('job_date >=', $date_from)->where('job_date <=', $date_to);
                    }
                    // month and year
                    elseif (isset($month_year) && !empty($month_year)) {
                        $this->template->set('current_month_year', $month_year);

                        $period = $month_year;
                        $month_year = substr($month_year, 3, 6) . '-' . substr($month_year, 0, 2);
                        $batches->like('job_date', $month_year);
                    }
                    // only year
                    elseif ($year) {
                        $this->template->set('current_year', $year);
                        $period = $year;
                        $batches->like('job_date', $year);
                    }

                    $batches->order_by('job_date', 'asc')->get();

                    $this->template->set('job', $job);
                    $this->template->set('period', $period);
                    $this->template->set('batches', $batches);
                }
            }
        }

        // manager
        $this->template->set('title', 'Rapoarte grafice per job');
        $this->template->build('report/job_by_graphic');
    }

// ends global_job




    /* Get batches by date and job also comparison
     *
     * @param current job_id and period; comparison job_id and period
     * */

    public function batches_by_date_and_job() {
        $response = array('status' => 0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $new_job_id = $this->input->post('new_job_id');
            $current_job_id = $this->input->post('current_job_id');
            if (isset($new_job_id) && is_numeric($new_job_id) && isset($current_job_id) && is_numeric($current_job_id)) {
                $current_client = new Client($current_job_id);
                $new_client = new Client($new_job_id);
                if ($current_client->exists() && $new_client->exists()) {
                    /* comparison dates */
                    $cmp_date_from = $this->input->post('cmp_date_from');
                    $cmp_date_to = $this->input->post('cmp_date_to');
                    $cmp_month_year = $this->input->post('cmp_month_year');
                    $cmp_year = $this->input->post('cmp_year');
                    /* current dates */
                    $current_date_from = $this->input->post('current_date_from');
                    $current_date_to = $this->input->post('current_date_to');
                    $current_month_year = $this->input->post('current_month_year');
                    $current_year = $this->input->post('current_year');

                    $current_batches = new Batch();
                    $new_batches = new Batch();
                    $current_batches->where('client_id', $current_job_id);
                    $new_batches->where('client_id', $new_job_id);

                    // Comparison period
                    // comparison from date - to date
                    if (isset($cmp_date_from) && !empty($cmp_date_from) && isset($cmp_date_to) && !empty($cmp_date_to)) {
                        $cmp_period = $cmp_date_from . ' - ' . $cmp_date_to;
                        $date = new DateTime($cmp_date_from);
                        $cmp_date_from = $date->format('Y-m-d');
                        $date = new DateTime($cmp_date_to);
                        $cmp_date_to = $date->format('Y-m-d');

                        //$current_batches->where('job_date >=', $current_date_from)->where('job_date <=', $current_date_to);
                        $new_batches->where('job_date >=', $cmp_date_from)->where('job_date <=', $cmp_date_to);
                    }
                    // comparison month and year
                    elseif (isset($cmp_month_year) && !empty($cmp_month_year)) {
                        $cmp_period = $cmp_month_year;
                        $cmp_month_year = substr($cmp_month_year, 3, 6) . '-' . substr($cmp_month_year, 0, 2);
                        // $current_batches->like('job_date', $current_month_year);
                        $new_batches->like('job_date', $cmp_month_year);
                    }
                    //only comparison year
                    elseif ($cmp_year) {
                        $cmp_period = $cmp_year;
                        // $current_batches->like('job_date', $current_year);
                        $new_batches->like('job_date', $cmp_year);
                    }
                    /* ends comparison dates */

                    // Current period
                    // current from date - to date
                    if (isset($current_date_from) && !empty($current_date_from) && isset($current_date_to) && !empty($current_date_to)) {
                        $current_period = $current_date_from . ' - ' . $current_date_to;
                        $date = new DateTime($current_date_from);
                        $current_date_from = $date->format('Y-m-d');
                        $date = new DateTime($current_date_to);
                        $current_date_to = $date->format('Y-m-d');

                        $current_batches->where('job_date >=', $current_date_from)->where('job_date <=', $current_date_to);
                        // $new_batches->where('job_date >=', $date_from)->where('job_date <=', $date_to);
                    }
                    // current month and year
                    elseif (isset($current_month_year) && !empty($current_month_year)) {
                        $current_period = $current_month_year;
                        $current_month_year = substr($current_month_year, 3, 6) . '-' . substr($current_month_year, 0, 2);
                        $current_batches->like('job_date', $current_month_year);
                        // $new_batches->like('job_date', $month_year);
                    }
                    //only current_year
                    elseif ($current_year) {
                        $current_period = $current_year;
                        $current_batches->like('job_date', $current_year);
                        // $new_batches->like('job_date', $year);
                    }

                    $current_batches->order_by('job_date', 'asc')->get();
                    $new_batches->order_by('job_date', 'asc')->get();

                    $operators = array(); // key is name, value is number of bills
                    foreach ($current_batches as $batch) {
                        $usr = new User($batch->user_id);
                        if (isset($operators['current'][$usr->lname . ' ' . $usr->fname])) {
                            $operators['current'][$usr->lname . ' ' . $usr->fname] += $batch->no_bills;
                        } else {
                            $operators['current'][$usr->lname . ' ' . $usr->fname] = $batch->no_bills;
                        }
                    }
                    foreach ($new_batches as $batch) {
                        $usr = new User($batch->user_id);
                        if (isset($operators['new'][$usr->lname . ' ' . $usr->fname])) {
                            $operators['new'][$usr->lname . ' ' . $usr->fname] += $batch->no_bills;
                        } else {
                            $operators['new'][$usr->lname . ' ' . $usr->fname] = $batch->no_bills;
                        }
                    }

                    $response = array(
                        'status' => 1,
                        'operators' => $operators,
                        'current_job_name' => $current_client->name,
                        'new_job_name' => $new_client->name,
                        'current_period' => $current_period,
                        'cmp_period' => $cmp_period
                    );
                }
            }
        }

        echo json_encode($response);
    }

// ends batches by job date and job id

    public function load_jobs_gu() {
        $response = array('status' => 0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->input->post('user');
            if (is_numeric($user)) {
                $user = new User($user);
                if ($user->exists()) {
                    $date_from = $this->input->post('date_from');
                    $date_to = $this->input->post('date_to');
                    $month_year = $this->input->post('month_year');
                    $year = $this->input->post('year');

                    $batches = new Batch();
                    $batches->where('user_id', $user->id);
                    // from date to date
                    if (isset($date_from) && !empty($date_from) && isset($date_to) && !empty($date_to)) {
                        $date = new DateTime($date_from);
                        $date_from = $date->format('Y-m-d');
                        $date = new DateTime($date_to);
                        $date_to = $date->format('Y-m-d');

                        $batches->where('job_date >=', $date_from)->where('job_date <=', $date_to);
                    }
                    // month and year
                    elseif (isset($month_year) && !empty($month_year)) {
                        $period = $month_year;
                        $month_year = substr($month_year, 3, 6) . '-' . substr($month_year, 0, 2);
                        $batches->like('job_date', $month_year);
                    }
                    //only year
                    elseif ($year) {
                        $period = $year;
                        $batches->like('job_date', $year);
                    }

                    $batches->order_by('job_date', 'asc')->get();

                    $jobs = array();
                    foreach ($batches as $batch) {
                        $job = new Client($batch->client_id);
                        $jobs[$job->id] = $job->name;
                    }

                    natcasesort($jobs); // sort the jobs ascending

                    $response = array(
                        'status' => 1,
                        'jobs' => $jobs
                    );
                }
            }
        }

        echo json_encode($response);
    }

}

/* End of file Home.php */
/* Location: ./application/controllers/home.php */