<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid" style="padding: 0 30px">
        <div class="navbar-header">
            <a href="/" class="navbar-brand">Work Management Tool</a>
        </div>

        <div class="navbar-collapse collapse" id="navbar-main">

            <?php if(isset($user)): ?>
                <ul class="nav navbar-nav">
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown">Activitate <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a tabindex="-1" href="/">Introducere Activitate zilnica</a></li>
                            <li><a tabindex="-1" href="/activity/offline">Introducere Activitate offline</a></li>
                            <li><a tabindex="-1" href="/activity/list">Listare activitate</a></li>
                        </ul>
                    </li>
                </ul>

                <?php if( $user->role == 'MANAGER' ): ?>

                <ul class="nav navbar-nav">
                    <li>
                        <a href="/activity/errors" id="themes">Introducere erori</a>
                    </li>
                </ul>

                <ul class="nav navbar-nav">
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown">Rapoarte <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a tabindex="-1" href="/report/user">Tabelare per user</a></li>
                            <li><a tabindex="-1" href="/report/job">Tabelare per job</a></li>
                            <li class="divider"></li>
                            <li><a tabindex="-1" href="/report/global-user">Grafice per user</a></li>
                            <li><a tabindex="-1" href="/report/global-job">Grafice per job</a></li>
                        </ul>
                    </li>
                </ul>

                <ul class="nav navbar-nav">
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown">Setari <span class="caret"></span></a>
                        <ul class="dropdown-menu" aria-labelledby="themes">
                            <li><a tabindex="-1" href="/settings/add">Adaugare utilizatori</a></li>
                            <li><a tabindex="-1" href="/settings">Listare utilizatori</a></li>
                            <li class="divider"></li>
                            <li><a tabindex="-1" href="/jobs/add">Adaugare Joburi</a></li>
                            <li><a tabindex="-1" href="/jobs">Listare Joburi</a></li>
                        </ul>
                    </li>
                </ul>

                <?php endif; ?>
            <?php endif; ?>

            <?php if(isset($user) && $user->exists()): ?>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><?= $user->fname . ' ' . $user->lname . ' (' . $user->role . ')' ; ?> <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a tabindex="-1" href="/account/change-password">Schimbare parola</a></li>
                            <li><a tabindex="-1" href="/logout">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            <?php endif; ?>

        </div>
    </div>
</div>