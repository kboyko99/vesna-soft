let key;
let socket = io.connect("http://new.shpp.me:9347");

function pad(num, size) {
    var s = num+"";
    while (s.length < size) s = "0" + s;
    return s;
}

socket.on("key", function (key) {
    console.log("newkey = " + key);
    setupIframe(key);
});

let countdown = 600;
let message = 'До конца осталось: ';
let interval;
socket.on("countdown_to_start", (cd) => {
    countdown = cd;
    message = "До начала осталось: \n";
    $(".contact-us").removeClass('hidden');
    console.log('start log');
});
socket.on("countdown_to_end", (cd) => {
    countdown = cd;
    $(".contact-us").addClass('hidden');
    message = 'До конца осталось: ';
    console.log('end log');
});
socket.on("game_over", ()=>{
    console.log("thfis");
});
socket.on("success", (key)=>{
    countdown = 0;
    let msg = "Ваш секретный ключ: " + key;
    $('.message').html(msg);
    $(".b-popup").removeClass('hidden');
});
socket.on("disconnect", ()=>{
    $(".contact-us").addClass('hidden');
});
socket.on("connect_failed", ()=>{
    $(".contact-us").addClass('hidden');
});
function setupIframe(key) {
    $("#terminal").attr("src", "http://new.shpp.me:9349/" + key + "/");
}
function autoType(elementClass, typingSpeed){
    let thhis = $(elementClass);
    thhis.css({
        "position": "relative",
        "display": "inline-block"
    });
    thhis.prepend('<div class="cursor" style="right: initial; left:0;"></div>');
    thhis = thhis.find(".text-js");
    let text = thhis.text().trim().split('');
    let amntOfChars = text.length;
    let newString = "";
    thhis.text("|");
    setTimeout(function(){
        thhis.css("opacity",1);
        thhis.prev().removeAttr("style");
        thhis.text("");
        for( let i = 0; i < amntOfChars; i++ ){
            (function(i,char){
                setTimeout(function() {
                    newString += char;
                    thhis.text(newString);
                },i*typingSpeed);
            })(i+1,text[i]);
        }
    },1500);
}

let countDown = function () {
    setTimeout(()=>{
        countdown--;
        $('.countdown_info').html(message).css('display: inline-block');
        let minutes = Math.floor(countdown/60);
        let seconds = countdown % 60;
        $("#countdown").html(minutes + ":" + pad(seconds, 2));
        if (countdown >= 0)
            countDown();
        else
            $("#countdown").html("0");
    }, 1000)
};
$('.close-button').on('click', ()=>{
   closePopUp();
});
function closePopUp() {
    $(".b-popup").addClass('hidden');
}
$(document).ready(function(){
    $(".b-popup").addClass('hidden');
    autoType(".type-js",30);
    setTimeout(()=>{
        countDown();
        $(".countdown-container").removeClass('hidden');
        // $(".b-popup").removeClass('hidden');
    }, 15000);
});