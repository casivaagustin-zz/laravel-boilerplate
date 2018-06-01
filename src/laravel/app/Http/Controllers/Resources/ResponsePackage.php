<?php

namespace App\Http\Controllers\Resources;

class ResponsePackage
{

    public $message = '';

    public $status = BaseApi::HTTP_OK;

    public $data = [];

    static public function error($error, $data = [], $status = BaseApi::HTTP_INVALID_REQUEST) {
        return new ResponsePackage($data, $error, $status);
    }

    static public function create($message, $data, $status = BaseApi::HTTP_OK) {
        return new ResponsePackage($data, $message, $status);
    }

    public function __construct($data = [], $message = '', $status = BaseApi::HTTP_OK)
    {
        $this->data = $data;
        $this->message = $message;
        $this->status = $status;
    }

    /**
     * Use it to set the user data in the response.
     *
     * @param $label
     * @param $data
     *
     * @return $this
     */
    public function setData($label, $data)
    {
        $this->data[$label] = $data;
        return $this;
    }

    /**
     * Use it to set the error messages to the client
     *
     * @param $error
     * @param null $status
     *
     * @return $this
     */
    public function setError($error, $status = null)
    {
        if (!empty($status)) {
            $this->setStatus($status);
        } elseif ($this->status == BaseApi::HTTP_OK) {
            $this->setStatus(BaseApi::HTTP_INVALID_REQUEST);
        }
        $this->message = $error;
        return $this;
    }

    /**
     * Use it to set messages for the client.
     *
     * @param $message
     *
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Use it to set the error status of the response
     *
     * @param $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Generates the Json Response
     */
    public function response()
    {
        return response()->json($this, $this->status);
    }


    /**
     * Sets the response as a validator error for custom validations
     *
     * @param array $errors
     *
     *   Format of errors
     *   [field => [message, message, ...]
     *
     *   For example
     *   for example ["dob" => ["Age must be grather than 13"]]
     */
    public function setValidationError(array $errors)
    {
        $this->setError('The given data failed to pass validation.', BaseApi::HTTP_INVALID_REQUEST);
        $this->setData("errors", $errors);
    }
}
