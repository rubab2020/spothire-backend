<?php

namespace App\Http\Controllers;

use App\Award;
use App\Tag;
use App\User;
use Illuminate\Http\Request;
use App\SpotHire\Helpers\EncodeHelper;
use App\SpotHire\Helpers\CustomHelper;
use App\SpotHire\Transformers\WorkerProfileTransformer;
use App\SpotHire\Transformers\CustomTransformer;

class AwardController extends ApiController
{
    /**
     * @var integer
     **/
    private $clientInfo;

    /**
     * @var App\Helpers\EncodeHelper
     **/
    private $encodeHelper;

    function __construct(EncodeHelper $encodeHelper, WorkerProfileTransformer $workerProfileTransformer)
    {
        $this->clientInfo = auth()->guard('api')->authenticate();
        $this->encodeHelper = $encodeHelper;
        $this->workerProfileTransformer = $workerProfileTransformer;
    }

    public function getAwards(){
        $awards = Award::where('user_id', $this->clientInfo->id)->get();
        return $this->respond(['data'=>$this->workerProfileTransformer->transformAwards($awards)]);
    }

    public function saveAward(Request $request){
        $awards = $request->all();

        foreach($awards as $award){
            $awardId = $this->encodeHelper->decodeData($award['id']);
            
            if( !CustomHelper::IsNullOrEmptyString($award['title']) 
                || !CustomHelper::IsNullOrEmptyString($award['institution']) 
                || !CustomHelper::IsNullOrEmptyString($award['date']) 
            ){
                $awardObj = !CustomHelper::IsNullOrEmptyString($awardId) ? Award::find($awardId) : new Award();

                $awardObj->user_id      = $this->clientInfo->id;
                $awardObj->title        = $award['title'];
                $awardObj->institute    = $award['institution'];
                $awardObj->date         = !CustomHelper::IsNullOrEmptyString($award['date']) 
                                            ? date('Y-m-d',strtotime($award['date'])) 
                                            : $award['date'];
                $awardObj->save();
            }
        }

        $awards = Award::where('user_id',$this->clientInfo->id)->get();
        
        return $this->respond(['data'=>$this->workerProfileTransformer->transformAwards($awards)]);
    }

    public function deleteAward(Request $request){
        $credentials = $request->all();
        $awardId = $this->encodeHelper->decodeData($credentials['id']);

        $delete = Award::where('user_id', $this->clientInfo->id)->where('id', $awardId)->delete();

        if(!$delete) return $this->respondInternalError('Failed Deleting award.');
        
        return  $this->respondDeleteingResource('Award deleted successfully.');
    }
}
