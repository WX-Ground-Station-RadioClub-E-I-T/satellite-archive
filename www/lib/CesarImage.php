<?php
/**
  * Class cesarDatabase
  * cesarImage class that holds the information from the database of each image.
  *
  * @author Fran Acien (https://github.com/acien101)
  */

class CesarImage implements JsonSerializable{
    private $id;
    private $path;
    private $filename_final;
    private $filename_original;
    private $filename_thumb;
    private $date_obs;
    private $observatory_id;
    private $filesize_processed;
    private $date_updated;
    private $date_upload;
    private $visits;
    private $tags;
    private $metadata;
    private $observatory;

    /**
     * cesarImage constructor.
     * @param $id
     * @param $path
     * @param $filename_final
     * @param $filename_original
     * @param $ext_src
     * @param $filename_thumb
     * @param $date_obs
     * @param $filesize_processed
     * @param $date_updated
     * @param $visits
     * @param $tags
     */
    public function __construct($id, $path, $filename_final, $filename_original, $filename_thumb, $date_obs, $filesize_processed, $date_updated, $visits, $tags, $date_upload)
    {
        $this->id = $id;
        $this->path = $path;
        $this->filename_final = $filename_final;
        $this->filename_original = $filename_original;
        $this->filename_thumb = $filename_thumb;
        $this->date_obs = $date_obs;
        $this->filesize_processed = $filesize_processed;
        $this->date_updated = $date_updated;
        $this->visits = $visits;
        $this->tags = $tags;
        $this->date_upload = $date_upload;
    }


    public function mostrarVar() {
        echo $this->var;
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
    public function getFilenameFinal()
    {
        return $this->filename_final;
    }

    /**
     * @param mixed $filename_final
     */
    public function setFilenameFinal($filename_final)
    {
        $this->filename_final = $filename_final;
    }

    /**
     * @return mixed
     */
    public function getFilenameOriginal()
    {
        return $this->filename_original;
    }

    /**
     * @param mixed $filename_original
     */
    public function setFilenameOriginal($filename_original)
    {
        $this->filename_original = $filename_original;
    }

    /**
     * @return mixed
     */
    public function getExtSrc(){
        return ARCHIVE_ENDPOINT . $this->path . $this->filename_thumb;
    }

    /**
     * @return mixed
     */
    public function getFilenameThumb()
    {
        return $this->filename_thumb;
    }

    /**
     * @param mixed $filename_thumb
     */
    public function setFilenameThumb($filename_thumb)
    {
        $this->filename_thumb = $filename_thumb;
    }

    /**
     * @return mixed
     */
    public function getDateObs()
    {
        return $this->date_obs;
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
    public function getFilesizeProcessed()
    {
        return $this->filesize_processed;
    }

    /**
     * @param mixed $filesize_processed
     */
    public function setFilesizeProcessed($filesize_processed)
    {
        $this->filesize_processed = $filesize_processed;
    }

    /**
     * @return mixed
     */
    public function getDateUpdated()
    {
        return $this->date_updated;
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
  public function &getMetadata(){
    return $this->metadata;
  }

  /**
   * @param mixed $metadata
   */
  public function setMetadata(CesarMetadata &$metadata){
    $this->metadata = $metadata;
  }

  /**
   * @return mixed
   */
  public function &getObservatory(){
    return $this->observatory;
  }

  /**
   * @param mixed $observatory
   */
  public function setObservatory(&$observatory){
    $this->observatory = $observatory;
  }

  /**
   * @return mixed
   */
  public function getObservatoryId(){
    return $this->observatory_id;
  }

  /**
   * @param mixed $observatory_id
   */
  public function setObservatoryId($observatory_id){
    $this->observatory_id = $observatory_id;
  }

  /**
   * @return mixed
   */
  public function getDateUpload(){
    return $this->date_upload;
  }

  /**
   * @param mixed $date_upload
   */
  public function setDateUpload($date_upload){
    $this->date_upload = $date_upload;
  }

  public function jsonSerialize(){
    return [
      'id' => $this->id,
      'path' => $this->path,
      'extSrc' => $this->getExtSrc(),
      'filename_final' => $this->filename_final,
      'filename_original' => $this->filename_original,
      'filename_thumb' => $this->filename_thumb,
      'date_obs' => $this->date_obs,
      'filesize_processed' => $this->filesize_processed,
      'date_updated' => $this->date_updated,
      'visits' => $this->visits,
      'tags' => $this->tags,
      'date_upload' => $this->date_upload,
      'sun_xy_px' => $this->metadata->getSunXyPx(),
      'bitpix' => $this->metadata->getBitpix(),
      'naxis' => $this->metadata->getNaxis(),
      'naxis1' => $this->metadata->getNaxis1(),
      'naxis2' => $this->metadata->getNaxis2(),
      'history' => $this->metadata->getHistory(),
      'exposure' => $this->metadata->getExposure(),
      'origin' => $this->metadata->getOrigin(),
      'telescop' => $this->metadata->getTelescop(),
      'instrume' => $this->metadata->getInstrume(),
      'script' => $this->metadata->getScript(),
      'mnt_name' => $this->metadata->getMntName(),
      'latitude' => $this->metadata->getLatitude(),
      'longitud' => $this->metadata->getLongitud(),
      'altitude' => $this->metadata->getAltitude(),
      'tel_ra' => $this->metadata->getTelRa(),
      'tel_dec' => $this->metadata->getTelDec(),
      'tel_az' => $this->metadata->getTelAz(),
      'tel_alt' => $this->metadata->getTelAlt(),
      'lst' => $this->metadata->getLst(),
      'mnt_flip' => $this->metadata->getMntFlip(),
      'black' => $this->metadata->getBlack(),
      'eph_sun_diam_px' => $this->metadata->getEphSunDiamPx(),
      'original_shape' => $this->metadata->getOriginalShape(),
      'color_gamma' => $this->metadata->getColorGamma(),
      'observatory_id' => $this->observatory->getId(),
      'observatory_name' => $this->observatory->getName(),
      'observatory_shortdescription' => $this->observatory->getShortDescription(),
      'observatory_sectionurl' => $this->observatory->getSectionUrl(),
      'observatory_datecreated' => $this->observatory->getDateCreated(),
      'observatory_dateupdated' => $this->observatory->getDateUpdated(),
      'observatory_iduserupdate' => $this->observatory->getIdUserUpdate(),
      'observatory_loginuserupdate' => $this->observatory->getLoginUserUpdate()
    ];
  }
}
