<?php


class ArchiveStation{
    private $id;
    private $name;
    private $shortDescription;
    private $latitude;
    private $longitude;
    private $elevation;
    private $sectionURL;
    private $datecreated;
    private $dateupdated;

    /**
     * ArchiveStation constructor.
     * @param $id
     * @param $name
     * @param $shortDescription
     * @param $latitude
     * @param $longitude
     * @param $elevation
     * @param $sectionURL
     * @param $datecreated
     * @param $dateupdated
     */
    public function __construct($id, $name, $shortDescription, $latitude, $longitude, $elevation, $sectionURL, $datecreated, $dateupdated)
    {
        $this->id = $id;
        $this->name = $name;
        $this->shortDescription = $shortDescription;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->elevation = $elevation;
        $this->sectionURL = $sectionURL;
        $this->datecreated = $datecreated;
        $this->dateupdated = $dateupdated;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * @param mixed $shortDescription
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param mixed $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * @return mixed
     */
    public function getElevation()
    {
        return $this->elevation;
    }

    /**
     * @param mixed $elevation
     */
    public function setElevation($elevation)
    {
        $this->elevation = $elevation;
    }

    /**
     * @return mixed
     */
    public function getSectionURL()
    {
        return $this->sectionURL;
    }

    /**
     * @param mixed $sectionURL
     */
    public function setSectionURL($sectionURL)
    {
        $this->sectionURL = $sectionURL;
    }

    /**
     * @return mixed
     */
    public function getDatecreated()
    {
        $date = strtotime($this->datecreated);
        return date('d-m-Y H:i:s', $date);
    }

    /**
     * @param mixed $datecreated
     */
    public function setDatecreated($datecreated)
    {
        $this->datecreated = $datecreated;
    }

    /**
     * @return mixed
     */
    public function getDateupdated()
    {
        $date = strtotime($this->dateupdated);
        return date('d-m-Y H:i:s', $date);
    }

    /**
     * @param mixed $dateupdated
     */
    public function setDateupdated($dateupdated)
    {
        $this->dateupdated = $dateupdated;
    }
}