<?php
namespace PipIWYG\SablCore\Http\Controllers\Api;

use Illuminate\Routing\Controller as Controller;
use Illuminate\Http\Response;
/**
 * SABL API Base Controller, managing data capture through standard CRUD, and handling API Requests and Response
 *
 * @package PipIWYG\SablCore\Http\Controllers\Api
 * @author Quintin Stoltz <qstoltz@gmail.com>
 */
abstract class SablApiBaseController
    extends Controller
{
    /**
     * Common Wrapper used to return a pre-defined set array structure for all requests
     */
    protected final function generateResponse($message = 'Invalid Request', $code = Response::HTTP_UNPROCESSABLE_ENTITY, array $response_data = []) : array
    {
        // Define Initial Result
        $result = [
            'success' => ($code === Response::HTTP_OK || $code === Response::HTTP_CREATED),
            'message' => $message,
            'code' => $code,
        ];

        // Validate to check if we need to include data in the response
        if (is_array($response_data) && !empty($response_data)) {
            $result['data'] = $response_data;
        }

        // Return Result
        return $result;
    }

    /**
     * Wrapper to return Json Response
     * @param array $result
     * @return mixed
     */
    protected final function respondToRequest(array $result)
    {
        // Check for required array keys in response
        if (isset($result['success']) && isset($result['message']) && isset($result['code'])) {
            // Return JSon Response with Result of above query
            return response()->json($result,$result['code']);
        }

        // Return JSon Response with Result of above query
        return response()->json($this->generateResponse(),
            Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
