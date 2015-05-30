<!-- Daily activity
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

                <form class="bs-example form-horizontal daily-activity" action="/" method="post">
                    <fieldset>
                        <legend>Introducere Activitate Zilnica - <?= date('d.m.Y'); ?></legend>

                        <div class="form-group">
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

                        <div class="form-group">
                            <label for="start_time" class="col-lg-2 control-label">Ora inceperii</label>
                            <div class="col-lg-10" id="start_time">
                                <button type="button" class="btn btn-default disabled">Start</button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="end_time" class="col-lg-2 control-label">Ora incheierii</label>
                            <div class="col-lg-10" id="end_time">
                                <button type="button" class="btn btn-default disabled">Stop</button>
                            </div>
                        </div>

                        <div class="form-group total_time_wrapper" style="display: none;">
                            <label for="total_time" class="col-lg-2 control-label">Timp Total</label>
                            <div class="col-lg-10" id="total_time"></div>
                        </div>


                        <div class="form-group">
                            <label for="bills_no" class="col-lg-2 control-label">Numar de facturi</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="bills_no" name="bills_no" disabled>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="obs" class="col-lg-2 control-label">Observatii</label>
                            <div class="col-lg-10">
                                <textarea class="form-control" rows="3" id="obs" name="obs" disabled></textarea>
                            </div>
                        </div>

                        <div class="hidden-inputs"></div>

                        <div class="form-group">
                            <div class="col-lg-10 col-lg-offset-2">
                                <button type="submit" class="btn btn-primary disabled">Trimite</button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
        <div class="col-lg-3"></div>
    </div>
</div>