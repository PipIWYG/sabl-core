<?php
namespace PipIWYG\SablCore\Http\Controllers\Api;

use Illuminate\Routing\Controller as Controller;
use Illuminate\Http\Response;
use PipIWYG\SablCore\Models\Address;
use PipIWYG\SablCore\Models\Contact;
use PipIWYG\SablCore\Models\AddressBook;
use PipIWYG\SablCore\Models\EmailAddress;
use PipIWYG\SablCore\Models\PhoneNumber;

/**
 * SABL API Controller, managing data capture through standard CRUD, and handling API Requests and Response
 *
 * @package PipIWYG\SablCore\Http\Controllers\Api
 * @author Quintin Stoltz <qstoltz@gmail.com>
 */
class SablApiController
    extends Controller
{
    /**
     * Common Method to return Invalid Request Input Data used in API Query Functions
     * @return array
     */
    private function responseInvalidRequest($message = 'Invalid Request', $code = Response::HTTP_UNPROCESSABLE_ENTITY) : array
    {
        return [
            'success' => false,
            'message' => $message,
            'code' => $code,
        ];
    }

    /**
     * Create a new address record using the supplied input
     * @return array
     */
    private function address_create() : array
    {
        // Get Request Data and attempt to apply some minimal validation for empty values
        $request_data = request()->only(['street_address', 'city', 'country', 'contact_id']);

	// Apply minimal data validation for capture. A better approach for more advanced data validation may be to make use of a Request Validator object to define validation error
	// messages. Simple use for example purposes.
        if (empty($request_data) || (!isset($request_data['street_address']) || !isset($request_data['city']) || !isset($request_data['country']) || !isset($request_data['contact_id']))) {
            return $this->responseInvalidRequest('Invalid Request Input Data');
        }

        // Get Street Address and Validate for Empty
        $street_address = trim($request_data['street_address']);
        if (empty($street_address)) {
	    return $this->responseInvalidRequest('A street address is required to create a new record');
        }

        // Get City Name and validate for Empty
        $city = trim($request_data['city']);
        if (empty($city)) {
	    return $this->responseInvalidRequest('A valid city name is required to create a new record');
        }

        // Get Country Name and validate for Empty
        $country = trim($request_data['country']);
        if (empty($country)) {
	    return $this->responseInvalidRequest('A valid country name is required to create a new record');
        }

        // Get Contact ID and validate for Empty
        $contact_id = trim($request_data['contact_id']);
        if (empty($contact_id)) {
	    return $this->responseInvalidRequest('A Contact ID is required to create a new record');
        }

        // Define Initial Failed Response
        $message = 'Failed to create address record';
        $result = [
            'success' => false,
            'message' => $message,
            'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
        ];

        try {
            Address::create(['contact_id' => $contact_id, 'street_name'=>$street_address,'city'=>$city,'country'=>$country]);
            $result['success'] = true;
            $result['message'] = 'Record Successfully Created';
            $result['code'] = Response::HTTP_OK;

        } catch(\Exception $e) {
            Log::error($message);
            Log::error($e->getMessage());
        }

        // Return Result
        return $result;
    }

    /**
     * Create a new email address record using the supplied input
     * @return array
     */
    private function email_address_create() : array
    {
        // Get Request Data and attempt to apply some minimal validation for empty values
        $request_data = request()->only(['email_address','contact_id']);
        if (empty($request_data) || (!isset($request_data['email_address']) || !isset($request_data['contact_id']))) {
            return $this->responseInvalidRequestInputData();
        }

        // Get Email Address and validate for Empty
        $email_address = trim($request_data['email_address']);
        if (empty($email_address)) {
            return [
                'success' => false,
                'message' => 'A email address is required to create a new record',
                'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
            ];
        }

        // Get Contact ID and validate for Empty
        $contact_id = trim($request_data['contact_id']);
        if (empty($contact_id)) {
            return [
                'success' => false,
                'message' => 'A Contact ID is required to create a new record',
                'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
            ];
        }

        // Define initial response message and array
        $message = 'Failed to create email address record';
        $result = [
            'success' => false,
            'message' => $message,
            'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
        ];

        try {
            EmailAddress::create(['contact_id' => $contact_id, 'email_address'=>$email_address]);
            $result['success'] = true;
            $result['message'] = 'Record Successfully Created';
            $result['code'] = Response::HTTP_OK;
        } catch(\Exception $e) {
            Log::error($message);
            Log::error($e->getMessage());
        }

        // Return Result
        return $result;
    }

    /**
     * Create a new phone number record using the supplied input
     * @return array
     */
    private function phone_number_create() : array
    {
        // Get Request Data and attempt to apply some minimal validation for empty values
        $request_data = request()->only(['phone_number','contact_id']);
        if (empty($request_data) || (!isset($request_data['phone_number']) || !isset($request_data['contact_id']))) {
            return $this->responseInvalidRequestInputData();
        }

        // Get Phone Number and Validate for Empty
        $phone_number = trim($request_data['phone_number']);
        if (empty($phone_number)) {
            return [
                'success' => false,
                'message' => 'A phone number is required to create a new record',
                'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
            ];
        }

        // Get Contact ID and validate for Empty
        $contact_id = trim($request_data['contact_id']);
        if (empty($contact_id)) {
            return [
                'success' => false,
                'message' => 'A Contact ID is required to create a new record',
                'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
            ];
        }

        // Define Initial Response message and array
        $message = 'Failed to create phone number record';
        $result = [
            'success' => false,
            'message' => $message,
            'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
        ];

        try {
            PhoneNumber::create(['contact_id' => $contact_id, 'phone_number'=>$phone_number]);
            $result['success'] = true;
            $result['message'] = 'Record Successfully Created';
            $result['code'] = Response::HTTP_OK;
        } catch(\Exception $e) {
            Log::error($message);
            Log::error($e->getMessage());
        }

        // Return Result
        return $result;
    }

    /**
     * Create a new contact group record using the supplied input
     * @return array
     */
    private function group_create() : array
    {
        // Get Request Data and attempt to apply some minimal validation for empty values
        $request_data = request()->only(['group_name']);
        if (empty($request_data) || (!isset($request_data['group_name']))) {
            return $this->responseInvalidRequestInputData();
        }

        // Get Group Name and validate for Empty
        $group_name = trim($request_data['group_name']);
        if (empty($group_name)) {
            return [
                'success' => false,
                'message' => 'A group name is required to create a new record',
                'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
            ];
        }

        // Define initial response message and array
        $message = 'Failed to create contact group record';
        $result = [
            'success' => false,
            'message' => $message,
            'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
        ];

        try {
            ContactGroup::create(['group_name' => $group_name]);
            $result['success'] = true;
            $result['message'] = 'Record Successfully Created';
            $result['code'] = Response::HTTP_OK;
        } catch(\Exception $e) {
            Log::error($message);
            Log::error($e->getMessage());
        }

        // Return result
        return $result;
    }

    /**
     * Create a new contact record using the supplied first_name and last_name
     * @return array
     */
    private function contact_create() : array
    {
        // Get Request Data and attempt to apply some minimal validation for empty values
        $request_data = request()->only(['first_name','last_name','group_id']);
        if (empty($request_data) || (!isset($request_data['first_name']) || !isset($request_data['last_name']) || !isset($request_data['group_id']))) {
            return $this->responseInvalidRequestInputData();
        }

        // Get first name and validate for Empty
        $first_name = trim($request_data['first_name']);
        if (empty($first_name)) {
            return [
                'success' => false,
                'message' => 'A valid first name is required to create a new contact',
                'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
            ];
        }

        // Get Last Name and Validate for Empty
        $last_name = trim($request_data['last_name']);
        if (empty($last_name)) {
            return [
                'success' => false,
                'message' => 'A valid last name is required to create a new contact',
                'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
            ];
        }

        // Get Group ID and validate for Empty
        $group_id = trim($request_data['group_id']);
        if (empty($group_id)) {
            return [
                'success' => false,
                'message' => 'A valid group ID is required to create a new contact',
                'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
            ];
        }

        // Define initial response message and array
        $message = 'Failed to create contact';
        $result = [
            'success' => false,
            'message' => $message,
            'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
        ];

        try {
            Contact::create(['first_name'=>$first_name,'last_name'=>$last_name,'group_id'=>$group_id]);
            $result['success'] = true;
            $result['message'] = 'Record Successfully Created';
            $result['code'] = Response::HTTP_OK;

        } catch(\Exception $e) {
            Log::error($message);
            Log::error($e->getMessage());
        }

        // Return Result
        return $result;
    }

    /**
     * Query/View a contact record
     * @return array
     */
    private function contact_view() : array
    {
        // Get Request Data and attempt to apply some minimal validation for empty values
        $request_data = request()->only(['contact_id','first_name','last_name']);
        if (empty($request_data)) {
            return $this->responseInvalidRequestInputData();
        }

        // Initial Output Response Array
        $result = [
            'success' => false,
            'message' => 'Invalid Request',
            'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
        ];

        // Check if a valid contact ID has been defined
        if (isset($request_data['contact_id'])) {
            // Retrieve Contact ID
            $contact_id = intval($request_data['contact_id']);

            // Query Data, including relationships
            $result = Contact::where('id','=',$contact_id)
                ->with(['addresses','email_addresses','phone_numbers','group'])
                ->first();

            // Null check for record not found
            if (null === $result) {
                return [
                    'success' => false,
                    'message' => 'Record Not Found',
                    'code' => Response::HTTP_NOT_FOUND,
                ];
            }

            // Convert result to array for response
            $result = [
                'success' => true,
                'message' => 'Request Successful',
                'code' => Response::HTTP_OK,
                'data' => $result->toArray(),
            ];
        }

        // Return Result
        return $result;
    }

    /**
     * handleApiQuery
     *
     * @param null|string $query The request scope query
     * @param null|string $action An identifier to define an action, such as 'all' or 'id'
     * @param null|int $id Should $action be set to id, this is the ID of the record to query
     * @return JsonResponse
     */
    public function handleApiQuery($query = null, $action = null, $id = null)
    {
        // Initial Output Response Array
        $result = [
            'success' => false,
            'message' => 'Invalid Request',
            'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
        ];

        // Select query scope
        switch($query) {
            case "contact_group":
                // Select query action
                switch ($action) {
                    case "create":
                        $result = $this->group_create();
                        break;
                }
                break;

            case "contact":
                // Select query action
                switch ($action) {
                    case "create":
                        $result = $this->contact_create();
                        break;
                    case "view":
                        $result = $this->contact_view();
                        break;
                }
                break;

            case "address":
                // Select query action
                switch ($action) {
                    case "create":
                        $result = $this->address_create();
                        break;
                }
                break;

            case "email_address":
                // Select query action
                switch ($action) {
                    case "create":
                        $result = $this->email_address_create();
                        break;
                }
                break;

            case "phone_number":
                // Select query action
                switch ($action) {
                    case "create":
                        $result = $this->phone_number_create();
                        break;
                }
                break;
        }

        // Return JSon Response with Result of above query
        return response()->json($result,$result['code']);
    }
}
