<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>LAMP STACK</title>

<link rel="stylesheet" href="/assets/css/style.css">
<link rel="stylesheet" href="/dep/bootstrap/dist/css/bootstrap.min.css" />
<link rel="stylesheet" href="/dep/tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.min.css" />

<script type="text/javascript" src="/dep/jquery/dist/jquery.min.js"></script>
<script type="text/javascript" src="/dep/@fortawesome/fontawesome-free/js/all.js"></script>
<script type="text/javascript" src="/dep/moment/min/moment.min.js"></script>
<script type="text/javascript" src="/dep/bootstrap/dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/dep/tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.min.js"></script>
<script type="text/javascript" src="/lib/modal-triggers.js"></script>

<script type="text/javascript">
  $(function () {
    $('[data-toggle="tooltip"]').tooltip()
  });

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

<?php include './lib/conf.php' ?>
<?php include './lib/CesarDatabase.php' ?>
<?php include './lib/CesarImage.php' ?>
<?php include './lib/CesarMetadata.php' ?>
<?php include './lib/CesarObservatory.php' ?>
