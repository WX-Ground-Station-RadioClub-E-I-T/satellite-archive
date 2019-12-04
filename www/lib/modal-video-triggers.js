$(document).ready(function(){
  $('#videoModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal

    var title = button.data('title');
    var id = button.data('video-id');
    var path = button.data('path');
    var filter = button.data('filter');
    var date = button.data('date');
    var datecreated = button.data('date-created');
    var numimages = button.data('numimages');
    var duration = button.data('duration');
    var source = button.data('source');
    var rateText = button.data('rate-text');
    var rate = button.data('rate');
    var visits = button.data('visits');

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

    // Delete existing video a generate it
    $("#card-video").remove();      // Star ratet
    $(".video-here").remove();  // Text with the rate
    if(path != ""){

      var videohtml = "<video id=\"card-video\" class=\"video video-js vjs-default-skin\" controls preload=\"auto\" width=\"400\" height=\"213\" poster=\"http://cesar.esa.int/sun_monitor/archive/helios/visible/2018/201803/20180316/image_hel_visible_20180316T103051_processed_thumbnail.jpg\" data-setup=\"{}\"> <source src=\"" + path + "\"type=\'video/mp4\' /> <p class=\"vjs-no-js\"> To view this video please enable JavaScript, and consider upgrading to a web browser that <a href=\"http://videojs.com/html5-video-support/\" target=\"_blank\">supports HTML5 video</a></p></video>";

      var othervar = "<source id=\"video-source\" src=\"20170124_halpha.mp4\" type=\'video/mp4\'/>"
      //$('#card-video').append(othervar);

      $('#card-video-item').append(videohtml);

      videojs(document.getElementById('card-video'), {
        controls: true,
        autoplay: true,
        preload: 'auto',
        plugins: {
          framebyframe: {
            fps: 23.98,
            steps: [
              { text: '-5', step: -5 },
              { text: '-1', step: -1 },
              { text: '+1', step: 1 },
              { text: '+5', step: 5 },
            ]
          }
        }
      });
      vid=document.getElementById("card-video");
      vid.disablePictureInPicture = true;
    }


    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    var modal = $(this);
    modal.find('.modal-title').text(title);
    $("#filter").text(filter);
    $("#date").text(date);
    $("#datecreated").text(datecreated);
    $("#numimages").text(numimages);
    $("#duration").text(duration);
    $("#source").text(source);
    $("#rate").text(rate);
    $("#visits").text(visits);
    $("#path").text(path);

    $('.modal-body .card-img-top').attr('src', "http://cesar.esa.int/sun_monitor/archive/helios/visible/2018/201803/20180316/image_hel_visible_20180316T103051_processed_thumbnail.jpg");
  });
});
