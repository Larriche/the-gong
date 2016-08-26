<?php
class PrettyDate
{
	protected $year;

	protected $month;

	protected $day;

	protected $hour;

	protected $minute;

	protected $second;


	public function __construct($date)
	{
		$datePattern = '/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/';
		$dateTimePattern = '/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/';

		if(preg_match($datePattern,$date)){
            $dateParts = explode("-",$date);

            $this->year = $dateParts[0];
            $this->month = $dateParts[1];
            $this->day = $dateParts[2];
		}
		else{
			if(preg_match($dateTimePattern,$date)){
				$dateTimeParts = explode(" ",$date);

				$dateParts = explode("-",$dateTimeParts[0]);
				$timeParts = explode(":",$dateTimeParts[1]);

				$this->year = $dateParts[0];
	            $this->month = $dateParts[1];
	            $this->day = $dateParts[2];

	            $this->hour = $timeParts[0];
	            $this->minute = $timeParts[1];
	            $this->second = $timeParts[2];

            }

		}
	}

	public function getMonthName()
	{
		switch($this->month)
		{
			case '01':
			    return "Jan";
			case '02':
			    return "Feb";
            case '03':
                return "Mar";
            case '04':
                return "Apr";
            case '05':
                return  "May";
            case '06':
                return "Jun";
            case '07':
                return "July";
            case  '08':
                return "Aug";
            case "09":
                return "Sept";
            case "10":
                return "Oct";
            case "11":
                return "Nov";
            case "12":
                return "Dec";
            default:
                return null;
		}
	}
    
    public function getMonth()
    {
    	return $this->month;
    }

	public function getDay()
	{
		return $this->day;
	}

	public function getYear()
	{
		return $this->year;
	}

	public function isValid()
	{
		return ($this->month > 0) && ($this->day > 0) && ($this->year) > 0;
	}

	public function getReadable()
	{
		if($this->isValid()){
		    $dateStr = $this->getMonthName()." ".$this->day." ,".$this->year;
		    if(!empty($this->hour) && !empty($this->minute) && !empty($this->second))
			    $dateStr .= " at " .$this->hour.":".$this->minute.":".$this->second;
		}
		else{
			$dateStr = "unknown";
		}
		
		return $dateStr;
	}

	public static function compare($date1,$date2)
	{
		if($date1->getYear() > $date2->getYear()){
			return 1;
		}
		elseif($date1->getYear() < $date2->getYear())
		{
			return -1;
		}
		else{
			if($date1->getMonth() > $date2->getMonth()){
                return 1;
			}
			elseif($date1->getMonth() < $date2->getMonth()){
				return -1;
			}
			else{
				if($date1->getDay() > $date2->getDay()){
                    return 1;
				}
				else if($date1->getDay() < $date2->getDay()){
					return -1;
				}
			}
		}

		return 0;
	}

	public function getHour()
	{
		if(!empty($this->hour)){
			return $this->hour;
		}

		return null;
	}

	public function getMinute()
	{
		if(!empty($this->minute)){
			return $this->minute;
		}

		return null;
	}
}
?>