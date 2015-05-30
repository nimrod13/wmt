<!-- Add errors
================================================== -->
<div class="bs-docs-section">
    <div class="row">
        <div class="col-lg-3"></div>
        <div class="col-lg-6">
            <div class="well">
                <form class="bs-example form-horizontal report-form" action="/report/job" method="post">
                    <fieldset>
                        <legend>Rapoarte per job</legend>

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

                        <div class="form-group">
                            <label for="date_from" class="col-lg-2 control-label">Data</label>
                            <div class="col-lg-5">
                                <input type="text" class="form-control" id="date_from" name="date_from" placeholder="De la data..">
                            </div>
                            <div class="col-lg-5">
                                <input type="text" class="form-control" id="date_to" name="date_to" placeholder="Pana la data..">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="month_year" class="col-lg-2 control-label">Luna si an</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="month_year" name="month_year" placeholder="Luna si an..">
                                <span class="help-block">Selectati luna si anul dorit si apoi alegeti oricare zi</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="year" class="col-lg-2 control-label">An</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="year" name="year" placeholder="An..">
                                <span class="help-block">Selectati anul dorit si apoi alegeti oricare zi</span>
                            </div>
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

    <?php if( isset($job) && isset($batches) ): ?>
        <!-- data table plugin -->
        <script src='/assets/libs/datatable/js/jquery.dataTables.min.js'></script>
        <div class="row">
            <div class="col-lg-12">

                <div class="page-header">
                    <h3 id="tables">Raport de activitate pentru `<?= $job->name; ?>` pe perioada: <?= $period; ?></h3>

                    <h5>Total nr Facturi: <?= $total_no_bills; ?></h5>
                    <h5>Total nr Facturi/Ora: <?= $bills_per_hour; ?></h5>
                    <h5>Total nr Erori: <?= $total_no_errors; ?></h5>
                    <h5>Total nr Erori in %: <?= $error_percentage; ?></h5>
                    <h5>Total nr LI: <?= $total_no_li; ?></h5>
                    <h5>Timp total: <?= $total_time; ?> min</h5>
                </div>

                <div class="bs-example table-responsive">
                    <table class="table table-striped table-bordered bootstrap-datatable" id="datatable-job">

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Data</th>
                                <th>Nr Facturi</th>
                                <th>Nr Facturi/Ora</th>
                                <th>Timp Total</th>
                                <th>Nr Erori</th>
                                <th>Nr Erori in %</th>
                                <th>LI</th>
                            </tr>
                        </thead>

                        <tbody>
                        <?php $i=0; $temp_batches = $batches; foreach( $batches as $batch ): ?>
                            <?php $job_dates[$batch->job_date]++; if( $job_dates[$batch->job_date] < 2 ): ?>
                                <tr >
                                    <td> <?= ++$i; ?> </td>
                                    <?php
                                    $date = new DateTime( $batch->job_date );
                                    $date = $date->format('d.m.Y');
                                    ?>
                                    <td> <?= $date; ?> </td>

                                    <?php
                                    $no_bills_temp = 0;
                                    foreach($temp_batches as $temp_batch){
                                        if( $batch->job_date == $temp_batch->job_date ){
                                            $no_bills_temp += $temp_batch->no_bills;
                                        }
                                    }
                                    ?>
                                    <td> <?= $no_bills_temp; ?> </td>

                                    <td> <?= number_format( $no_bills_temp / 60, 2 ); ?> </td>

                                    <?php
                                    $no_errors_temp = 0;
                                    $total_time_temp = 0;
                                    foreach($temp_batches as $temp_batch){
                                        if( $batch->job_date == $temp_batch->job_date ){
                                            $no_errors_temp += $temp_batch->errors;
                                            $total_time_temp += $temp_batch->total_time;
                                        }
                                    }
                                    ?>

                                    <td> <?= $total_time_temp; ?> </td>

                                    <td> <?= $no_errors_temp; ?> </td>

                                    <td> <?= ($no_bills_temp !=0 && $no_errors_temp !=0)?number_format ( $no_errors_temp/ $no_bills_temp *100 , 2 ):0; ?> </td>

                                    <?php
                                    $no_li_temp = 0;
                                    foreach($temp_batches as $temp_batch){
                                        if( $batch->job_date == $temp_batch->job_date ){
                                            $no_li_temp += $temp_batch->li;
                                        }
                                    }
                                    ?>
                                    <td> <?= $no_li_temp; ?> </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        </tbody>

                    </table>
                </div>

            </div>
        </div>


        <script type="text/javascript">
            var job_id = '<?= $job->id; ?>';
            $(document).ready(function(){
                /* Insert a 'details' column to the table */
                var nCloneTh = document.createElement( 'th' );
                var nCloneTd = document.createElement( 'td' );
                nCloneTd.innerHTML = '<img src="/assets/libs/datatable/images/details_open.png">';
                nCloneTd.className = "center";

                $('#datatable-job thead tr').each( function () {
                    this.insertBefore( nCloneTh, this.childNodes[0] );
                } );
                $('#datatable-job tbody tr').each( function () {
                    this.insertBefore(  nCloneTd.cloneNode( true ), this.childNodes[0] );
                } );

                /* Initialse DataTables, with no sorting on the 'details' column */
                var oTable = $('#datatable-job').dataTable( {
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
                    },
                    "aoColumnDefs": [
                        { "bSortable": false, "aTargets": [ 0 ] }
                    ],
                    "aaSorting": [[1, 'asc']]
                });

                /* Add event listener for opening and closing details
                 * Note that the indicator for showing which row is open is not controlled by DataTables,
                 * rather it is done here
                 */
                $( document ).on('click', "#datatable-job tbody td img",function () {
                    var nTr = this.parentNode.parentNode;
                    if ( this.src.match('details_close') )
                    {
                        /* This row is already open - close it */
                        this.src = "/assets/libs/datatable/images/details_open.png";
                        oTable.fnClose( nTr );
                    }
                    else
                    {
                        var that = this;
                        var rowData = oTable.fnGetData( nTr );
                        var job_date = rowData[2];

                        var innerTable = '<div class="col-lg-1"></div>' +
                            '<div class="col-lg-10">' +
                                '<table class="table table-striped table-bordered table-condensed report-inner-table">' +
                                    '<thead>' +
                                        '<tr>' +
                                            '<th>Utilizator</th>' +
                                            '<th>Nr Facturi</th>' +
                                            '<th>Nr Facturi/Ora</th>' +
                                            '<th>Timp Total</th>' +
                                            '<th>Nr Erori</th>' +
                                            '<th>Nr Erori in %</th>' +
                                            '<th>LI</th>' +
                                        '</tr>' +
                                    '</thead>' +
                                    '<tbody>';

                        // go ajax
                        var url = '/get-batches-by-job-date';
                        var data = 'job_date='+job_date+'&job_id='+job_id;
                        $.ajax({
                            type: "POST",
                            url: url,
                            async: true,
                            data: data,
                            dataType: "json",
                            beforeSend: function(x) {},
                            success: function( data, textStatus, jqXHR ){
                                if( data.status == 1 )
                                {
                                    $.each(data.batches, function( index, batch ) {
                                        innerTable += '<tr>' +
                                            '<td>'+ batch.user_name +'</td>' +
                                            '<td>'+ batch.no_bills +'</td>' +
                                            '<td>'+ (batch.no_bills / 60).toFixed(2) +'</td>' +
                                            '<td>'+ batch.total_time +'</td>' +
                                            '<td>'+ batch.errors +'</td>' +
                                            '<td>'+ (batch.errors / batch.no_bills * 100).toFixed(2) +'</td>' +
                                            '<td>'+ batch.li +'</td>' +
                                            '</tr>';
                                    });

                                    innerTable += '</tbody>' +
                                        '</table></div><div class="col-lg-1"></div>';

                                    /* Open this row */
                                    that.src = "/assets/libs/datatable/images/details_close.png";
                                    oTable.fnOpen( nTr, innerTable, 'details' );
                                }
                            },
                            error: function( jqXHR, textStatus, errorThrown ){},
                            complete: function( jqXHR, textStatus ){}
                        }); // the end
                    }
                }); /* ends ajax call */

            }); /* ends doc.ready */
        </script>
    <?php endif; ?>

</div>
