<?php

/**
 * Class CesarVideo
 *
 * Video class
 *
 * @author Fran Acien (https://github.com/acien101)
 */
class CesarVideo{
  private $id;
  private $path;
  private $filter;
  private $duration;
  private $numimages;
  private $datecreated;
  private $source;
  private $rate;
  private $visits;

  /**
   * CesarVideo constructor.
   * @param $id
   * @param $path
   * @param $filter
   * @param $duration
   * @param $numimages
   * @param $datecreated
   * @param $source
   * @param $rate
   * @param $visits
   */
  public function __construct($id, $path, $filter, $duration, $numimages, $datecreated, $source, $rate, $visits){
    $this->id = $id;
    $this->path = $path;
    $this->filter = $filter;
    $this->duration = $duration;
    $this->numimages = $numimages;
    $this->datecreated = $datecreated;
    $this->source = $source;
    $this->rate = $rate;
    $this->visits = $visits;
  }

  /**
   * @return mixed
   */
  public function getId(){
    return $this->id;
  }

  /**
   * @param mixed $id
   */
  public function setId($id){
    $this->id = $id;
  }

  /**
   * @return mixed
   */
  public function getPath(){
    return $this->path;
  }

  /**
   * @param mixed $path
   */
  public function setPath($path){
    $this->path = $path;
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
  public function getDuration(){
    return $this->duration;
  }

  /**
   * @param mixed $duration
   */
  public function setDuration($duration){
    $this->duration = $duration;
  }

  /**
   * @return mixed
   */
  public function getNumimages(){
    return $this->numimages;
  }

  /**
   * @param mixed $numimages
   */
  public function setNumimages($numimages){
    $this->numimages = $numimages;
  }

  /**
   * @return mixed
   */
  public function getDatecreated(){
    return $this->datecreated;
  }

  /**
   * @param mixed $datecreated
   */
  public function setDatecreated($datecreated){
    $this->datecreated = $datecreated;
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
  }

  /**
   * @return mixed
   */
  public function getRate(){
    return $this->rate;
  }

  /**
   * @param mixed $rate
   */
  public function setRate($rate){
    $this->rate = $rate;
  }

  /**
   * @return mixed
   */
  public function getVisits(){
    return $this->visits;
  }

  /**
   * @param mixed $visits
   */
  public function setVisits($visits){
    $this->visits = $visits;
  }

  /**
   * @return mixed
   */
  public function getExtSrc(){
      return ARCHIVE_VIDEOS_ENDPOINT . $this->path;
  }
}
