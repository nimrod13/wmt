<!-- Edit Daily activity
================================================== -->
<div class="bs-docs-section">
    <div class="row">
        <div class="col-lg-3"></div>
        <div class="col-lg-6">
            <div class="well">

                <?php if( isset($success) && !empty($success) && $success == true ): ?>
                    <div class="alert alert-dismissable alert-success">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>Succes!</strong> Activitatea a actualizata cu succes. <br>
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

                <form class="bs-example form-horizontal" action="/activity/edit/<?= $batch->id; ?>" method="post">
                    <fieldset>
                        <?php $job_date = $batch->job_date; $job_date = new DateTime( $job_date ); $job_date = $job_date->format('d.m.Y');  ?>
                        <legend>Actualizare Activitate Zilnica - <?= $job_date; ?></legend>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Job</label>
                            <div class="col-lg-10">
                                <select class="form-control" disabled>
                                    <?php $client = new Client( $batch->client_id ); ?>
                                    <option><?= $client->name; ?></option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Ora inceperii</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" value="<?= $batch->job_start; ?>" disabled>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Ora incheierii</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" value="<?= $batch->job_stop; ?>" disabled>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Timp total</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" value="<?= $batch->total_time; ?>" disabled>
                            </div>
                        </div>

                        <div class="form-group star <?php if( form_error("bills_no") && form_error("bills_no") != NULL ) echo 'has-error'; ?>">
                            <label for="bills_no" class="col-lg-2 control-label">Numar de facturi</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="bills_no" name="bills_no" value="<?= $batch->no_bills; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="obs" class="col-lg-2 control-label">Observatii</label>
                            <div class="col-lg-10">
                                <textarea class="form-control" rows="3" id="obs" name="obs"><?= $batch->obs; ?></textarea>
                            </div>
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