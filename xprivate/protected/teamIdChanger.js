$( document ).ready(function() {
  $('.apply-button').click(function(event) {
    var parentTd = $(this).parent();
    var teamIdInput = parentTd.find('.team-id-input');
    var oldTeamNumber = parentTd.find('.team-id-input').data('id');
    var newTeamNumber = parentTd.find('.team-id-input').val();
    var participantId = $(this).data('id');
    var teamIdSpan = parentTd.find('.team-id-span');

    if(newTeamNumber != oldTeamNumber){
      $.ajax({
        url: 'changeTeamId.php',
        type: 'POST',
        data: {participantId: participantId, newTeamNumber: newTeamNumber}
      })
      .done(function(response) {
        console.log(response);
        teamIdInput.data('id', newTeamNumber);
        teamIdSpan.text(newTeamNumber);
        teamIdInput.css("border-color", "#2ecc71");
        setTimeout(function(){ teamIdInput.css("border-color", "#000");}, 450)
        showToast('Изменения сохранены', 2000);
      })
      .fail(function(response) {
        alert('some error');
        showToast('Что-то не так: '+ response, 5000);
      })
    }else{
      showToast('Значение не изменилось', 2000);
    }
  });

  function showToast(text, delay){
    toast = $('.toast');
    toast.text(text);
    toast.slideDown();
    setTimeout(function(){toast.slideUp()}, delay);
  }

});
