<?php
/**
 * Created by PhpStorm.
 * User: rohan
 * Date: 11/23/2015
 * Time: 5:10 AM
 */

//APP INFO
define("APP_NAME","Yii Starter");
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

//notification read unread
define('UNREAD', 0);
define('READ',1);
define('NOT_NOTIFIED', 0);
define('NOTIFIED', 1);

define('DEV',false);