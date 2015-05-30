<!-- time picker plugin -->
<script src='/assets/js/jquery.ui.timepicker.js'></script>


<!-- Post activity
================================================== -->
<div class="bs-docs-section">
    <div class="row">
        <div class="col-lg-3"></div>
        <div class="col-lg-6">
            <div class="well">

                <?php if( isset($success) && !empty($success) && $success == true ): ?>
                    <div class="alert alert-dismissable alert-success">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>Succes!</strong> Activitatea a fost salvata. <br>
                    </div>
                <?php endif; ?>

                <?php if( isset($error) && !empty($error) && $error == true ): ?>
                    <div class="alert alert-dismissable alert-danger">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>Eroare!</strong> Activitatea nu au putut fi salvata in baza de date.
                    </div>
                <?php endif; ?>

                <?php
                $errors = validation_errors();
                if( isset($errors) && !empty($errors) ): ?>
                    <div class="alert alert-dismissable alert-danger">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>Eroare!</strong> Verificati datele introduse si completati toate campurile obligatorii.
                    </div>
                <?php endif; ?>

                <form class="bs-example form-horizontal post-factor-form" action="/activity/offline" method="post">
                    <fieldset>
                        <legend>Introducere Activitate Offline - <?= date('d.m.Y'); ?></legend>

                        <div class="form-group star <?php if( form_error("job") && form_error("job") != NULL ) echo 'has-error'; ?>">
                            <label for="job" class="col-lg-2 control-label">Job</label>
                            <div class="col-lg-10">
                                <select class="form-control" id="job" name="job">
                                    <option>Selectare Job</option>
                                    <?php foreach( $clients as $client ): ?>
                                        <option value="<?= $client->id; ?>"><?= $client->name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group star <?php if( (form_error("work_date") && form_error("work_date") != NULL) ) echo 'has-error'; ?>">
                            <label for="work_date" class="col-lg-2 control-label">Data</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="work_date" name="work_date">
                            </div>
                        </div>

                        <div class="form-group star <?php if( (form_error("start_time") && form_error("start_time") != NULL) || (form_error("total_time") && form_error("total_time") != NULL) ) echo 'has-error'; ?>">
                            <label for="start_time" class="col-lg-2 control-label">Ora inceperii</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="start_time" name="start_time">
                            </div>
                        </div>

                        <div class="form-group star <?php if( (form_error("finish_time") && form_error("finish_time") != NULL) || (form_error("total_time") && form_error("total_time") != NULL) ) echo 'has-error'; ?>">
                            <label for="finish_time" class="col-lg-2 control-label">Ora incheierii</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="finish_time" name="finish_time">
                                <span class="help-block">Trebuie sa fie mai tarzie decat ora inceperii</span>
                            </div>
                        </div>

                        <div class="form-group total-time-wrapper" style="display: none;">
                            <label for="total_time" class="col-lg-2 control-label">Timp Total</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="total_time" name="total_time" disabled>
                            </div>
                        </div>


                        <div class="form-group star <?php if(form_error("bills_no") && form_error("bills_no") != NULL) echo 'has-error';?>">
                            <label for="bills_no" class="col-lg-2 control-label">Numar facturi</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="bills_no" name="bills_no" placeholder="Numar..">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="li" class="col-lg-2 control-label">LI</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="li" name="li" placeholder="Numar..">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="obs" class="col-lg-2 control-label">Observatii</label>
                            <div class="col-lg-10">
                                <textarea class="form-control" rows="2" id="obs" name="obs"></textarea>
                            </div>
                        </div>

                        <div class="hidden-inputs">
                            <input type="hidden" name="total_time">
                            <input type="hidden" name="activity">
                        </div>

                        <div class="form-group">
                            <p class="col-lg-10 col-lg-offset-2" style="padding-left: 7;">Campurile marcate cu <span style="color: #F00;">*</span> sunt obligatorii!</p>
                        </div>

                        <div class="form-group">
                            <div class="col-lg-10 col-lg-offset-2">
                                <button type="submit" class="btn btn-primary">Trimite</button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
        <div class="col-lg-3"></div>
    </div>
</div>


<script type="text/javascript">
    /*
     * >>> Offline activity <<<
     * */
    // time pickers at offline activity
    var offline_start_time = $('.post-factor-form #start_time');
    var offline_finish_time = $('.post-factor-form #finish_time');
    offline_start_time.timepicker({
        rows: 4,
        minutes: {
            starts: 0,                // First displayed minute
            ends: 59,                 // Last displayed minute
            interval: 1              // Interval of displayed minutes
        }
    });
    offline_finish_time.timepicker({
        rows: 4,
        minutes: {
            starts: 0,                // First displayed minute
            ends: 59,                 // Last displayed minute
            interval: 1              // Interval of displayed minutes
        }
    });

    offline_start_time.change(function(event){
        compute_time_difference();
    });

    offline_finish_time.change(function(event){
        compute_time_difference();
    });
</script>