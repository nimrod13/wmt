<!-- jqplot plugin -->
<script language="javascript" type="text/javascript" src='/assets/js/jquery.jqplot.min.js'></script>
<script language="javascript" type="text/javascript" src="/assets/js/jqplot.canvasTextRenderer.min.js"></script>
<script language="javascript" type="text/javascript" src="/assets/js/jqplot.canvasAxisTickRenderer.min.js"></script>
<script language="javascript" type="text/javascript" src="/assets/js/jqplot.dateAxisRenderer.min.js"></script>
<script language="javascript" type="text/javascript" src="/assets/js/jqplot.cursor.min.js"></script>

<!-- Chart report
================================================== -->
<div class="bs-docs-section">
    <div class="row">
        <div class="col-lg-3"></div>
        <div class="col-lg-6">
            <div class="well">

                <?php if( isset($error) && !empty($error) && $error == true ): ?>
                    <div class="alert alert-dismissable alert-danger">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>Eroare!</strong> <?= $message; ?>
                    </div>
                <?php endif; ?>

                <?php
                $errors = validation_errors();
                if( isset($errors) && !empty($errors) ): ?>
                    <div class="alert alert-dismissable alert-danger">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>Eroare!</strong> Alegeti un utilizator, un job si selectati un interval!
                    </div>
                <?php endif; ?>

                <form class="bs-example form-horizontal report-form global-report" action="/report/global-user" method="post">
                    <fieldset>
                        <legend>Rapoarte grafice per utilizator</legend>

                        <div class="form-group star <?php if( form_error("user") && form_error("user") != NULL ) echo 'has-error'; ?>">
                            <label for="user" class="col-lg-2 control-label">Utilizator</label>
                            <div class="col-lg-10">
                                <select class="form-control" id="user" name="user">
                                    <option>Selectare utilizator</option>
                                    <?php foreach( $users as $user ): ?>
                                        <option value="<?= $user->id; ?>"><?= $user->fname . ' ' . $user->lname; ?></option>
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

                        <div class="form-group star gru-job-wrapper" style="display: none;">
                            <label for="job" class="col-lg-2 control-label">Job</label>
                            <div class="col-lg-10">
                                <select class="form-control" id="job" name="job"></select>
                            </div>
                        </div>

                        <p>  </p>

                        <div class="form-group">
                            <div class="col-lg-10 col-lg-offset-2">
                                <button type="submit" class="btn btn-primary disabled">Trimite</button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
        <div class="col-lg-3"></div>
    </div>

    <?php if( isset($usr) && isset($batches) ): ?>
    <div id="gu-graph-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-header">
                    <h3 id="tables">Raport de activitate pentru `<?= $usr->lname . ' ' . $usr->fname; ?>` pe perioada: <?= $period; ?></h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div id="chart1" style="height: 700px;"></div>
            </div>
        </div>

        <?php if($method === 'days'): ?>

            <script type="text/javascript" class="code">
                var dateFormat = '%d.%m.%Y';
                var line1=[
                    <?php $max_y = 0; foreach( $batches as $batch ): ?>
                        <?php
                            if( $batch->no_bills > $max_y ){ $max_y = $batch->no_bills; }
                            echo "['$batch->job_date', $batch->no_bills],";
                            ?>
                    <?php endforeach; ?>
                    ];

                var line2=[
                    <?php foreach( $batches as $batch ): ?>
                    <?php echo "['$batch->job_date', $batch->errors],";?>
                    <?php endforeach; ?>
                ];
            </script>

        <?php elseif( $method === 'months' ): ?>
            <?php
                $b_bills = array();
                $b_errors = array();
                foreach( $batches as $batch ){
                    $job_date = substr($batch->job_date, 0, 7);
                    $b_bills[ $job_date ] = 0;
                    $b_errors[ $job_date ] = 0;
                }
                foreach( $batches as $batch ){
                    $job_date = substr($batch->job_date, 0, 7);
                    $b_bills[ $job_date ] += $batch->no_bills;
                    $b_errors[ $job_date ] += $batch->errors;
                }
            ?>

            <script type="text/javascript" class="code">
                var dateFormat = '%m.%Y';
                var line1=[
                    <?php foreach( $b_bills as $date => $no_bills ): ?>
                    <?php echo "['$date', $no_bills],";?>
                    <?php endforeach; ?>
                ];

                var line2=[
                    <?php foreach( $b_errors as $date => $no_errors ): ?>
                    <?php echo "['$date', $no_errors],";?>
                    <?php endforeach; ?>
                ];
                <?php $max_y = max($b_bills); ?>

            </script>

        <?php endif; ?>

        <?php
            if( $job === false )
            {
                $client_name = 'Toate joburile';
            }
            else
            {
                $client_name = $job->name;
            }
        ?>

        <script type="text/javascript" class="code">

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
                    return max + 10;
                }
            }

            $(document).ready(function(){
                var max_y = <?= $max_y; ?>;
                // Enable plugins like highlighter and cursor by default.
                // Otherwise, must specify show: true option for those plugins.
                $.jqplot.config.enablePlugins = true;

                var plot1 = $.jqplot('chart1', [line1, line2], {
                    title: '<?= $client_name; ?>',
                    axes:{
                        xaxis:{
                            renderer:$.jqplot.DateAxisRenderer,
                            rendererOptions:{
                                tickRenderer:$.jqplot.CanvasAxisTickRenderer
                            },
                            tickOptions:{
                                fontSize:'10pt',
                                fontFamily:'Tahoma',
                                angle:-40,
                                formatString: dateFormat
                            }
                        },
                        yaxis:{
                            min: 0,
                            /*                            max: format_maximum( max_y ),*/
                            rendererOptions:{
                                tickRenderer:$.jqplot.CanvasAxisTickRenderer},
                            tickOptions:{
                                fontSize:'10pt',
                                fontFamily:'Tahoma',
                                angle:30,
                                formatString: '%d'
                            }
                        }
                    },
                    series:[
                        {
                            lineWidth:4, markerOptions:{ style:'square' }
                        },
                        {
                            // Change our line width and use a diamond shaped marker.
                            lineWidth:2,
                            markerOptions: { style:'dimaond' }
                        }
                    ],
                    cursor:{
                        zoom:true,
                        looseZoom: true
                    }
                });

            });
        </script>
    </div>
    <?php endif; ?>



</div>


