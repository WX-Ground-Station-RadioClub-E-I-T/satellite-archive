<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap 4 DatePicker</title>

    <script type="text/javascript" src="/dep/jquery/dist/jquery.min.js"></script>
    <script type="text/javascript" src="/dep/@fortawesome/fontawesome-free/js/all.js"></script>
    <script type="text/javascript" src="/dep/moment/min/moment.min.js"></script>
    <script type="text/javascript" src="/dep/bootstrap/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/dep/tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.min.js"></script>
    <link rel="stylesheet" href="/dep/bootstrap/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/dep/tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.min.css" />

  <!-- include your less or built css files  -->
  <!--
  bootstrap-datetimepicker-build.less will pull in "../bootstrap/variables.less" and "bootstrap-datetimepicker.less";
  or
  <link rel="stylesheet" href="/Content/bootstrap-datetimepicker.css" />
  -->
</head>
<body>
  <div class="container">
      <div class='col-md-5'>
          <div class="form-group">
             <div class="input-group date" id="datetimepicker7" data-target-input="nearest">
                  <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker7"/>
                  <div class="input-group-append" data-target="#datetimepicker7" data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                  </div>
              </div>
          </div>
      </div>
      <div class='col-md-5'>
          <div class="form-group">
             <div class="input-group date" id="datetimepicker8" data-target-input="nearest">
                  <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker8"/>
                  <div class="input-group-append" data-target="#datetimepicker8" data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <script type="text/javascript">
      $(function () {
          $('#datetimepicker7').datetimepicker();
          $('#datetimepicker8').datetimepicker({
              useCurrent: false
          });
          $("#datetimepicker7").on("change.datetimepicker", function (e) {
              $('#datetimepicker8').datetimepicker('minDate', e.date);
          });
          $("#datetimepicker8").on("change.datetimepicker", function (e) {
              $('#datetimepicker7').datetimepicker('maxDate', e.date);
          });
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

</body>
</html>
