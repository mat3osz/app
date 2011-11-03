<?php

/**
 * SponsorshipDashboard
 * @author Jakub "Szeryf" Kurcek
 * @author Sean Colombo
 *
 * Date provider class control proper date settings for series object.
 *
 * TODO: These should implement some interface (or, more likely, extend some base-class) & that should be documented (at least commented).
 * NOTE: 'Gapi' is short for "Google API" which is used here because one of the sources is Google Analytics which expects a specific date format.
 *
 * WARNING: Hourly and Weekly reports are still experimental.
 */


/** HOUR **/
class SponsorshipDashboardDateProviderHour {

	public function getType(){
		return SponsorshipDashboardDateProvider::SD_FREQUENCY_HOUR;
	}
	
	public function getGapiEndDate(){
		return date( "Y-m-d ga", mktime(0, 0, 0, date( "m" ), date( "d" )-1, date( "Y" )));
	}

	public function getGapiStartDate( $dateUnits = 0 ){
		$startTime = ( !empty( $dateUnits ) ) ? mktime(date("H")-$dateUnits, 0, 0, date( "m" ), date( "d" ), date( "Y" )) : strtotime( SponsorshipDashboardDateProvider::SD_START_DATE );
		return date( "Y-m-d ga", $startTime );
	}

	public function getGapiDateFromResult( $result ){
		$aDate = array();
		$aDate[] = $result->getYear();
		$aDate[] = $result->getMonth();
		$aDate[] = $result->getDay();
		$aDate[] = $result->getHour();

		return implode( '-', $aDate );
	}

	public function getGapiSamplingDateFromResult( $result ){
		return mktime( $result->getHour(), 0, 0, $result->getMonth(), $result->getDay(), $result->getYear() );
	}

	public function getGapiSortingDate(){
		return array( '-hour', '-day', '-month', '-year' );
	}

	public function getGapiDateDimensionsTable(){
		return array( 'hour', 'day', 'month', 'year' );
	}

	public function getMobileDateString( $forWhere = false ){
		return ( $forWhere ) ? "DATE_FORMAT( ts, '%Y%m%d %g%a' )" : "DATE_FORMAT( ts, '%Y-%m-%d %g%a' )";
	}

	function getEndDate(){
		return $this->getGapiEndDate();
	}

	function getStartDate(){
		return $this->getGapiStartDate();
	}	
} // end SponsorshipDashboardDateProviderDay

 
/** DAY **/
class SponsorshipDashboardDateProviderDay {

	public function getType(){
		return SponsorshipDashboardDateProvider::SD_FREQUENCY_DAY;
	}
	
	public function getGapiEndDate(){
		return date( "Y-m-d", mktime(0, 0, 0, date( "m" ), date( "d" )-1, date( "Y" )));
	}

	public function getGapiStartDate( $dateUnits = 0 ){
		$startTime = ( !empty( $dateUnits ) ) ? mktime(0, 0, 0, date( "m" ), date( "d" )-1-$dateUnits, date( "Y" )) : strtotime( SponsorshipDashboardDateProvider::SD_START_DATE );
		return date( "Y-m-d", $startTime );
	}

	public function getGapiDateFromResult( $result ){
		$aDate = array();
		$aDate[] = $result->getYear();
		$aDate[] = $result->getMonth();
		$aDate[] = $result->getDay();

		return implode( '-', $aDate );
	}

	public function getGapiSamplingDateFromResult( $result ){
		return mktime( 0,0,0, $result->getMonth(), $result->getDay(), $result->getYear() );
	}

	public function getGapiSortingDate(){
		return array( '-day', '-month', '-year' );
	}

	public function getGapiDateDimensionsTable(){
		return array( 'day', 'month', 'year' );
	}

	public function getMobileDateString( $forWhere = false ){
		return ( $forWhere ) ? "DATE_FORMAT( ts, '%Y%m%d' )" : "DATE_FORMAT( ts, '%Y-%m-%d' )";
	}

	function getEndDate(){
		return $this->getGapiEndDate();
	}

	function getStartDate(){
		return $this->getGapiStartDate();
	}	
} // end SponsorshipDashboardDateProviderDay

 /** MONTH **/
class SponsorshipDashboardDateProviderMonth {

	public function getType(){
		return SponsorshipDashboardDateProvider::SD_FREQUENCY_MONTH;
	}

	public function getGapiEndDate(){
		return date("Y-m-d", mktime(0, 0, 0, date( "m" ), 0, date( "Y" )));
	}

	public function getGapiStartDate( $dateUnits = 0 ){
		$startTime = ( !empty( $dateUnits ) ) ? mktime(0, 0, 0, ( date( "m" )-$dateUnits ), 0 , date( "Y" )) : strtotime( SponsorshipDashboardDateProvider::SD_START_DATE );
		return date( "Y-m-d", $startTime );
	}

	public function getGapiDateFromResult( $result ){
		$aDate = array();
		$aDate[] = $result->getYear();
		$aDate[] = $result->getMonth();
		
		return implode( '-', $aDate );
	}

	public function getGapiSamplingDateFromResult( $result ){
		return mktime( 0,0,0, $result->getMonth(), 0, $result->getYear() );
	}

	public function getGapiSortingDate(){
		return array( '-month', '-year' );		
	}
	
	public function getGapiDateDimensionsTable(  ){
		return array( 'month', 'year' );
	}

	public function getMobileDateString( $forWhere = false ){
		return ( $forWhere ) ? "DATE_FORMAT( ts, '%Y%m' )" : "DATE_FORMAT( ts, '%Y-%m' )";
	}

	function getEndDate(){
		return date("Y-m", mktime(0, 0, 0, date( "m" ), 0, date( "Y" )));
	}

	function getStartDate(){
		return ( !empty( $dateUnits ) ) ? date("Y-m", mktime(0, 0, 0, ( date( "m" )-$dateUnits ), 0 , date( "Y" ))) : self::SD_START_DATE;
	}
} // end SponsorshipDashboardDateProviderMonth

/** YEAR **/
class SponsorshipDashboardDateProviderYear {

	public function getType(){
		return SponsorshipDashboardDateProvider::SD_FREQUENCY_YEAR;
	}

	public function getGapiEndDate(){
		return date("Y-m-d", mktime(0, 0, 0, 1, 1, date( "Y" )));
	}

	public function getGapiStartDate( $dateUnits = 0 ){
		$startTime = ( !empty( $dateUnits ) ) ? mktime(0, 0, 0, 0, 0 , date( "Y" ) - $dateUnits ) : strtotime( SponsorshipDashboardDateProvider::SD_START_DATE );
		return date( "Y-m-d", $startTime );
	}

	public function getGapiDateFromResult( $result ){
		$aDate = array();
		$aDate[] = $result->getYear();

		return implode( '-', $aDate );
	}

	public function getGapiSortingDate(){
		return array( '-year' );
	}

	public function getGapiDateDimensionsTable(  ){
		return array( 'year' );
	}

	public function getMobileDateString( $forWhere = false ){
		return ( $forWhere ) ? "DATE_FORMAT( ts, '%Y' )" : "DATE_FORMAT( ts, '%Y' )";
	}

	function getEndDate(){
		return $this->getGapiEndDate();
	}

	function getStartDate(){
		return $this->getGapiStartDate();
	}
} // end SponsorshipDashboardDateProviderYear


class SponsorshipDashboardDateProvider {

	const SD_FREQUENCY_HOUR = 0; // WARNING: EXPERIMENTAL
	const SD_FREQUENCY_DAY = 1;
	const SD_FREQUENCY_WEEK = 2; // WARNING: EXPERIMENTAL
	const SD_FREQUENCY_MONTH = 3;
	const SD_FREQUENCY_YEAR = 4;
	const SD_START_DATE = '2010-04-01 00:00:00';

	public static function getProvider( $kind = self::SD_FREQUENCY_DAY ){

		switch ( $kind ){
			case self::SD_FREQUENCY_HOUR : $className = 'SponsorshipDashboardDateProviderHour'; break;
			case self::SD_FREQUENCY_DAY : $className = 'SponsorshipDashboardDateProviderDay'; break;
			case self::SD_FREQUENCY_WEEK : $className = 'SponsorshipDashboardDateProviderWeek'; break;
			case self::SD_FREQUENCY_YEAR : $className = 'SponsorshipDashboardDateProviderYear'; break;

			case self::SD_FREQUENCY_MONTH :
		 	default: $className = 'SponsorshipDashboardDateProviderMonth';  break;
		}

		return new $className();
	}
}
