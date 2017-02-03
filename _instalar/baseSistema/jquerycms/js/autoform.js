$(document).ready(function () {
    $('select.bootstrap-multiselect').multiselect({
        templates: {
            filter: '<div class="input-group"><span class="input-group-addon"><i class="fa fa-search"></i></span><input class="form-control multiselect-search" type="text"></div>'
        }, maxHeight: 200,
        onDropdownShown: function (even) {
            this.$filter.find('.multiselect-search').focus();
        },
        onChange: function (t) {
            $(this.$filter).find("input").val("");
            this.doSearch("");
            $(this.$filter).find("input").focus();
        },
        enableCaseInsensitiveFiltering: true,
        nonSelectedText: "Selecione...",
        selectAllText: ' Selecionar todos',
        filterPlaceholder: 'Buscar',
        nSelectedText: 'selecionado',
        allSelectedText: 'Todos selecionados',
    });
    //$('select.required').parent().append('<label generated="true" class="error"></label>');
});

Number.prototype.formataMoeda = function (c, d, t) {
    var n = this,
            c = isNaN(c = Math.abs(c)) ? 2 : c,
            d = d == undefined ? "," : d,
            t = t == undefined ? "." : t,
            s = n < 0 ? "-" : "",
            i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
            j = (j = i.length) > 3 ? j % 3 : 0;
    return 'R$ ' + s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};


var floatInteger = {
    permitirSomenteInteirosKeyDown: function (a) {
        if (a.keyCode == 46 || a.keyCode == 8 || a.keyCode == 9 || a.keyCode == 27 || a.keyCode == 13 || (a.keyCode == 65 && a.ctrlKey === true) || (a.keyCode >= 35 && a.keyCode <= 39)) {
            return;
        } else {
            if (a.shiftKey || (a.keyCode < 48 || a.keyCode > 57) && (a.keyCode < 96 || a.keyCode > 105)) {
                a.preventDefault();
            }
        }
    },
    formatarNumeroSeparadorMilhar: function (f) {
        f = this.removerSeparador(f);
        f += "";
        var j = '.';
        var x = f.split(j);
        var x1 = x[0];
        var x2 = x.length > 1 ? "," + x[1] : "";
        var g = /(\d+)(\d{3})/;
        while (g.test(x1)) {
            x1 = x1.replace(g, "$1" + j + "$2");
        }
        return x1 + x2;
    },
    removerSeparador: function (f) {
        var j = '.';
        var g = new RegExp("[" + j + "]", "g");
        return f.toString().replace(g, "");
    },
    initFields: function () {
        $("form").on("keydown", "input.floatInteger", function (a) {
            return floatInteger.permitirSomenteInteirosKeyDown(a);
        });

        $("form").on("keyup", "input.floatInteger", function (a) {
            var valor = $(this).val();
            valor = floatInteger.formatarNumeroSeparadorMilhar(valor);
            return $(this).val(valor);
        });

        $("input.floatInteger").closest("form").submit(function (e) {
            $("input.floatInteger").each(function () {
                var valor = $(this).val();
                valor = floatInteger.removerSeparador(valor);
                $(this).val(valor);
            });
        });

        $("input.floatInteger").each(function () {
            var valor = $(this).val();
            valor = floatInteger.formatarNumeroSeparadorMilhar(valor);
            $(this).val(valor);
        });
    }
};

$(document).ready(function () {
    floatInteger.initFields();
});

String.prototype.toSemAcentos = function () {
    str = this;

    var from = "ÃÀÁÄÂÈÉËÊÌÍÏÎÕÒÓÖÔÙÚÜÛÑÇãàáäâèéëêìíïîõòóöôùúüûñç";
    var to = "AAAAAEEEEIIIIOOOOOUUUUNCaaaaaeeeeiiiiooooouuuunc";
    for (var i = 0, l = from.length; i < l; i++) {
        str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
    }

    return str;
};

String.prototype.toSemEspaco = function () {
    return this.replace(/ /g, '');
};