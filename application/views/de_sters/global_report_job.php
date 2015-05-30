<!-- jqplot plugin -->
<script type="text/javascript" src='/assets/js/jquery.jqplot.min.js'></script>
<script type="text/javascript" src="/assets/js/jqplot.barRenderer.min.js"></script>
<script type="text/javascript" src="/assets/js/jqplot.categoryAxisRenderer.min.js"></script>
<script type="text/javascript" src="/assets/js/jqplot.pointLabels.min.js"></script>

<!-- Add errors
================================================== -->
<div class="bs-docs-section">
    <div class="row">
        <div class="col-lg-3"></div>
        <div class="col-lg-6">
            <div class="well">
                <form class="bs-example form-horizontal report-form" action="/report/global-job" method="post">
                    <fieldset>
                        <legend>Rapoarte grafice per job</legend>

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

    <?php if( isset($job) && isset($batches) && !empty($batches) && ($batches->count() > 0) ): ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="page-header">
                    <h3 id="tables" class="cmp-title">Raport de activitate pentru `<?= $job->name; ?>` pe perioada: <?= $period; ?></h3>
                    <!--<label>Alegeti job pentru comparatie: </label>-->
                    <br>
                    <select id="compare-jobs" style="padding: 7px 7px 8px 7px;">
                        <option>Selectare Job</option>
                        <?php foreach( $clients as $client ): ?>
<!--                            --><?php /*if($client->id != $job->id): */?>
                                <option value="<?= $client->id; ?>"><?= $client->name; ?></option>
<!--                            --><?php /*endif; */?>
                        <?php endforeach; ?>
                    </select>
                    <input type="text" id="cmp_date_from" placeholder="De la data..">
                    <input type="text" id="cmp_date_to" placeholder="Pana la data..">
                    <input type="text" id="cmp_month_year" placeholder="Luna si an..">
                    <input type="text" id="cmp_year" placeholder="An..">
                    <button id="cmp_submit_btn" class="btn btn-primary btn-sm" style="height: 38px;margin-bottom: 2px;">Compara</button>

                    <div id="cmp" style="display: none;">
                        <?php if( isset($current_date_from) && isset($current_date_to) ): ?>
                            <input type="text" id="current_date_from" value="<?= $current_date_from; ?>">
                            <input type="text" id="current_date_to" value="<?= $current_date_to; ?>">
                        <?php elseif( isset($current_month_year) ): ?>
                            <input type="text" id="current_month_year" value="<?= $current_month_year; ?>">
                        <?php elseif( isset($current_year) ): ?>
                            <input type="text" id="current_year" value="<?= $current_year; ?>">
                        <?php endif; ?>
                        <input type="hidden" id="cmp_job_id" value="<?= $job->id; ?>">
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div id="chart1" style="height: 700px;"></div>
            </div>
        </div>


        <?php
            // $job, $period, $batches
            $users = array();
            foreach( $batches as $batch ){
                //$client = new Client( $batch->client_id );
                if( !empty($users[ $batch->user_id ]) )
                {
                    $users[ $batch->user_id ] = 0;
                }
                else
                {
                    $users[ $batch->user_id ] = $batch->no_bills;
                }
            }
        ?>

        <?php if( !empty( $users ) ): ?>
            <script type="text/javascript" class="code">
                var users_bills = [
                    <?php
                        foreach( $users as $no_bills )
                        {
                            echo "$no_bills,";
                        }
                    ?>
                ];

                var max_y = <?= max( $users ); ?>;

                var ticks = [
                    <?php
                        foreach( $users as $user_id => $no_bills )
                        {
                            $usr = new User( $user_id );
                            echo "'" . $usr->fname . " " . $usr->lname . "',";
                        }
                    ?>
                ];

                var series = [
                    <?php
                        foreach( $users as $user_id => $no_bills )
                        {
                            $usr = new User( $user_id );
                            echo "{label:'" . $usr->fname . " " . $usr->lname . "'},";
                        }
                    ?>
                ];
            </script>


            <script type="text/javascript" class="code">
                $(document).ready(function(){
                    var plot1 = $.jqplot('chart1', [users_bills], {
                        seriesDefaults:{
                            renderer:$.jqplot.BarRenderer,
                            rendererOptions: {fillToZero: true, barWidth: 30, varyBarColor: true},
                            pointLabels: { show: true },
                        },
                        axes: {
                            // Use a category axis on the x axis and use our custom ticks.
                            xaxis: {
                                renderer: $.jqplot.CategoryAxisRenderer,
                                ticks: ticks
                            },
                            yaxis: {
                                min: 0,
                                max: format_maximum( max_y ),
                                tickOptions: {formatString: '%d'}
                            }
                        },
                    });

                    /* comparison dates */
                    var cmp_date_from = $('#cmp_date_from');
                    var cmp_date_to = $('#cmp_date_to');
                    var cmp_month_year = $('#cmp_month_year');
                    var cmp_year = $('#cmp_year');
                    cmp_date_from.datepicker({
                            "dateFormat": "dd.mm.yy",
                            "changeMonth": true,
                            "changeYear": true,
                            yearRange: "-100:+0"
                        }
                    );
                    cmp_date_to.datepicker({
                            "dateFormat": "dd.mm.yy",
                            "changeMonth": true,
                            "changeYear": true,
                            yearRange: "-100:+0"
                        }
                    );
                    cmp_month_year.datepicker({
                            "dateFormat": "mm.yy",
                            "changeDay": false,
                            "changeMonth": true,
                            "changeYear": true,
                            yearRange: "-100:+0"
                        }
                    );
                    cmp_year.datepicker({
                            "dateFormat": "yy",
                            "changeDay": false,
                            "changeMonth": false,
                            "changeYear": true,
                            yearRange: "-100:+0"
                        }
                    );

                    // changed from date
                    cmp_date_from.change(function(event){
                        cmp_month_year.val('');
                        cmp_year.val('');

                    });
                    // changed to date
                    cmp_date_to.change(function(event){
                        cmp_month_year.val('');
                        cmp_year.val('');
                    });
                    // changed month year
                    cmp_month_year.change(function(event){
                        cmp_date_from.val('');
                        cmp_date_to.val('');
                        cmp_year.val('');
                    });
                    // changed year
                    cmp_year.change(function(event){
                        cmp_date_from.val('');
                        cmp_date_to.val('');
                        cmp_month_year.val('');
                    });



                });


                /* If the user changes comparison select execute ajax to get data.
                 *
                 * */
                $('#cmp_submit_btn').click(function(){
                    var current_job_id = $('#cmp #cmp_job_id').val();
                    var new_job_id = $('#compare-jobs').val();
                    /* comparison period */
                    var cmp_date_from = $('#cmp_date_from').val();
                    var cmp_date_to = $('#cmp_date_to').val();
                    var cmp_month_year = $('#cmp_month_year').val();
                    var cmp_year = $('#cmp_year').val();
                    /* current period */
                    var current_date_from = $('#current_date_from').val();
                    var current_date_to = $('#current_date_to').val();
                    var current_month_year = $('#current_month_year').val();
                    var current_year = $('#current_year').val();

                    if( $.isNumeric(new_job_id) && ( (cmp_date_from && cmp_date_from) || cmp_month_year || cmp_year)  )
                    {
                        var data = 'new_job_id='+new_job_id+'&current_job_id='+current_job_id;
                        /* comparison period */
                        if( cmp_date_from && cmp_date_to )
                        {
                            data += '&cmp_date_from='+cmp_date_from+'&cmp_date_to='+cmp_date_to;
                        }
                        else if( cmp_month_year )
                        {
                            data += '&cmp_month_year='+cmp_month_year;
                        }
                        else if( cmp_year )
                        {
                            data += '&cmp_year='+cmp_year;
                        }
                        /* current_period */
                        if( current_date_from && current_date_to )
                        {
                            data += '&current_date_from='+current_date_from+'&current_date_to='+current_date_to;
                        }
                        else if( current_month_year )
                        {
                            data += '&current_month_year='+current_month_year;
                        }
                        else if( current_year )
                        {
                            data += '&current_year='+current_year;
                        }

                        var url = '/report/load-jobs-for-comparison';
                        var message_fail = 'Eroare! Serverul nu a returnat job-urile.';

                        $.ajax({
                            type: "POST",
                            url: url,
                            async: true,
                            dataType: "json",
                            data: data,
                            beforeSend: function(x) {
                                // do stuff before the request is sent
                                $('#la-anim-1').addClass('la-animate');
                            },
                            success: function( data, textStatus, jqXHR ){
                                if( data.status == 1 )
                                {
                                    //console.log( data );

                                    var current_operators = data.operators.current;
                                    var new_operators = data.operators.new;
                                    var current_job_name = data.current_job_name;
                                    var new_job_name = data.new_job_name;
                                    var current_period = data.current_period;
                                    var cmp_period = data.cmp_period;
                                    if( new_operators )
                                    {
                                        $("#tables.cmp-title").html('Raport de activitate pentru `'+current_job_name+'` pe perioada: '+current_period+' & `'+new_job_name+'` pe perioada: '+ cmp_period);
                                        redraw_plot(current_operators, new_operators, current_job_name, new_job_name);
                                    }
                                    else
                                    {
                                        alert('Nu exista activitate pentru job-ul `'+new_job_name+'` pe perioada '+period);
                                    }
                                }
                                else
                                {
                                    alert( message_fail );
                                }
                            },
                            error: function( jqXHR, textStatus, errorThrown ){
                                alert( message_fail );
                            },
                            complete: function( jqXHR, textStatus ){
                                // do stuff when the request is finished
                                setTimeout( function() {
                                    $('#la-anim-1').removeClass('la-animate');
                                }, 2000 );
                            }
                        }); // the end

                    }
                    else
                    {
                        alert('Trebuie sa alegeti un Job si o perioada (Intre data - pana la data / An si luna / An).');
                    }
                });

                function format_maximum( max ){
                    if( max > 10000 )
                    {
                        return Math.ceil( max / 10000 ) * 10000;
                    }
                    else if( max > 1000 )
                    {
                        return Math.ceil( max / 1000 ) * 1000;
                    }
                    else if( max > 100 )
                    {
                        return Math.ceil( max / 100 ) * 100;
                    }
                    else
                    {
                        return max;
                    }
                }

                function redraw_plot( current_operators, new_operators , current_job_name, new_job_name)
                {
                    $('#chart1').html('');
                    var operators = [];
                    var bills = [];
                    var max_y = 0;
                    $.each( current_operators, function( name, no_bills) {
                        operators.push( {label:name} );
                        var new_no_bills = (new_operators[name])?new_operators[name]:0;
                        if( Math.max(no_bills, new_no_bills) > max_y ){ max_y = Math.max(no_bills, new_no_bills); }
                        bills.push( [no_bills, new_no_bills] );
                    });
                    $.each( new_operators, function( name, no_bills){
                        if( !(name in current_operators) ){
                            operators.push( {label:name} );
                            var current_no_bills = (current_operators[name])?current_operators[name]:0;
                            if( Math.max(no_bills, current_no_bills) > max_y ){ max_y = Math.max(no_bills, current_no_bills); }
                            bills.push( [ current_no_bills, no_bills] );
                        }
                    });

                    var ticks = [current_job_name, new_job_name];

                    var plot1 = $.jqplot('chart1', bills, {
                        seriesDefaults:{
                            renderer:$.jqplot.BarRenderer,
                            rendererOptions: {fillToZero: true, barWidth: 30},
                            pointLabels: { show: true },
                        },
                        series: operators,
                        legend: {
                            show: true,
                            placement: 'outsideGrid'
                        },
                        axes: {
                            xaxis: {
                                renderer: $.jqplot.CategoryAxisRenderer,
                                ticks: ticks
                            },
                            yaxis: {
                                min: 0,
                                max: format_maximum( max_y ),
                                tickOptions: {formatString: '%d'},
                                /*autoscale: true*/
                            }
                        }
                    });

/*                    var minY = plot1.axes.yaxis._dataBounds.min;
                    var maxY = plot1.axes.yaxis._dataBounds.max;

                    plot1.axes.yaxis.min = minY;
                    plot1.axes.yaxis.max = maxY;
                    plot1.replot();*/
                }


            </script>
        <?php endif; ?>
    <?php endif; ?>

</div>
