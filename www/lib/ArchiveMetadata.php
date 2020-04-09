<?php


class ArchiveMetadata{
    private $satellite; //Name of the satellite received
    private $norad_id; //NORAD ID of the satellite received
    private $freq; //Frequency of the reception in MHz
    private $transponder; //Transponder of that frequency
    private $bandwidth; //Bandwidth used when demodulation/filtering in Hz
    private $deviation; //Deviation frequency used in demodulation in Hz
    private $codification; //Codification used on the data received
    private $tle; //TLE used on the reception
    private $tle_date; //Date when the TLE was fetched in %Y%m%d-%H%M%S
    private $azi_rise; //Azimuth when the satellite starts (rise) in degrees
    private $azi_set; //Azimuth when ends reception (set) in degrees
    private $start_epoch; //Epoch when the reception starts
    private $end_epoch; //Epoch when the pass ends
    private $duration; //Duration of the pass
    private $max_elev; //Maximun elevation of the pass
    private $direction;
    private $decod_software;
    private $radio;

    /**
     * @return mixed
     */
    public function getSatellite()
    {
        return $this->satellite;
    }

    /**
     * @param mixed $satellite
     */
    public function setSatellite($satellite)
    {
        $this->satellite = $satellite;
    }

    /**
     * @return mixed
     */
    public function getNoradId()
    {
        return $this->norad_id;
    }

    /**
     * @param mixed $norad_id
     */
    public function setNoradId($norad_id)
    {
        $this->norad_id = $norad_id;
    }

    /**
     * @return mixed
     */
    public function getFreq()
    {
        return $this->freq;
    }

    /**
     * @param mixed $freq
     */
    public function setFreq($freq)
    {
        $this->freq = $freq;
    }

    /**
     * @return mixed
     */
    public function getTransponder()
    {
        return $this->transponder;
    }

    /**
     * @param mixed $transponder
     */
    public function setTransponder($transponder)
    {
        $this->transponder = $transponder;
    }

    /**
     * @return mixed
     */
    public function getBandwidth()
    {
        return $this->bandwidth;
    }

    /**
     * @param mixed $bandwidth
     */
    public function setBandwidth($bandwidth)
    {
        $this->bandwidth = $bandwidth;
    }

    /**
     * @return mixed
     */
    public function getDeviation()
    {
        return $this->deviation;
    }

    /**
     * @param mixed $deviation
     */
    public function setDeviation($deviation)
    {
        $this->deviation = $deviation;
    }

    /**
     * @return mixed
     */
    public function getCodification()
    {
        return $this->codification;
    }

    /**
     * @param mixed $codification
     */
    public function setCodification($codification)
    {
        $this->codification = $codification;
    }

    /**
     * @return mixed
     */
    public function getTle()
    {
        return $this->tle;
    }

    /**
     * @param mixed $tle
     */
    public function setTle($tle)
    {
        $this->tle = $tle;
    }

    /**
     * @return mixed
     */
    public function getTleDate()
    {
        return $this->tle_date;
    }

    /**
     * @param mixed $tle_date
     */
    public function setTleDate($tle_date)
    {
        $this->tle_date = $tle_date;
    }

    /**
     * @return mixed
     */
    public function getAziRise()
    {
        return $this->azi_rise;
    }

    /**
     * @param mixed $azi_rise
     */
    public function setAziRise($azi_rise)
    {
        $this->azi_rise = $azi_rise;
    }

    /**
     * @return mixed
     */
    public function getAziSet()
    {
        return $this->azi_set;
    }

    /**
     * @param mixed $azi_set
     */
    public function setAziSet($azi_set)
    {
        $this->azi_set = $azi_set;
    }

    /**
     * @return mixed
     */
    public function getStartEpoch()
    {
        return $this->start_epoch;
    }

    /**
     * @param mixed $start_epoch
     */
    public function setStartEpoch($start_epoch)
    {
        $this->start_epoch = $start_epoch;
    }

    /**
     * @return mixed
     */
    public function getEndEpoch()
    {
        return $this->end_epoch;
    }

    /**
     * @param mixed $end_epoch
     */
    public function setEndEpoch($end_epoch)
    {
        $this->end_epoch = $end_epoch;
    }

    /**
     * @return mixed
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param mixed $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
     * @return mixed
     */
    public function getMaxElev()
    {
        return $this->max_elev;
    }

    /**
     * @param mixed $max_elev
     */
    public function setMaxElev($max_elev)
    {
        $this->max_elev = $max_elev;
    }

    /**
     * @return mixed
     */
    public function getDecodSoftware()
    {
        return $this->decod_software;
    }

    /**
     * @param mixed $decod_software
     */
    public function setDecodSoftware($decod_software)
    {
        $this->decod_software = $decod_software;
    }

    /**
     * @return mixed
     */
    public function getRadio()
    {
        return $this->radio;
    }

    /**
     * @param mixed $radio
     */
    public function setRadio($radio)
    {
        $this->radio = $radio;
    }

    /**
     * @return mixed
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * @param mixed $direction
     */
    public function setDirection($direction)
    {
        $this->direction = $direction;
    } //Decodification software used



    /**
     * Add params with the id, following the database schema
     */
    public function setById($id, $value)
    {
        switch ($id) {
            case 1:
                $this->satellite = $value;
                break;
            case 2:
                $this->norad_id = $value;
                break;
            case 3:
                $this->freq = $value;
                break;
            case 4:
                $this->transponder = $value;
                break;
            case 5:
                $this->bandwidth = $value;
                break;
            case 6:
                $this->deviation = $value;
                break;
            case 7:
                $this->codification = $value;
                break;
            case 8:
                $this->tle = $value;
                break;
            case 9:
                $this->tle_date = $value;
                break;
            case 10:
                $this->azi_rise = $value;
                break;
            case 11:
                $this->azi_set = $value;
                break;
            case 12:
                $this->start_epoch = $value;
                break;
            case 13:
                $this->end_epoch = $value;
                break;
            case 14:
                $this->duration = $value;
                break;
            case 15:
                $this->max_elev = $value;
                break;
            case 16:
                $this->direction = $value;
                break;
            case 17:
                $this->decod_software = $value;
                break;
            case 18:
                $this->radio = $value;
                break;
        }
    }
}