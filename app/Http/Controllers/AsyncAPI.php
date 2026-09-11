<?php

namespace App\Http\Controllers;

use App\Libraries\SendApiError;
use App\Models\ApiRequestHeader;
use App\Models\ApiRequestNote;
use App\PublicApi\getRates;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Ubench;

class AsyncAPI
{
    /**
     * requestHeader
     * Holds the ApiRequestHeader object
     *
     * @var ApiRequestHeader
     */
    protected readonly ApiRequestHeader $requestHeader;

    /**
     * request
     * Holds the illuminate request object
     *
     * @var Request|mixed
     */
    protected readonly Request $request;

    protected ?Ubench $ubench = null;

    /**
     * __construct
     * The class construct. We setup the apiRequestHeader here
     *
     * @param ApiRequestHeader $p_requestHeader
     * @param Request $p_Request
     * @throws Exception
     */
    public function __construct(ApiRequestHeader $p_requestHeader, Request $p_Request, Ubench $ubench)
    {
        $this->ubench = $ubench;
        $this->ubench->start();

        $model = $p_requestHeader->newInstance();

        $model->input = $p_Request->all();
        $model->save();

        $this->requestHeader = $model;

        App::instance('apiRequestHeader', $this->requestHeader);
        ApiRequestNote::newNote('debug', 'API Connection Started');

        $this->request = App::make(Request::class);
    }

    /**
     * getRates
     * Get the rates from all of our providers
     *
     * @param $slug
     * @return JsonResponse
     * @throws Exception
     */
    public function getRates($slug)
    {
        $api = new getRates($this->requestHeader, $slug, $this->request);

        $response = []; //placeholder
        try {
            $response = $api->dispatch(); //dispatch our requests in fancy async mode
        } catch (Exception $e) {
            return $this->abortResponse($e); //if we get an exception back abort the response
        }

        //complete the response
        return $this->successResponse($response);
    }

    /**
     * abortResponse
     * This method is responsible for aborting the response
     *
     * @param string|Exception $message
     * @return JsonResponse
     * @throws Exception
     */
    protected function abortResponse(string|Exception $message)
    {
        //if the message is an exception just grab the text from the exception
        if ($message instanceof Exception) {
            $message = $message->getMessage();
        }

        ApiRequestNote::newNote('error', $message);

        $output = [
            'error' => $message
        ];

        //update our api request header
        $this->requestHeader->isSuccess = false;
        $this->requestHeader->output = $output;
        $this->requestHeader->save();

        //we want to send a notification to people
        SendApiError::sendErrorEmail($message);

        //if the env is production we don't want to return the actual
        //exception to the end user
        if (App::isProduction()) {
            $output['error'] = 'Internal Server Error';
        }

        //return our response
        return response()->json(
            $output,
            500
        );
    }

    /**
     * successResponse
     * This method is responsible for returning a successful response
     *
     * @param array $data
     * @return JsonResponse
     * @throws Exception
     */
    protected function successResponse(array $data)
    {
        ApiRequestNote::newNote('debug', 'API Connection Ended');

        //update our api request header
        $this->requestHeader->isSuccess = true;
        $this->requestHeader->output = $data;
        $this->requestHeader->save();

        if (count($data) == 0) {
            //we want to send a notification to people
            SendApiError::sendErrorEmail('No rates returned');
        }

        $this->ubench->end();
        $timer = $this->ubench->getTime(true);
        if ($timer > 10) {
            //we want to send a notification to people
            SendApiError::sendErrorEmail('Runtime took more than 10 seconds');
        }

        ApiRequestNote::newNote('debug', 'API Connection Timer: ['.round($timer,2,PHP_ROUND_HALF_UP).'] seconds');


        return response()->json(
            $data,
            200
        );
    }
}