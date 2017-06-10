$(function() {
	$('#raty').raty({ 
		number: 10 ,
		hints: [1,2,3,4,5,6,7,8,9,10],
		click: function(score, evt) {
			return $(this).attr('data-score',score);
		}
	});

	var paintBorderRed = function(selector) {
	    selector.css({"border-color":"red"});
	}

	var scrollToDiv = function(element, extraScroll) {
	    extraScroll = extraScroll || 0;
	    var offset = element.offset();
	    var offsetTop = offset.top;
	    var totalScroll = offsetTop + extraScroll;

	    $('html, body').animate({scrollTop: totalScroll}, 300);
	}

	var customLoading = function(elem, action) {
		action = action || "create";
		if(action == "create") {
			elem.addClass("customLoading");
		}
		else {
			elem.removeClass("customLoading");
		}
	}

	$(".regbutton").hover(
	    function() {
	        $(this).addClass("hover");
	    },
	    function() {
	        $(this).removeClass("hover");
	    }
	)


	$("#submit").click(function() {

		if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
	        $(this).addClass("hover");
	        setTimeout(function() {
	            $(".submit").removeClass("hover");
	        },400)
	    }

	    var find = $("#find").val();
	    var raty = $('#raty').attr('data-score');
	    if(!raty)
	    	raty = 0;
	    var opinion = $('#opinion').val();
	    var regexpOpinion = /[\{\}\[\]\<\>\/\;]/g;
	    opinion = opinion.substring(0,500).replace(regexpOpinion,"");


	    $.ajax({
	        url: "xprivate/saveFeedback.php",
	        type: 'POST',
	        data:{find:find, raty:raty, opinion:opinion},
	        beforeSend: function() {customLoading($("#submit")); return true;},
			complete: function() {customLoading($("#submit"),"remove")},
			error: function() {$("#submit").css("background-color","red")}
	    }).done(function() {
	        $("#anketa .submit").hide();
	        $("#anketa .sectionHead").html("Дякуємо за ваш відгук :)");
	        $("#anketa .anketa").slideUp();
	        $("#backToMain").slideDown();
	        scrollToDiv($("#anketa"));
		})

	})


})
