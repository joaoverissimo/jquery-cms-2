<?php /* @var $objAtendimentoInteracao objAtendimentoInteracao */ ?>

<div class="interacao-agenda-item" id="interacao-agenda-item-<?php echo $objAtendimentoInteracao->getCod(); ?>">
    <div class="botoes">
        <div class="btn-group btn-group-sm">
            <a class="btn btn-default dropdown-toggle btn-acoes" data-toggle="dropdown" href="#" onclick="javascript: void(0);" aria-expanded="false">
                Ações
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-right">
                <li class="btn-abrir">
                    <a href="/adm/atendimento/editar.php?cod=<?php echo $objAtendimentoInteracao->getAtendimento(); ?>">
                        <i class="fa fa-list-alt"></i> Abrir o atendimento
                    </a>
                </li>
                <li class="btn-realizado">
                    <a href="/adm/atendimento/interacao-realizado.php?interacao=<?php echo $objAtendimentoInteracao->getCod(); ?>&tema=branco" rel="iFrameNoReload">
                        <i class="fa fa-check-circle-o"></i> Marcar como realizado
                    </a>
                </li>
                <li class="btn-reagendar">
                    <a href="/adm/atendimento/interacao-reagendar.php?interacao=<?php echo $objAtendimentoInteracao->getCod(); ?>&tema=branco" rel="iFrameNoReload">
                        <i class="fa fa-clock-o"></i> Re-agendar tarefa
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="data-horario">
        <span rel="tooltip" title="<?php echo $objAtendimentoInteracao->objAtendimentoInteracoesTipo()->getDescricao(); ?>">
            <i class="<?php echo $objAtendimentoInteracao->objAtendimentoInteracoesTipo()->getIcon(); ?>"></i>
        </span>
        <span class="data-horario-info">
            <?php echo $objAtendimentoInteracao->objAgendaData()->getDataPtBr(); ?>
            <?php echo $objAtendimentoInteracao->getAgendaInicio() ? $objAtendimentoInteracao->objAgendaInicio()->getStrHM() : ""; ?>
            <?php echo $objAtendimentoInteracao->getAgendaFim() ? " até " . $objAtendimentoInteracao->objAgendaFim()->getStrHM() : ""; ?>
        </span>
    </div>
    <div class="cliente">
        <?php echo $objAtendimentoInteracao->objAtendimento()->objCliente()->getNome(); ?>
    </div>
    <div class="texto">
        <?php if ($objAtendimentoInteracao->getTexto()) : ?>
            <blockquote><?php echo $objAtendimentoInteracao->getTexto(); ?></blockquote>
        <?php endif; ?>
    </div>
    <hr/>
</div>