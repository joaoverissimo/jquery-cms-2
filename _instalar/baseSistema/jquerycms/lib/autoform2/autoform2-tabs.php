<?php

class autoform2tabs {

    private $id;
    private $html;
    private $i;
    private $tabs = array();

    function __construct($id = "nav_tabs", $class = "tabs-left") {
        $id = toRewriteString($id);

        $this->id = $id;
        $this->html = "<div class='tabbable $class' id='$id'><ul class='nav nav-tabs'>";
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