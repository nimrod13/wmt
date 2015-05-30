<!-- Add errors
================================================== -->
<div class="bs-docs-section">
    <div class="row">
        <div class="col-lg-3"></div>
        <div class="col-lg-6">
            <div class="well">
                <form class="bs-example form-horizontal" action="/activity/errors" method="post">
                    <fieldset>
                        <legend>Introducere Erori</legend>

                        <div class="form-group star <?php if( form_error("work_date") && form_error("work_date") != NULL ) echo 'has-error'; ?>">
                            <label for="work_date" class="col-lg-2 control-label">Data</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="work_date" name="work_date">
                            </div>
                        </div>

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

                        <p>Campurile marcate cu <span style="color: #F00;">*</span> sunt obligatorii!</p>

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

    <?php if( isset($batches) ): ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <h3 id="tables"><?= $job->name; ?> - <?= $work_date; ?></h3>
            </div>

            <div class="bs-example table-responsive">
                <table class="table table-striped table-bordered table-hover bootstrap-datatable datatable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Utilizator</th>
                            <th>Nr Facturi</th>
                            <th>Nr Erori</th>
                            <th style="width: 230px;">Adauga Erori</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php $i=0; foreach( $batches as $batch ): ?>
                            <tr>
                                <td><?= ++$i; ?></td>
                                <?php $usr = new User($batch->user_id); ?>
                                <td> <?= $usr->fname . ' ' . $usr->lname; ?> </td>
                                <td><?= $batch->no_bills; ?></td>
                                <td class="td-no-of-errors<?= $usr->id; ?>"><?= $batch->errors; ?></td>
                                <td class="errors-actions" style="text-align: right;">
                                    <input type="text" style="width: 100px; height: 40px;" data-rel="<?= $usr->id; ?>">
                                    <button type="button" class="btn btn-default btn-sm"  onclick="send_no_of_errors( '<?= $usr->id; ?>', '<?= $job->id; ?>', '<?= $work_date; ?>' );" style="margin-bottom: 3px;">Trimite</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
    <?php endif; ?>


</div>