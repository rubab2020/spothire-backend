<?php
namespace App\Http\Controllers;

class ApiController extends Controller
{
    /**
     * Setting setus 200 by defualt for getting data
     *
     * @var int
     **/
    protected $statusCode = 200;

    /**
     * @return mixed
     **/
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param mixed $statusCode
     **/
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function respond($data, $headers = [])
    {
        return response()->json($data, $this->getStatusCode(), $headers);
    }

    public function respondWithError($message)
    {
        return $this->respond([
            'error' => [
                'message' => $message
            ]
        ]);
    }

    public function respondWithSuccess($message)
    {
        return $this->respond([
            'success' => [
                'message' => $message
            ]
        ]);
    }

    public function respondWithSuccessWithData($message, $data)
    {
        return $this->respond([
            'success' => [
                'message' => $message,
                'data'  => $data
            ]
        ]);
    }

    public function respondValidationError($message = 'Bad Request!')
    {
        return $this->setStatusCode(400)->respondWithError($message);
    }

    public function respondValidationErrors($messages)
    {
        return $this->setStatusCode(400)->respondWithError($messages);
    }

    public function respondUnauthorized($message  = 'Unauthorized!')
    {
        return $this->setStatusCode(401)->respondWithError($message);
    }

    public function respondForbidden($message  = 'Forbidden!')
    {
        return $this->setStatusCode(403)->respondWithError($message);
    }

    public function respondNotFound($message  = 'Not Found!')
    {
        return $this->setStatusCode(404)->respondWithError($message);
    }

    public function respondInternalError($message  = 'Internal Server Error!')
    {
        return $this->setStatusCode(500)->respondWithError($message);
    }

    public function respondServiceUnavailable($message  = 'Service Unavailable, Please try again later!')
    {
        return $this->setStatusCode(503)->respondWithError($message);
    }

    public function respondCreatingResource($message = 'Created Successfully')
    {
        return $this->setStatusCode(201)->respondWithSuccess($message);
    }

    public function respondCreatingResourceWithData(
        $message = 'Created Successfully', 
        $data
    ){
        return $this->setStatusCode(201)
                ->respondWithSuccessWithData($message, $data);
    }

    public function respondUpdatingResource($message = 'Updated Successfully')
    {
        return $this->setStatusCode(200)->respondWithSuccess($message);
    }

    public function respondUpdatingResourceWithData(
        $message = 'Updated Successfully',
        $data
    ){
        return $this->setStatusCode(200)
                    ->respondWithSuccessWithData($message, $data);
    }

    public function respondDeleteingResource($message = 'Deleted Successfully')
    {
        return $this->setStatusCode(200)->respondWithSuccess($message);
    }
}