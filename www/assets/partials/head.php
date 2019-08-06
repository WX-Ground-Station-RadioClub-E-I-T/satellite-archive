<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<?php include './lib/conf.php' ?>
<?php include './lib/CesarDatabase.php' ?>
<?php include './lib/CesarImage.php' ?>
<?php include './lib/CesarMetadata.php' ?>
<?php include './lib/CesarObservatory.php' ?>

<title>Cesar Archive Viewer</title>

<link href="http://fonts.googleapis.com/css?family=Roboto+Slab:400,300,700" rel="stylesheet" type="text/css">

<link rel="stylesheet" href="<?php echo DEPENDENCIES_ENDPOINT . "bootstrap/dist/css/bootstrap.min.css"; ?>"/>
<link rel="stylesheet" href="<?php echo DEPENDENCIES_ENDPOINT . "tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.min.css"; ?>"/>
<link rel="stylesheet" href="/assets/css/style.css">

<script type="text/javascript" src="<?php echo DEPENDENCIES_ENDPOINT . "jquery/dist/jquery.min.js"; ?>"></script>
<script type="text/javascript" src="<?php echo DEPENDENCIES_ENDPOINT . "@fortawesome/fontawesome-free/js/all.js"; ?>"></script>
<script type="text/javascript" src="<?php echo DEPENDENCIES_ENDPOINT . "moment/min/moment.min.js"; ?>"></script>
<script type="text/javascript" src="<?php echo DEPENDENCIES_ENDPOINT . "popper.js/dist/umd/popper.min.js"; ?>"></script>
<script type="text/javascript" src="<?php echo DEPENDENCIES_ENDPOINT . "bootstrap/dist/js/bootstrap.min.js"; ?>"></script>
<script type="text/javascript" src="<?php echo DEPENDENCIES_ENDPOINT . "tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.min.js"; ?>"></script>
<script type="text/javascript" src="/lib/modal-triggers.js"></script>

<link rel="apple-touch-icon" sizes="57x57" href="/assets/images/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/assets/images/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/assets/images/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="/assets/images/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="/assets/images/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="/assets/images/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="/assets/images/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="/assets/images/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/assets/images/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="/assets/images/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="/assets/images/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon-16x16.png">
<link rel="manifest" href="/assets/images/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/assets/images/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">

<script type="text/javascript">
  $(function () {
    $('[data-toggle="tooltip"]').tooltip()
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
