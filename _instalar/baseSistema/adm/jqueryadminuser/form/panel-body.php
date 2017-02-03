<div class="panel-body">
    <?php if ($dados !== false) : ?>
        <form method="post" action="deletar-multi.php" id="form-grid">
            <table class="table table-hover table-striped" id="tablelista">
                <thead>
                    <tr>
                        <th class="th-multi"></th>
                        <th><?php echo __('jqueryadminuser.nome'); ?></th> 
                        <th><?php echo __('jqueryadminuser.mail'); ?></th> 
                        <th><?php echo __('jqueryadminuser.grupo'); ?></th> 

                        <th class="th-actions"></th>
                    </tr>
                </thead>
                <?php foreach ($dados as $registro) : ?>
                    <tr>
                        <td><label><input type="checkbox" name="multi[]" value="<?php echo $registro->getCod(); ?>" class="multi-input" /></td>
                        <td><?php echo $registro->getNome(); ?></td>
                        <td><?php echo $registro->getMail(); ?></td>
                        <td><?php echo $registro->objGrupo()->getTitulo(); ?></td>

                        <td>
                            <div class="btn-group">
                                <a class="btn btn-primary" href="editar.php?cod=<?php echo $registro->getCod(); ?>"><i class="fa fa-pencil"></i> <?php echo __('editar'); ?></a>
                                <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="deletar.php?cod=<?php echo $registro->getCod(); ?>"><i class="fa fa-trash-o"></i> <?php echo __('deletar'); ?></a></li>
                                </ul>
                            </div>  
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <div class="multi-actions">
                <label class="text-muted">
                    <input type="checkbox" id="multi_all" /> Selecionar/Desselecionar
                </label> 

                <div class="btn-group btn-group-xs dropup" id="btn-group-selecionados">
                    <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#" onclick="javascript: void(0);">
                        Com os selecionados...
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="#" data-action="deletar-multi.php"><i class="fa fa-trash-o"></i> Deletar</a></li>
                    </ul>
                </div>
            </div>
        </form>
    <?php else : ?>
        Não existem registros.
    <?php endif; ?>

    <?php echo $pager->getPager(); ?>
</div>