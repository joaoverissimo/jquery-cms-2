<table class="table table-hover table-striped" id="tablelista">
    <thead>
        [..]

        <th><~?php echo __('table_<?php echo $detalhe;?>'); ?~></th>

        [..]

        <tr>
            [..]

            <td><a href="../<?php echo $detalhe;?>/index.php?<?php echo $relacao;?>=<~?php echo $registro->get<?php echo $master_primaryKeyU;?>; ?>"><~?php echo __('table_<?php echo $detalhe;?>'); ?~></a></td>

            [..]