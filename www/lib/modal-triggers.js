$(document).ready(function(){
  $('#imageModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal

    var station = button.data('station');
    var dateObs = button.data('date-obs');
    var radio = button.data('radio');
    var lat = button.data('station-lat');
    var long = button.data('station-long');
    var ele = button.data('station-ele');
    var id = button.data('image-id');
    var src = button.data('image-src');
    var satellite = button.data('satellite');
    var rate = button.data('rate');
    var rateText = button.data('rate-text');
    var title = button.data('title');

    // Delete existing rate(previous modal) and generate it if there is a rate on the DB
    $(".rate").remove();      // Star ratet
    $("#rateitem").remove();  // Text with the rate
    if(rate != ""){
      var rateprophtml = "<li class=\"list-group-item\" id=\"rateitem\"><b>" + rateText + ":</b> <a id=\"ratetext\">" + rate + "</a></li>";
      $("#properties").append(rateprophtml);
      var ratehtml = "<div class=\"rate\" data-rate-value=" +  rate + "></div>";
      $('#ratetext').text(rate);
      $('#rating').attr('data-rate-value', rate);
      $('#ratecont').append(ratehtml);
      $(".rate").rate({
        max_value: 5,
        step_size: 0.5,
        readonly: true,
        initial_value: rate
      });
    }



    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    var modal = $(this);
    modal.find('.modal-title').text(title);
    $("#date-uploaded").text(dateObs);
    $("#radio").text(radio);
    $("#lat").text(lat + "°");
    $("#long").text(long + "°");
    $("#ele").text(ele + " m");
    $('.modal-body .card-img-top').attr('src', src);
    $('.modal-footer a[href]').attr('href', 'singleobs.php?id=' + id);
  });
});
