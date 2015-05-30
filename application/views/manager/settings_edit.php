<!-- Edit user
================================================== -->
<div class="bs-docs-section">
<div class="row">
<div class="col-lg-3"></div>
<div class="col-lg-6">
<div class="well">

    <?php
    $success = $this->session->flashdata('success');
    if( isset($success) && !empty($success) && $success == true ): ?>
        <div class="alert alert-dismissable alert-success">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>Success!</strong> Utilizatorul <?= $utilizator->fname . ' ' . $utilizator->lname; ?> a fost actualizat cu succes. <br>
        </div>
    <?php endif; ?>

    <?php
        $error = $this->session->flashdata('error');
        if( isset($error) && !empty($error) && $error == true ): ?>
            <div class="alert alert-dismissable alert-danger">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>Eroare!</strong> Modificarile nu au putut fi salvate in baza de date.
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

    <?php $u = $utilizator; ?>

    <form class="bs-example form-horizontal" action="/settings/edit/<?= $utilizator->id; ?>" method="post">
        <fieldset>
            <legend>Actualizare utilizator `<?= $utilizator->fname . ' ' .$utilizator->lname; ?>`</legend>

            <!-- Role -->
            <div class="form-group star <?php if( form_error("role") && form_error("role") != NULL ) echo 'has-error'; ?>">
                <label for="role" class="col-lg-3 control-label">Rol</label>
                <div class="col-lg-9">
                    <select class="form-control" id="role" name="role">
                        <option>Selectare Rol</option>
                        <option value="OPERATOR" <?= ($u->role == 'OPERATOR')?'selected':''; ?>> Operator </option>
                        <option value="MANAGER" <?= ($u->role == 'MANAGER')?'selected':''; ?>> Manager </option>
                    </select>
                </div>
            </div>

            <!-- Personal no -->
            <div class="form-group star <?php if( form_error("personal_no") && form_error("personal_no") != NULL ) echo 'has-error'; ?>">
                <label for="personal_no" class="col-lg-3 control-label">Numar Personal</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" id="personal_no" name="personal_no" value="<?= $u->personal_no; ?>">
                    <span class="help-block">Trebuie sa fie unic!</span>
                </div>
            </div>

            <!-- Last name -->
            <div class="form-group star <?php if( form_error("lname") && form_error("lname") != NULL ) echo 'has-error'; ?>">
                <label for="lname" class="col-lg-3 control-label">Nume</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" id="lname" name="lname" value="<?= $u->lname; ?>">
                </div>
            </div>

            <!-- First name -->
            <div class="form-group star <?php if( form_error("fname") && form_error("fname") != NULL ) echo 'has-error'; ?>">
                <label for="fname" class="col-lg-3 control-label">Prenume</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" id="fname" name="fname" value="<?= $u->fname; ?>">
                </div>
            </div>

            <!-- back account -->
            <div class="form-group star <?php if( form_error("bank_acc") && form_error("bank_acc") != NULL ) echo 'has-error'; ?>">
                <label for="bank_acc" class="col-lg-3 control-label">Cont Banca</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" id="bank_acc" name="bank_acc" value="<?= $u->bank_acc; ?>">
                </div>
            </div>

            <!-- CNP -->
            <div class="form-group star <?php if( form_error("cnp") && form_error("cnp") != NULL ) echo 'has-error'; ?>">
                <label for="cnp" class="col-lg-3 control-label">CNP</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" id="cnp" name="cnp" value="<?= $u->cnp; ?>">
                </div>
            </div>

            <!-- CI Series -->
            <div class="form-group star <?php if( form_error("ci_code") && form_error("ci_code") != NULL ) echo 'has-error'; ?>">
                <label for="ci_code" class="col-lg-3 control-label">Serie Buletin</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" id="ci_code" name="ci_code" value="<?= $u->ci_code; ?>">
                </div>
            </div>

            <!-- Address CI -->
            <div class="form-group star <?php if( form_error("ci_address") && form_error("ci_address") != NULL ) echo 'has-error'; ?>">
                <label for="ci_address" class="col-lg-3 control-label">Adresa Buletin</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" id="ci_address" name="ci_address" value="<?= $u->ci_address; ?>">
                </div>
            </div>

            <!-- Address -->
            <div class="form-group star <?php if( form_error("address") && form_error("address") != NULL ) echo 'has-error'; ?>">
                <label for="address" class="col-lg-3 control-label">Adresa Curenta</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" id="address" name="address" value="<?= $u->address; ?>">
                </div>
            </div>

            <!-- Birth Date -->
            <div class="form-group star <?php if( form_error("birth_date") && form_error("birth_date") != NULL ) echo 'has-error'; ?>">
                <label for="birth_date" class="col-lg-3 control-label">Data Nasterii</label>
                <div class="col-lg-9">
                    <?php
                        $date = new DateTime( $u->birth_date );
                        $birth_date = $date->format('d.m.Y');
                    ?>
                    <input type="text" class="form-control" id="birth_date" name="birth_date" value="<?= $birth_date; ?>">
                </div>
            </div>

            <!-- Employment Date -->
            <div class="form-group star <?php if( form_error("employment_date") && form_error("employment_date") != NULL ) echo 'has-error'; ?>">
                <label for="employment_date" class="col-lg-3 control-label">Data Angajarii</label>
                <div class="col-lg-9">
                    <?php
                        $date = new DateTime( $u->employment_date );
                        $employment_date = $date->format('d.m.Y');
                    ?>
                    <input type="text" class="form-control" id="employment_date" name="employment_date" value="<?= $employment_date; ?>">
                </div>
            </div>

            <!-- Position -->
            <div class="form-group star <?php if( form_error("position") && form_error("position") != NULL ) echo 'has-error'; ?>">
                <label for="position" class="col-lg-3 control-label">Functia</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" id="position" name="position" value="<?= $u->position; ?>">
                </div>
            </div>

            <!-- Employment period -->
            <div class="form-group star <?php if( form_error("employment_period") && form_error("employment_period") != NULL ) echo 'has-error'; ?>">
                <label for="employment_period" class="col-lg-3 control-label">Perioada Angajarii</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" id="employment_period" name="employment_period" value="<?= $u->employment_period; ?>">
                </div>
            </div>

            <!-- BAC -->
            <div class="form-group star <?php if( form_error("bac") && form_error("bac") != NULL ) echo 'has-error'; ?>">
                <label for="bac" class="col-lg-3 control-label">Bacalaureat</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" id="bac" name="bac" value="<?= $u->bac; ?>">
                </div>
            </div>

            <!-- Faculty -->
            <div class="form-group star <?php if( form_error("faculty") && form_error("faculty") != NULL ) echo 'has-error'; ?>">
                <label for="faculty" class="col-lg-3 control-label">Facultate</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" id="faculty" name="faculty" value="<?= $u->faculty; ?>">
                </div>
            </div>

            <!-- Master -->
            <div class="form-group star <?php if( form_error("master") && form_error("master") != NULL ) echo 'has-error'; ?>">
                <label for="master" class="col-lg-3 control-label">Master</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" id="master" name="master" value="<?= $u->master; ?>">
                </div>
            </div>

            <!-- Telephone -->
            <div class="form-group star <?php if( form_error("telephone") && form_error("telephone") != NULL ) echo 'has-error'; ?>">
                <label for="telephone" class="col-lg-3 control-label">Telefon Fix</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" id="telephone" name="telephone" value="<?= $u->telephone; ?>">
                </div>
            </div>

            <!-- Mobile -->
            <div class="form-group star <?php if( form_error("mobile") && form_error("mobile") != NULL ) echo 'has-error'; ?>">
                <label for="mobile" class="col-lg-3 control-label">Telefon Mobil</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" id="mobile" name="mobile" value="<?= $u->mobile; ?>">
                </div>
            </div>

            <!-- Email -->
            <div class="form-group star <?php if( form_error("email") && form_error("email") != NULL ) echo 'has-error'; ?>">
                <label for="email" class="col-lg-3 control-label">Email</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" id="email" name="email" value="<?= $u->email; ?>">
                    <span class="help-block">Emailul trebuie sa fie unic!</span>
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