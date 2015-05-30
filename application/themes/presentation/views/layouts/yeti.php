<?php $hide_me = isset($user) && $user->exists(); ?>
<!DOCTYPE html>
<html lang="en">

<!-- the head -->
<head>
    <?= $template['partials']['head']; ?>
</head>
<!-- ends head -->

<!-- body -->
<body>
<!-- youtube loading effect -->
<div class="la-anim-1" id="la-anim-1"></div>

        <?= $template['partials']['header']; ?>

        <div class="container-fluid" style="padding: 0 30px; padding-top: 60px; min-height: 586px;">
            <!-- js disabled -->
            <noscript>
                <div class="alert alert-block span10">
                    <h4 class="alert-heading">Warning!</h4>
                    <p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a> enabled to use this site.</p>
                </div>
            </noscript>            
                   
            <?= $template['body']; ?>

        </div><!--/.fluid-container-->

        <?= $template['partials']['footer']; ?>

    <script src="/assets/js/bootstrap.min.js"></script>
    <!-- jquery UI library -->
    <script src="/assets/libs/jquery-ui-1.10.4/js/jquery-ui-1.10.4.custom.min.js"></script>
    <script src="/assets/js/jquery.cookie.js"></script>
    <script src="/assets/js/wmt.js"></script>
</body>
</html>