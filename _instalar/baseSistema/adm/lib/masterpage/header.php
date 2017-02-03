<?php
/* @var $currentUser objJqueryadminuser */

$headerDadosTutoriais = dataExecSqlDireto($Conexao, "SELECT cod, versao, tipo, titulo, link, data FROM  zchangelog WHERE  link !=  '' and (data BETWEEN NOW() - INTERVAL 15 DAY AND NOW()) LIMIT 0 , 30");
$headerDadosVersoes = dataExecSqlDireto($Conexao, "SELECT cod, versao, tipo, titulo, link, data FROM zchangelog WHERE versao = (select versao from  zchangelog where cod= (select max(cod) from zchangelog)) ORDER BY COD DESC LIMIT 0,5");
$headerDadosMaxVersao = dataExecSqlDireto($Conexao, "SELECT max(cod) as rt FROM zchangelog", false);
?>
<header class="header">
    <div class="logo-container">
        <?php include 'logo.php'; ?>
        <div class="visible-xs toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
            <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
        </div>
    </div>

    <!-- start: search & user box -->
    <div class="header-right">

        <?php if (false): ?>
            <form action="http://preview.oklerthemes.com/porto-admin/1.5.1/pages-search-results.html" class="search nav-form">
                <div class="input-group input-search">
                    <input type="text" class="form-control" name="q" id="q" placeholder="Search...">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
                    </span>
                </div>
            </form>
        <?php endif; ?>

        <span class="separator"></span>

        <ul class="notifications">
            <li id="notifications-tutoriais" data-versao="<?php echo $headerDadosMaxVersao['rt']; ?>">
                <a href="#" class="dropdown-toggle notification-icon" data-toggle="dropdown">
                    <i class="fa fa-play"></i>
                    <?php if (issetArray($headerDadosTutoriais)): ?>
                        <span class="badge"><?php echo count($headerDadosTutoriais); ?></span>
                    <?php endif; ?>
                </a>

                <div class="dropdown-menu notification-menu large">
                    <div class="notification-title">
                        <span class=" label label-default"><?php echo issetArray($headerDadosTutoriais) ? count($headerDadosTutoriais) : ""; ?></span>
                        Últimos tutoriais
                    </div>

                    <div class="content">
                        <ul>
                            <?php if (issetArray($headerDadosTutoriais)): ?>
                                <?php foreach ($headerDadosTutoriais as $headerRegistroTutoriais) : ?>
                                    <li>
                                        <p class="clearfix mb-xs">
                                            <span class="message"><?php echo $headerRegistroTutoriais['titulo']; ?></span>
                                            <span class="message  text-dark">
                                                <a target="_blank" href="<?php echo $headerRegistroTutoriais['link']; ?>">[assistir]</a>
                                            </span>
                                        <hr />
                                        </p>
                                    </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li>
                                    <p class="clearfix mb-xs">
                                        <span class="message">Nenhum tutorial recente</span>
                                        <span class="message  text-dark">
                                            <a href="/adm/login/changelog.php">Ver todos</a>
                                        </span>
                                    </p>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </li>

            <?php if (false): ?>
                <li>
                    <a href="#" class="dropdown-toggle notification-icon" data-toggle="dropdown">
                        <i class="fa fa-envelope"></i>
                        <span class="badge">4</span>
                    </a>

                    <div class="dropdown-menu notification-menu">
                        <div class="notification-title">
                            <span class=" label label-default">230</span>
                            Messages
                        </div>

                        <div class="content">
                            <ul>
                                <li>
                                    <a href="#" class="clearfix">
                                        <figure class="image">
                                            <img src="/jquerycms/js/temp/!sample-user.jpg" alt="Joseph Doe Junior" class="img-circle">
                                        </figure>
                                        <span class="title">Joseph Doe</span>
                                        <span class="message">Lorem ipsum dolor sit.</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="clearfix">
                                        <figure class="image">
                                            <img src="/jquerycms/js/temp/!sample-user.jpg" alt="Joseph Junior" class="img-circle">
                                        </figure>
                                        <span class="title">Joseph Junior</span>
                                        <span class="message truncate">Truncated message. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sit amet lacinia orci. Proin vestibulum eget risus non luctus. Nunc cursus lacinia lacinia. Nulla molestie malesuada est ac tincidunt. Quisque eget convallis diam, nec venenatis risus. Vestibulum blandit faucibus est et malesuada. Sed interdum cursus dui nec venenatis. Pellentesque non nisi lobortis, rutrum eros ut, convallis nisi. Sed tellus turpis, dignissim sit amet tristique quis, pretium id est. Sed aliquam diam diam, sit amet faucibus tellus ultricies eu. Aliquam lacinia nibh a metus bibendum, eu commodo eros commodo. Sed commodo molestie elit, a molestie lacus porttitor id. Donec facilisis varius sapien, ac fringilla velit porttitor et. Nam tincidunt gravida dui, sed pharetra odio pharetra nec. Duis consectetur venenatis pharetra. Vestibulum egestas nisi quis elementum elementum.</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="clearfix">
                                        <figure class="image">
                                            <img src="/jquerycms/js/temp/!sample-user.jpg" alt="Joe Junior" class="img-circle">
                                        </figure>
                                        <span class="title">Joe Junior</span>
                                        <span class="message">Lorem ipsum dolor sit.</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="clearfix">
                                        <figure class="image">
                                            <img src="/jquerycms/js/temp/!sample-user.jpg" alt="Joseph Junior" class="img-circle">
                                        </figure>
                                        <span class="title">Joseph Junior</span>
                                        <span class="message">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sit amet lacinia orci. Proin vestibulum eget risus non luctus. Nunc cursus lacinia lacinia. Nulla molestie malesuada est ac tincidunt. Quisque eget convallis diam.</span>
                                    </a>
                                </li>
                            </ul>

                            <hr>

                            <div class="text-right">
                                <a href="#" class="view-more">View All</a>
                            </div>
                        </div>
                    </div>
                </li>
            <?php endif; ?>

            <li id="notifications-versoes" data-versao="<?php echo $headerDadosMaxVersao['rt']; ?>">
                <a href="#" class="dropdown-toggle notification-icon" data-toggle="dropdown">
                    <i class="fa fa-server"></i>
                    <?php if (issetArray($headerDadosVersoes)): ?>
                        <span class="badge"><?php echo count($headerDadosVersoes); ?></span>
                    <?php endif; ?>
                </a>

                <div class="dropdown-menu notification-menu">
                    <div class="notification-title">
                        <span class=" label label-default"><?php echo count($headerDadosVersoes); ?></span>
                        Histórico de Versões
                    </div>

                    <div class="content">
                        <ul>
                            <?php if (issetArray($headerDadosVersoes)): ?>
                                <?php foreach ($headerDadosVersoes as $headerRegistroVersoes) : ?>
                                    <li>
                                        <a href="/adm/login/changelog.php" class="clearfix">
                                            <div class="image">
                                                <i class="fa fa-server <?php
                                                if ($headerRegistroVersoes['tipo'] == 'I') {
                                                    echo "bg-success";
                                                } elseif ($headerRegistroVersoes['tipo'] == 'A') {
                                                    echo "bg-warning";
                                                } elseif ($headerRegistroVersoes['tipo'] == 'C') {
                                                    echo "bg-danger";
                                                }
                                                ?>"></i>
                                            </div>
                                            <span class="title"><?php echo $headerRegistroVersoes['titulo']; ?></span>
                                            <span class="message"><?php echo $headerRegistroVersoes['versao']; ?> - <?php echo Fncs_LerData($headerRegistroVersoes['data']); ?></span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>                            
                            <?php endif; ?>

                            <hr>

                            <div class="text-right">
                                <a href="/adm/login/changelog.php" class="view-more">Histórico de verões</a>
                            </div>
                    </div>
                </div>
            </li>
        </ul>

        <span class="separator"></span>

        <?php if (isset($currentUser)) : ?>
            <div id="userbox" class="userbox">
                <a href="#" data-toggle="dropdown">
                    <figure class="profile-picture">
                        <span class="img-circle"><i class="fa fa-user" style="font-size: 25px;color: #777777;"></i></span>
                    </figure>
                    <div class="profile-info">
                        <span class="name"><?php echo $currentUser->getNome(); ?></span>
                        <span class="role"><?php echo $currentUser->objGrupo()->getTitulo(); ?></span>
                    </div>

                    <i class="fa custom-caret"></i>
                </a>

                <div class="dropdown-menu">
                    <ul class="list-unstyled">
                        <li class="divider"></li>
                        <li>
                            <a role="menuitem" tabindex="-1" href="<?php echo $adm_folder; ?>/home/minha-conta.php"><i class="fa fa-user-secret"></i> Minha conta</a>
                        </li>
                        <li>
                            <a role="menuitem" tabindex="-1" href="<?php echo $adm_folder; ?>/login/logout.php"><i class="fa fa-power-off"></i> Sair</a>
                        </li>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <!-- end: search & user box -->
</header>
