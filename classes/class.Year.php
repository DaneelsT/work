<?php

namespace Work\Year;

/**
 * A class describing the actions and properties of the year class.
 *
 * @author Gaetan Dumortier
 * @since 3 Februari 2017
*/

use \Carbon\Application\Application;

class Year {

    /**
    * Contains the unique identifier of the year
    */
    private $mId;

    /**
    * Contains the numeric representation of the year
    */
    private $mYear;

    /**
    * Contains the total of worked hours of the year
    */
    private $mHoursWorked;

    /**
    * Contains the total days worked in the year
    */
    private $mDaysWorked;

    /**
    * Contains the earnings of the year
    */
    private $mEarnings;

    /**
    * Contains the total sundays worked in the year
    */
    private $mSundays;

    private function setId($id) {
        $this->mId = $id;
    }

    private function setYear($year) {
        $this->mYear = $year;
    }

    private function setHoursWorked($hours) {
        $this->mHoursWorked = $hours;
    }

	private function setDaysWorked($days) {
		$this->mDaysWorked = $days;
	}

    private function setEarnings($earnings) {
        $this->mEarnings = $earnings;
    }

    private function setSundays($sunday) {
        $this->mSundays = $sunday;
    }

    public function __construct($id, $year, $hours, $days, $earnings, $sunday) {
        $this->setId($id);
        $this->setYear($year);
        $this->setHoursWorked($hours);
		$this->setDaysWorked($days);
        $this->setEarnings($earnings);
        $this->setSundays($sunday);
	}

    public function getId() {
        return $this->mId;
    }

    public function getYear() {
        return $this->mYear;
    }

    public function getHoursWorked() {
        return $this->mHoursWorked;
    }

	public function getDaysWorked() {
		return $this->mDaysWorked;
	}

    public function getEarnings() {
        return $this->mEarnings;
    }

    public function getEarningsWithFee() {
        return $this->mEarnings + ($this->mSundays * $this->getSundayFee());
    }

    public function getSundays() {
        return $this->mSundays;
    }

    public function getSundayFee() {
    	return Application::getInstance()->getConfiguration("sunday_fee");
    }

}
