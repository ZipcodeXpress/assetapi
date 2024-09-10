<?php
namespace Common\Common;

class MagneticTrackParser
{
	static private $sTrackData = null;
	static private $card_holder_name = null;
	static private $surname = null;
	static private $card_code = null;
	static private $exp_month = null;
	static private $exp_year = null;
	static private $exp_date = null;
	static private $track1 = null;
	static private $track2 = null;
	static private $hasTrack1 = false;
	static private $hasTrack2 = false;
	
    //-- Example: Track 1 & 2 Data
    //-- %B1234123412341234^CardUser/John^030510100000019301000000877000000?;1234123412341234=0305101193010877?
    //-- Key off of the presence of "^" and "="
  
    //-- Example: Track 1 Data Only
    //-- B1234123412341234^CardUser/John^030510100000019301000000877000000?
    //-- Key off of the presence of "^" but not "="
  
    //-- Example: Track 2 Data Only
    //-- 1234123412341234=0305101193010877?
    //-- Key off of the presence of "=" but not "^"
	
    public function parse($track1='', $track2='') {

        $strParse = $track1.$track2;
        self::$sTrackData = $track1.$track2;

        if ($strParse != '' ) {
            // echo($strParse);

            //--- Determine the presence of special characters
            $nHasTrack1 = strpos($strParse, "^");
            $nHasTrack2 = strpos($strParse, "=");

            //--- Set boolean values based off of character presence
            self::$hasTrack1 = $bHasTrack1 = false;
            self::$hasTrack2 = $bHasTrack2 = false;
            if ($nHasTrack1 > 0) { self::$hasTrack1 = $bHasTrack1 = true; }
            if ($nHasTrack2 > 0) { self::$hasTrack2 = $bHasTrack2 = true; }

            //--- Test messages
            // echo('$nHasTrack1: ' . $nHasTrack1 . ' $nHasTrack2: ' . $nHasTrack2);
            // echo('$bHasTrack1: ' . $bHasTrack1 . ' $bHasTrack2: ' . $bHasTrack2);        

            //--- Initialize
            $bTrack1_2    = false;
            $bTrack1        = false;
            $bTrack2        = false;

            //--- Determine tracks present
            if (( $bHasTrack1) && ( $bHasTrack2)) { $bTrack1_2 = true; }
            if (( $bHasTrack1) && (!$bHasTrack2)) { $bTrack1     = true; }
            if ((!$bHasTrack1) && ( $bHasTrack2)) { $bTrack2     = true; }

            //--- Test messages
            // echo('$bTrack1_2: ' . $bTrack1_2 . ' $bTrack1: ' . $bTrack1 . ' $bTrack2: ' . $bTrack2);

            //--- Initialize echo message on error
            $bShowAlert = false;
            
            //-----------------------------------------------------------------------------        
            //--- Track 1 & 2 cards
            //--- Ex: B1234123412341234^CardUser/John^030510100000019301000000877000000?;1234123412341234=0305101193010877?
            //-----------------------------------------------------------------------------        
            if ($bTrack1_2) { 
                //            echo('Track 1 & 2 swipe');

                $strCutUpSwipe = '' . $strParse . ' ';
                $arrayStrSwipe = explode("^", $strCutUpSwipe);
        
                if ( sizeof($arrayStrSwipe) > 2 ) {
                    self::$card_code = $this->stripAlpha( substr($arrayStrSwipe[0], 1, strlen($arrayStrSwipe[0])) );
                    self::$card_holder_name                    = trim($arrayStrSwipe[1]);
                    self::$exp_month                 = substr($arrayStrSwipe[2], 2, 2);
                    self::$exp_year                    = substr($arrayStrSwipe[2], 0, 2); 
                    self::$exp_date                    = self::$exp_month.self::$exp_year;
                    
                    //--- Different card swipe readers include or exclude the % in the front of the track data - when it's there, there are
                    //---     problems with parsing on the part of credit cards processor - so strip it off
                    if ( substr($sTrackData,0,1) == '%' ) {
                        $sTrackData = substr($sTrackData, 1,strlen($sTrackData));
                    }

                    $track2sentinel = strpos($sTrackData, ";");
                    if( $track2sentinel != -1 ){
                        self::$track1 = substr($sTrackData, 0, $track2sentinel);
                        self::$track2 = substr($sTrackData, $track2sentinel);
                    }

                    //--- parse name field into first/last names
                    $nameDelim = strpos(self::$card_holder_name, "/");
                    if( $nameDelim != -1 ){
                        self::$surname = substr(self::$card_holder_name, 0, $nameDelim);
                    }
                } else { 
                    //--- for "if ( sizeof($arrayStrSwipe) > 2 )" 
                    $bShowAlert = true;    //--- Error -- show echo message
                }
            }
            
            //-----------------------------------------------------------------------------
            //--- Track 1 only cards
            //--- Ex: B1234123412341234^CardUser/John^030510100000019301000000877000000?
            //-----------------------------------------------------------------------------        
            if ($bTrack1) {
                //            echo('Track 1 swipe');
                $strCutUpSwipe = '' . $strParse . ' ';
                $arrayStrSwipe = explode("^", $strCutUpSwipe);
        
                if ( sizeof($arrayStrSwipe) > 2 ) {
                    self::$card_code = $sAccountNumber = $this->stripAlpha( substr($arrayStrSwipe[0], 1, strlen($arrayStrSwipe[0])) );
                    self::$card_holder_name = $sName	= $arrayStrSwipe[1];
                    self::$exp_month = $sMonth	= substr($arrayStrSwipe[2], 2,4);
                    self::$exp_year = $sYear	= '20' . substr($arrayStrSwipe[2], 0,2); 
        
                    
                    //--- Different card swipe readers include or exclude the % in
                    //--- the front of the track data - when it's there, there are
                    //---     problems with parsing on the part of credit cards processor - so strip it off
                    if ( substr($sTrackData, 0,1) == '%' ) { 
                        self::$track1 = $sTrackData = substr($sTrackData, 1,strlen($sTrackData));
                    }
        
                    //--- Add track 2 data to the string for processing reasons
                    //                if (substr($sTrackData, strlen($sTrackData)-1,1) != '?')    //--- Add a ? if not present
                    //                { $sTrackData = $sTrackData . '?'; }
                    self::$track2 = ';' . $sAccountNumber . '=' . substr($sYear,2,4) . $sMonth . '111111111111?';
                    $sTrackData = $sTrackData . self::$track2;
        
                    //--- parse name field into first/last names
                    $nameDelim = strpos(self::$card_holder_name, "/");
                    if( $nameDelim != -1 ){
                        self::$surname = substr(self::$card_holder_name,0, $nameDelim);
                    }
                } else { 
                    //--- for "if ( sizeof($arrayStrSwipe) > 2 )"
                    $bShowAlert = true;    //--- Error -- show echo message
                }
            }
            
            //-----------------------------------------------------------------------------
            //--- Track 2 only cards
            //--- Ex: 1234123412341234=0305101193010877?
            //-----------------------------------------------------------------------------        
            if ($bTrack2)
            {
                //echo('Track 2 swipe');
            
                $nSeperator    = strpos($strParse, "=");
                $sCardNumber = substr($strParse,1,$nSeperator);
                $sYear             = $strParse.substr($nSeperator+1,2);
                $sMonth            = $strParse.substr($nSeperator+3,2);

                // echo($sCardNumber . ' -- ' . $sMonth . '/' . $sYear);

                self::$card_code = $sAccountNumber = $this->stripAlpha($sCardNumber);
                self::$exp_month = $sMonth		= $sMonth;
                self::$exp_year = $sYear			= '20' . $sYear; 
                    
                //--- Different card swipe readers include or exclude the % in the front of the track data - when it's there, 
                //---    there are problems with parsing on the part of credit cards processor - so strip it off
                if ( substr($sTrackData,0,1) == '%' ) {
                    $sTrackData = substr($sTrackData,1,strlen($sTrackData));
                }
            }
            
            //-----------------------------------------------------------------------------
            //--- No Track Match
            //-----------------------------------------------------------------------------        
            if (((!$bTrack1_2) && (!$bTrack1) && (!$bTrack2)) || ($bShowAlert))
            {
                //echo('Difficulty Reading Card Information.\n\nPlease Swipe Card Again.');
            }

            //        echo('Track Data: ' . document.formFinal.trackdata.value);
                    
                    //document.formFinal.trackdata.value = replaceChars(document.formFinal.trackdata.value,';','');
                    //document.formFinal.trackdata.value = replaceChars(document.formFinal.trackdata.value,'?','');

            //        echo('Track Data: ' . document.formFinal.trackdata.value);

        } //--- end "if ( $strParse != '' )"

        return array(
            'surname' => self::$surname,
            'card_holder_name' => self::$card_holder_name,
            'card_code' => self::$card_code,
            'exp_month' => self::$exp_month,
            'exp_year' => self::$exp_year,
            'exp_date' => self::$exp_date,
            'has_track1' => self::$hasTrack1,
            'has_track2' => self::$hasTrack2,
            'track1' => self::$track1,
            'track2' => self::$track2,
            'raw_input_str' => self::$sTrackData,
        );
    }

    private static function stripAlpha($sInput){
        if( $sInput == null )	return '';
        return preg_replace('/[^0-9]/', '', $sInput);
    }
}
