//BUSCA RÁPIDA
$(function () {
    $('input#filtraList').quicksearch('#tablelista tbody tr');
});

//Prety Photo
function querySt(ji) {
    hu = window.location.search.substring(1);
    gy = hu.split("&");

    for (i=0;i<gy.length;i++) {
        ft = gy[i].split("=");
        if (ft[0] == ji) {
            return ft[1];
        }
    }
}

$(document).ready(function () {
    $("a[rel^='iFrameReload']").prettyPhoto({ 
        callback: function(){
            window.location = $(location).attr('href');// + querySt("GaleriaId");
        },
        default_width: "97%",
        default_height: "97%",
        deeplinking: false,
        social_tools: ''
    });
        
    $("a[rel^='iFrameNoReload']").prettyPhoto({ 
        default_width: "97%",
        default_height: "97%",
        deeplinking: false,
        social_tools: ''
    });
});
    
function closeTheIFrameImDone() {
    jQuery.prettyPhoto.close();
}

$(document).ready(function() {
    
    //Set active and current itens of menu
    var s = $(location).attr('pathname').replace(/\//g,'');
    $('li a').each(function(){
        var test = $(this).attr('href').replace(/\//g,'');
		
        if (test == s){
            $(this).addClass('current');
            $(this).parent().addClass('active');
        } else {
            $(this).removeClass('current');
            $(this).parent().removeClass('active');
        }
    });
	
    
    //Enable the tooltip
    $('a[rel=tooltip]').tooltip();
	
	//Enable tabs
    $('.tabbable .tab-pane.active').each(function(){
        var tab_href = $($(this)).attr("id");
        var s = "a[href=#" + tab_href + "]";
        $(s).tab("show");
    }); 
});