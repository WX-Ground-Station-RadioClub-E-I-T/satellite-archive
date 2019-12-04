<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<?php include 'lib/conf.php' ?>
<?php include 'lib/CesarDatabase.php' ?>
<?php include 'lib/CesarImage.php' ?>
<?php include 'lib/CesarMetadata.php' ?>
<?php include 'lib/CesarObservatory.php' ?>
<?php include 'lib/CesarVideo.php' ?>

<?php
  switch(substr ( $_SERVER [ "HTTP_ACCEPT_LANGUAGE" ], 0 , 2 )){
    case "en":
      include 'assets/locale/en_US/strings.php';
      break;
    case "es":
      include 'assets/locale/es_ES/strings.php';
      break;
    default:
      include 'assets/locale/en_US/strings.php';
  }

  $ismobile = false;
  $useragent=$_SERVER['HTTP_USER_AGENT'];
  if(!preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
    $ismobile = true;
  }
?>
<?php

date_default_timezone_set('UTC');

?>

<title>Cesar Archive Viewer</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<meta property="og:title" content="Cesar Archive Viewer" />
<meta name="twitter:title" content="Cesar Archive Viewer" />
<meta name="author" content=""/>
<meta property="og:site_name" content="Cesar Archive Viewer" />
<meta property="og:description"   content="CESAR Educational Initiative sky observations database" />
<meta property="og:url" content="<?php echo "https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}"?>" />
<meta property="og:type" content="website" />
<meta name="twitter:card" content="summary" />
<link href="http://fonts.googleapis.com/css?family=Roboto+Slab:400,300,700" rel="stylesheet" type="text/css">

<link rel="stylesheet" href="<?php echo DEPENDENCIES_ENDPOINT . "bootstrap/dist/css/bootstrap.min.css"; ?>"/>
<link rel="stylesheet" href="<?php echo DEPENDENCIES_ENDPOINT . "tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.min.css"; ?>"/>
<link rel="stylesheet" href="<?php echo DEPENDENCIES_ENDPOINT . "video.js/dist/video-js.min.css"; ?>"/>
<link rel="stylesheet" href="<?php echo DEPENDENCIES_ENDPOINT . "videojs-sublime-skin/dist/videojs-sublime-skin.min.css"; ?>"/>
<link rel="stylesheet" href="assets/css/style.css">

<script type="text/javascript" src="<?php echo DEPENDENCIES_ENDPOINT . "jquery/dist/jquery.min.js"; ?>"></script>
<script type="text/javascript" src="<?php echo DEPENDENCIES_ENDPOINT . "@fortawesome/fontawesome-free/js/all.js"; ?>"></script>
<script type="text/javascript" src="<?php echo DEPENDENCIES_ENDPOINT . "moment/min/moment.min.js"; ?>"></script>
<script type="text/javascript" src="<?php echo DEPENDENCIES_ENDPOINT . "popper.js/dist/umd/popper.min.js"; ?>"></script>
<script type="text/javascript" src="<?php echo DEPENDENCIES_ENDPOINT . "bootstrap/dist/js/bootstrap.min.js"; ?>"></script>
<script type="text/javascript" src="<?php echo DEPENDENCIES_ENDPOINT . "tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.min.js"; ?>"></script>
<script type="text/javascript" src="<?php echo DEPENDENCIES_ENDPOINT . "video.js/dist/video.min.js"; ?>"></script>
<script type="text/javascript" src="<?php echo DEPENDENCIES_ENDPOINT . "videojs-framebyframe/dist/videojs.framebyframe.min.js"; ?>"></script>
<script type="text/javascript" src="lib/modal-triggers.js"></script>
<script type="text/javascript" src="lib/modal-video-triggers.js"></script>
<script type="text/javascript" src="assets/js/rater.min.js"></script>

<link rel="apple-touch-icon" sizes="57x57" href="assets/images/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="assets/images/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="assets/images/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="assets/images/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="assets/images/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="assets/images/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="assets/images/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="assets/images/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="assets/images/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="assets/images/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="assets/images/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon-16x16.png">
<link rel="manifest" href="assets/images/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="assets/images/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">

<script type="text/javascript">
  $(function () {
    $('[data-toggle="tooltip"]').tooltip({
      trigger : 'hover'
    });
  });


  $.fn.datetimepicker.Constructor.Default = $.extend({}, $.fn.datetimepicker.Constructor.Default, {
    viewMode: 'days',
    format:'DD/MM/YYYY',
    icons: {
      time: 'far fa-clock',
      date: 'far fa-calendar',
      up: 'fas fa-arrow-up',
      down: 'fas fa-arrow-down',
      previous: 'fas fa-chevron-left',
      next: 'fas fa-chevron-right',
      today: 'far fa-calendar-check-o',
      clear: 'far fa-trash',
      close: 'far fa-times'}
    });

</script>

<script type="text/javascript">
$.fn.datetimepicker.Constructor.Default = $.extend({}, $.fn.datetimepicker.Constructor.Default, {
            icons: {
                time: 'far fa-clock',
                date: 'far fa-calendar',
                up: 'fas fa-arrow-up',
                down: 'fas fa-arrow-down',
                previous: 'fas fa-chevron-left',
                next: 'fas fa-chevron-right',
                today: 'far fa-calendar-check-o',
                clear: 'far fa-trash',
                close: 'far fa-times'
            } });

</script>
