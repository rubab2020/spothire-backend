<?php

namespace App\Http\Controllers;

use App\Concentration;
use App\Degree;
use App\Institution;
use App\Qualification;
use App\UserConcentration;
use Illuminate\Http\Request;
use App\SpotHire\Helpers\EncodeHelper;
use App\SpotHire\Helpers\CustomHelper;
use App\SpotHire\Transformers\WorkerProfileTransformer;
use App\SpotHire\Transformers\CustomTransformer;

class QualificationController extends ApiController
{
    /**
     * @var integer
     **/
    private $clientInfo;

    /**
     * @var App\Helpers\EncodeHelper
     **/
    private $encodeHelper;

    /**
    * @var App\SpotHire\Transformers\WorkerProfileTransformer
    **/
    protected $workerProfileTransformer;

    function __construct(EncodeHelper $encodeHelper, WorkerProfileTransformer $workerProfileTransformer, CustomTransformer $customTransformer)
    {
        $this->clientInfo = auth()->guard('api')->authenticate();
        $this->encodeHelper = $encodeHelper;
        $this->workerProfileTransformer = $workerProfileTransformer;
        $this->customTransformer = $customTransformer;
    }

    public function getQualifications(Request $request){
        return $this->respond($this->getQualificationData($this->clientInfo));
    }

    public function saveQualifications(Request $request){
        $qualifications = $request->all();

        foreach($qualifications as $qualification){
            $qualificationId  = $this->encodeHelper->decodeData($qualification['id']);

            if( !CustomHelper::IsNullOrEmptyString($qualification['degree'])
                || !CustomHelper::IsNullOrEmptyString($qualification['institution'])
                || !CustomHelper::IsNullOrEmptyString($qualification['result_cgpa'])
                || !CustomHelper::IsNullOrEmptyString($qualification['cgpa_scale'])
                || !CustomHelper::IsNullOrEmptyString($qualification['completing_date'])
                || !CustomHelper::IsNullOrEmptyString($qualification['enrollment_data'])
            ){
                $qualifctnObj = !CustomHelper::IsNullOrEmptyString($qualificationId)  
                                ? Qualification::find($qualificationId)
                                : new Qualification();

                // create degree and institution
                if(!CustomHelper::IsNullOrEmptyString($qualification['degree'])){
                    Degree::firstOrCreate(['name' => $qualification['degree']]);
                }
                if(!CustomHelper::IsNullOrEmptyString($qualification['institution'])){
                    Institution::firstOrCreate(
                        ['name' => $qualification['institution']]
                    );
                }

                $qualifctnObj->user_id           = $this->clientInfo->id;
                $qualifctnObj->degree            = $qualification['degree'];
                $qualifctnObj->institution       = $qualification['institution'];
                $qualifctnObj->result_cgpa       = $qualification['result_cgpa'];
                $qualifctnObj->cgpa_scale        = $qualification['cgpa_scale'];
                $qualifctnObj->completing_date   = !CustomHelper::IsNullOrEmptyString($qualification['completing_date']) 
                                                    ? date('Y-m-d',strtotime($qualification['completing_date'])) 
                                                    : $qualification['completing_date'];
                $qualifctnObj->enrolled          = (isset($qualification['enrollment_data']) 
                                                    && $qualification['enrollment_data'] == true)
                                                     ? true : false;
                $qualifctnObj->save();
            }
        }
        return $this->respond($this->getQualificationData($this->clientInfo));
    }

    public function deleteQualification(Request $request){
        $credentials = $request->all();
        $workID = $this->encodeHelper->decodeData($credentials['qualification_id']);

        $delete = Qualification::where('user_id',$this->clientInfo->id)
                                            ->where('id',$workID)
                                            ->delete();
        
        if(!$delete) return $this->respondInternalError('Failed Deleting qualification.');
        
        return  $this->respondDeleteingResource('Qualification deleted successfully.');
    }

    public function getQualificationData($user){
        $qualifications  = Qualification::where('user_id',$user->id)
                                ->orderBy('completing_date', 'desc')
                                ->get();
        $degrees        = Degree::where('is_active', true)->get();
        $institutions   = Institution::where('is_active', true)->get();

        return[
            'data' => $this->workerProfileTransformer->transformQualifications($qualifications),
            'degrees'=> $this->customTransformer->transformDegrees($degrees),
            'institutions' => $this->customTransformer->transformInstitutions($institutions),
        ];
    }
}
