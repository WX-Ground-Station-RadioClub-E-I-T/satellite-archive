<!DOCTYPE html>
<html>
<head>
    <?php include 'assets/partials/head.php' ?>
    <script type="text/javascript" src="<?php echo DEPENDENCIES_ENDPOINT . "hammerjs/hammer.min.js"; ?>"></script>
    <script type="text/javascript" src="<?php echo DEPENDENCIES_ENDPOINT . "xzoom/src/xzoom.js"; ?>"></script>
    <script type="text/javascript" src="<?php echo DEPENDENCIES_ENDPOINT . "foundation-sites/dist/js/foundation.min.js"; ?>"></script>
    <script type="text/javascript" src="lib/zoom.js"></script>

    <?php
    $imageId = $_GET["id"];
    $obj = new ArchiveDatabase(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DATABASE);
    $obj->addVisitObs($imageId);    // Adding a new visit to observation
    $avrRate = $obj->getAvrRate($imageId);

    $pass = $obj->getImageById($imageId);

    $localDate = new DateTime();
    $interval = $localDate->diff($pass->getDateObsDatetime());
    $delta =($interval->format('%d') > 0)? $interval->format('%d') . " days ":"";
    $delta .=($interval->format('%h') > 0)? $interval->format('%h') . " hours ":"";
    $delta .=($interval->format('%i') > 0)? $interval->format('%i') . " minutes ":"";
    ?>

    <meta property="og:image"         content="<?php  echo $pass->getExtSrc()?>" />

    <script>
        $(document).ready(function(){
            $(".rate").rate({
                max_value: 5,
                step_size: 0.5,
                change_once: false, // Determines if the rating can only be set once
                ajax_method: 'POST',
                url: './addrate.php',
                additional_data: {id: <?php echo $imageId ?>},
                initial_value: "<?php echo $avrRate;?>"
            });

            $(".rate").on("change", function(ev, data){
              $("#vote-text").text("<?php echo RATE_POST; ?>")
            });

        });
    </script>

</head>
<body>

<!-- Navigation -->
<?php include 'assets/partials/navbar.php'?>

<!-- Page Content -->
<div class="container  mt-3">

  <!-- Page Features -->
  <div class="row main-block">
    <div class="col">
      <div class="xzoom-container">
        <img class="xzoom card-img-top" id="xzoom-default" src="<?php echo $pass->getExtSrc(); ?>" xoriginal="<?php echo $pass->getExtSrc(); ?>" />
        <!-- Thumbnails -->
        <?php
        foreach ($pass->getImagesSrc() as $pic) {
          echo <<<END
          <a href="{$pic[0]}">
            <img class="xzoom-gallery" width="80" src="{$pic[0]}" xpreview="{$pic[0]}">
          </a>
END;
        }
        ?>
      </div>

      <div class="card bg-secondary icons-sub-logo">
        <div class="card-header">
          <a href="generatecsv.php?id=<?php echo $pass->getId(); ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo BUTTON_CSV_TOOLTIP; ?>"><i class="fas fa-file-csv icon" style="color:#00a9e0"></i></a>
          <a href="generatejson.php?id=<?php echo $pass->getId(); ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo BUTTON_JSON_TOOLTIP; ?>"><i class="far fa-file-code icon" style="color:#00a9e0"></i></a>

          <?php
          $i = 0;
          foreach ($pass->getFilesSrc() as $file) {
            echo <<<END
            <a href="{$file[0]}" data-toggle="tooltip" data-placement="bottom" title="{$file[1]}"><i class="far fa-file icon" style="color:#00a9e0"></i></a>
END;
            if($i == 5) break;
          }

          $i = 0; // Counter
          foreach ($pass->getImagesSrc() as $file) {
            echo <<<END
            <a href="{$file[0]}" data-toggle="tooltip" data-placement="bottom" title="{$file[1]}"><i class="far fa-image icon" style="color:#00a9e0"></i></a>
END;
            $i++;
            if($i == 5) break;
          }

          echo "<a href=\"" . $pass->getDirectorySrc() . "\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"" . BUTTON_DIR_TOOLTIP . "\"><i class=\"far fa-folder-open icon\" style=\"color:#00a9e0\"></i></a>";
          ?>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-5 mt-4">
          <div class="card icons-sub-logo">
            <div class="card-body">
              <!-- Load Facebook SDK for JavaScript -->
              <div id="fb-root"></div>
              <script>(function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
                fjs.parentNode.insertBefore(js, fjs);
              }(document, 'script', 'facebook-jssdk'));</script>

              <!-- Facebook share button code -->
              <div class="fb-share-button"
              data-href="<?php echo "https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";?>"
              data-layout="button">
              </div>

              <a href="<?php echo "https://twitter.com/intent/tweet?text=Check%20out%20this%20observation%20from%20ESAC%20Helios%20observatory%20https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo SOCIAL_TWITTER_TOOLTIP; ?>"><i class="fab fa-twitter-square icon" style="color:#1DA1F2"></i></a>
              <a href="<?php echo "mailto:?subject=Check out this observation from ESAC Helios Observatory&amp;body=Check out this site https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}"; ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo SOCIAL_MAIL_TOOLTIP; ?>"><i class="fas fa-envelope icon" style="color:#00a9e0"></i></a>
            </div>
          </div>
        </div>

        <div class="col-sm-6 mt-4 mb-4 rating-block">
          <div class="card">
            <div class="card-body">
              <?php
              $formatedRate = number_format($avrRate, 1);
              if ($avrRate != ""){
                echo "<h4>" . RATE_MAIN . "</h4>
      					<h2 class=\"bold padding-bottom-7\">{$formatedRate}<small>/ 5</small></h2>
                <div class=\"rate\"></div>";
              } else {
                echo "<h4>" . RATE_PRE . "</h4>
                <div class=\"rate\"></div>";
              }
              ?>
              <div id="vote-text"></div>
    					</button>
            </div>
      		</div>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="col-sm">
        <ul class="list-group list-group-flush">
          <li class="list-group-item list-group-item-primary" data-toggle="tooltip" data-placement="top" title="<?php echo META_SOURCE_TOOLTIP; ?>"><b class="float-left"><?php echo META_SATELLITE; ?>:</b> <a class="float-right" id="alt"><?php echo $pass->getMetadata()->getSatellite(); ?></a></li>
          <li class="list-group-item list-group-item-primary" data-toggle="tooltip" data-placement="top" title="<?php echo META_NORAD_ID_TOOLTIP; ?>"><b class="float-left"><?php echo META_NORAD_ID; ?>:</b> <a class="float-right" id="alt"><?php echo $pass->getMetadata()->getNoradId(); ?></a></li>
          <li class="list-group-item list-group-item-success" data-toggle="tooltip" data-placement="top" title="<?php echo META_FREQ_TOOLTIP; ?>"><b class="float-left"><?php echo META_FREQ; ?>:</b> <a class="float-right" id="alt"><?php echo $pass->getMetadata()->getFreq(); ?></a></li>
          <li class="list-group-item list-group-item-success" data-toggle="tooltip" data-placement="top" title="<?php echo META_TRANSPONDER_TOOLTIP; ?>"><b class="float-left"><?php echo META_TRANSPONDER; ?>:</b> <a class="float-right" id="alt"><?php echo $pass->getMetadata()->getTransponder(); ?></a></li>
          <li class="list-group-item list-group-item-success" data-toggle="tooltip" data-placement="top" title="<?php echo META_MAX_ELEV_TOOLTIP; ?>"><b class="float-left"><?php echo META_MAX_ELEV; ?>:</b> <a class="float-right" id="alt"><?php echo $pass->getMetadata()->getMaxElev(); ?></a></li>
          <li class="list-group-item list-group-item-success" data-toggle="tooltip" data-placement="top" title="<?php echo META_DURATION_TOOLTIP; ?>"><b class="float-left"><?php echo META_DURATION; ?>:</b> <a class="float-right" id="alt"><?php echo $pass->getMetadata()->getDuration(); ?></a></li>
          <li class="list-group-item list-group-item-success" data-toggle="tooltip" data-placement="top" title="<?php echo META_RADIO_TOOLTIP; ?>"><b class="float-left"><?php echo META_RADIO; ?>:</b> <a class="float-right" id="alt"><?php echo $pass->getMetadata()->getRadio(); ?></a></li>
          <li class="list-group-item list-group-item-success" data-toggle="tooltip" data-placement="top" title="<?php echo $delta . " ago"; ?>"><b class="float-left"><?php echo META_DATE_OBS; ?>:</b> <a class="float-right" id="alt"><?php echo $pass->getDateObs(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="<?php echo META_SHORT_DESC_TOOLTIP; ?>"><b class="float-left"><?php echo META_SHORT_DESC; ?>:</b> <a class="float-right" id="alt"><?php echo $pass->getStation()->getShortDescription(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="<?php echo META_IMG_ID_TOOLTIP; ?>"><b class="float-left"><?php echo META_IMG_ID; ?>:</b> <a class="float-right" id="alt"><?php echo $pass->getId(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="<?php echo META_OBS_NAME_TOOLTIP; ?>"><b class="float-left"><?php echo META_OBS_NAME; ?>:</b> <a class="float-right" id="alt"><?php echo $pass->getStation()->getName(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="<?php echo META_OPR_SIN_TOOLTIP; ?>"><b class="float-left"><?php echo META_OPR_SIN; ?>:</b> <a class="float-right" id="alt"><?php echo $pass->getStation()->getDateCreated(); ?></a></li>
          <li class="list-group-item list-group-item-info" data-toggle="tooltip" data-placement="top" title="<?php echo META_LAT_TOOLTIP; ?>"><b class="float-left"><?php echo META_LAT; ?>:</b> <a class="float-right" id="alt" align="right"><?php echo $pass->getStation()->getLatitude(); ?></a></li>
          <li class="list-group-item list-group-item-info" data-toggle="tooltip" data-placement="top" title="<?php echo META_LAT_TOOLTIP; ?>"><b class="float-left"><?php echo META_LONG; ?>:</b> <a class="float-right" id="alt"><?php echo $pass->getStation()->getLongitude(); ?></a></li>
          <li class="list-group-item list-group-item-info" data-toggle="tooltip" data-placement="top" title="<?php echo META_ALT_TOOLTIP; ?>"><b class="float-left"><?php echo META_ALT; ?>:</b> <a class="float-right" id="alt"><?php echo $pass->getStation()->getElevation(); ?></a></li>
          <li class="list-group-item list-group-item-info" data-toggle="tooltip" data-placement="top" title="<?php echo META_BANDWIDTH_TOOLTIP; ?>"><b class="float-left"><?php echo META_BANDWIDTH; ?>:</b> <a class="float-right" id="alt"><?php echo $pass->getMetadata()->getBandwidth(); ?></a></li>
          <li class="list-group-item list-group-item-info" data-toggle="tooltip" data-placement="top" title="<?php echo META_DEVIATION_TOOLTIP; ?>"><b class="float-left"><?php echo META_DEVIATION; ?>:</b> <a class="float-right" id="alt"><?php echo $pass->getMetadata()->getDeviation(); ?></a></li>
          <li class="list-group-item list-group-item-info" data-toggle="tooltip" data-placement="top" title="<?php echo META_CODIFICATION_TOOLTIP; ?>"><b class="float-left"><?php echo META_CODIFICATION; ?>:</b> <a class="float-right" id="alt"><?php echo $pass->getMetadata()->getCodification(); ?></a></li>
          <li class="list-group-item list-group-item-info" data-toggle="tooltip" data-placement="top" title="<?php echo META_TLE_TOOLTIP; ?>"><b class="float-left"><?php echo META_TLE; ?>:</b> <a class="float-right" id="alt"><?php echo $pass->getMetadata()->getTle(); ?></a></li>
          <li class="list-group-item list-group-item-info" data-toggle="tooltip" data-placement="top" title="<?php echo META_TLE_DATE_TOOLTIP; ?>"><b class="float-left"><?php echo META_TLE_DATE; ?>:</b> <a class="float-right" id="alt"><?php echo $pass->getMetadata()->getTleDate(); ?></a></li>
          <li class="list-group-item list-group-item-info" data-toggle="tooltip" data-placement="top" title="<?php echo META_AZI_RISE_TOOLTIP; ?>"><b class="float-left"><?php echo META_AZI_RISE; ?>:</b> <a class="float-right" id="alt"><?php echo $pass->getMetadata()->getAziRise(); ?></a></li>
          <li class="list-group-item list-group-item-info" data-toggle="tooltip" data-placement="top" title="<?php echo META_AZI_SET_TOOLTIP; ?>"><b class="float-left"><?php echo META_AZI_SET; ?>:</b> <a class="float-right" id="alt"><?php echo $pass->getMetadata()->getAziSet(); ?></a></li>
          <li class="list-group-item list-group-item-info" data-toggle="tooltip" data-placement="top" title="<?php echo META_EPOCH_STARTS_TOOLTIP; ?>"><b class="float-left"><?php echo META_EPOCH_STARTS; ?>:</b> <a class="float-right" id="alt"><?php echo $pass->getMetadata()->getStartEpoch(); ?></a></li>
          <li class="list-group-item list-group-item-info" data-toggle="tooltip" data-placement="top" title="<?php echo META_EPOCH_ENDS_TOOLTIP; ?>"><b class="float-left"><?php echo META_EPOCH_ENDS; ?>:</b> <a class="float-right" id="alt"><?php echo $pass->getMetadata()->getEndEpoch(); ?></a></li>
          <li class="list-group-item list-group-item-dark" data-toggle="tooltip" data-placement="top" title="<?php echo META_DECOD_SOFTWARE_TOOLTIP; ?>"><b class="float-left"><?php echo META_DECOD_SOFTWARE; ?>:</b> <a class="float-right" id="alt"><?php echo $pass->getMetadata()->getDecodSoftware(); ?></a></li>


        </ul>
      </div>
    </div>
  </div>
  <!-- /.row -->
</div>
<!-- /.container -->

<!-- Footer -->
<?php include 'assets/partials/footer.php'?>
</body>
</html>
