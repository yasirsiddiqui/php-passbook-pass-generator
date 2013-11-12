<?php
/**
 * PHP Class to create pass for Apple PassBook App
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace PassBook;

define("BOARDING_PASS","boardingPass");
define("COUPON","coupon");
define("EVENT_TICKET","eventTicket");
define("GENERIC","generic");
define("STORE_CARD","storeCard");

define("TRANSIT_TYPE_AIR","PKTransitTypeAir");
define("TRANSIT_TYPE_TRAIN","PKTransitTypeTrain");
define("TRANSIT_TYPE_BUS","PKTransitTypeBus");
define("TRANSIT_TYPE_BOAT","PKTransitTypeBoat");
define("TRANSIT_TYPE_GENERIC","PKTransitTypeGeneric");

class Pass {
    
    private $passtype;
    private $passdata;
    private $passdetail;
    private $logopath;
    private $logohdpath;
    private $iconpath;
    private $iconpathhd;
    private $background;
    private $background_hd;
    private $thumbnail;
    private $thumbnail_hd;
    private $strip;
    private $strip_hd;
    
    /**
     * Class Constructor
     * 
     * @param string $passtype Pass type which you want to create
     * 
     * @param string $pass_identifier Pass identifier Use the Certificates, Identifiers & Profiles area of the Member Center to register a pass type identifier.
     *
     * @param string $team_identifier Team identifier The team idetifier is a series of letters and numbers issued to you by Apple.
     *
     * @param string $orginization_name The organization name is displayed on the lock screen.
     *
     * @param string $pass_desc Pass description.
     *
     * @param string $pass_logo_text Logo text that will be displayed on pass header.
     *
     * @param string $pass_foreground_color RGB value of Pass foreground color. Pass complete RGB value e.g. rgb(22, 55, 110)
     *
     * @param string $pass_background_color RGB value of Pass background color. Pass complete RGB value e.g. rgb(22, 55, 110)
     *
    */
    
    public function __construct($passtype,$pass_identifier,$team_identifier,$orginization_name,$pass_desc,$pass_logo_text,$pass_foreground_color,$pass_background_color) {
        
        if($passtype!="boardingPass" && $passtype!="coupon" && $passtype!="eventTicket" && $passtype!="generic" && $passtype!="storeCard") {
            
            throw new \Exception("Please provide a valid Pass Type.");
        }
        else {
            $this->passtype = $passtype;
        }
        if(empty($pass_identifier) || empty($team_identifier) || empty($orginization_name)) {
            throw new Exception("Please provide all required fields information");
        }
        $this->passdata = array();
        
        $this->passdata['formatVersion'] = 1;
        $this->passdata['passTypeIdentifier'] = $pass_identifier;
        $this->passdata['teamIdentifier'] = $team_identifier;
        $this->passdata['organizationName'] = $orginization_name;
        
        $dateobj = new \DateTime('NOW');
        $date =  $dateobj->format(\DateTime::W3C);
        
        $this->passdata['relevantDate'] = $date;
        $this->passdata['serialNumber'] = uniqid();
        
        $this->passdata[$this->passtype] = array();
        
        if(!empty($pass_desc)){
            $this->passdata['description'] = $pass_desc;
        }
        if(!empty($pass_logo_text)) {
            $this->passdata['logoText'] = $pass_logo_text;
        }
        if(!empty($pass_foreground_color)){
            $this->passdata['foregroundColor'] = $pass_foreground_color;
        }
        if(!empty($pass_background_color)){
            $this->passdata['backgroundColor'] = $pass_background_color;
        }
    }
    
    /**
     * Function addBarCode
     *
     * @param string $barcode_data Bar code information or data to be stored as barcode
     *  
     */
    
    public function addBarCode($barcode_data) {
        
        if(!empty($barcode_data)) {
            
            $this->passdata['barcode']['message'] = $barcode_data;
            $this->passdata['barcode']['format'] = "PKBarcodeFormatPDF417";
            $this->passdata['barcode']['messageEncoding'] = "iso-8859-1";
        }
    }
    
    /**
     * Function addLocation
     *
     * @param $latitude Location's latitude
     *
     * @param $longitude Location's longitude
     *
     */
    
    public function addLocation($latitude,$longitude) {
        
        if(!empty($latitude)&&!empty($longitude)) {
            $latlongdata = array();
            $latlongdata['longitude'] = $longitude;
            $latlongdata['latitude'] = $latitude;
            $this->passdata['locations'][] = $latlongdata;
        } 
    }
    
    /**
     * Function boardingPass_addTransitType
     *
     * Add transit type to boarding pass. All constants are defined above
     *
     * @param $type Transit type
     *
     */
    public function boardingPass_addTransitType($type) {
        if($type!="PKTransitTypeAir" && $type!="PKTransitTypeTrain" && $type!="PKTransitTypeBus" && $type!="PKTransitTypeBoat" && $type!="PKTransitTypeGeneric") {
            throw new \Exception("Please provide a valid transit type");
        }
        $this->passdata[$this->passtype]['transitType'] = $type; 
    }
    
    /**
     * Function boardingPass_addHeaderInfo
     *
     * Add Header information to Boarding pass
     *
     * @param $header_label Label to be appeared
     *
     * @param $header_value Header label's value
     *
     */
    public function boardingPass_addHeaderInfo($header_label,$header_value) {
        
        $headerdata = array('label'=>$header_label,'key'=>'gate','value'=>$header_value);
        $this->passdata[$this->passtype]['headerFields'][] = $headerdata; 
    }
    
    /**
     * Function boardingPass_addDepartureInfo
     *
     * Add Departure information to Boarding pass
     *
     * @param $departure_city Departure city
     *
     * @param $departure_code Departure code
     *
     */
    public function boardingPass_addDepartureInfo($departure_city,$departure_code) {
        
        if($this->passtype!="boardingPass") {
            throw new \Exception("You can add departure info only to Boarding pass");
        }
        
        if($this->passtype=="boardingPass"&&count(@$this->passdata[$this->passtype]['primaryFields'])==2) {
            throw new \Exception("You can add only two Primary fields to Boarding Pass");
        }
        
        $depar_info = array("key" => "depart","label" => $departure_city,"value" => $departure_code);
        $this->passdata[$this->passtype]['primaryFields'][] = $depar_info; 
    }
    
    /**
     * Function boardingPass_addArrivalInfo
     *
     * Add Arrival information to Boarding pass
     *
     * @param $arrival_city Arrival city
     *
     * @param $arrival_code Arrival code
     *
     */
    public function boardingPass_addArrivalInfo($arrival_city,$arrival_code) {
        
        if($this->passtype!="boardingPass") {
            throw new \Exception("You can add arrival info only to Boarding pass");
        }
        
        if($this->passtype=="boardingPass"&&count(@$this->passdata[$this->passtype]['primaryFields'])==2) {
            throw new \Exception("You can add only two Primary fields to Boarding Pass");
        }
        
        $arriva_info = array("key" => "arrive","label" => $arrival_city,"value" => $arrival_code);
        $this->passdata[$this->passtype]['primaryFields'][] = $arriva_info;  
    }
    
    /**
     * Function boardingPass_addDepartureTime
     *
     * Add Departure time to boarding pass
     *
     * @param $time_label Time label
     *
     * @param $time_value Time label value
     *
     */
    public function boardingPass_addDepartureTime($time_label,$time_value) {
        
        if($this->passtype!="boardingPass") {
            throw new \Exception("You can add departure time only to Boarding pass");
        }
        
        if($this->passtype=="boardingPass"&&count(@$this->passdata[$this->passtype]['auxiliaryFields'])==5) {
            throw new \Exception("You can add only 5 Auxiliary fields to Boarding Pass");
        }
        $time_info = array("key" => "boardingTime","label" => $time_label,"value" => $time_value);
        $this->passdata[$this->passtype]['auxiliaryFields'][] = $time_info; 
    }
    
    /**
     * Function boardingPass_addFlightInfo
     *
     * Add Flight information to boarding pass
     *
     * @param $flight_label Flight label
     *
     * @param $flight_value Flight label value
     *
     */
    public function boardingPass_addFlightInfo($flight_label,$flight_value) {
        
        if($this->passtype!="boardingPass") {
            throw new \Exception("You can add Flight info only to Boarding pass");
        }
        
        if($this->passtype=="boardingPass"&&count(@$this->passdata[$this->passtype]['auxiliaryFields'])==5) {
            throw new \Exception("You can add only 5 Auxiliary fields to Boarding Pass");
        }
        $flight_info = array("key" => "flightNewName","label" => $flight_label,"value" => $flight_value);
        $this->passdata[$this->passtype]['auxiliaryFields'][] = $flight_info; 
    }
    
    /**
     * Function boardingPass_addClassInfo
     *
     * Add Class information to boarding pass
     *
     * @param $class_label Flight Class label
     *
     * @param $class_value Flight Class label value
     *
     */
    public function boardingPass_addClassInfo($class_label,$class_value) {
        
        if($this->passtype!="boardingPass") {
            throw new \Exception("You can add Class info only to Boarding pass");
        }
        
        if($this->passtype=="boardingPass"&&count(@$this->passdata[$this->passtype]['auxiliaryFields'])==5) {
            throw new \Exception("You can add only 5 Auxiliary fields to Boarding Pass");
        }
        $class_info = array("key" => "class","label" => $class_label,"value" => $class_value);
        $this->passdata[$this->passtype]['auxiliaryFields'][] = $class_info; 
    }
    
    /**
     * Function boardingPass_addFlightDate
     *
     * Add Flight date to boarding pass
     *
     * @param $date_label Flight date label
     *
     * @param $date_value Flight date label value
     *
     */
    public function boardingPass_addFlightDate($date_label,$date_value) {
        
        if($this->passtype!="boardingPass") {
            throw new \Exception("You can add Class info only to Boarding pass");
        }
        
        if($this->passtype=="boardingPass"&&count(@$this->passdata[$this->passtype]['auxiliaryFields'])==5) {
            throw new \Exception("You can add only 5 Auxiliary fields to Boarding Pass");
        }
        $class_info = array("key" => "date","label" => $date_label,"value" => $date_value);
        $this->passdata[$this->passtype]['auxiliaryFields'][] = $class_info; 
    }
    
    /**
     * Function boardingPass_addPessangerInfo
     *
     * Add Flight Passenger Info to boarding pass
     *
     * @param $pessanger_label Pessanger label
     *
     * @param $pessanger_name Flight Pessanger label value
     *
     */
    public function boardingPass_addPessangerInfo($pessanger_label,$pessanger_name) {
        
        if($this->passtype!="boardingPass") {
            throw new \Exception("You can add Pessanger info only to Boarding pass");
        }
        
        if($this->passtype=="boardingPass"&&count(@$this->passdata[$this->passtype]['secondaryFields'])==5) {
            throw new \Exception("You can add only 5 Auxiliary fields to Boarding Pass");
        }
        $pessanger_info = array("key" => "passenger","label" => $pessanger_label,"value" => $pessanger_name);
        $this->passdata[$this->passtype]['secondaryFields'][] = $pessanger_info; 
    }
    
     /**
     * Function couponPass_addHeaderInfo
     *
     * Add header information to Coupoun pass
     *
     * @param $header_label Header label
     *
     * @param $header_value Header label value
     *
     */
    public function couponPass_addHeaderInfo($header_label,$header_value) {
        
        $headerdata = array('label'=>$header_label,'key'=>'headerkey','value'=>$header_value);
        $this->passdata[$this->passtype]['headerFields'][] = $headerdata; 
        
    }
    
     /**
     * Function couponPass_addDesc
     *
     * Add Description to Coupoun pass
     *
     * @param $label Description label
     *
     * @param $label_value Description label
     *
     */
    public function couponPass_addDesc($label,$label_value) {
        
        if($this->passtype!="coupon") {
            throw new \Exception("You can add Coupon title only to Coupon pass");
        }
        
        if($this->passtype=="coupon"&&count(@$this->passdata[$this->passtype]['primaryFields'])==1) {
            throw new \Exception("You can add only 1 primary field to Coupon Pass");
        }
        $coupon_info = array("key" => "offer","label" => $label,"value" => $label_value);
        $this->passdata[$this->passtype]['primaryFields'][] = $coupon_info;
    }
    
     /**
     * Function couponPass_addInfo
     *
     * Add information to coupon pass
     *
     * @param $info_label information label
     *
     * @param $info_title information label value
     *
     */
    public function couponPass_addInfo($info_label,$info_title) {
        
         if($this->passtype=="coupon"&&count(@$this->passdata[$this->passtype]['auxiliaryFields'])==4) {
            throw new \Exception("You can add only 4 information fields to Coupon Pass");
        }
        $key = "key_". (string)(count(@$this->passdata[$this->passtype]['auxiliaryFields'])+1);
        $coupon_info = array("key" => $key,"label" => $info_label,"value" => $info_title);
        $this->passdata[$this->passtype]['auxiliaryFields'][] = $coupon_info;
    }
    
     /**
     * Function eventPass_addHeaderInfo
     *
     * Add header info to Event pass
     *
     * @param $header_label header label
     *
     * @param $header_value header label value
     *
     */
    public function eventPass_addHeaderInfo($header_label,$header_value) {
        
        $headerdata = array('label'=>$header_label,'key'=>'gate','value'=>$header_value);
        $this->passdata[$this->passtype]['headerFields'][] = $headerdata; 
    }
    
    /**
     * Function eventPass_addEvent
     *
     * Add Event to event pass
     *
     * @param $event_label event label
     *
     * @param $event_name event label value
     *
     */
    public function eventPass_addEvent($event_label,$event_name) {
        
        if($this->passtype!="eventTicket") {
            throw new \Exception("You can add Event only to Event pass");
        }
        
        if($this->passtype=="eventTicket"&&count(@$this->passdata[$this->passtype]['primaryFields'])==1) {
            throw new \Exception("You can add only 1 primary field to Event Pass");
        }
        $event_info = array("key" => "offer","label" => $event_label,"value" => $event_name);
        $this->passdata[$this->passtype]['primaryFields'][] = $event_info;
        
    }
    
    /**
     * Function eventPass_addInfo
     *
     * Add information to Event pass
     *
     * @param $info_label information label
     *
     * @param $info_title information label value
     *
     */
    public function eventPass_addInfo($info_label,$info_value) {
        
        $key = "key_". (string)(count(@$this->passdata[$this->passtype]['secondaryFields'])+1);
        $event_info = array("key" => $key,"label" => $info_label,"value" => $info_value);
        $this->passdata[$this->passtype]['secondaryFields'][] = $event_info;
    }
    
    /**
     * Function storePass_addHeaderInfo
     *
     * Add header info to Store pass
     *
     * @param $header_label header label
     *
     * @param $header_value header label value
     *
     */
    public function storePass_addHeaderInfo($header_label,$header_value) {
        
        $headerdata = array('label'=>$header_label,'key'=>'headerkey','value'=>$header_value);
        $this->passdata[$this->passtype]['headerFields'][] = $headerdata; 
    }
    
    /**
     * Function storePass_addBalance
     *
     * Add balance info to Store pass
     *
     * @param $bal_label Balance label
     *
     * @param  Float $bal_value Balance label
     *
     * @param $currency_code 3 digits currency code
     *
     */
    public function storePass_addBalance($bal_label,$bal_value = 0.0,$currency_code) {
        
        if($this->passtype!="storeCard") {
            throw new \Exception("You can add Balance only to Store pass");
        }
        
        $balance = floatval($bal_value);
        
        if($this->passtype=="storeCard"&&count(@$this->passdata[$this->passtype]['primaryFields'])==1) {
            throw new \Exception("You can add only 1 primary field to Store Pass");
        }
        $event_info = array("key" => "balance","label" => $bal_label,"value" => $balance,'currencyCode'=>$currency_code);
        $this->passdata[$this->passtype]['primaryFields'][] = $event_info;
    }
    
    /**
     * Function storePass_addInfo
     *
     * Add info to Store pass
     *
     * @param $info_label information label
     *
     * @param $info_value information label value
     *
     */
     public function storePass_addInfo($info_label,$info_value) {
        
        $key = "key_". (string)(count(@$this->passdata[$this->passtype]['auxiliaryFields'])+1);
        $event_info = array("key" => $key,"label" => $info_label,"value" => $info_value);
        $this->passdata[$this->passtype]['auxiliaryFields'][] = $event_info;
    }
    
     /**
     * Function genericPass_addHeaderInfo
     *
     * Add header info to Generic pass
     *
     * @param $header_label header label
     *
     * @param $header_value header label value
     *
     */
    public function genericPass_addHeaderInfo($header_label,$header_value) {
        
        $headerdata = array('label'=>$header_label,'key'=>'headerkey','value'=>$header_value);
        $this->passdata[$this->passtype]['headerFields'][] = $headerdata; 
    }
    
     /**
     * Function genericPass_addMemberInfo
     *
     * Add Member info to Generic pass
     *
     * @param $label Member info label
     *
     * @param $value Member info label value
     *
     */
    public function genericPass_addMemberInfo($label,$value) {
        
        $member_info = array("key" => "subtitle","label" => $label,"value" => $value);
        $this->passdata[$this->passtype]['primaryFields'][] = $member_info;
    }
    
     /**
     * Function genericPass_addInfo
     *
     * Add info to Generic pass
     *
     * @param $info_label information label
     *
     * @param $info_value information label value
     *
     */
    public function genericPass_addInfo($info_label,$info_value) {
        
        $key = "key_". (string)(count(@$this->passdata[$this->passtype]['auxiliaryFields'])+1);
        $event_info = array("key" => $key,"label" => $info_label,"value" => $info_value);
        $this->passdata[$this->passtype]['auxiliaryFields'][] = $event_info;
    }
    
     /**
     * Function addBackScreenInfo
     *
     * Add info to Back screen of the pass
     *
     * @param $info_label information label
     *
     * @param $info_value information label value
     *
     */
    public function addBackScreenInfo($info_label,$info_value) {
        
        $key = "key_". (string)(count(@$this->passdata[$this->passtype]['backFields'])+1);
        $backscreeninfo = array("key" => $key,"label" => $info_label,"value" => $info_value);
        $this->passdata[$this->passtype]['backFields'][] = $backscreeninfo;
    }
    
     /**
     * Function generatePass
     *
     * Generates pass
     *
     * @param $path Path where pass directory should be created
     *
     * @param $passname Pass name. Pass directory will be create with this name
     *
     */
    public function generatePass($path,$passname) {
        
        if(strpos($passname,".pass")===false) {
            $passname.= ".pass";
        }
        
        if(!file_exists($path.$passname)) {
            
            if(mkdir($path.$passname,0777)) {
            
                $this->createJsonFile($path.$passname);
                $this->copyFiles($path,$passname);
                return true;
            }
            else {
                throw new \Exception("Error occured. Can't write directory to the path specified");
            }    
        }
        else { // File exsists so no need to create directory just put content files in it
            
            $this->createJsonFile($path.$passname);
            $this->copyFiles($path,$passname);
            return true;
        }
    }
    
    /**
     * Function createJsonFile
     *
     * Creates pass json file
     *
     * @param $path Path to the pass directory
     * 
     */
    private function createJsonFile($path) {
        
        file_put_contents($path."/pass.json",$this->getpassData());
    }
    
     /**
     * Function copyFiles
     *
     * Copies Pass files to pass directory
     *
     * @param $path Path to the pass directory
     *
     * @param $passname Name of the pass
     * 
     */
    private function copyFiles($path,$passname) {
        
            if($this->logopath){
                copy($this->logopath,$path.$passname."/logo.png");
            }
            if($this->logohdpath){
                copy($this->logohdpath,$path.$passname."/logo@2x.png");
            }
            if($this->iconpath){
                copy($this->iconpath,$path.$passname."/icon.png");
            }
            if($this->iconpathhd){
                copy($this->iconpathhd,$path.$passname."/icon@2x.png");
            }
            if($this->background){
                copy($this->background,$path.$passname."/background.png");
            }
            if($this->background_hd){
                copy($this->background_hd,$path.$passname."/background@2x.png");
            }
            if($this->thumbnail){
                copy($this->thumbnail,$path.$passname."/thumbnail.png");
            }
            if($this->thumbnail_hd){
                copy($this->thumbnail_hd,$path.$passname."/thumbnail@2x.png");
            }
            if($this->strip){
                copy($this->strip,$path.$passname."/strip.png");
            }
            if($this->strip_hd){
                copy($this->strip_hd,$path.$passname."/strip@2x.png");
            }    
    }
   
    /**
     * Function addStrip
     *
     * Adds strip to the pass
     *
     * @param $path Image path from where image should be copied
     *
     */
    public function addStrip($path) {
        $this->strip = $path;
    }
    
    /**
     * Function addStrip_hd
     *
     * Adds HD strip to the pass
     *
     * @param $path Image path from where image should be copied
     *
     */
    public function addStrip_hd($path) {
        $this->strip_hd = $path;
    }
    
    public function addBackgroud($path) {
        $this->background = $path;
    }
    
    /**
     * Function addBackgroud_hd
     *
     * Adds HD Background to the pass
     *
     * @param $path Image path from where image should be copied
     *
     */
    public function addBackgroud_hd($path) {
        $this->background_hd = $path;
    }
    
    /**
     * Function addThumbnail
     *
     * Adds thumbnail to the pass
     *
     * @param $path Image path from where image should be copied
     *
     */
    public function addThumbnail($path) {
        $this->thumbnail = $path;
    }
    
    /**
     * Function addThumbnail_hd
     *
     * Adds HD thumbnail to the pass
     *
     * @param $path Image path from where image should be copied
     *
     */
    public function addThumbnail_hd($path) {
        $this->thumbnail_hd = $path;
    }
    
    /**
     * Function addLogo
     *
     * Adds Logo to the pass
     *
     * @param $path Image path from where image should be copied
     *
     */
    public function addLogo($logopath) {
        $this->logopath = $logopath;
    }
    
     /**
     * Function addLogoHD
     *
     * Adds HD Logo to the pass
     *
     * @param $path Image path from where image should be copied
     *
     */
    public function addLogoHD($logopath) {
        $this->logohdpath = $logopath;
    }
    
     /**
     * Function addIcon
     *
     * Adds icon to the pass
     *
     * @param $path Image path from where image should be copied
     *
     */
    public function addIcon($iconpath) {
       $this->iconpath = $iconpath; 
    }
    
     /**
     * Function addIconHD
     *
     * Adds HD icon to the pass
     *
     * @param $path Image path from where image should be copied
     *
     */
    public function addIconHD($iconpath) {
        $this->iconpathhd = $iconpath;
    }
    
     /**
     * Function getpassData
     *
     * Converts pass data to json format
     *
     * @param $path Image path from where image should be copied
     *
     */
    private function getpassData() {
        return json_encode($this->passdata);
    }
}
?>