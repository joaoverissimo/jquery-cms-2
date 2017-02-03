<div class="panel-body">
    <?php if ($dados !== false) : ?>
        <form id="form-grid" method="post" action="deletar-multi.php">
            <table class="table table-hover table-striped" id="tablelista">
                <thead>
                    <tr>
                        <th class="th-multi"></th>
                        <th><?php echo __('jqueryadmingrupo.titulo'); ?></th> 

                        <th class="th-actions" style="width: 150px;"></th>
                    </tr>
                </thead>
                <?php foreach ($dados as $registro) : ?>
                    <tr>
                        <td><label><input type="checkbox" name="multi[]" value="<?php echo $registro->getCod(); ?>" class="multi-input" /></td>
                        <td><?php echo $registro->getTitulo(); ?></td>

                        <td>
                            <div class="btn-group">
                                <a class="btn btn-primary" href="permissoes.php?cod=<?php echo $registro->getCod(); ?>"><i class="icon-th-large icon-white"></i> <?php echo __('permissoes'); ?></a>
                                <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="editar.php?cod=<?php echo $registro->getCod(); ?>"><i class="fa fa-pencil"></i> <?php echo __('editar'); ?></a>
                                    <li><a href="deletar.php?cod=<?php echo $registro->getCod(); ?>"><i class="fa fa-trash-o"></i> <?php echo __('deletar'); ?></a></li>
                                </ul>
                            </div>  
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            
            <?php include '../lib/masterpage/page-multi-actions-default.php'; ?>
        </form>
    <?php else : ?>
        Não existem registros.
    <?php endif; ?>


    <?php echo $pager->getPager(); ?>

</div>