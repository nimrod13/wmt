<!-- List jobs
     ================================================== -->
<div class="bs-docs-section">

    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <h3 id="tables">Listare Joburi</h3>
            </div>

            <div class="bs-example table-responsive">
                <table class="table table-striped table-bordered table-hover datatable">

                    <thead>
                        <tr>
                            <th style="width: 50px !important;">#</th>
                            <th>Titlu</th>
                            <th style="width: 60px !important;">Actiuni</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php $i = 0; foreach($clients as $client): ?>
                            <tr>
                                <td><?= ++$i; ?></td>
                                <td><?= $client->name; ?></td>
                                <td> <a href="/jobs/edit/<?= $client->id; ?>" class="btn btn-sm btn-default pull-right"><i class="fa fa-pencil"></i></a> </td>
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
