<?php
include_once("PassBookPass.class.php");

use PassBook\Pass as ApplePass;

// Create boarding pass
$obj = new ApplePass(BOARDING_PASS,"pass.com.test.generic","EMBCMHCP7X","Shaheen Airways","Shaheen Airways Boarding Pass","Shaheen Airways","rgb(22, 55, 110)","rgb(50, 91, 185)");
$obj->addBarCode("SHAW72EC20I");
// Add location
$obj->addLocation("67.43903","61.4532322");
$obj->addLocation("51.85342","53.7540967");
$obj->boardingPass_addTransitType(TRANSIT_TYPE_AIR);
$obj->boardingPass_addHeaderInfo("Gate","10");
$obj->boardingPass_addDepartureInfo("New York","ACE");
$obj->boardingPass_addArrivalInfo("Dallas","DQT");
$obj->boardingPass_addDepartureTime("Time","5:30 PM");
$obj->boardingPass_addFlightInfo("Flight #","390");
$obj->boardingPass_addClassInfo("Class","Economy");
$obj->boardingPass_addFlightDate("Date","04/19");
$obj->boardingPass_addPessangerInfo("Name","John");
$obj->addIcon("/var/www/passimages/icon.png");
$obj->addIconHD("/var/www/passimages/icon@2x.png");
$obj->addLogo("/var/www/passimages/logo.png");
$obj->addLogoHD("/var/www/passimages/logo@2x.png");
$obj->addBackScreenInfo("Copyrights","All rights reserved");
if($obj->generatePass("/var/www/passbook/test/","BoardingPass")){
    echo "<br> Your Pass has been generated. Now sign the pass by using following <a href='https://developer.apple.com/library/ios/documentation/UserExperience/Conceptual/PassKit_PG/Chapters/Introduction.html'>link</a>";
}

// Create COUPON pass
$obj2 = new ApplePass(COUPON,"pass.com.test.generic","EMBCMHCP7X","Super General Store","Super General Store Coupon","Super Store","rgb(22, 55, 110)","rgb(50, 91, 185)");
$obj2->addBarCode("SGSC00145UP");

$obj2->addLocation("27.43903","69.4532322");
$obj2->addLocation("88.85342","33.7540967");
$obj2->couponPass_addHeaderInfo("No","6901");
$obj2->couponPass_addDesc("on all mobile phones","5% Off");
$obj2->couponPass_addInfo("Expiry:","04/26/2014");
$obj2->couponPass_addInfo("Member:","John");
$obj->addIcon("/var/www/images/coupon/icon.png");
$obj2->addIconHD("/var/www/images/coupon/icon@2x.png");
$obj2->addLogo("/var/www/images/coupon/logo.png");
$obj2->addLogoHD("/var/www/images/coupon/logo@2x.png");
$obj2->addBackScreenInfo("Copyrights","All rights reserved Super General Store");
if($obj2->generatePass("/var/www/passbook/test/","CouponPass")){
    echo "<br> Your Pass has been generated. Now sign the pass by using following <a href='https://developer.apple.com/library/ios/documentation/UserExperience/Conceptual/PassKit_PG/Chapters/Introduction.html'>link</a>";
}

// Create Event ticket pass
$obj3 = new ApplePass(EVENT_TICKET,"pass.com.test.generic","EMBCMHCP7X","ABC Entertainment","Summer Gala","ABC Entertainment","rgb(22, 55, 110)","rgb(50, 91, 185)");
$obj3->addBarCode("ETDEY01402Q");

$obj3->eventPass_addHeaderInfo("Seat#","424");
$obj3->eventPass_addEvent("Event","Summer Fashion Show");
$obj3->eventPass_addInfo("Location","123 Ave NY");
$obj3->eventPass_addInfo("Date","09/23/2013");
$obj3->eventPass_addInfo("Time","3 PM");
$obj3->addIcon("/var/www/images/event/icon.png");
$obj3->addIconHD("/var/www/images/event/icon@2x.png");
$obj3->addLogo("/var/www/images/event/logo.png");
$obj3->addLogoHD("/var/www/images/event/logo@2x.png");
$obj3->addBackgroud("/var/www/images/event/background.png");
$obj3->addBackgroud_hd("/var/www/images/event/background@2x.png");
$obj3->addThumbnail("/var/www/images/event/thumbnail.png");
$obj3->addThumbnail_hd("/var/www/images/event/thumbnail@2x.png");
$obj3->addBackScreenInfo("Copyrights","All rights reserved ABC Entertainment");
if($obj3->generatePass("/var/www/passbook/test/","EventTicket")){
    echo "<br> Your Pass has been generated. Now sign the pass by using following <a href='https://developer.apple.com/library/ios/documentation/UserExperience/Conceptual/PassKit_PG/Chapters/Introduction.html'>link</a>";
}

// Create Generic pass
$obj4 = new ApplePass(GENERIC,"pass.com.test.generic","EMBCMHCP7X","Town Club","Membership pass for Town Club","Town Club","rgb(56, 190, 110)","rgb(22, 245, 105)");
$obj4->addBarCode("GE001RWM8A");
$obj4->genericPass_addHeaderInfo("ID","040");
$obj4->genericPass_addMemberInfo("Name","John");
$obj4->genericPass_addInfo("Member Since","2009");
$obj4->genericPass_addInfo("LEVEL","SILVER");
$obj4->genericPass_addInfo("Expiry","June 2014");
$obj4->addIcon("/var/www/images/generic/icon.png");
$obj4->addIconHD("/var/www/images/generic/icon@2x.png");
$obj4->addLogo("/var/www/images/generic/logo.png");
$obj4->addLogoHD("/var/www/images/generic/logo@2x.png");
$obj4->addThumbnail("/var/www/images/generic/thumbnail.png");
$obj4->addThumbnail_hd("/var/www/images/generic/thumbnail@2x.png");
$obj4->addBackScreenInfo("Copyrights","All rights reserved Town Club");
if($obj4->generatePass("/var/www/passbook/test/","GenericPass")){
    echo "<br> Your Pass has been generated. Now sign the pass by using following <a href='https://developer.apple.com/library/ios/documentation/UserExperience/Conceptual/PassKit_PG/Chapters/Introduction.html'>link</a>";
}

// Create Store Card pass
$obj5 = new ApplePass(STORE_CARD,"pass.com.test.generic","EMBCMHCP7X","ABC Organization","Store card for ABC Organization","ABC Corp.","rgb(112, 45, 22)","rgb(22, 200, 15)");
$obj5->addBarCode("G3E1R0092A");
$obj5->storePass_addHeaderInfo("No","AS34");
$obj5->storePass_addBalance("Balance",19.45,"USD");
$obj5->storePass_addInfo("Deal of the Day","Oranges");
$obj5->addIcon("/var/www/images/store/icon.png");
$obj5->addIconHD("/var/www/images/store/icon@2x.png");
$obj5->addLogo("/var/www/images/store/logo.png");
$obj5->addStrip("/var/www/images/store/strip.png");
$obj5->addStrip_hd("/var/www/images/store/strip@2x.png");
$obj5->addBackScreenInfo("Copyrights","All rights reserved ABC Organization");
if($obj5->generatePass("/var/www/passbook/test/","StoreCard")){
    echo "<br> Your Pass has been generated. Now sign the pass by using following <a href='https://developer.apple.com/library/ios/documentation/UserExperience/Conceptual/PassKit_PG/Chapters/Introduction.html'>link</a>";
}


?>