<?php


class ArchiveImage{
    private $id;
    private $path;
    private $filekey;
    private $date_obs;
    private $station_id;
    private $date_updated;
    private $date_upload;
    private $visits;
    private $tags;
    private $featured;
    private $rate;
    private $metadata;
    private $station;

    /**
     * ArchiveImage constructor.
     * @param $id
     * @param $path
     * @param $filekey
     * @param $date_obs
     * @param $station_id
     * @param $date_updated
     * @param $date_upload
     * @param $visits
     * @param $tags
     * @param $featured
     * @param $rate
     */
    public function __construct($id, $path, $filekey, $date_obs, $station_id, $date_updated, $date_upload, $visits, $tags, $featured, $rate)
    {
        $this->id = $id;
        $this->path = $path;
        $this->filekey = $filekey;
        $this->date_obs = $date_obs;
        $this->station_id = $station_id;
        $this->date_updated = $date_updated;
        $this->date_upload = $date_upload;
        $this->visits = $visits;
        $this->tags = $tags;
        $this->featured = $featured;
        $this->rate = $rate;
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return mixed
     */
    public function getFilekey()
    {
        return $this->filekey;
    }

    /**
     * @param mixed $filekey
     */
    public function setFilekey($filekey)
    {
        $this->filekey = $filekey;
    }

    /**
     * @return mixed
     */
    public function getExtSrc(){
      $satellite = $this->metadata->getSatellite();
      if($satellite == "NOAA 19" || $satellite == "NOAA 15" || $satellite == "NOAA 18") {
        return ARCHIVE_ENDPOINT . $this->path . $this->filekey . "-MCIR.png";
      }
      elseif ($satellite == "METEOR-M 2"){
        return ARCHIVE_ENDPOINT . $this->path . $this->filekey . ".png";
      }
    }

    /**
     * return a array with image src on #0 and tooltip text on #1
     */
    public function getImagesSrc(){
      $satellite = $this->metadata->getSatellite();
      $result = array();
      if($satellite == "NOAA 19" || $satellite == "NOAA 15" || $satellite == "NOAA 18") {
        $result[] = array(ARCHIVE_ENDPOINT . $this->path . $this->filekey . "-MCIR.png", BUTTON_MSA_FILE_TOOLTIP);
        $result[] = array(ARCHIVE_ENDPOINT . $this->path . $this->filekey . "-MSA.png", BUTTON_MCIR_FILE_TOOLTIP);
        $result[] = array(ARCHIVE_ENDPOINT . $this->path . $this->filekey . "-HVCT.png", BUTTON_HVCT_FILE_TOOLTIP);
        $result[] = array(ARCHIVE_ENDPOINT . $this->path . $this->filekey . "-THERM.png", BUTTON_THERM_FILE_TOOLTIP);
        $result[] = array(ARCHIVE_ENDPOINT . $this->path . $this->filekey . "-NO.png", BUTTON_NO_FILE_TOOLTIP);
        $result[] = array(ARCHIVE_ENDPOINT . $this->path . $this->filekey . "-MB.png", BUTTON_MB_FILE_TOOLTIP);
        $result[] = array(ARCHIVE_ENDPOINT . $this->path . $this->filekey . "-HF.png", BUTTON_HF_FILE_TOOLTIP);
        $result[] = array(ARCHIVE_ENDPOINT . $this->path . $this->filekey . "-HE.png", BUTTON_HE_FILE_TOOLTIP);
        $result[] = array(ARCHIVE_ENDPOINT . $this->path . $this->filekey . "-EC.png", BUTTON_EC_FILE_TOOLTIP);
        $result[] = array(ARCHIVE_ENDPOINT . $this->path . $this->filekey . "-CC.png", BUTTON_CC_FILE_TOOLTIP);
        $result[] = array(ARCHIVE_ENDPOINT . $this->path . $this->filekey . "-MD.png", BUTTON_MD_FILE_TOOLTIP);
        $result[] = array(ARCHIVE_ENDPOINT . $this->path . $this->filekey . "-ZA.png", BUTTON_ZA_FILE_TOOLTIP);
      }
      elseif ($satellite == "METEOR-M 2"){
        $result[] = array(ARCHIVE_ENDPOINT . $this->path . $this->filekey . ".png", BUTTON_COMPOSITE_FILE_TOOLTIP);
      }
      return $result;
    }

    /**
     * @return Files in a array with file endpoint on #0 and tooltip text on #1
     */
    public function getFilesSrc(){
      $satellite = $this->metadata->getSatellite();
      $result = array();
      if($satellite == "NOAA 19" || $satellite == "NOAA 15" || $satellite == "NOAA 18") {
        $result[] = array(ARCHIVE_ENDPOINT . $this->path . $this->filekey . ".wav", BUTTON_NOAA_WAV_FILE_TOOLTIP);
        $result[] = array(ARCHIVE_ENDPOINT . $this->path . $this->filekey . ".iq", BUTTON_NOAA_IQ_FILE_TOOLTIP);
      }
      elseif ($satellite == "METEOR-M 2"){
        $result[] = array(ARCHIVE_ENDPOINT . $this->path . $this->filekey . ".wav", BUTTON_MET_WAV_FILE_TOOLTIP);
        $result[] = array(ARCHIVE_ENDPOINT . $this->path . $this->filekey . ".qpsk", BUTTON_MET_QPSK_FILE_TOOLTIP);
        $result[] = array(ARCHIVE_ENDPOINT . $this->path . $this->filekey . ".iq", BUTTON_MET_IQ_FILE_TOOLTIP);
        $result[] = array(ARCHIVE_ENDPOINT . $this->path . $this->filekey . ".dec", BUTTON_MET_DEC_FILE_TOOLTIP);
      }
      return $result;
    }

    public function getDirectorySrc(){
      return ARCHIVE_ENDPOINT . $this->path;
    }

    /**
     * @return mixed
     */
    public function getDateObs()
    {
        $date = strtotime($this->date_obs);
        return date('d-m-Y H:i:s', $date);
    }

    /**
     * @return mixed
     */
    public function getDateObsDatetime()
    {
        return  new DateTime($this->date_obs);
    }


    /**
     * @param mixed $date_obs
     */
    public function setDateObs($date_obs)
    {
        $this->date_obs = $date_obs;
    }

    /**
     * @return mixed
     */
    public function getStationId()
    {
        return $this->station_id;
    }

    /**
     * @param mixed $station_id
     */
    public function setStationId($station_id)
    {
        $this->station_id = $station_id;
    }

    /**
     * @return mixed
     */
    public function getDateUpdated()
    {
        $date = strtotime($this->date_updated);
        return date('d-m-Y H:i:s', $date);
    }

    /**
     * @param mixed $date_updated
     */
    public function setDateUpdated($date_updated)
    {
        $this->date_updated = $date_updated;
    }

    /**
     * @return mixed
     */
    public function getDateUpload()
    {
        return $this->date_upload;
    }

    /**
     * @param mixed $date_upload
     */
    public function setDateUpload($date_upload)
    {
        $this->date_upload = $date_upload;
    }

    /**
     * @return mixed
     */
    public function getVisits()
    {
        return $this->visits;
    }

    /**
     * @param mixed $visits
     */
    public function setVisits($visits)
    {
        $this->visits = $visits;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param mixed $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @return mixed
     */
    public function getFeatured()
    {
        return $this->featured;
    }

    /**
     * @param mixed $featured
     */
    public function setFeatured($featured)
    {
        $this->featured = $featured;
    }

    /**
     * @return mixed
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param mixed $rate
     */
    public function setRate($rate)
    {
        $this->rate = $rate;
    }

    /**
     * @return mixed
     */
    public function &getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @param ArchiveMetadata $metadata
     */
    public function setMetadata(ArchiveMetadata &$metadata)
    {
        $this->metadata = $metadata;
    }

    /**
     * @return mixed
     */
    public function &getStation()
    {
        return $this->station;
    }

    /**
     * @param mixed $station
     */
    public function setStation(&$station)
    {
        $this->station = $station;
    }


    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'path' => $this->path,
            'filekey' => $this->filekey,
            'extSrc' => $this->getExtSrc(),
            'date_obs' => $this->date_obs,
            'station_id' => $this->station_id,
            'date_updated' => $this->date_updated,
            'date_upload' => $this->date_upload,
            'visits' => $this->visits,
            'tags' => $this->tags,
            'featured' => $this->featured,
            'rate' => $this->rate,
            'satellite' => $this->metadata->getSatellite(),
            'norad_id' => $this->metadata->getNoradId(),
            'freq' => $this->metadata->getFreq(),
            'transponder' => $this->metadata->getTransponder(),
            'bandwidth' => $this->metadata->getBandwidth(),
            'deviation' => $this->metadata->getDeviation(),
            'codification' => $this->metadata->getCodification(),
            'tle' => $this->metadata->getTle(),
            'tle_date' => $this->metadata->getTleDate(),
            'azi_rise' => $this->metadata->getAziRise(),
            'azi_set' => $this->metadata->getAziSet(),
            'start_epoch' => $this->metadata->getStartEpoch(),
            'end_epoch' => $this->metadata->getEndEpoch(),
            'duration' => $this->metadata->getDuration(),
            'max_elev' => $this->metadata->getMaxElev(),
            'decod_software' => $this->metadata->getDecodSoftware(),
            'radio' => $this->metadata->getRadio(),
            'station_id' => $this->station->getId(),
            'station_name' => $this->station->getName(),
            'station_shordescription' => $this->station->getShortDescription(),
            'station_latitude' => $this->station->getLatitude(),
            'station_longitude' => $this->station->getLongitude(),
            'station_elevation' => $this->station->getElevation(),
            'station_date_created' => $this->station->getDatecreated()
        ];
    }
}