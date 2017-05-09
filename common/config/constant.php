<?php
/**
 * Created by PhpStorm.
 * User: rohan
 * Date: 11/23/2015
 * Time: 5:10 AM
 */
if(isset($_SERVER['SERVER_NAME']))
{
	$host = $_SERVER['SERVER_NAME'];
//BASE URL

    if($host == 'localhost' ||$host == '192.168.1.107' ){
        //define("BASE_URL","http://192.168.1.107/crewfacts");
        define("BASE_URL","http://app.crewfacilities.com");
    } else if($host =='demo') {
        define("BASE_URL","http://demo.thetatechnolabs.com");
    }else{
        define("BASE_URL","http://app.crewfacilities.com");
    }

define("DEFAULT_IMAGE",BASE_URL."/backend/web/images/default_image");
define("LOGO",BASE_URL."/backend/web/images/logo.png");
define("CREWLOGO",BASE_URL."/backend/web/images/crewfacilities_logo.png");
define("CREWWEB",BASE_URL."/backend/web/images/web.png");
define("CREWAPPLE",BASE_URL."/backend/web/images/apple.png");
define("CREWANDROID",BASE_URL."/backend/web/images/android.png");
}


//APP INFO
define("APP_NAME","Circle Society");
define("VERSION","0.0.1");
define("COMPANY_NAME","Theta Thechnolabs");
//Status
define("ACTIVE",1);
define("INACTIVE",0);
//status of user availabilty
define("AVAILABLE",1);
define("NOT_AVAILABLE",0);

//Deleted
define("DELETED",1);
define("NOT_DELETED",0);

//platforms
define("WEB",0);
define("IOS",1);
define("ANDROID",2);

//web Notification alerts
define('RESERVATION_ASSIGNED',1);
define('EXTEND',2);



// Roles
define("SUPER_ADMIN",1);
define("ADMIN",2);
define("RESERVATIONIST",3);
define("CLIENT",4);
define("CLIENT_APP_USER",5);
define("ACCOUNTANT",6);
define("PROCUREMENT",7);

//default password
define('DEFAULT_PASSWORD','default@99');

//User's Verification Status
define("USER_VERIFIED",1);
define("USER_PENDING",0);

//some Default values
define("DEFAULT_COUNTRY_CODE",91);
define("DEFAULT_USER_ID",1);

//Login Status
define("NOT_LOGGED_IN",0);
define("LOGGED_IN",1);

//DEFUALT VALUES
define('DEVLOPMENT_ENV',false);
//pagesize use for backend
define('PAGESIZE',5);
//limit use for REST API
define("LIMIT",10);


define("REST_DEFAULT_MESSAGE_STRING","oops... there is some problem in code, edison.. you can do it and we know that");

define('NOT_SPECIFIED',0);
define('MALE',1);
define('FEMALE',2);

//push notification
define('FIREBASE_API_KEY', 'AIzaSyBgBfe7Yxb9TLk6iPkXZgSs-AAYDRutxyQ');


// Expedia Credentials
define('API_KEY','8k02cl0s57ultid3b2oicb7lh');
define('SECRET_KEY','emhn54ch0e8iv');
define('CID','500089');

//send mail types constants
define('WELCOME_MAIL',1);
define('FACILITY_CC_RESERVATION',2);
define('FACILITY_DIRECT_BILL_RESERVATION',3);
define('RESERVATION_CONFIRMATION_MAIL',4);
define('CHECK_IN_REMINDER_MAIL',5);
define('FORGOT_PASSWORD_MAIL',6);
define('WELCOME_STAFF_USER',7);
define('RESERVATION_CANCELLATION_CLIENT',8);
define('RESERVATION_CANCELLATION_FACILITY',9);
define('EXTEND_TO_CLIENT',10);




//notification read unread
define('UNREAD', 0);
define('READ',1);
define('NOT_NOTIFIED', 0);
define('NOTIFIED', 1);

//for sending fax
define('FAXURL',"http://ws.interfax.net/dfs.asmx?WSDL");
define('FAX_USERNAME', 'crewfac');
define('FAX_PASSWORD', 'Someday16');
define('FAX_TEST',false);
define('FAX_ADMIN','+18667199092');

//admin email
define('ANDREA', 'andrea@crewfacilities.com');
define('ADMIN_EMAIL', 'info@crewfacilities.com');

//define('ADMIN_EMAIL', 'rohanmashiyava@gmail.com');
//define('ADMIN_EMAIL', 'mitesh.thetatechnolabs@gmail.com');
//for hotel confirmation
define('CONFIRM',1);
define('NOT_CONFIRM',0);


define('DEV',false);

// cc email
define('CLIENT_CC','');
define('FACILITY_CC','');
define('FACILTY_BCC','ap@crewfacilities.com');
define('CLIENT_BCC', 'ar@CrewFacilities.com');
/*define('FACILTY_BCC','hello@thetatechnolabs.com');
define('CLIENT_BCC', 'ar@CrewFacilities.com');*/