<?php
namespace App\SpotHire\Transformers;

use App\SpotHire\Transformers\Transformer;
use App\SpotHire\Helpers\EncodeHelper;

class MessageTransformer extends Transformer
{
	/**
     * @var App\Helpers\EncodeHelper
     **/
    private $encodeHelper;

    function __construct(EncodeHelper $encodeHelper)
    {
        $this->encodeHelper = $encodeHelper;
    }

	/**
     * transform a object for mapping between api parameters and database columns
     *
     * @param array of objects $jobs
     * @return array
     **/
    public function transform($message)
    {
        $data = [
            'id'                => $this->encodeHelper->encodeData($message['id']),
            'application_id'    => $this->encodeHelper->encodeData($message['application_id']),
            'sender_id'         => $this->encodeHelper->encodeData($message['sender_id']),
            'receiver_id'       => $this->encodeHelper->encodeData($message['receiver_id']),
            'message'           => $message['message'],
            'is_receiver_seen'  => $message['is_receiver_seen']?true:false,
            'created_at'        => $message['created_at'],
            'updated_at'        => $message['updated_at'],
        ];

        if(isset($message['job_id'])){
            $data['job_id'] = $this->encodeHelper->encodeData($message['job_id']);
        }

        return $data;
    }
}