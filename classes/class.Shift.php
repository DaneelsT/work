<?php

namespace Work\Shift;

/**
 * A class describing the actions and properties of the shift class.
 *
 * @author Gaetan Dumortier
 * @version 0.1
 * @since 24 October 2016
 */

class Shift {

    /**
     * Contains the unique identifier of the shift
     */
    private $mId;

    /**
     * Contains the date of the shift
     */
    private $mDate;

    /**
     * Contains the starttime of the shift
     */
    private $mStartTime;

    /**
     * Contains the endtime of the shift
     */
    private $mEndTime;
	
	/**
	 * Defines wheter the shift is a sunday or holiday 
	*/
	private $mIsSunday;

    private function setId($id) {
        $this->mId = $id;
    }

    private function setDate($date) {
        $this->mDate = $date;
    }

    private function setStartTime($time) {
        $this->mStartTime = $time;
    }

    private function setEndTime($time) {
        $this->mEndTime = $time;
    }
	
	private function setSunday($sunday) {
		$this->mIsSunday = $sunday;
	}

    public function __construct($id, $date, $starttime, $endtime, $sunday) {
        $this->setId($id);
        $this->setDate($date);
        $this->setStartTime($starttime);
        $this->setEndTime($endtime);
		$this->setSunday($sunday);
    }

    public function getId() {
        return $this->mId;
    }

    public function getDate() {
        return $this->mDate;
    }

    public function getStartTime() {
        return $this->mStartTime;
    }

    public function getEndTime() {
        return $this->mEndTime;
    }
	
	public function isSunday() {
		return $this->mIsSunday;
	}

}
