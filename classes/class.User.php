<?php

namespace Work\User;

/**
 * A class describing the actions and properties of the user class.
 *
 * @author  Joeri Hermans, Gaetan Dumortier
 * @since   1 November 2016
 */

class User
{

    /**
     * Contains the unique identifier of the user.
     */
    private $mId;

    /**
     * Contains the unique username of the user.
     */
    private $mUsername;

    /**
     * Contains the e-mail address of the user.
     */
    private $mEmail;

    /**
     * Contains the unique name of the user.
     */
    private $mName;

    /**
     * Contains the unique surname of the user.
     */
    private $mSurname;

    /**
     * Contains the gender of the user.
     *
     * @note    According to ISO/IEC-5218
     */
    private $mGender;

    /**
     * Contains the disabled/enabled state of the user.
     */
    private $mDisabled;
	
	/**
	 * Contains the hourly pay of the user
	 */
	private $mHourlyPay;
	
	/**
	 * Contains the sunday fee of the user
	 */
	private $mSundayFee;
	
	/**
	 * Defines if the user is an administrator
	 */
	private $mAdmin;
    
    /**
     * Defines the prefered language of the user
    */
	private $mLanguage;
	
    public function __construct($id, $username, $email, $name, $surname, $gender,
    								$pay, $fee, $disabled = 0, $admin = 0, $lang = 'en_US')
    {
        $this->setId($id);
        $this->setUsername($username);
        $this->setEmail($email);
        $this->setName($name);
        $this->setSurname($surname);
        $this->setGender($gender);
		$this->setHourlyPay($pay);
		$this->setSundayFee($fee);
        $this->setDisabled($disabled);
		$this->setAdmin($admin);
        $this->setLanguage($lang);
    }
	
	private function setId($id)
    {
        $this->mId = $id;
    }

    private function setUsername($username)
    {
        $this->mUsername = $username;
    }

    public function setEmail($email)
    {
        $this->mEmail = $email;
    }

    public function setName($name)
    {
        $this->mName = $name;
    }

    public function setSurname($surname)
    {
        $this->mSurname = $surname;
    }

    public function setGender($gender)
    {
        $this->mGender = (int) $gender;
    }
	
	public function setHourlyPay($pay)
	{
		$this->mHourlyPay = $pay;
	}
	
	public function setSundayFee($fee)
	{
		$this->mSundayFee = $fee;
	}

    public function setDisabled($disabled)
    {
        $this->mDisabled = (int) $disabled;
    }
	
	public function setAdmin($admin)
	{
		$this->mAdmin = (int) $admin;
	}
    
    public function setLanguage($lang) {
        $this->mLanguage = $lang;
    }
    
    public function getId()
    {
        return $this->mId;
    }

    public function getUsername()
    {
        return $this->mUsername;
    }

    public function getEmail()
    {
        return $this->mEmail;
    }

    public function getName()
    {
        return $this->mName;
    }

    public function getSurname()
    {
        return $this->mSurname;
    }

    public function getGender()
    {
        return $this->mGender;
    }

    public function getGenderString()
    {
        $str = "";

        if( $this->isMale() ) {
            $str = "Male";
        } elseif( $this->isFemale() ) {
            $str = "Female";
        } else {
            $str = "Unknown";
        }

        return $str;
    }

    public function getFullName()
    {
        return $this->mName . " " . $this->mSurname;
    }

    public function isMale()
    {
        return ( $this->mGender === 1 );
    }

    public function isFemale()
    {
        return ( $this->mGender === 2 );
    }

    public function isGenderUnknown()
    {
        return ( $this->mGender === 0 );
    }
	
	public function getHourlyPay()
	{
		return $this->mHourlyPay;
	}
	
	public function getSundayFee()
	{
		return $this->mSundayFee;
	}

    public function isDisabled()
    {
        return ( $this->mDisabled === 1 );
    }
	
	public function isAdmin()
	{
		return ( $this->mAdmin === 1 );
	}
    
    public function getLanguage() {
        return $this->mLanguage;
    }
    
    public static function isValidGender($gender)
    {
        $valid;
        
        if( $gender >= 1 && $gender <= 2 )
            $valid = true;
        else
            $valid = false;
        
        return $valid;
    }
    
}