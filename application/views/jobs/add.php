<!-- Add jobs
================================================== -->
<div class="bs-docs-section">
    <div class="row">
        <div class="col-lg-3"></div>
        <div class="col-lg-6">
            <div class="well">

                <?php
                $success = $this->session->flashdata('success');
                $title = $this->session->flashdata('title');

                if( isset($success) && !empty($success) && $success == true ): ?>
                    <div class="alert alert-dismissable alert-success">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>Success!</strong> Jobul <?= $title; ?> a fost adaugat cu succes. <br>
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

                <form class="bs-example form-horizontal" action="/jobs/add" method="post">
                    <fieldset>
                        <legend>Introducere Joburi in Sistem</legend>

                        <!-- Title -->
                        <div class="form-group star <?php if( form_error("title") && form_error("title") != NULL ) echo 'has-error'; ?>">
                            <label for="title" class="col-lg-3 control-label">Titlu</label>
                            <div class="col-lg-9">
                                <input type="text" class="form-control" id="title" name="title">
                                <span class="help-block">Trebuie sa fie unic!</span>
                            </div>
                        </div>

                        <p>Campurile marcate cu <span style="color: #F00;">*</span> sunt obligatorii!</p>

                        <div class="form-group">
                            <div class="col-lg-9 col-lg-offset-3">
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