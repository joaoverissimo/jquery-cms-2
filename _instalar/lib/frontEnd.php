<?php

function fEnd_Bool($value) {
    if (isset($value)) {
        if ($value)
            return __("Sim");
        else
            return __("Não");
    } else
        return "Não";
}

function fEnd_BoolIcon($value, $icon) {
    switch ($icon) {
        case "star":
            if ($value)
                return "<img src='/js/imagems/star.png' />";
            else
                return "<img src='/js/imagems/star_grey.png' />";
            break;

        default:
            break;
    }
}

function fEnd_MsgString($msg, $tipo = 'success', $moreinfo = '') {
    if ($moreinfo)
        $moreinfo = "<p>$moreinfo</p>";
    
    switch ($tipo) {
        case 1 || 'success':
            return "<div class='success'><button type='button' class='close' data-dismiss='alert'>×</button><h4 class='alert-heading'>$msg</h4>$moreinfo</div>";
            break;
        case 2 || 'error':
            return "<div class='error'><button type='button' class='close' data-dismiss='alert'>×</button><h4 class='alert-heading'>$msg</h4>$moreinfo</div>";
            break;
        case 3 || 'warning':
            return "<div class='warning'><button type='button' class='close' data-dismiss='alert'>×</button><h4 class='alert-heading'>$msg</h4>$moreinfo</div>";
            break;
        default:
            return "<div class='info'><button type='button' class='close' data-dismiss='alert'>×</button><h4 class='alert-heading'>$msg</h4>$moreinfo</div>";
            break;
    }
}