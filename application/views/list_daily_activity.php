<!-- List daily activities
     ================================================== -->
<div class="bs-docs-section">

    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <h3 id="tables">Listare Activitati</h3>
            </div>

            <div class="bs-example table-responsive">
                <table class="table table-striped table-bordered table-hover datatable">

                    <thead>
                        <tr>
                            <th style="width: 50px !important;">#</th>
                            <th>Job</th>
                            <th>Ora incepere</th>
                            <th>Ora incheiere</th>
                            <th>Data</th>
                            <th>Timp total</th>
                            <th>Numar facturi</th>
                            <th>LI</th>
                            <th>Numar erori</th>
                            <th>Observatii</th>
                            <th style="width: 60px !important;">Actiuni</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php $i = 0; foreach($batches as $batch): ?>
                            <tr>
                                <td><?= ++$i; ?></td>

                                <?php $client = new Client( $batch->client_id ); ?>
                                <td><?= $client->name; ?></td>

                                <td><?= $batch->job_start; ?></td>
                                <td><?= $batch->job_stop; ?></td>

                                <?php $date = new DateTime($batch->job_date); $date = $date->format('d.m.Y'); ?>
                                <td><?= $date; ?></td>

                                <td><?= $batch->total_time; ?></td>
                                <td><?= $batch->no_bills; ?></td>
                                <td><?= $batch->li; ?></td>
                                <td><?= $batch->errors; ?></td>
                                <td><?= $batch->obs; ?></td>

                                <td> <a href="/activity/edit/<?= $batch->id; ?>" class="btn btn-sm btn-default pull-right"><i class="fa fa-pencil"></i></a> </td>
                            </tr>
                        <?php endforeach; ?>

                    </tbody>

                </table>
            </div><!-- /example -->
        </div>
    </div>
</div>

<script src='/assets/libs/datatable/js/jquery.dataTables.min.js'></script>
<script type="text/javascript">
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
</script>