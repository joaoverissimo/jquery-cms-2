<?php

class autoform2tabs {

    private $id;
    private $html;
    private $i;
    private $tabs = array();

    function __construct($id = "nav_tabs", $class = "tabs-left", $formName = "cadastro") {
        $id = toRewriteString($id);

        $this->id = $id;
        $s = "<div class='tabbable $class' id='$id'><ul class='nav nav-tabs'>";
        if ($formName) {
            $s.= $this->getValidador($formName);
        }
        $this->html = $s;
    }

    public function getValidador($formName = "cadastro") {
        $id = $this->id;
        $s = "<script> $(document).ready(function(){ $('#$formName').submit(function(){ if (!$('#$formName').valid()) { $($('.nav-tabs li a').get().reverse()).each(function(){ var \$link = $(this); var \$tab = \$link.closest('.tabbable').find('.tab-content ' + \$link.attr('href')); if (\$tab.find('.control-group.error').length > 0) { \$link.tab('show'); } }); } }); }); </script>";

        return $s;
    }

    public function start() {
        $this->html .= "</ul><div class='tab-content'><div class='tab-pane active' id='{$this->tabs[0]}'>";
        return $this->html;
    }

    public function tab() {
        $this->i++;
        return "</div><div class='tab-pane' id='{$this->tabs[$this->i]}'>";
    }

    public function end() {
        return "</div></div></div>";
    }

    public function addTab($label) {
        $tabs = $this->tabs;
        $count = count($tabs);

        $this->html .= "<li><a href='#{$this->id}_{$count}' data-toggle='tab'>{$label}</a></li>";
        $this->tabs[] = "{$this->id}_{$count}";
    }

}