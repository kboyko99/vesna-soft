$(document).ready(function() {
    // initialize();
    get_partners(); // getting sponsors
    get_questions();

    $('#scrollup').click( function() { // "up" button - scrolling to top
        $('html,body').animate({
            scrollTop: 0
        }, 300);
    });

    $('.anketa .agreement .more').click(function () {
      var details = $('.anketa .agreement .details');
      if(details.is(":visible")) {
        details.slideUp();
      } else {
        details.slideDown();
      }
      $('.anketa .agreement .more').toggleClass('hidden');
    });

    $(".camera").click(function() {
        $.ajax({
            url: "xprivate/cameraLog.php",
            type: 'POST',
            data: {"camera":true}
        })
    });

    $('.jury-photo').hover(
        function() {
            var el = $(this);
            if(!el.attr('animated')){
                el.attr('animated', "yes");
                $(this).children().slideDown( 200, function(){
                    el.removeAttr('animated');
                });
            }
        }, function() {
            var el = $(this);
            el.attr('animated', "yes");
            $(this).children().slideUp( 200, function(){
                el.removeAttr('animated');
            });
        }
    );

    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
        var prompt = $(".about-popover-prompt");
        $(".about-popover").click(function() {
            if(prompt.is(":visible")) {
                prompt.slideUp(200)

            }
            else {
                prompt.slideDown(200)
            }
        }).on("mouseleave",function () {
            prompt.slideUp(200)
        })
    }
    else {
        $(".about-popover-content").hover(
            function() {
                var el = $(this);
                if(!el.attr('animated')){
                    el.attr('animated', "yes");
                    el.parent().find(".about-popover-prompt").slideDown(200, function(){
                        el.removeAttr('animated');
                    });
                }
            },
            function() {
                var el = $(this);
                el.attr('animated', "yes");
                el.parent().find(".about-popover-prompt").slideUp(200, function(){
                    el.removeAttr('animated');
                });
            }
        )
    }

    $(".regbutton").hover(
        function() {
            $(this).addClass("hover");
        },
        function() {
            $(this).removeClass("hover");
        }
    );

    $(window).scroll(function() { //fading in/out "up" button while scrolling up/down the page
        if ($(document).scrollTop() > 500 ) {
            $('#scrollup').fadeIn('fast');
        } else {
            $('#scrollup').fadeOut('fast');
        }
    });

    $('#project, #hakaton, #i-have-a-team').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green'
    });
    $('#project, #hakaton, #i-have-a-team').on('ifToggled', function(event){
        $(".icheckbox_square-green").removeClass("checkboxError");
        if(event.target.id == "project") {
            var aboutProjectDiv = $(".anketaItem.aboutProject");
            if(event.target.checked) {
                aboutProjectDiv.find("span").css('margin-top','0')
                aboutProjectDiv.css('margin-top','20px').slideDown();
            }
            else {
                aboutProjectDiv.slideUp();
            }
        }

        if(event.target.id === "hakaton") {
          var teamDiv = $(".anketaItemCheck.team");
          if(event.target.checked) {
            teamDiv.css('margin-top','20px').slideDown();
          }else{
            iHaveATeamCB = $("#i-have-a-team");
            iHaveATeamCB.prop('checked', false);
            iHaveATeamCB.parent().removeClass('checked');
            $('.my-team input').val('');
            teamDiv.slideUp();
            $(".anketaItem.my-team").slideUp();
            $('.order-number').slideUp();
          }
        }

        if (event.target.id === "i-have-a-team") {
            var teamDiv = $(".anketaItem.my-team");
            if (event.target.checked) {
                $('.order-number').slideDown();
                teamDiv.css('margin-top', '20px').slideDown();
            } else {
                teamDiv.slideUp();
                $('.order-number').slideUp();
                $('.my-team input').val('');
            }
        }
    });

    $('#email').bind("propertychange change keyup input paste", function(event){
      var email = $('#email').val();
      setTimeout(  function(){
        if( email == $('#email').val() ){
          $.ajax({
            url: 'xprivate/isEmailExistInDB.php',
            method: "POST",
            data: {
              email: $('#email').val()
            }
          }).done(function(response){
            if(response != 'null' ){
              participant = JSON.parse(response);

              participant.goHackaton =  participant.team_id > 0;
              participant.hasProject = (participant.project_title != null && participant.project_title != '');

              if(participant.goHackaton && participant.hasProject){

                $('.message').html('Ви вже зареєструвалися на хакатон і додали свій проект.  Якщо у вас виникли питання напишіть нам на пошту: <a href="mailto:vesna@programming.kr.ua">vesna@programming.kr.ua</a>');
                $('.hackenproject').slideDown();

                $('.regbutton.submit').hide();
                $('.updatebutton').hide();
                $('.team').hide();
                $('.my-team').hide();

                $('.aboutProject').hide();
                $('.project').slideUp();
                $('.hakaton').hide();

              }else  if(participant.goHackaton){
                state = 'alreadyHackathon';
                $('.message').html('Ви вже зареструвалися на хакатон. Можете додати проект.  Якщо у вас виникли питання напишіть нам на пошту: <a href="mailto:vesna@programming.kr.ua">vesna@programming.kr.ua</a>');
                $('.hackenproject').slideDown();

                $('.regbutton.submit').hide();
                $('.updatebutton').show();

                $('.team').hide();
                $('.my-team').hide();

                $('.project').show();
                $('.hakaton').hide();

              }else  if(participant.hasProject){
                state = 'alreadyProject';

                $('.message').html('Ви вже додали свій проект. Можете зареєструватися на хакатон.  Якщо у вас виникли питання напишіть нам на пошту: <a href="mailto:vesna@programming.kr.ua">vesna@programming.kr.ua</a>');
                $('.hackenproject').slideDown();

                $('.regbutton.submit').hide();
                $('.updatebutton').show();

                $('.aboutProject').hide();
                $('.project').hide();
                $('.hakaton').show();
              }

            }else{
                $('.hackenproject').hide();

                $('.regbutton.submit').show();
                $('.updatebutton').hide();

                $('.project').show();
                $('.hakaton').show();
            }
          });
        }
      },900);

    });

});

function get_partners() { // getting sponsors
	$.ajax({
		dataType:'json',
		url: "xprivate/partners.json"
	}).done(function(msg){
        var org = $('.org').html();
        for (var i = 0, len = parseInt(Object.keys(msg.org).length); i < len; i++){
            org = org+'<div class="importantPic"><a href="'+msg.org[i]['href']+'" target="_blank"><img src="img/serv/'+msg.org[i]['src']+'"><span>'+msg.org[i]['desc']+'</span></a></div>';
            // org = org+'<div class="importantPic"><div><img src="img/serv/'+msg.org[i]['src']+'"></div></div>';
            $('.org').html(org);
        }
		var main = $('.main').html();
		for (var i = 0, len = parseInt(Object.keys(msg.main).length); i < len; i++){
            main = main+'<div class="importantPic"><a href="'+msg.main[i]['href']+'" target="_blank"><img src="img/serv/'+msg.main[i]['src']+'"><br>'+msg.main[i]['desc']+'</a></div>';
			// main = main+'<div class="importantPic"><div><img src="img/serv/'+msg.main[i]['src']+'"></div></div>';
			$('.main').html(main);
		}
		var info = $('.info').html();
		for (var i = 0, len = parseInt(Object.keys(msg.info).length); i < len; i++){
            info = info+'<div class="importantPic"><a href="'+msg.info[i]['href']+'" target="_blank"><img src="img/serv/'+msg.info[i]['src']+'"><br>'+msg.info[i]['desc']+'</a></div>';
			// info = info+'<div class="importantPic"><div><img src="img/serv/'+msg.info[i]['src']+'"></div></div>';
			$('.info').html(info);
		}
	})
}

function get_questions() {
    $.ajax({
        dataType:'json',
        url: "xprivate/getQuestions.php"
    }).done(function(msg){
        var faqContent = "";
        msg.forEach(function(val) {
            faqContent+=  '<div class="faqItem">'+
                        '<div class="question">'+
                            '<img src="img/farrow.png" alt="faq arrow">'+
                            '<span>'+val.q+'</span>'+
                        '</div>'+
                        '<div class="answer">'+
                            val.a+
                        '</div>'+
                    '</div>'
        })
        $("#faq").append(faqContent);
        $($(".faqItem")[0]).addClass("open").find(".answer").show();

        $("#faq .faqItem .question").click(function(e) {
            var parrent = $(e.currentTarget.parentElement)
            if(parrent.hasClass("open")) {
                parrent.removeClass("open");
                parrent.find(".answer").slideUp();
            }
            else {
                parrent.addClass("open");
                parrent.find(".answer").slideDown();
            }
        })
    })
}

function scrollToDiv(element, extraScroll) {
    extraScroll = extraScroll || 0;
    var offset = element.offset();
    var offsetTop = offset.top;
    var totalScroll = offsetTop + extraScroll;

    $('html, body').animate({scrollTop: totalScroll}, 300);
}

function customLoading(elem, action) {
    action = action || "create";
    if(action === "create") {
        elem.addClass("customLoading");
    }
    else {
        elem.removeClass("customLoading");
    }
}

/* --------------------------
 * COUNTDOWN
 * -------------------------- */
var targetDate = new Date("2017/05/20 10:00:00");

var days;
var hrs;
var min;
var sec;

$(function() {
    timeToLaunch();

    numberTransition('#days .number', days, 1000, 'easeOutQuad');
    numberTransition('#hours .number', hrs, 1000, 'easeOutQuad');
    numberTransition('#minutes .number', min, 1000, 'easeOutQuad');
    numberTransition('#seconds .number', sec, 1000, 'easeOutQuad');

    setTimeout(countDownTimer,1001);
});

function timeToLaunch(){

    var currentDate = new Date();

    var diff = (currentDate - targetDate)/1000;
    diff = Math.abs(Math.floor(diff));

    days = Math.floor(diff/(24*60*60));
    sec = diff - days * 24*60*60;

    hrs = Math.floor(sec/(60*60));
    sec = sec - hrs * 60*60;

    min = Math.floor(sec/(60));
    sec = sec - min * 60;
}

function countDownTimer(){

    timeToLaunch();

    $( "#days").find(".number" ).text(days);
    $( "#hours").find(".number" ).text(hrs);
    $( "#minutes").find(".number" ).text(min);
    $( "#seconds").find(".number" ).text(sec);

    setTimeout(countDownTimer,1000);
}

function numberTransition(id, endPoint, transitionDuration, transitionEase){
    $({numberCount: $(id).text()}).animate({numberCount: endPoint}, {
        duration: transitionDuration,
        easing:transitionEase,
        step: function() {
            $(id).text(Math.floor(this.numberCount));
        },
        complete: function() {
            $(id).text(this.numberCount);
        }
    });
}

/* --------------------------
 * END COUNTDOWN
 * -------------------------- */

function paintBorderRed(selector) {
    selector.css({"border-color":"red"});
}

$('.scroll-a').click(function(e) { // navigation
    e.preventDefault();
    var el = $(this).attr('href');
    var elWrapped = $(el);
    if(e.button === 0) {
        scrollToDiv(elWrapped);
        // location.href = el;
    }
    else if (e.button === 1) {
        window.open(el, '_blank')
    }
    return false;
});

$('.form-input').focus(function() { // making input green-bordered if focused
    $(this).css({"border-color":"#1B7E5A"});
});

var state = '';
var regexpName = /^['"А-Яа-яЁёІіЇїєЄґҐa-zA-Z\s\-]+$/;
var regexpAge = /^\d{1,2}$/;
var regexpPhone = /^[0-9]{10,12}$/;
var regexpMail = /^.+@[\w]+\.[\w]+$/;
var regexpAboutProject = /[\{\}\[\]\<\>\/\;]/g;

function baseValidation(){
  if(!(fio.match(regexpName))) {
      paintBorderRed($('#FIO'));
      validation = false;
  }
  if(!(age.match(regexpAge) && parseInt(age)>0)) {
      paintBorderRed($('#age'));
      validation = false;
  }

  if(!(phone.match(regexpPhone))) {
      paintBorderRed($('#phone'));
      validation = false;
  }

  if(!(email.match(regexpMail))) {
      paintBorderRed($('#email'));
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
}
var validation = true;
var fio = '';
var age = '';
var email = '';
var phone = '';

function getPersonInfo(){
  fio = $('#FIO').val();
  age = $('#age').val();
  email = $('#email').val();
  phone = $('#phone').val();
}

var hakaton = '';
var iHaveATeam = '';
var memberNameOne = '';
var memberNameTwo = '';
var memberAgeOne = '';
var memberAgeTwo = '';
var memberEmailOne = '';
var memberEmailTwo = '';
var memberOneExist = false;
var memberTwoExist = false;

function getHackathonInfo(){
  hakaton = $("#hakaton").prop('checked');
  iHaveATeam = $("#i-have-a-team").prop('checked');
  memberNameOne = $('#member-name1').val();
  memberOneExist = memberNameOne !== '';
  memberNameTwo = $('#member-name2').val();
  memberTwoExist = memberNameTwo !== '';
  memberAgeOne = $('#member-age1').val();
  memberAgeTwo = $('#member-age2').val();
  memberEmailOne = $('#member-email1').val();
  memberEmailTwo = $('#member-email2').val();
}

var project = '';
var aboutProject = '';
var projectTitle = '';

function getProjectInfo(){
  project = $("#project").prop('checked');
  projectTitle = $('#project-title').val();
  aboutProject = $('#aboutProject').val();
}
function projectValidation(){
  if(project && (aboutProject === "" || projectTitle === "")) {
      if(aboutProject === ""){
        paintBorderRed($('#aboutProject'));
        scrollToDiv($("#aboutProject"),(-$(window).height()/2))
      }
      if(projectTitle === ""){
        paintBorderRed($('#project-title'));
        scrollToDiv($("#project-title"),(-$(window).height()/2))
      }
      validation = false;
  }
}

function teamInfoValidation(){
  if(hakaton && iHaveATeam){

    if(!(memberNameOne.match(regexpName))){
      paintBorderRed($('#member-name1'));
      validation = false;
    }
    if ( !( memberAgeOne.match(regexpAge) && parseInt(memberAgeOne) > 0 ) ) {
      paintBorderRed($('#member-age1'));
      validation = false;
    }
    if(!(memberEmailOne.match(regexpMail)) ){
      paintBorderRed($('#member-email1'));
      validation = false;
    }

    if(memberNameTwo !== '' || memberAgeTwo !== '' || memberEmailTwo !== ''){
      if(memberNameOne === '' || memberAgeOne === '' || memberEmailOne === ''){
        paintBorderRed($('#member-name1'));
        paintBorderRed($('#member-age1'));
        paintBorderRed($('#member-email1'));
      }
      if(!(memberNameTwo.match(regexpName))){
        paintBorderRed($('#member-name2'));
        validation = false;
      }
      if (!(memberAgeTwo.match(regexpAge) && parseInt(memberAgeTwo)>0) ){
        paintBorderRed($('#member-age2'));
        validation = false;
      }
      if( !(memberEmailTwo.match(regexpMail)) ){
        paintBorderRed($('#member-email2'));
        validation = false;
      }
    }

    if(email === memberEmailOne || email === memberEmailTwo || memberEmailTwo === memberEmailOne ){
      validation = false;
      $('.wrongemail').slideDown();
      $('.emailmessage').html('Емейли мають відрізнятися.  Якщо у вас виникли питання напишіть нам на пошту: <a href="mailto:vesna@programming.kr.ua">vesna@programming.kr.ua</a>');
    }else{
      $('.wrongemail').hide();
    }
  }
}
function updateData(){
  if(validation){
    $.ajax({
      url: "xprivate/updateData.php",
      type: 'POST',
      data:{state: state, fio:fio, age:age, email:email, phone:phone, hakaton:hakaton, project:project, aboutProject:aboutProject, projectTitle:projectTitle, memberNameOne:memberNameOne, memberAgeOne:memberAgeOne, memberEmailOne:memberEmailOne, memberNameTwo:memberNameTwo, memberAgeTwo:memberAgeTwo, memberEmailTwo:memberEmailTwo},
      beforeSend: function() {customLoading($('.updatebutton')); return true;},
      complete: function() {  customLoading(self,"remove")},
      error: function() {self.css("background-color","red")}
    })
    .done(function () {
      $("#anketa .updatebutton").hide();
      $('.hackenproject').hide();
      $("#anketa .anketa").slideUp();
      $("#anketa .thanks").show();
      $('.wrongemail').hide();
      scrollToDiv($("#anketa"));
    });
  }
}

$('.updatebutton').click(function(event) {
  var self = $(this);
  if (self.hasClass('customLoading')) {
    return false;
  }

  validation = true;

  getPersonInfo();
  baseValidation();

  if(state === 'alreadyProject'){
    getHackathonInfo();
    if(hakaton){
      teamInfoValidation();
    }else{
      $('.icheckbox_square-green').addClass("checkboxError")
      return false;
    }

  }

  if(state === 'alreadyHackathon'){
    getProjectInfo();
    if(project){
      projectValidation();
    }else{
      $('.icheckbox_square-green').addClass("checkboxError")
      return false;
    }
  }

  if(memberOneExist){
    $.ajax({
      url: 'xprivate/isEmailExistInDB.php',
      type: 'POST',
      data: {email: memberEmailOne}
    })
    .then(function(response){
      if(response === 'null' ){
        if(memberTwoExist){
          $.ajax({
            url: 'xprivate/isEmailExistInDB.php',
            type: 'POST',
            data: {email: memberEmailTwo}
          })
          .then(function(response){
            if(response == 'null' ){
              updateData();
            }else {
              //member email two in db
              $('.wrongemail').slideDown();
              $('.emailmessage').html('Емейл члена вашої команди вже є в базі.  Якщо у вас виникли питання напишіть нам на пошту: <a href="mailto:vesna@programming.kr.ua">vesna@programming.kr.ua</a>');
              validation = false;
              paintBorderRed($('#member-email2'));
              return false;
            }
          });
        }else{
          updateData();
        }
      }else{
        //member email one exist in db
        $('.wrongemail').slideDown();
        $('.emailmessage').html('Емейл члена вашої команди вже є в базі.  Якщо у вас виникли питання напишіть нам на пошту: <a href="mailto:vesna@programming.kr.ua">vesna@programming.kr.ua</a>.');
        validation = false;
        paintBorderRed($('#member-email1'));
        return false;
      }
    });
  }else{
    updateData();
  }
});


$(".submit").click(function() { // submit form and validate
    var self = $(this);
    if (self.hasClass('customLoading')) {
        return false;
    }

    validation = true;

    getPersonInfo();
    getHackathonInfo();
    getProjectInfo();

    phone = phone.replace(/[^\d]+/g,"");
    age = age.replace(/[^\d]+/g,"");
    aboutProject = aboutProject.substring(0,500).replace(regexpAboutProject,"");
    projectTitle = projectTitle.substring(0,500).replace(regexpAboutProject,"");

    baseValidation();

    if(!hakaton && !project) {
        scrollToDiv($("#hakaton"),(-$(window).height()/2));
        $('.icheckbox_square-green').addClass("checkboxError");
        return false;
    }

    projectValidation();
    teamInfoValidation();

    if(validation){
      $.ajax({
          url: "xprivate/saveData.php",
          type: 'POST',
          data:{fio:fio, age:age, email:email, phone:phone, hakaton:hakaton, project:project, aboutProject:aboutProject, projectTitle:projectTitle, memberNameOne:memberNameOne, memberAgeOne:memberAgeOne, memberEmailOne:memberEmailOne, memberNameTwo:memberNameTwo, memberAgeTwo:memberAgeTwo, memberEmailTwo:memberEmailTwo},
          beforeSend: function() {customLoading(self); return true;},
          complete: function() {  customLoading(self,"remove")},
          error: function() {self.css("background-color","red")}
      })
      .done(function () {
          $("#anketa .submit").hide();
          $("#anketa .anketa").slideUp();
          $("#anketa .thanks").show();
          scrollToDiv($("#anketa"));
      });
    }
});
