<!DOCTYPE html>
<html>
<head>
    <?php include './assets/partials/head.php' ?>
    <script type="text/javascript" src="/dep/hammerjs/hammer.min.js"></script>
    <script type="text/javascript" src="/dep/xzoom/src/xzoom.js"></script>
    <script type="text/javascript" src="/dep/foundation-sites/dist/js/foundation.min.js"></script>
    <script type="text/javascript" src="/lib/zoom.js"></script>
</head>
<body>

<!-- Navigation -->
<?php include './assets/partials/navbar.php'?>

<!-- Page Content -->
<div class="container">

  <?php
  $imageId = $_GET["id"];
  $obj = new CesarDatabase(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DATABASE);
  $pic = $obj->getImageById($imageId);
  ?>

  <!-- Page Features -->
  <div class="row main-block">


    <div class="col-6">
      <!--
      <img class="card-img-top"
           src="<?php echo $pic->getExtSrc(); ?>"
           alt="">
        -->

      <div class="xzoom-container">
        <img class="xzoom card-img-top" id="xzoom-default" src="<?php echo $pic->getExtSrc(); ?>" xoriginal="<?php echo $pic->getExtSrc(); ?>" />
      </div>


      <div class="card bg-secondary icons-sub-logo">
        <div class="card-header">

          <a href="/test.php?id=<?php echo $pic->getId(); ?>" data-toggle="tooltip" data-placement="bottom" title="CSV file output"><i class="fas fa-file-csv icon" style="color:#00a9e0"></i></a>

          <a href="/generatejson.php?id=<?php echo $pic->getId(); ?>" data-toggle="tooltip" data-placement="bottom" title="JSON file output"><i class="far fa-file-code icon" style="color:#00a9e0"></i></a>

          <a href="<?php echo $pic->getExtSrc(); ?>" data-toggle="tooltip" data-placement="bottom" title="Download Thumbnail"><i class="far fa-image icon" style="color:#00a9e0"></i></a>

          <a href="<?php echo $pic->getExtSrcLarge(); ?>" data-toggle="tooltip" data-placement="bottom" title="Download Large Image"><i class="far fa-image icon" style="color:#00a9e0"></i></a>

          <a href="<?php echo $pic->getExtSrcBitmap(); ?>" data-toggle="tooltip" data-placement="bottom" title="Download Bitmap"><i class="far fa-image icon" style="color:#00a9e0"></i></a>

        </div>
      </div>
    </div>

    <div class="col">
      <div class="col-sm">
        <ul class="list-group list-group-flush">
          <li class="list-group-item list-group-item-primary"><b class="float-left">Observatory Name:</b> <a class="float-right" id="alt"><?php echo $pic->getObservatory()->getName(); ?></a></li>
          <li class="list-group-item list-group-item-primary"><b class="float-left">Source:</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getSource(); ?></a></li>
          <li class="list-group-item list-group-item-primary"><b class="float-left">Short description:</b> <a class="float-right" id="alt"><?php echo $pic->getObservatory()->getShortDescription(); ?></a></li>
          <li class="list-group-item list-group-item-primary"><b class="float-left">Since:</b> <a class="float-right" id="alt"><?php echo $pic->getObservatory()->getDateCreated(); ?></a></li>
          <li class="list-group-item list-group-item-primary" data-toggle="tooltip" data-placement="top" title="Organisation responsible for data"><b class="float-left">Organization</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getOrigin(); ?></a></li>
          <li class="list-group-item list-group-item-success"><b class="float-left">Date observation:</b> <a class="float-right" id="alt"><?php echo $pic->getDateObs(); ?></a></li>
          <li class="list-group-item list-group-item-success"><b class="float-left">Date updated:</b> <a class="float-right" id="alt"><?php echo $pic->getDateUpdated(); ?></a></li>
          <li class="list-group-item list-group-item-success"><b class="float-left">Date uploaded:</b> <a class="float-right" id="alt"><?php echo $pic->getDateUpload(); ?></a></li>
          <li class="list-group-item list-group-item-dark"><b class="float-left">Filesize:</b> <a class="float-right" id="alt"><?php echo $pic->getFilesizeProcessed(); ?></a></li>
          <li class="list-group-item list-group-item-info" data-toggle="tooltip" data-placement="top" title="Observatory latitude"><b class="float-left">Latitude</b> <a class="float-right" id="alt" align="right"><?php echo $pic->getMetadata()->getLatitude(); ?></a></li>
          <li class="list-group-item list-group-item-info" data-toggle="tooltip" data-placement="top" title="Observatory longitude"><b class="float-left">Longitude</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getLongitud(); ?></a></li>
          <li class="list-group-item list-group-item-info" data-toggle="tooltip" data-placement="top" title="Observatory altitude"><b class="float-left">Altitude</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getAltitude(); ?></a></li>
          <li class="list-group-item list-group-item-info" data-toggle="tooltip" data-placement="top" title="Telescope right ascension"><b class="float-left">TEL-RA</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getTelRa(); ?></a></li>
          <li class="list-group-item list-group-item-info" data-toggle="tooltip" data-placement="top" title="Telescope declination"><b class="float-left">TEL-DEC</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getTelDec(); ?></a></li>
          <li class="list-group-item list-group-item-info" data-toggle="tooltip" data-placement="top" title="Telescope azimuth"><b class="float-left">TEL-AZ</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getTelAz(); ?></a></li>
          <li class="list-group-item list-group-item-info" data-toggle="tooltip" data-placement="top" title="Telescope altitude"><b class="float-left">TEL-ALT</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getTelAlt(); ?></a></li>
          <li class="list-group-item" data-toggle="tooltip" data-placement="top" title="Name of the data acqusition telescope"><b class="float-left">Telescop</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getTelescop(); ?></a></li>
          <li class="list-group-item" data-toggle="tooltip" data-placement="top" title="Name of the data acqusition instrument"><b class="float-left">Instrument</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getInstrume(); ?></a></li>
          <li class="list-group-item" data-toggle="tooltip" data-placement="top" title="Exposure length in milliseconds"><b class="float-left">Time exposure</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getExposure() ." ms"; ?></a></li>
          <li class="list-group-item" data-toggle="tooltip" data-placement="top" title="Path of the processing script"><b class="float-left">Script</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getScript(); ?></a></li>
          <li class="list-group-item" data-toggle="tooltip" data-placement="top" title=""><b class="float-left">Mount name</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getMntName(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="Local Sidereal Time"><b class="float-left">LST</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getLst(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="Position of the center of the Sun, e.g.(horizontal, vertical), in pixels relative to the top left corner"><b class="float-left">Sun XY-PX</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getSunXyPx(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="Bits per pixel component"><b class="float-left">BITPIX</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getBitpix(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="Number of Axis, i.e. dimensions, should be 2 or 3"><b class="float-left">NAXIS</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getNaxis(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="Width in pixels"><b class="float-left">NAXIS1</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getNaxis1(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="Height in pixels"><b class="float-left">NAXIS2</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getNaxis2(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="Processing info and other relevant stuff"><b class="float-left">History</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getHistory(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="Telescope flip: 0 = EAST , 1 = WEST"><b class="float-left">Mount flip</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getMntFlip(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="Image is dark"><b class="float-left">Black</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getBlack(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="Diameter of the Sun in pixels"><b class="float-left">EPH-SUN-DIAM-PX</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getEphSunDiamPx(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="Shape of the original (BMP) image array"><b class="float-left">ORIGINAL-SHAPE</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getOriginalShape(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="Color adjustment gamma"><b class="float-left">COLOR-GAMMA</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getColorGamma(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="Unsharp gamma"><b class="float-left">UNSHARP-GAMMA</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getUnsharpGamma(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="Filter type / Wavelength"><b class="float-left">FILTER</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getFilter(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="Pipeline configuration mode"><b class="float-left">PIPELINE-CONFIG-MODE</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getPipelineConfigMode(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="Unsharp flag"><b class="float-left">UNSHARP-FLAG</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getUnsharpFlag(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="Lower value for mask of the Sun"><b class="float-left">MASK-LOW</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getMaskLow(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="Stretching parameters"><b class="float-left">STRETCH-INPUT</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getStretchInput(); ?></a></li>
        </ul>
      </div>
    </div>
  </div>
  <!-- /.row -->
</div>
<!-- /.container -->

<!-- Footer -->
<?php include './assets/partials/footer.php'?>
</body>
</html>
