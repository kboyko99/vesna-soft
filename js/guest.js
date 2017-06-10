$(function() {
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

	$('.form-input').focus(function() { // making input green-bordered if focused
	    $(this).css({"border-color":"#1B7E5A"});
	});

	$("#submit").click(function() {
		var self = $(this);
		if (self.hasClass('customLoading')) {
			return false;
		}

	    var validation = true;
	    var fio = $('#FIO').val();
	    var email = $('#email').val();
	    var about = $('#aboutYou').val();
	    
	    var regexpName = /^['"А-Яа-яЁёІіЇїєЄґҐa-zA-Z\s\-]+$/;
	    var regexpMail = /^.+@[\w]+\.[\w]+$/;
	    var regexpAbout = /[\{\}\[\]\<\>\/\;]/g;

	    about = about.substring(0,500).replace(regexpAbout,"");
	    
	    if(!(fio.match(regexpName))) {
	        paintBorderRed($('#FIO'));
	        validation = false;
	    }

	    if(!(email.match(regexpMail))) { 
	        paintBorderRed($('#email'));
	        validation = false;
	    }

	    if(about == "") { 
	        paintBorderRed($('#aboutYou'));
	        validation = false;
	    }

	    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
	        $(this).addClass("hover");
	        setTimeout(function() {
	            $(".submit").removeClass("hover");
	        },400)
	    }

	    if(!validation) {
	        scrollToDiv($("#anketa"));
	        return false;
	    }

	    $.ajax({
	        url: "xprivate/saveGuest.php",
	        type: 'POST',
	        data:{fio:fio, email:email, about:about},
	        beforeSend: function() {customLoading(self); return true;},
			complete: function() {customLoading(self,"remove")},
			error: function() {self.css("background-color","red")}
	    }).done(function() {
	        $("#anketa .submit").hide();
	        $("#anketa .anketaAnnouncement").hide();
	        $("#anketa .anketa").slideUp();
	        $("#anketa .thanks").slideDown();
	        $("#backToMain").slideDown();
	        scrollToDiv($("#anketa"));
		})
	})
})


