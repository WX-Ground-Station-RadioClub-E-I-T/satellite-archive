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
    $obj = new CesarDatabase(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DATABASE);
    $obj->addVisitObs($imageId);    // Adding a new visit to observation
    $avrRate = $obj->getAvrRate($imageId);

    $pic = $obj->getImageById($imageId);
    ?>

    <meta property="og:image"         content="<?php  echo $pic->getExtSrcBitmap()?>" />

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
<div class="container">

  <!-- Page Features -->
  <div class="row main-block">
    <div class="col">
      <div class="xzoom-container">
        <img class="xzoom card-img-top" id="xzoom-default" src="<?php echo $pic->getExtSrc(); ?>" xoriginal="<?php echo $pic->getExtSrc(); ?>" />
      </div>

      <div class="card bg-secondary icons-sub-logo">
        <div class="card-header">
          <a href="generatecsv.php?id=<?php echo $pic->getId(); ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo BUTTON_CSV_TOOLTIP; ?>"><i class="fas fa-file-csv icon" style="color:#00a9e0"></i></a>
          <a href="generatejson.php?id=<?php echo $pic->getId(); ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo BUTTON_JSON_TOOLTIP; ?>"><i class="far fa-file-code icon" style="color:#00a9e0"></i></a>
          <a href="<?php echo $pic->getExtSrc(); ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo BUTTON_THUMB_TOOLTIP; ?>"><i class="far fa-image icon" style="color:#00a9e0"></i></a>
          <a href="<?php echo $pic->getExtSrcLarge(); ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo BUTTON_LARGE_TOOLTIP; ?>"><i class="far fa-image icon" style="color:#00a9e0"></i></a>
          <a href="<?php echo $pic->getExtSrcBitmap(); ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo BUTTON_BITMAP_TOOLTIP; ?>"><i class="far fa-image icon" style="color:#00a9e0"></i></a>
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


        <?php

        $video = $obj->getVideoFromPic($pic->getId());

        // If that pic appears in a video
        if($video!= null){
          $card_title = VIDEO_APPEARS;
          // Get the best image of that day, if not, put a sample pic
          $previewPic = $obj->getVideoPreviewPic($video->getId());
          $previewPicSrc = ($previewPic != NULL)? $previewPic->getExtSrc() : (($video->getFilter() == 'visible')? VIDEO_PREVIEW_VISIBLE_PIC_SAMPLE : VIDEO_PREVIEW_HALPHA_PIC_SAMPLE);

          echo <<<END
          <div class="col mt-4 mb-4 video-block">
            <div class="card">
              <div class="card-body">
                <h4>{$card_title}</h4>
                <video id="video" class="video-js vjs-default-skin" controls preload="auto" width="640" height="420"
                poster="{$previewPicSrc}"
                data-setup="{}">
                  <source src="{$video->getExtSrc()}" type='video/mp4'/>
                  <p class="vjs-no-js">
                    To view this video please enable JavaScript, and consider upgrading to a web browser that
                    <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                  </p>
                </video>
                <script type='text/javascript'>
                var video = videojs("video", {
                  controls: true,
                  autoplay: true,
                  preload: 'auto',
                  plugins: {
                    framebyframe: {
                      fps: 15,
                      steps: [
                        { text: '-5', step: -5 },
                        { text: '-1', step: -1 },
                        { text: '+1', step: 1 },
                        { text: '+5', step: 5 },
                      ]
                    }
                  }
                });
                vid=document.getElementById("video");
                vid.disablePictureInPicture = true;
                </script>
              </div>
            </div>
          </div>
END;
        }

        ?>



      </div>
    </div>

    <div class="col">
      <div class="col-sm">
        <ul class="list-group list-group-flush">
          <li class="list-group-item list-group-item-primary" data-toggle="tooltip" data-placement="top" title="<?php echo META_SOURCE_TOOLTIP; ?>"><b class="float-left"><?php echo META_SOURCE; ?>:</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getSource(); ?></a></li>
          <li class="list-group-item list-group-item-primary" data-toggle="tooltip" data-placement="top" title="<?php echo META_SHORT_DESC_TOOLTIP; ?>"><b class="float-left"><?php echo META_SHORT_DESC; ?>:</b> <a class="float-right" id="alt"><?php echo $pic->getObservatory()->getShortDescription(); ?></a></li>
          <li class="list-group-item list-group-item-success" data-toggle="tooltip" data-placement="top" title="<?php echo META_DATE_OBS_TOOLTIP; ?>"><b class="float-left"><?php echo META_DATE_OBS; ?>:</b> <a class="float-right" id="alt"><?php echo $pic->getDateObs(); ?></a></li>
          <li class="list-group-item list-group-item-success" data-toggle="tooltip" data-placement="top" title="<?php echo META_IMG_ID_TOOLTIP; ?>"><b class="float-left"><?php echo META_IMG_ID; ?>:</b> <a class="float-right" id="alt"><?php echo $pic->getId(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="<?php echo META_LST_TOOLTIP; ?>"><b class="float-left"><?php echo META_LST; ?>:</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getLst(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="<?php echo META_SUN_XY_PX_TOOLTIP; ?>"><b class="float-left"><?php echo META_SUN_XY_PX; ?>:</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getSunXyPx(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="<?php echo META_BITPIX_TOOLTIP; ?>"><b class="float-left"><?php echo META_BITPIX; ?>:</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getBitpix(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="<?php echo META_NAXIS_TOOLTIP; ?>"><b class="float-left"><?php echo META_NAXIS; ?>:</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getNaxis(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="<?php echo META_NAXIS1_TOOLTIP; ?>"><b class="float-left"><?php echo META_NAXIS1; ?>:</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getNaxis1(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="<?php echo META_NAXIS2_TOOLTIP; ?>"><b class="float-left"><?php echo META_NAXIS2; ?>:</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getNaxis2(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="<?php echo META_HIST_TOOLTIP; ?>"><b class="float-left"><?php echo META_HIST; ?>:</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getHistory(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="<?php echo META_MOUNT_FLIP_TOOLTIP; ?>"><b class="float-left"><?php echo META_MOUNT_FLIP; ?>:</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getMntFlip(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="<?php echo META_BLACK_TOOLTIP; ?>"><b class="float-left"><?php echo META_BLACK; ?>:</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getBlack(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="<?php echo META_EPH_SUN_DIAM_PX_TOOLTIP; ?>"><b class="float-left"><?php echo META_EPH_SUN_DIAM_PX; ?>:</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getEphSunDiamPx(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="<?php echo META_ORIGINAL_SHAPE_TOOLTIP; ?>"><b class="float-left"><?php echo META_ORIGINAL_SHAPE; ?>:</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getOriginalShape(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="<?php echo META_COLOR_GAMMA_TOOLTIP; ?>"><b class="float-left"><?php echo META_COLOR_GAMMA; ?>:</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getColorGamma(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="<?php echo META_UNSHARP_GAMMA_TOOLTIP; ?>"><b class="float-left"><?php echo META_UNSHARP_GAMMA; ?>:</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getUnsharpGamma(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="<?php echo META_FILTER_TOOLTIP; ?>"><b class="float-left"><?php echo META_FILTER; ?>:</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getFilter(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="<?php echo META_PIPELINE_CONFIG_MODE_TOOLTIP; ?>"><b class="float-left"><?php echo META_PIPELINE_CONFIG_MODE; ?>:</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getPipelineConfigMode(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="<?php echo META_UNSHARP_FLAG_TOOLTIP; ?>"><b class="float-left"><?php echo META_UNSHARP_FLAG; ?>:</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getUnsharpFlag(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="<?php echo META_MASK_LOW_TOOLTIP; ?>"><b class="float-left"><?php echo META_MASK_LOW; ?>:</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getMaskLow(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="<?php echo META_STRETCH_INPUT_TOOLTIP; ?>"><b class="float-left"><?php echo META_STRETCH_INPUT; ?>:</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getStretchInput(); ?></a></li>
          <li class="list-group-item list-group-item-dark" data-toggle="tooltip" data-placement="top" title="<?php echo META_FILESIZE_TOOLTIP; ?>"><b class="float-left"><?php echo META_FILESIZE; ?>:</b> <a class="float-right" id="alt"><?php echo $pic->getFilesizeProcessed(); ?></a></li>
          <li class="list-group-item list-group-item-info" data-toggle="tooltip" data-placement="top" title="<?php echo META_OBS_NAME_TOOLTIP; ?>"><b class="float-left"><?php echo META_OBS_NAME; ?>:</b> <a class="float-right" id="alt"><?php echo $pic->getObservatory()->getName(); ?></a></li>
          <li class="list-group-item list-group-item-info" data-toggle="tooltip" data-placement="top" title="<?php echo META_OPR_SIN_TOOLTIP; ?>"><b class="float-left"><?php echo META_OPR_SIN; ?>:</b> <a class="float-right" id="alt"><?php echo $pic->getObservatory()->getDateCreated(); ?></a></li>
          <li class="list-group-item list-group-item-info" data-toggle="tooltip" data-placement="top" title="<?php echo META_OPR_BY_TOOLTIP; ?>"><b class="float-left"><?php echo META_OPR_BY; ?>:</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getOrigin(); ?></a></li>
          <li class="list-group-item list-group-item-info" data-toggle="tooltip" data-placement="top" title="<?php echo META_LAT_TOOLTIP; ?>"><b class="float-left"><?php echo META_LAT; ?>:</b> <a class="float-right" id="alt" align="right"><?php echo $pic->getMetadata()->getLatitude(); ?></a></li>
          <li class="list-group-item list-group-item-info" data-toggle="tooltip" data-placement="top" title="<?php echo META_LAT_TOOLTIP; ?>"><b class="float-left"><?php echo META_LONG; ?>:</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getLongitud(); ?></a></li>
          <li class="list-group-item list-group-item-info" data-toggle="tooltip" data-placement="top" title="<?php echo META_ALT_TOOLTIP; ?>"><b class="float-left"><?php echo META_ALT; ?>:</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getAltitude(); ?></a></li>
          <li class="list-group-item list-group-item-info" data-toggle="tooltip" data-placement="top" title="<?php echo META_TEL_RA_TOOLTIP; ?>"><b class="float-left"><?php echo META_TEL_RA; ?>:</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getTelRa(); ?></a></li>
          <li class="list-group-item list-group-item-info" data-toggle="tooltip" data-placement="top" title="<?php echo META_TEL_DEC_TOOLTIP; ?>"><b class="float-left"><?php echo META_TEL_DEC; ?>:</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getTelDec(); ?></a></li>
          <li class="list-group-item list-group-item-info" data-toggle="tooltip" data-placement="top" title="<?php echo META_TEL_AZ_TOOLTIP; ?>"><b class="float-left"><?php echo META_TEL_AZ; ?>:</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getTelAz(); ?></a></li>
          <li class="list-group-item list-group-item-info" data-toggle="tooltip" data-placement="top" title="<?php echo META_TEL_ALT_TOOLTIP; ?>"><b class="float-left"><?php echo META_TEL_ALT; ?>:</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getTelAlt(); ?></a></li>
          <li class="list-group-item list-group-item-info" data-toggle="tooltip" data-placement="top" title="<?php echo META_TEL_TOOLTIP; ?>"><b class="float-left"><?php echo META_TEL; ?>:</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getTelescop(); ?></a></li>
          <li class="list-group-item list-group-item-info" data-toggle="tooltip" data-placement="top" title="<?php echo META_INST_TOOLTIP; ?>"><b class="float-left"><?php echo META_INST; ?>:</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getInstrume(); ?></a></li>
          <li class="list-group-item list-group-item-info" data-toggle="tooltip" data-placement="top" title="<?php echo META_TIME_EXP_TOOLTIP; ?>"><b class="float-left"><?php echo META_TIME_EXP; ?>:</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getExposure() ." ms"; ?></a></li>
          <li class="list-group-item list-group-item-info" data-toggle="tooltip" data-placement="top" title="<?php echo META_SCRPT_TOOLTIP; ?>"><b class="float-left"><?php echo META_SCRPT; ?>:</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getScript(); ?></a></li>
          <li class="list-group-item list-group-item-info" data-toggle="tooltip" data-placement="top" title="<?php echo META_MNT_NAME_TOOLTIP; ?>"><b class="float-left"><?php echo META_MNT_NAME; ?>:</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getMntName(); ?></a></li>
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
