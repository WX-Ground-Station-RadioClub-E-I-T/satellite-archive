<?php

/**
 * Class ArchiveMetadata
 *
 * Metadata of each picture
 *
 * @author Fran Acien (https://github.com/acien101)
 */
class ArchiveMetadata{
  private $sun_xy_px;       //Position of the center of the Sun, e.g.(horizontal, vertical), in pixels relative to the top left corner
  private $bitpix;          //Bits per pixel component
  private $naxis;           //Number of Axis, i.e. dimensions, should be 2 or 3
  private $naxis1;          //Width in pixels
  private $naxis2;          //Height in pixels
  private $history;         //Processing info and other relevant stuff
  private $exposure;        //Exposure length in milliseconds
  private $origin;          //Organisation responsible for data
  private $telescop;        //Name of the data acqusition telescope
  private $instrume;        //Name of the data acqusition instrument
  private $script;          //Path of the processing script
  private $mnt_name;        //Name of the Mount
  private $latitude;        //Observatory latitude
  private $longitud;        //Observatory longitude
  private $altitude;        //Observatory altitude
  private $tel_ra;          //Telescope right ascension
  private $tel_dec;         //Telescope declination
  private $tel_az;          //Telescope azimuth
  private $tel_alt;         //Telescope altitude
  private $lst;             //Local Sidereal Time
  private $mnt_flip;        //Telescope flip: 0 = EAST , 1 = WEST
  private $black;           //Image is dark
  private $eph_sun_diam_px; //Diameter of the Sun in pixels
  private $original_shape;  //Shape of the original (BMP) image array
  private $color_gamma;     //Color adjustment gamma
  private $unsharp_gamma;   //Uncharp Gamma
  private $filter;          //Filter type/ wavelength
  private $pipeline_config_mode;  // Pipeline configuration mode
  private $unsharp_flag;    //Uncharp flag
  private $mask_low;        // Stretching parameters
  private $stretch_input;   // Stretching parametes
  private $source;

  /**
   * @return mixed
   */
  public function getUnsharpGamma(){
    return $this->unsharp_gamma;
  }

  /**
   * @param mixed $unsharp_gamma
   */
  public function setUnsharpGamma($unsharp_gamma){
    $this->unsharp_gamma = $unsharp_gamma;
  }

  /**
   * @return mixed
   */
  public function getFilter(){
    return $this->filter;
  }

  /**
   * @param mixed $filter
   */
  public function setFilter($filter){
    $this->filter = $filter;
  }

  /**
   * @return mixed
   */
  public function getPipelineConfigMode(){
    return $this->pipeline_config_mode;
  }

  /**
   * @param mixed $pipeline_config_mode
   */
  public function setPipelineConfigMode($pipeline_config_mode){
    $this->pipeline_config_mode = $pipeline_config_mode;
  }

  /**
   * @return mixed
   */
  public function getUnsharpFlag(){
    return $this->unsharp_flag;
  }

  /**
   * @param mixed $unsharp_flag
   */
  public function setUnsharpFlag($unsharp_flag){
    $this->unsharp_flag = $unsharp_flag;
  }

  /**
   * @return mixed
   */
  public function getMaskLow(){
    return $this->mask_low;
  }

  /**
   * @param mixed $mask_low
   */
  public function setMaskLow($mask_low){
    $this->mask_low = $mask_low;
  }

  /**
   * @return mixed
   */
  public function getStretchInput(){
    return $this->stretch_input;
  }

  /**
   * @param mixed $stretch_input
   */
  public function setStretchInput($stretch_input){
    $this->stretch_input = $stretch_input;
  }

  /**
   * @return mixed
   */
  public function getSource(){
    return $this->source;
  }

  /**
   * @param mixed $source
   */
  public function setSource($source){
    $this->source = $source;
  }          // Name of the source (Ex: Sun, M51, etc.)

  /**
   * @return mixed
   */
  public function getSunXyPx(){
    return $this->sun_xy_px;
  }

  /**
   * @return mixed
   */
  public function getBitpix(){
    return $this->bitpix;
  }

  /**
   * @return mixed
   */
  public function getNaxis(){
    return $this->naxis;
  }

  /**
   * @return mixed
   */
  public function getNaxis1(){
    return $this->naxis1;
  }

  /**
   * @return mixed
   */
  public function getNaxis2(){
    return $this->naxis2;
  }

  /**
   * @return mixed
   */
  public function getHistory(){
    return $this->history;
  }

  /**
   * @return mixed
   */
  public function getExposure(){
    return $this->exposure;
  }

  /**
   * @return mixed
   */
  public function getOrigin(){
    return $this->origin;
  }

  /**
   * @return mixed
   */
  public function getTelescop(){
    return $this->telescop;
  }

  /**
   * @return mixed
   */
  public function getInstrume(){
    return $this->instrume;
  }

  /**
   * @return mixed
   */
  public function getScript(){
    return $this->script;
  }

  /**
   * @return mixed
   */
  public function getMntName(){
    return $this->mnt_name;
  }

  /**
   * @return mixed
   */
  public function getLatitude(){
    return $this->latitude;
  }

  /**
   * @return mixed
   */
  public function getLongitud(){
    return $this->longitud;
  }

  /**
   * @return mixed
   */
  public function getAltitude(){
    return $this->altitude;
  }

  /**
   * @return mixed
   */
  public function getTelRa(){
    return $this->tel_ra;
  }

  /**
   * @return mixed
   */
  public function getTelDec(){
    return $this->tel_dec;
  }

  /**
   * @return mixed
   */
  public function getTelAz(){
    return $this->tel_az;
  }

  /**
   * @return mixed
   */
  public function getTelAlt(){
    return $this->tel_alt;
  }

  /**
   * @return mixed
   */
  public function getLst(){
    return $this->lst;
  }

  /**
   * @return mixed
   */
  public function getMntFlip(){
    return $this->mnt_flip;
  }

  /**
   * @return mixed
   */
  public function getBlack(){
    return $this->black;
  }

  /**
   * @return mixed
   */
  public function getEphSunDiamPx(){
    return $this->eph_sun_diam_px;
  }

  /**
   * @return mixed
   */
  public function getOriginalShape(){
    return $this->original_shape;
  }

  /**
   * @return mixed
   */
  public function getColorGamma(){
    return $this->color_gamma;
  }

  /**
   * Add params with the id, following the database schema
   */
  public function setById($id, $value){
    switch($id){
      case 2:
        $this->sun_xy_px = $value;
        break;
      case 3:
        $this->bitpix = $value;
        break;
      case 4:
        $this->naxis = $value;
        break;
      case 5:
        $this->naxis1 = $value;
        break;
      case 6:
        $this->naxis2 = $value;
        break;
      case 7:
        $this->history = $value;
        break;
      case 8:
        $this->exposure = $value;
        break;
      case 9:
        $this->origin = $value;
        break;
      case 10:
        $this->telescop = $value;
        break;
      case 11:
        $this->instrume = $value;
        break;
      case 12:
        $this->script = $value;
        break;
      case 13:
        $this->mnt_name = $value;
        break;
      case 14:
        $this->latitude = $value;
        break;
      case 15:
        $this->longitud = $value;
        break;
      case 16:
        $this->altitude = $value;
        break;
      case 17:
        $this->tel_ra = $value;
        break;
      case 18:
        $this->tel_dec = $value;
        break;
      case 19:
        $this->tel_az = $value;
        break;
      case 20:
        $this->tel_alt = $value;
        break;
      case 21:
        $this->lst = $value;
        break;
      case 22:
        $this->mnt_flip = $value;
        break;
      case 23:
        $this->black = $value;
        break;
      case 24:
        $this->eph_sun_diam_px = $value;
        break;
      case 25:
        $this->original_shape = $value;
        break;
      case 26:
        $this->color_gamma = $value;
        break;
      case 27:
        $this->unsharp_gamma = $value;
        break;
      case 28:
        $this->filter = $value;
        break;
      case 29:
        $this->pipeline_config_mode = $value;
        break;
      case 30:
        $this->unsharp_flag = $value;
        break;
      case 31:
        $this->mask_low = $value;
        break;
      case 32:
        $this->stretch_input = $value;
        break;
      case 33:
        $this->unsharp_flag = $value;
        break;
      case 34:
        $this->source = $value;
        break;
    }
  }
}
