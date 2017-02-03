//BUSCA RÁPIDA
$(function () {
    $('input#filtraList').quicksearch('#tablelista tbody tr');
});

//Prety Photo
function querySt(ji) {
    hu = window.location.search.substring(1);
    gy = hu.split("&");

    for (i = 0; i < gy.length; i++) {
        ft = gy[i].split("=");
        if (ft[0] == ji) {
            return ft[1];
        }
    }
}

function prettyPhotoStart() {
    $("a[rel^='iFrameNoReload'], a[rel^='iFrameReload']").each(function () {
        var $link = $(this);
        $link.magnificPopup({
            type: 'iframe',
            preloader: true,
            callbacks: {
                open: function () {
                    $(".tooltip.in").hide();

                    var setContentContainerWidth = function (el) {
                        var winWidth = $(window).width();
                        var linkWidth;
                        if ($link.attr("data-prefered-width") === undefined) {
                            if ($(".content-body").length == 1) {
                                linkWidth = $(".content-body").width();
                            } else {
                                linkWidth = $(".content-body").width();
                                $(".content-body").each(function () {
                                    if ($(this).width() > linkWidth) {
                                        linkWidth = $(this).width();
                                    }
                                });
                            }
                        } else {
                            linkWidth = $link.attr("data-prefered-width");
                        }

                        if (winWidth < linkWidth) {
                            el.contentContainer.width(winWidth - 25);
                        } else {
                            el.contentContainer.width(linkWidth);
                        }
                    };
                    var setContentContainerHeight = function (el) {
                        var winHeight = $(window).height();
                        var linkHeight;
                        if ($link.attr("data-prefered-height") === undefined) {
                            linkHeight = 500;
                        } else {
                            linkHeight = $link.attr("data-prefered-height");
                        }

                        if (winHeight < linkHeight) {
                            el.contentContainer.height(linkHeight);
                        } else {
                            el.contentContainer.height(linkHeight);
                        }
                    };

                    setContentContainerWidth(this);
                    setContentContainerHeight(this);
                },
                close: function () {
                    if ($link.attr("rel") == "iFrameReload") {
                        window.location = $(location).attr('href');
                    }
                }
            }
        });
    });
}

$(document).ready(function () {
    prettyPhotoStart();
});

function closeTheIFrameImDone(fncCallback, prmCallback) {
    $.magnificPopup.close();

    if (fncCallback && prmCallback) {
        if (typeof window[fncCallback.toString()] === 'function') {
            window[fncCallback.toString()](prmCallback);
        }
    }
}

function closeTheIFrameImDoneReload(nameId, currentVal) {
    if (typeof currentVal === 'undefined' || currentVal == '') {
        currentVal = $('#' + nameId).val();
    }

    prmVal = $('#' + nameId + '-data-hidden').val();

    $('#' + nameId).load('/adm/home/_insercao-dinamica.php',
            {current: currentVal, prm: prmVal},
            function () {
                $('#' + nameId).select2().change();
                $(".btn-group.open").removeClass("open");
            }
    );
}

$(document).ready(function () {

    //Set active and current itens of menu
    var setCurrentItemMenu = function () {
        var s = $(location).attr('pathname')
        var patchInicio = s.indexOf('/', 1) + 1;

        var patchFim = s.lastIndexOf('/');
        var patch = s.substring(patchInicio, patchFim);

        $li = $("li[data-path='" + patch + "']");
        $li.addClass('nav-active');


        $li.parents(".nav-parent").addClass("nav-expanded nav-active");
    };

    setCurrentItemMenu();

    //Enable the tooltip
    $('a[rel=tooltip], span[rel=tooltip]').tooltip({html: true});

    //Enable tabs
    $('.tabbable .tab-pane.active').each(function () {
        var tab_href = $($(this)).attr("id");
        var s = "a[href=#" + tab_href + "]";
        $(s).tab("show");
    });

    //Tabela lista - altera checkbox
    $("#tablelista tr").click(function () {
        $ck = $(this).find(".multi-input");
        $ck.prop("checked", !$ck.prop("checked"));
    });

    $("#tablelista tr .multi-input").click(function () {
        $ck = $(this);
        $ck.prop("checked", !$ck.prop("checked"));
    });

    $('#multi_all').click(function () {
        var checkedStatus = this.checked;
        $('#tablelista tr').find('td:first :checkbox').each(function () {
            if ($(this).is(":visible")) {
                $(this).prop('checked', checkedStatus);
            }
        });
    });

    $("#multi_submit").click(function () {
        return confirm("Deletar os registros Selecionados?");
    });

    $("#mn-jquerycms").hide();

    $(".form-inline .form-group").each(function () {
        $formGroup = $(this);
        $formGroup.addClass("col-md-4 col-sm-4 col-xs-12");

        $controlLabel = $formGroup.find("label.control-label");
        $controlLabel.addClass("col-md-12").removeClass("col-md-3");

        $divInput = $formGroup.find(".col-md-6");
        $divInput.removeClass("col-md-6");

        $controlLabel.prependTo($divInput);
    });

    $("#btn-group-selecionados .dropdown-menu li a").click(function () {
        var link = $(this).attr("data-action");

        if (link == "deletar-multi.php") {
            if (!confirm("Essa ação pode remover vários registros. Deseja continuar?")) {
                return false;
            }
        }

        $("#form-grid").attr("action", link).submit();
    });

    $(".alert a").addClass("alert-link");

});

if (querySt("tema") == "branco") {
    $(window).load(function () {
        $(".group-insercao-dinamica").removeClass('group-insercao-dinamica');
        $(".btn-group-insercao-dinamica").hide();
    });
}

$(window).load(function () {
    if ($(window).width() < 768) {
        //small screens
        $(".page-search-form").trigger('panel:toggle');
        /*$("#form-grid table tr td").hide();
         $("#form-grid table tr td:first-child").show();
         $("#form-grid table tr td:last-child").show();
         $("#form-grid table tr td:nth-child(2)").show();
         
         $("#form-grid table tr th").hide();
         $("#form-grid table tr th:first-child").show();
         $("#form-grid table tr th:last-child").show();
         $("#form-grid table tr th:nth-child(2)").show();*/
    } else if ($(window).width() >= 768 && $(window).width() <= 992) {
        //$(".daterangepicker").addClass("show-calendar");
    } else if ($(window).width() > 992 && $(window).width() <= 1200) {
        //$(".daterangepicker").addClass("show-calendar");
    } else {
        //huge screens
    }
});

$(document).ready(function () {
    var fncMobileObtemBtnToolBar = function () {
        var $ul = $("<ul class='dropdown-menu'></ul>");
        $(".btn-toolbar a").each(function () {
            if (!$(this).hasClass("btn-primary")) {
                if ($(this).attr("href") != "#") {
                    var $link = $("<a>");
                    $link.attr("href", $(this).attr("href"));
                    $link.html($(this).html());

                    $li = $("<li>").append($link);
                    $ul.append($li);
                }

                $(this).addClass("hidden-sm hidden-xs");
            }
        });

        var $btnGroup = $('<div class="btn-group visible-sm visible-xs"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i></button></div>');
        $btnGroup.append($ul);

        if ($ul.find("li").length > 0) {
            $(".btn-toolbar").append($btnGroup);
        }
    };

    var fncMobileObtemEditarBtn = function () {
        $("#form-grid table td:last-child, #form-grid table th:last-child").addClass("hidden-sm hidden-xs");

        $("#form-grid table td:last-child").each(function () {
            $td = $(this);
            var $ul = $("<ul class='dropdown-menu'></ul>");

            $td.find("a").each(function () {
                if ($(this).attr("href") != "#") {
                    var $link = $("<a>");
                    $link.attr("href", $(this).attr("href"));
                    $link.html($(this).html());

                    $li = $("<li>").append($link);
                    $ul.append($li);
                }
            });

            var $btnGroup = $('<div class="btn-group visible-sm visible-xs"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i></button></div> ');
            if ($ul.find("li").length > 0) {
                $btnGroup.append($ul);
                $td.closest("tr").find("td:first-child").prepend($btnGroup);
            }
        });
    };

    fncMobileObtemBtnToolBar();
    fncMobileObtemEditarBtn();
});

$(document).ready(function () {
    $("#btn-side-bar-menu").click(function () {
        if ($("html").hasClass("sidebar-left-collapsed") == false) {
            //fechado
            $.cookie('cke-sidebar-left', 'collapsed', {expires: 365, path: '/'});
        } else {
            //aberto
            $.cookie('cke-sidebar-left', 'opened', {expires: 365, path: '/'});
        }
    });

    if (!$.cookie('cke-sidebar-left') !== undefined && $.cookie('cke-sidebar-left') == "collapsed") {
        $("html").addClass("sidebar-left-collapsed");
    }

});

$(document).ready(function () {
    $("ul.notifications li").click(function () {
        var $li = $(this);
        $.cookie($li.attr("id"), $li.attr("data-versao"), {expires: 365, path: '/'});
    });

    $("ul.notifications li").each(function () {
        var $li = $(this);
        var id = $li.attr("id");
        var versao = $li.attr("data-versao");
        if ($.cookie(id) !== undefined && $.cookie(id) == versao) {
            $li.find("span.badge").hide();
        }
    });
});

function ajaxAutoLoad($el) {
    var url = $el.attr("data-url");
    $el.load(url, function (e) {
        prettyPhotoStart();
    });
}

$(document).ready(function () {
    $(".ajax-auto-load").each(function () {
        ajaxAutoLoad($(this));
    });
});

$(document).ready(function () {
    $.fn.select2.defaults.set("theme", "bootstrap");
    $.fn.select2.defaults.set("width", "style");

    $('select:not(.not-select2)').select2();
    //$('select.required').parent().append('<label generated="true" class="error"></label>');
});
