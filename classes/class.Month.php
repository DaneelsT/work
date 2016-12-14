<?php

namespace Work\Month;

/**
 * A class describing the actions and properties of the month class.
 *
 * @author Gaetan Dumortier
 * @since 24 October 2016
*/

use \Carbon\Application\Application;

class Month {
	
    /**
     * Contains the unique identifier of the month
     */
    private $mId;

    /**
     * Contains the numeric presentation of the month
     */
    private $mMonth;
    
    /**
     * Contains the total of worked hours of the month
     */
    private $mHoursWorked;
	
	/**
	 * Contains the total days worked in the month
	 */
	private $mDaysWorked;

    /**
     * Contains the earnings of the month
     */
    private $mEarnings;
    
    /**
     * Contains the total sundays worked in the month
     */
    private $mSundays;

    private function setId($id) {
        $this->mId = $id;
    }
    
    private function setMonth($month) {
        $this->mMonth = $month;
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
    
    public function __construct($id, $month, $hours, $days, $earnings, $sunday) {
        $this->setId($id);
        $this->setMonth($month);
        $this->setHoursWorked($hours);
		$this->setDaysWorked($days);
        $this->setEarnings($earnings);
        $this->setSundays($sunday);
	}

    public function getId() {
        return $this->mId;
    }

    public function getMonth() {
        return $this->mMonth;
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
