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
                        <strong>Succes!</strong> Parola a fost schimbata. <br>
                    </div>
                <?php endif; ?>

                <?php if( isset($error) && !empty($error) && $error == true ): ?>
                    <div class="alert alert-dismissable alert-danger">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>Eroare!</strong> <?= $er_message; ?>
                    </div>
                <?php endif; ?>

                <?php
                $errors = validation_errors();
                if( isset($errors) && !empty($errors) ): ?>
                    <div class="alert alert-dismissable alert-danger">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>Eroare!</strong> Verificati datele introduse.
                    </div>
                <?php endif; ?>

                <form class="bs-example form-horizontal" action="/account/change-password" method="post">
                    <fieldset>
                        <legend>Schimbare parola</legend>

                        <div class="form-group star <?php if( form_error("old_pass") && form_error("old_pass") != NULL ) echo 'has-error'; ?>">
                            <label for="old_pass" class="col-lg-2 control-label">Parola veche</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="old_pass" name="old_pass">
                            </div>
                        </div>

                        <div class="form-group star <?php if( form_error("pass") && form_error("pass") != NULL ) echo 'has-error'; ?>">
                            <label for="pass" class="col-lg-2 control-label">Parola noua</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="pass" name="pass">
                                <span class="help-block">Lungime parola noua 6 - 20 de caractere!</span>
                            </div>
                        </div>

                        <div class="form-group star <?php if( (form_error("re_pass") && form_error("re_pass") != NULL) || ( form_error("pass") && form_error("pass") != NULL ) ) echo 'has-error'; ?>">
                            <label for="re_pass" class="col-lg-2 control-label">Parola noua (confirmare)</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="re_pass" name="re_pass">
                                <span class="help-block">Confirmarea noi parole!</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-lg-10 col-lg-offset-2">
                                <button type="submit" class="btn btn-primary">Trimite</button>
                                <button type="reset" class="btn btn-default">Anulare</button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
        <div class="col-lg-3"></div>
    </div>
</div>