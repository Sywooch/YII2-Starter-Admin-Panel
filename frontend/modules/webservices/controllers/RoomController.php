<?php
/**
 * Created by PhpStorm.
 * User: rohan
 * Date: 23/9/16
 * Time: 11:06 AM
 */
namespace frontend\modules\webservices\controllers;

use common\models\Details;
use common\models\ReservationRooms;
use common\models\Reservations;
use Yii;
use frontend\components\RestController;
use yii\base\Exception;
use yii\helpers\Json;


class RoomController extends RestController
{
	public function actionCheckIn()
    {
        try {
            $tokendata = $this->checkAuth();    
            if(Yii::$app->request->isPost){
                $postdata = JSON::decode(file_get_contents("php://input"));
                $id=$postdata['room_id'];               
            $room = ReservationRooms::find()->where(['id'=>$id])->one();
            if($room){
            	$connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();
            	$reservation = $room->reservation;

            	if($reservation->status == Reservations::CHECK_OUT){
            			$code=400;
            			$data = $this->makeResponse($code,'Could not check out room because reservation is checked out.');
            	}elseif($reservation->status == Reservations::CANCEL_BY_CREWFACTS){
            			$code=400;
            			$data = $this->makeResponse($code,'Could not check out room because reservation is cancelled by crewfacts.');
            	}elseif ($reservation->status == Reservations::CANCEL_BY_CLIENT) {
            			$code=400;
            			$data = $this->makeResponse($code,'Could not check out room because reservation is cancelled by client.');
            	}else{
            		$room->status = Reservations::CHECKED_IN;
            		if($room->save(false)){
            			if($reservation->status == Reservations::PENDING_CHECK_IN){
			                $reservation->status = Reservations::CHECKED_IN;
			                if($reservation->save(false)){
			                	$transaction->commit();
			                	$code =200;
            					$data = $this->makeResponse($code,'Checked in',Details::reservationInfo($reservation));
			                }else{
								$transaction->rollBack();
								$code =400;
								$data = $this->makeResponse($code,'Error occurred while check in');
							}
            			}else{
            				$transaction->rollback();
            				$code= 400;
            				$data = $this->makeResponse($code,'Error occurred while check in because reservation is not in pending mode.');
            			}
            		}else{
            			$transaction->rollback();
            			$code= 400;
            			$data = $this->makeResponse($code,'Error occurred while check in.');
            		}            		
            	}            	
            }else{
                $code = 400;
                $data = $this->makeResponse($code, 'No room exists with this room id.');

            }
            }else{
                $code=400;
                $data=$this->makeResponse($code,$this->getStatusMessageCode($code)); 
            }     
        } catch (Exception $e) {
            $code = 500;
            $data = $this->makeResponse($code, 'something went wrong');
        }
        $this->sendResponse($code, JSON::encode($data));
	}

	// Room checked out by id
	public function actionCheckOut()
    {
        try {
            $tokendata = $this->checkAuth();
            if(Yii::$app->request->isPost){
            $postdata = JSON::decode(file_get_contents("php://input"));
            $id = $postdata['room_id'];
            $room = ReservationRooms::find()->where(['id'=>$id])->one();
            if($room){
            	$connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();
            	$reservation = $room->reservation;
            	if($reservation->status == Reservations::CHECK_OUT){
            		$code=400;
            		$data = $this->makeResponse($code,'Whole reservation is already checked out.');
            	}elseif($reservation->status == Reservations::CANCEL_BY_CREWFACTS){
            		$code=400;
            		$data = $this->makeResponse($code,'Reservation is already cancelled by crewfacts.');
            	}elseif($reservation->status == Reservations::CANCEL_BY_CLIENT){
            		$code=400;
            		$data = $this->makeResponse($code,'Reservation is already cancelled by client.');
            	}else{
            		$room->status = Reservations::CHECK_OUT;
            		if($room->save(false)){
            			$flg = 0;
                		foreach($reservation->reservationRooms as $room){
	                    	if($room->id != $id){
	                        	if($room->status == Reservations::CHECK_OUT){
	                           		 $flg++;
	                        	}
	                    	}
                		}
		                if($flg == (sizeof($reservation->reservationRooms)-1))
		                {
		                    $reservation->status = Reservations::CHECK_OUT;
		                    if($reservation->save(false)){
		                    	$transaction->commit();
		                    	$code = 200;
		                    	$data = $this->makeResponse($code,'Checked out successfully', Details::reservationInfo($reservation));
		                    }else{
		                    	$transaction->rollback();
		                    	$code= 400;
		                    	$data = $this->makeResponse($code,'Error while checked out.');
		                    }
		                }else{
		                	$transaction->commit();
		                    $code = 200;
		                    $data = $this->makeResponse($code,'Checked out successfully', Details::reservationInfo($reservation));
		                }
            		}else{
            			$transaction->rollback();
            			$code=400;
            			$data = $this->makeResponse($code,'Error occured while check out.');
            		}
            	}
            }else{
                $code = 400;
                $data = $this->makeResponse($code, 'No room exists with this room id.');

            }
          }else{
                $code= 400;
                $this->makeResponse($code,$this->getStatusMessageCode($code));
            }
        } catch (Exception $e) {
            $code = 500;
            $data = $this->makeResponse($code, 'something went wrong');
        }
        $this->sendResponse($code, JSON::encode($data));
	}

    //check todays date with check in date and if check in time is in 2 hours then set the flag true
    public  function actionCheckInStatus(){
        try {
            $tokendata = $this->checkAuth();
            if(Yii::$app->request->isPost){
            $postdata = JSON::decode(file_get_contents("php://input"));
            $id = $postdata['room_id'];            
            $room = ReservationRooms::find()->where(['id'=>$id])->one();
            if($room){
                $todaysDate = date('Y-m-d',time());
                if($room->check_in_date == $todaysDate){
                    $time=date('H',time());
                    if($time>9){
                        $code = 200;
                        $data['code']=$code;
                        $data['enable']=true;
                    }else{
                        $code = 200;
                        $data['code']=$code;
                        $data['enable']=false;
                    }
                }else{
                    $code = 200;
                    $data['code']=$code;
                    $data['enable']=false;
                }
            }else{
                $code = 400;
                $data = $this->makeResponse($code, 'No room exists with this room id.');

            }
         }else{
                $code= 400;
                $this->makeResponse($code,$this->getStatusMessageCode($code));
            }
        } catch (Exception $e) {
            $code = 500;
            $data = $this->makeResponse($code, 'something went wrong');
        }
        $this->sendResponse($code, JSON::encode($data));
    }
    
}