<?php

/**
 * DateTimeCarbon
 * 
 * @package Core/Utility
 * @description 
 * @link ttp://php.net/manual/ja/class.datetime.php
 * @link http://php.net/manual/ja/class.datetimezone.php

 */
class DateTimeCarbon {

    protected $_dateTime;
    protected $_format;

    /**
     * @link <a href="http://php.net/manual/ja/class.datetime.php">http://php.net/manual/ja/class.datetime.php</a>
     * @link <a href="http://php.net/manual/ja/class.datetimezone.php">http://php.net/manual/ja/class.datetimezone.php</a>
     * @param null $timezone
     */
    public function __construct($timezone = null, $format = null)
    {
        $this->_format = is_null($format) ? "Y-m-d:H:i:s" : $format;
        $this->_dateTime = new DateTime();
        $this->_dateTime->setTimezone(new DateTimeZone(is_null($timezone) ? "Asia/Tokyo" : $timezone));
        $this->_dateTime->format(is_null($format) ? "Y-m-d:H:i:s" : $format);
    }

    /**
     * @todo <pre>シングルトンが望ましい</pre>
     * @link <a href="http://php.net/manual/ja/class.datetime.php">http://php.net/manual/ja/class.datetime.php</a>
     * @param string $format
     * @return DateTime
     * @throws RuntimeException
     */
    public function getDateTimeObject($format = null)
    {
        if (!$this->_dateTime instanceof DateTime)
            throw new RuntimeException(sprintf("%s::_dateTime Must be a DateTimeObject", get_class($this)));
        $this->_dateTime->format(is_null($format) ? $this->_format : $format);
        return $this->_dateTime;
    }

    /**
     * 
     * @return string
     */
    public function getDateTimeAsString()
    {
        return (string) $this->_dateTime->format(is_null($this->_format) ? "Y-m-d:H:i:s" : $this->_format);
    }

    /**
     * 
     * @param string $format
     * @return string
     */
    public function format($format = null)
    {
        return $this->_dateTime->format(is_null($format) ? "Y-m-d:H:i:s" : $format);
    }

    /**
     * 
     * @return string
     */
    public function getYear()
    {
        return $this->_dateTime->format("Y");
    }

    /**
     * 
     * @return string
     */
    public function getMonth()
    {
        return $this->_dateTime->format("m");
    }

    /**
     * 
     * @return string
     */
    public function getDate()
    {
        return $this->_dateTime->format("d");
    }

    /**
     * 
     * @return string
     */
    public function getHour()
    {
        return $this->_dateTime->format("H");
    }

    /**
     * 
     * @return string
     */
    public function getMinutes()
    {
        return $this->_dateTime->format("i");
    }

    /**
     * 
     * @return string
     */
    public function getSecond()
    {
        return $this->_dateTime->format("s");
    }

    /**
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->getDateTimeAsString();
    }

}
