<!-- List users
     ================================================== -->
<div class="bs-docs-section">

    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <h3 id="tables">Listare Utilizatori</h3>
            </div>

            <div class="bs-example table-responsive">
                <table class="table table-striped table-bordered table-hover datatable">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nume</th>
                            <th>Functie</th>
                            <th>Numar Personal</th>
                            <th>Email</th>
                            <th>Mobil</th>
                            <th style="width: 60px;">Actiuni</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php $i = 0; foreach($users as $user): ?>
                            <tr>
                                <td><?= ++$i; ?></td>
                                <td><?= $user->fname . ' ' . $user->lname; ?></td>
                                <td><?= $user->role; ?></td>
                                <td><?= $user->personal_no; ?></td>
                                <td><?= $user->email; ?></td>
                                <td><?= $user->mobile; ?></td>
                                <td> <a href="/settings/edit/<?= $user->id; ?>" class="btn btn-sm btn-default"><i class="fa fa-pencil"></i></a> </td>
                            </tr>
                        <?php endforeach; ?>

                    </tbody>

                </table>
            </div><!-- /example -->
        </div>
    </div>
</div>

<!-- data table plugin -->
<script src='/assets/libs/datatable/js/jquery.dataTables.min.js'></script>
<script type="text/javascript">
    $(document).ready(function(event){
        $('.datatable').dataTable({
            "sPaginationType": "full_numbers",
            "oLanguage": {
                "sZeroRecords": "Niciun rezultat",
                "sSearch": "Cauta:",
                "sInfo": "Listare de la _START_ la _END_ din _TOTAL_ rezultate",
                "sInfoFiltered": " - filtrate din _MAX_ inregistrari",
                "sLengthMenu": "Afiseaza cate _MENU_",
                "oPaginate": {
                    "sFirst":    "&laquo;",
                    "sPrevious": "&larr;",
                    "sNext":     "&rarr;",
                    "sLast":     "&raquo;"
                }
            }
        });
    });
</script>
