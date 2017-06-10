/**
 * Created by MI on 19.04.2017.
 */
$('input').bind("propertychange change keyup input paste", ()=>{
    let pass = $('#admin_pass').val();
    if(pass === "unsecureadminpass") {
        $('.container').removeClass('hidden');
        document.cookie = "logged=true;";
        $('.auth').hide();
        init();
    }
});
let init = () => {
    $.ajax({
        url: '../test_state.txt',
        method: 'GET'
    }).done( data => {
        if(data === 'on') {
            $('.show-test').addClass('hidden');
            $('.hide-test').removeClass('hidden');
        } else {
            $('.show-test').removeClass('hidden');
            $('.hide-test').addClass('hidden');
        }
    });

};
let toggle = () =>{
    $.ajax({
        url: '../toggleTest.php',
        method: 'GET'
    }).done( data => {
        if(data === 'on') {
            $('.show-test').addClass('hidden');
            $('.hide-test').removeClass('hidden');
        } else {
            $('.show-test').removeClass('hidden');
            $('.hide-test').addClass('hidden');
        }
    });
};
$('.toggle').on('click', ()=>{
    toggle();
});
$(document).ready(()=>{
    let cookie_logged = document.cookie.indexOf('logged') !== -1;
    if(cookie_logged){
        $('.hidden').removeClass('hidden');
        $('.auth').hide();
        init();
    }else{
        $('.auth input').focus();
    }
});
$('#sendmails').on('click', ()=>{
    $.ajax({
        url: 'http://vesnasoft.org/xprivate/sendmails.php',
        method: 'GET'
    }).done( () => {
        $('#sendmails').addClass('hidden');
    });
});
$('#closereg').on('click', ()=>{
    $.ajax({
        url: 'http://vesnasoft.org/xprivate/closeRegistration.php',
        method: 'GET'
    }).done( () => {
        $('#closereq').addClass('hidden');
    });
});