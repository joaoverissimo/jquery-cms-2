<header class="page-header">
    <h2><?php echo $pageVars["pageTitle"]; ?></h2>

    <div class="right-wrapper ">
        <ol class="breadcrumbs" style=" margin-right: 10px; ">
            <li>
                <a href="<?php echo $adm_folder; ?>">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <?php if (issetArray($pageVars["nav-breadcrumbs"])) : ?>
                <?php foreach ($pageVars["nav-breadcrumbs"] as $pageVarsUrlKey => $pageVarsUrlHef) : ?>
                    <li>
                        <a href="<?php echo $pageVarsUrlHef; ?>">
                            <span><?php echo $pageVarsUrlKey; ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
                <li>
                    <span>
                        <?php echo $pageVars["pageAction"]; /* home > contas a pagar > editar */ ?>
                    </span>
                </li>
            <?php else: ?>
                <li>
                    <span>
                        <?php echo $pageVars["pageTitle"]; /* home > contas a pagar */ ?>
                    </span>
                </li>
            <?php endif; ?>

        </ol>

        <?php if (false): ?><a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a><?php endif; ?>
    </div>
</header>