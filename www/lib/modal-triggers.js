$(document).ready(function(){
  $('#imageModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal

    var observatory = button.data('observatory');
    var dateObs = button.data('date-obs');
    var telescop = button.data('telecop');
    var instrume = button.data('instrume');
    var lat = button.data('observatory-lat');
    var long = button.data('observatory-long');
    var alt = button.data('observatory-alt');
    var exposure = button.data('exposure');
    var id = button.data('image-id');
    var src = button.data('image-src');
    var filter = button.data('filter');
    var source = button.data('source');
    var rate = button.data('rate');

    // Delete existing rate(previous modal) and generate it if there is a rate on the DB
    $(".rate").remove();      // Star ratet
    $("#rateitem").remove();  // Text with the rate
    if(rate != ""){
      var rateprophtml = "<li class=\"list-group-item\" id=\"rateitem\"><b>Rate:</b> <a id=\"ratetext\">" + rate + "</a></li>";
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
    modal.find('.modal-title').text(source + ' observation from ' + observatory);
    $("#date-uploaded").text(dateObs);
    $("#telescope").text(telescop);
    $("#instrume").text(instrume);
    $("#exposure").text(exposure + " s");
    $("#lat").text(lat + "°");
    $("#long").text(long + "°");
    $("#alt").text(alt + " m");
    $("#filter").text(filter);
    $('.modal-body .card-img-top').attr('src', src);
    $('.modal-footer a[href]').attr('href', 'singleobs.php?id=' + id);
  });
});
