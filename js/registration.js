$(document).ready(function () {
    $('.anketa .agreement .more').click(function () {
        let details = $('.anketa .agreement .details');
        if (details.is(":visible")) {
            details.slideUp();
        } else {
            details.slideDown();
        }
        $('.anketa .agreement .more').toggleClass('hidden');
    });

    $(".regbutton").hover(
        function () {
            $(this).addClass("hover");
        },
        function () {
            $(this).removeClass("hover");
        }
    );

    $('#project, #programming, #design, #robotics, #exhibition, #guest').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green'
    }).on('ifToggled', function (event) {
        $(".icheckbox_square-green").removeClass("checkboxError");
        if (event.target.id === "project") {
            let aboutProjectDiv = $(".anketaItem.aboutProject");
            if (event.target.checked) {
                aboutProjectDiv.find("span").css('margin-top', '0');
                $('.mt-10-px').css('margin-top', '10px');
                aboutProjectDiv.css('margin-top', '20px').slideDown();
            }
            else
                aboutProjectDiv.slideUp();
        }

        if (event.target.id === "exhibition") {
            let exhibitionDiv = $(".anketaItem.aboutExhibition");
            if (event.target.checked) {
                exhibitionDiv.find("span").css('margin-top', '0');
                exhibitionDiv.css('margin-top', '20px').slideDown();
            } else {
                exhibitionDiv.slideUp();
            }
        }
    });

    $('#email').bind("propertychange change keyup input paste", function (event) {
        let email = $('#email').val();
        setTimeout(function () {
            if (email === $('#efmail').val()) {
                $.ajax({
                    url: '../xprivate/isEmailExistInDB.php',
                    method: "POST",
                    data: {
                        email: email
                    }
                }).done(function (response) {
                    if (response !== 'null') {
                        participant = JSON.parse(response);
                        participant.goHackaton = participant.hackathon_key !== null && participant.hackathon_key !== '';
                        participant.hasProject = (participant.project_title !== null && participant.project_title !== '');
                        participant.exhibition = (participant.company !== null && participant.company !== '');
                        if (participant.goHackaton && participant.hasProject) {
                            $('.message').html('Ви вже зареєструвалися на хакатон і додали свій проект.  Якщо у вас виникли питання напишіть нам на пошту: <a href="mailto:vesna@programming.kr.ua">vesna@programming.kr.ua</a>');
                            $('.hackenproject').slideDown();

                            $('.regbutton.submit').hide();
                            $('.updatebutton').hide();
                            $('.team').hide();
                            $('.my-team').hide();

                            $('.aboutProject').hide();
                            $('.project').slideUp();
                            $('.hackathon').hide();

                        } else if (participant.goHackaton) {
                            state = 'alreadyHackathon';
                            $('.message').html('Ви вже зареструвалися на хакатон. Можете додати проект.  Якщо у вас виникли питання напишіть нам на пошту: <a href="mailto:vesna@programming.kr.ua">vesna@programming.kr.ua</a>');
                            $('.hackenproject').slideDown();

                            $('.regbutton.submit').hide();
                            $('.updatebutton').show();

                            $('.team').hide();
                            $('.my-team').hide();

                            $('.project').show();
                            $('.hackathon').hide();

                        } else if (participant.hasProject) {
                            state = 'alreadyProject';

                            $('.message').html('Ви вже додали свій проект. Можете зареєструватися на хакатон.  Якщо у вас виникли питання напишіть нам на пошту: <a href="mailto:vesna@programming.kr.ua">vesna@programming.kr.ua</a>');
                            $('.hackenproject').slideDown();

                            $('.regbutton.submit').hide();
                            $('.updatebutton').show();

                            $('.aboutProject').hide();
                            $('.project').hide();
                            $('.hackathon').show();
                        } else if (participant.exhibition) {
                            state = 'alreadyExhibition';

                            $('.message').html('Ви вже зареєструвалися на виставку.  Можете додати проект, або взяти участь у хакатоні. Якщо у вас виникли питання напишіть нам на пошту: <a href="mailto:vesna@programming.kr.ua">vesna@programming.kr.ua</a>');
                            $('.hackenproject').slideDown();

                            $('.regbutton.submit').hide();
                            $('.updatebutton').show();

                            $('.aboutexhibition').hide();
                            $('.exhibition').hide();
                            $('.hackathon').show();
                        } else {
                            $('.hackenproject').hide();

                            $('.regbutton.submit').show();
                            $('.updatebutton').hide();

                            $('.project').show();
                            $('.hackathon').show();
                        }
                    }
                });
            }
        }, 900);

    });
});

function paintBorderRed(selector) {
    selector.css({"border-color": "red"});
}

$('.form-input').focus(function () {
    $(this).css({"border-color": "#1B7E5A"});
});

function scrollToDiv(element, extraScroll) {
    extraScroll = extraScroll || 0;
    let offset = element.offset();
    let offsetTop = offset.top;
    let totalScroll = offsetTop + extraScroll;

    $('html, body').animate({scrollTop: totalScroll}, 300);
}

function customLoading(elem, action) {
    action = action || "create";
    if (action === "create") {
        elem.addClass("customLoading");
    }
    else {
        elem.removeClass("customLoading");
    }
}

let alerted = false;
let guest = false;

let state = '';
let regexpName = /^['"А-Яа-яЁёІіЇїєЄґҐa-zA-Z\s\-]+$/;
let regexpAge = /^\d{1,2}$/;
let regexpPhone = /^[0-9]{10,12}$/;
let regexpMail = /^.+@[\w]+\.[\w]+$/;
let regexpAboutProject = /[\{\}\[\]\<\>\;]/g;

let validation = true;
let fio = '';
let age = '';
let email = '';
let phone = '';
let city = '';

function getPersonInfo() {
    fio = $('#FIO').val();
    age = $('#age').val();
    email = $('#email').val();
    phone = $('#phone').val();
    city = $('#city').val();
    guest = $("#guest").prop('checked');
}

function baseValidation() {
    if (!(fio.match(regexpName))) {
        paintBorderRed($('#FIO'));
        validation = false;
    }
    if (!(age.match(regexpAge) && parseInt(age) > 0)) {
        paintBorderRed($('#age'));
        validation = false;
    }

    if (!(phone.match(regexpPhone))) {
        paintBorderRed($('#phone'));
        validation = false;
    }

    if (!(email.match(regexpMail))) {
        paintBorderRed($('#email'));
        validation = false;
    }

    if(city === ''){
        paintBorderRed($('#email'));
        validation = false;
    }


    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        $(this).addClass("hover");
        setTimeout(function () {
            $(".submit").removeClass("hover");
        }, 400)
    }

    if (!validation) {
        scrollToDiv($("#anketa"));
        return false;
    }
}

let project = '';
let project_category = '';
let projectTitle = '';
let aboutProject = '';

function getProjectInfo() {
    project = $("#project").prop('checked');
    if ($("#programming").prop('checked'))
        project_category += 'programming;';
    if ($("#design").prop('checked'))
        project_category += 'design;';
    if ($("#robotics").prop('checked'))
        project_category += 'robotics;';
    projectTitle = $('#project-title').val();
    aboutProject = $('#aboutProject').val();
}

function projectValidation() {
    if (project && (aboutProject === "" || projectTitle === "" || !robotics && !design && !programming)) {
        if (aboutProject === "") {
            paintBorderRed($('#aboutProject'));
            scrollToDiv($("#aboutProject"), (-$(window).height() / 2))
        }
        if (projectTitle === "") {
            paintBorderRed($('#project-title'));
            scrollToDiv($("#project-title"), (-$(window).height() / 2))
        }
        if (!robotics && !design && !programming) {
            $('#programming').parent('div').addClass("checkboxError");
            $('#design').parent('div').addClass("checkboxError");
            $('#robotics').parent('div').addClass("checkboxError");
            scrollToDiv($("#programming"), (-$(window).height() / 2))
        }
        validation = false;
    }
}

function exhibitionValidation() {
    if (exhibition && (company === "" || exhibition_product === "")) {
        if (company === "") {
            paintBorderRed($('#company-title'));
            scrollToDiv($(".aboutExhibition"), (-$(window).height() / 2))
        }
        if (exhibition_product === "") {
            paintBorderRed($('#exhibition-product'));
            scrollToDiv($(".aboutExhibition"), (-$(window).height() / 2))
        }
        validation = false;
    }
}

let exhibition = '';
let company = '';
let exhibition_product = '';
let interactive_element = '';
let exhibition_needs = '';

function getExhibitionInfo() {
    exhibition = $("#exhibition").prop('checked');
    company = $('#company-title').val();
    exhibition_product = $('#exhibition-product').val();
    interactive_element = $('#exhibition-interactive-element').val();
    exhibition_needs = $('#exhibition-details').val();
}
function makeObject() {
    return {
        fio: fio,
        age: age,
        email: email,
        phone: phone,
        city: city,
        hackathon: false,
        project: project,
        aboutProject: aboutProject,
        projectTitle: projectTitle,
        project_category: project_category,
        exhibition: exhibition,
        company: company,
        exhibition_product: exhibition_product,
        interactive_element: interactive_element,
        exhibition_needs: exhibition_needs,
        guest: guest
    };
};

$(".submit").click(function () { // submit form and validate
    let self = $(this);
    if (self.hasClass('customLoading')) {
        return false;
    }

    validation = true;

    getPersonInfo();
    getProjectInfo();
    getExhibitionInfo();

    phone = phone.replace(/[^\d]+/g, "");
    age = age.replace(/[^\d]+/g, "");
    aboutProject = aboutProject.substring(0, 500).replace(regexpAboutProject, "");
    projectTitle = projectTitle.substring(0, 500).replace(regexpAboutProject, "");

    baseValidation();

    if (!project && !exhibition && !guest) {
        $('.icheckbox_square-green').addClass("checkboxError");
        return false;
    }

    projectValidation();
    exhibitionValidation();

    if (validation) {
        $.ajax({
            url: "../xprivate/saveData.php",
            type: 'POST',
            data: makeObject(),
            beforeSend: function () {
                customLoading(self);
                return true;
            },
            complete: function () {
                customLoading(self, "remove")
            },
            error: function () {
                self.css("background-color", "red")
            }
        })
            .done(function (m) {
            //  alert(m);
                $("#anketa .submit").hide();
                $("#anketa .anketa").slideUp();
                $("#anketa .thanks").show();
                scrollToDiv($("#anketa"));
            });
    }
});
