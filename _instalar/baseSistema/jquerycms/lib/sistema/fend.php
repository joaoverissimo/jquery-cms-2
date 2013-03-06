<?php

$fEnd_closeTheIFrameImDone = "<script>$(document).ready(function(){setTimeout(function() {window.parent.closeTheIFrameImDone();},1450);});</script>";

function fEnd_Bool($value) {
    if (isset ($value)) {
        if ($value)
            return __("Sim");
        else
            return __("Não");
    } else
        return "Não";
}

function fEnd_MsgString($msg, $tipo = 'success', $moreinfo = '') {
    if ($moreinfo)
        $moreinfo = "<p>$moreinfo</p>";
    
    switch ($tipo) {
        case 'success':
            return "<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button><h4 class='alert-heading'>$msg</h4>$moreinfo</div>";
            break;
        case 'error':
            return "<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button><h4 class='alert-heading'>$msg</h4>$moreinfo</div>";
            break;
        case 'warning':
            return "<div class='alert alert-warning'><button type='button' class='close' data-dismiss='alert'>×</button><h4 class='alert-heading'>$msg</h4>$moreinfo</div>";
            break;
        default:
            return "<div class='alert alert-info'><button type='button' class='close' data-dismiss='alert'>×</button><h4 class='alert-heading'>$msg</h4>$moreinfo</div>";
            break;
    }
}