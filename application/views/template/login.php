<div class="row">
    <div class="col-lg-12">

        <div class="col-lg-4"></div>

        <div class="col-lg-4">
            <h3>Autentificare</h3>

            <?php if( (isset($login_error) && !empty($login_error)) || (validation_errors() && validation_errors() != null) ): ?>
                <div class="alert alert-dismissable alert-danger">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>Eroare!</strong> Verificati datele introduse.
                </div>
            <?php endif; ?>

            <form role="form" id="projects-login-form" method="POST" action="/login">

                <div class="form-group">
                    <label for="personal_no">Numar personal</label>
                    <input type="text" class="form-control" id="personal_no" name="personal_no">
                </div>

                <div class="form-group">
                    <label for="password">Parola</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>

                <button type="submit" class="btn btn btn-primary" id="projects-login-submit">
                    Login
                </button>

            </form>

        </div>

        <div class="col-lg-4"></div>

    </div>
</div>