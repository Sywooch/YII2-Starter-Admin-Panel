<?php
/**
 * Created by PhpStorm.
 * User: theta-php
 * Date: 7/1/17
 * Time: 3:53 PM
 */
namespace frontend\models;
class Url {

    CONST MEDIA_URL ="https://media.expedia.com";
    //CONST BASE_URL = "http://192.168.1.136/crewfacts/expedia";
    CONST BASE_URL = "https://app.crewfacilities.com/expedia";
    CONST ACCESS_TOKEN = 'httpx-thetatech-accesstoken';
    //CONST BASE_URL = "http://192.168.1.136/crewfacts/expedia";

    CONST HOTEL_SEARCH = self::BASE_URL."/hotel/get-list";
    CONST HOTEL_INFO = self::BASE_URL."/hotel/get-info";
    CONST ROOM_AVAILABILITY = self::BASE_URL."/room/availability";
    CONST PAYMENT_TYPES = self::BASE_URL."/payment/types";
    CONST BOOK_RESERVATION = self::BASE_URL."/book-reservation";

    CONST ITERNARY_VIEW = self::BASE_URL."/itinerary/view";
    CONST ITERNARY_CANCEL = self::BASE_URL."/itinerary/cancel";


}