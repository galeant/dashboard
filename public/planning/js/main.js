var emSize = parseFloat($("html").css("font-size"));

//cover image
$(".thumb img").fillBox();

//nicescroll
var niceOption = {
	cursorwidth: 4,
	cursorborder: 0,
	cursorborderradius: 0,
	cursorcolor: "#e17306",
	background: "transparent",
	scrollspeed: 90,
	mousescrollstep: 30,
	autohidemode: true,
	horizrailenabled: false,
	nativeparentscrolling: false,
	zindex: 20
};
function scrollbar(){
	"use strict";
	$("[data-scrollbar]").niceScroll(niceOption);
}

//check window size 
$("body").css("overflow","hidden");
function checkWidth(){
	if ($(window).width() < 768) {
		$("body").getNiceScroll().remove();
		$("body").css("overflow-y","auto");
	} else {
		scrollbar();
		$("body").getNiceScroll().resize();
	}
}

//preloader
$(window).on("load",function() {
	"use strict";
	setTimeout(loader, 500);
	function loader(){
		$("#status").fadeOut(300);
		$("body").addClass("load");
		setTimeout(function(){
			$("#preloader").remove();
		}, 1400);
		checkWidth();
	}
});

//mobile menu
$(".toggle-menu-wrapper").click(function(){
	$("body").toggleClass("active-menu");
	if($("body").hasClass("active-menu")){
		$("body").getNiceScroll().remove();
		$(".main-header").append("<div class='backdrop'></div>");
	} else {
		$(".main-header").find(".backdrop").remove();
		checkWidth();
	}
	
	//$(".backdrop").css("height",""+fullHeight+"px");
	$(".backdrop").click(function(){
		$("body").removeClass("active-menu");
		$(this).remove();
		checkWidth();
	});
});

//back to top
$(".back-to-top").click(function(){
	$("html, body").animate({scrollTop:0}, 1000, $.bez([.4,0,.2,1]));
});

//modal scroll
$(".modal").on("shown.bs.modal", function() {
	setTimeout(function(){
		$("[data-scrollbar]").getNiceScroll().resize();
	}, 400);
	
	if($("body").hasClass("modal-open")){
		$(this).find(".modal-content").append("<div class='backdrop'></div>");
	}
	
	$(".backdrop").click(function(){
		$(this).parents(".modal").modal("hide");
		$(".backdrop").remove();
	});
});

//resize lightslider
$(".collapse").on("show.bs.collapse", function () {
	$(".modal-plan-img").resize();
	setTimeout(function(){
		$("[data-scrollbar]").getNiceScroll().resize();
	}, 400);
});

//custom scroll inside select
$(".js-select").on("show.bs.select",function(){
	setTimeout(function(){
		$(".bootstrap-select [role='listbox']").niceScroll(niceOption);
		$("[data-scrollbar]").getNiceScroll().resize();
	}, 400);
});
$(".js-select[multiple='']").addClass("js-multiple");

$(".js-select").parent().append("<div class='clear-selected'><i class='ti-close'></i></div>");
$(".clear-selected").hide();
$(".js-select").on("changed.bs.select",function(){
	$(this).parents().eq(1).addClass("selected");
	if($(this).parents().eq(1).hasClass("selected")){
		$(this).parents().eq(1).find(".clear-selected").show();
	} else {
		$(this).parents().eq(1).find(".clear-selected").hide();
	}
	
	$(".clear-selected").click(function(){
		$(this).parent().find(".js-select").val("").selectpicker("refresh");
		$(this).hide();
	});
});


$(window).scroll(function() {
	var curPos = $("footer").position().top - $(window).height();
	if($(this).scrollTop() >= curPos) {
		$(".btn-fix, .btn-fix-wrapper").addClass("visible");
	}
	else { 
		$(".btn-fix, .btn-fix-wrapper").removeClass("visible");  
	}
});
