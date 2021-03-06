<?php
namespace PipIWYG\SablCore\Http\Controllers\Api;

use Illuminate\Routing\Controller as Controller;
use Illuminate\Http\Response;
use PipIWYG\SablCore\Models\Address;
use PipIWYG\SablCore\Models\Contact;
use PipIWYG\SablCore\Models\AddressBook;
use PipIWYG\SablCore\Models\EmailAddress;
use PipIWYG\SablCore\Models\Groups;
use PipIWYG\SablCore\Models\PhoneNumber;
use Illuminate\Support\Facades\Log;

/**
 * SABL API Controller, managing data capture through standard CRUD, and handling API Requests and Response
 *
 * @package PipIWYG\SablCore\Http\Controllers\Api
 * @author Quintin Stoltz <qstoltz@gmail.com>
 */
class SablApiController
    extends SablApiBaseController
{
    /**
     * Create a new address book record using the supplied input parameters
     * @return array
     */
    private function address_book_create() : array
    {
        // Get Request Data and attempt to apply some minimal validation for empty values
        $request_data = request()->only(['name']);

        // Basic validation for invalid input
        if (empty($request_data) || (!isset($request_data['name']))) {
            return $this->generateResponse('A name for the new address book is required.');
        }

        // Get Address Book Name and apply basic validation
        $address_book_name = trim($request_data['name']);
        if (empty($address_book_name)) {
            return $this->generateResponse('A name for the new address book is required.');
        }

        try {
            // Create new Record
            $record = AddressBook::create([
                'name' => $address_book_name
            ]);
            
            // Return successful response
            return $this->generateResponse(
                'Address Book Record Successfully Created',
                Response::HTTP_CREATED,
                $record->toArray());
            
        } catch(\Exception $e) {
            // Log for visibility
            Log::error($e->getMessage());

            // Return response with Error
            return $this->generateResponse("A server error occured. Data could not be processed.",
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Return Address Book Record Data
     *
     * @param $record_id
     * @return array
     */
    private function address_book_view($record_id = null) : array
    {
        // Null check on record id parameter, to use this method for both POST and GET requests
        if (null === $record_id && request()->method() === 'POST') {

            // Get Request Data and attempt to apply some minimal validation for empty values
            $request_data = request()->only(['id']);

            // Basic validation for invalid input
            if (empty($request_data) || (!isset($request_data['id']))) {
                return $this->generateResponse('A valid record ID is required for this request.');
            }

            // Set Record ID from post request data
            $record_id = $request_data['id'];
        }

        // Retrieve Contact ID
        $record_id = intval($record_id);

        // Query Data, including relationships
        $result = AddressBook::where('id','=',$record_id)
            ->with(['contacts'])
            ->first();

        // Null check for record not found
        if (null === $result) {
            return $this->generateResponse('Record Not Found.',
                Response::HTTP_NOT_FOUND);
        }

        // Return Generated Response including data
        return $this->generateResponse('Request Successful', Response::HTTP_OK, $result->toArray());
    }

    /**
     * Create a new contact record using the supplied first_name and last_name
     * @return array
     */
    private function contact_create() : array
    {
        // Get Request Data and attempt to apply some minimal validation for empty values
        $request_data = request()->only(['first_name','last_name','address_book_id']);
        if (empty($request_data) || (!isset($request_data['first_name']) || !isset($request_data['last_name']) || !isset($request_data['address_book_id']))) {
            return $this->generateResponse('Invalid Request Input Parameters');
        }

        // Get first name and validate for Empty
        $first_name = trim($request_data['first_name']);
        if (empty($first_name)) {
            return $this->generateResponse('A valid first name is required to create a new contact.');
        }

        // Get Last Name and Validate for Empty
        $last_name = trim($request_data['last_name']);
        if (empty($last_name)) {
            return $this->generateResponse('A valid last name is required to create a new contact.');
        }

        // Get Group ID and validate for Empty
        $address_book_id = trim($request_data['address_book_id']);
        if (empty($address_book_id)) {
            return $this->generateResponse('A valid address book ID is required to create a new contact.');
        }

        try {
            // Create new Record
            $record = Contact::create([
                'first_name' => $first_name,
                'last_name' => $last_name,
                'ab_id' => $address_book_id
            ]);

            // Return successful response
            return $this->generateResponse('Contact Record Successfully Created',
                Response::HTTP_CREATED,
                $record->toArray());

        } catch(\Exception $e) {
            // Log for visibility
            Log::error($e->getMessage());

            // Return response with Error
            return $this->generateResponse("A server error occured. Data could not be processed.",
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Query/View a contact record
     * @param $record_id
     * @return array
     */
    private function contact_view($record_id = null) : array
    {
        // Null check on record id parameter, to use this method for both POST and GET requests
        if (null === $record_id && request()->method() === 'POST') {

            // Get Request Data and attempt to apply some minimal validation for empty values
            $request_data = request()->only(['id']);

            // Basic validation for invalid input
            if (empty($request_data) || (!isset($request_data['id']))) {
                return $this->generateResponse('A valid record ID is required for this request.');
            }

            // Set Record ID from post request data
            $record_id = $request_data['id'];
        }

        // Retrieve Contact ID
        $record_id = intval($record_id);

        // Query Data, including relationships
        $result = Contact::where('id','=',$record_id)
            ->with(['addresses','email_addresses','phone_numbers','address_book','groups'])
            ->first();

        // Null check for record not found
        if (null === $result) {
            return $this->generateResponse('Record Not Found.',
                Response::HTTP_NOT_FOUND);
        }

        // Return Generated Response including data
        return $this->generateResponse('Request Successful', Response::HTTP_OK, $result->toArray());
    }

    /**
     * Create a new address record using the supplied input
     * @return array
     */
    private function address_create() : array
    {
        // Get Request Data and attempt to apply some minimal validation for empty values
        $request_data = request()->only(['street_address_primary', 'street_address_secondary', 'city', 'country', 'contact_id']);

        // Apply minimal data validation for capture. A better approach for more advanced data validation may be to make use of a Request Validator object to define validation error
        // messages. Simple use for example purposes.
        if (empty($request_data) || (!isset($request_data['street_address_primary']) || !isset($request_data['city']) || !isset($request_data['country']) || !isset($request_data['contact_id']))) {
            return $this->generateResponse('Invalid Request Input Parameters');
        }

        // Get Street Address and Validate for Empty
        $street_address_primary = trim($request_data['street_address_primary']);
        if (empty($street_address_primary)) {
            return $this->generateResponse('A street address is required to create a new record');
        }

        // Optional Field
        $street_address_secondary = "";
        if (isset($request_data['street_address_secondary']) && !empty(trim($request_data['street_address_secondary']))) {
            $street_address_secondary = trim($request_data['street_address_secondary']);
        }

        // Get City Name and validate for Empty
        $city = trim($request_data['city']);
        if (empty($city)) {
            return $this->generateResponse('A valid city name is required to create a new record');
        }

        // Get Country Name and validate for Empty
        $country = trim($request_data['country']);
        if (empty($country)) {
            return $this->generateResponse('A valid country name is required to create a new record');
        }

        // Get Contact ID and validate for Empty
        $contact_id = trim($request_data['contact_id']);
        if (empty($contact_id)) {
            return $this->generateResponse('A Contact ID is required to create a new record');
        }

        try {
            // Create Record
            $record = Address::create([
                'contact_id' => $contact_id,
                'street_address_primary' => $street_address_primary,
                'street_address_secondary' => $street_address_secondary,
                'city' => $city,
                'country' => $country,
            ]);

            // Return successful response
            return $this->generateResponse('Address Record Successfully Created',
                Response::HTTP_CREATED,
                $record->toArray());

        } catch(\Exception $e) {
            // Log for visibility
            Log::error($e->getMessage());

            // Return response with Error
            return $this->generateResponse("A server error occured. Data could not be processed.",
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Query/View a address record
     * @param $record_id
     * @return array
     */
    private function address_view($record_id = null) : array
    {
        // Null check on record id parameter, to use this method for both POST and GET requests
        if (null === $record_id && request()->method() === 'POST') {

            // Get Request Data and attempt to apply some minimal validation for empty values
            $request_data = request()->only(['id']);

            // Basic validation for invalid input
            if (empty($request_data) || (!isset($request_data['id']))) {
                return $this->generateResponse('A valid record ID is required for this request.');
            }

            // Set Record ID from post request data
            $record_id = $request_data['id'];
        }

        // Retrieve Contact ID
        $record_id = intval($record_id);

        // Query Data, including relationships
        $result = Address::where('id','=',$record_id)
            ->with(['contact'])
            ->first();

        // Null check for record not found
        if (null === $result) {
            return $this->generateResponse('Record Not Found.',
                Response::HTTP_NOT_FOUND);
        }

        // Return Generated Response including data
        return $this->generateResponse('Request Successful', Response::HTTP_OK, $result->toArray());
    }

    /**
     * Create a new email address record using the supplied input
     * @return array
     */
    private function email_address_create() : array
    {
        // Get Request Data and attempt to apply some minimal validation for empty values
        $request_data = request()->only(['email_address', 'contact_id']);
        if (empty($request_data) || (!isset($request_data['email_address']) || !isset($request_data['contact_id']))) {
            return $this->generateResponse('Invalid Request Input Parameters');
        }

        // Get Email Address and validate for Empty
        $email_address = trim($request_data['email_address']);
        if (empty($email_address)) {
            return $this->generateResponse('A email address is required to create a new record');
        }

        // Get Contact ID and validate for Empty
        $contact_id = trim($request_data['contact_id']);
        if (empty($contact_id)) {
            return $this->generateResponse('A Contact ID is required to create a new record');
        }

        try {
            // Create Record
            $record = EmailAddress::create([
                'contact_id' => $contact_id,
                'email_address' => $email_address,
            ]);

            // Return successful response
            return $this->generateResponse('Email Address Record Successfully Created',
                Response::HTTP_CREATED,
                $record->toArray());

        } catch(\Exception $e) {
            // Log for visibility
            Log::error($e->getMessage());

            // Return response with Error
            return $this->generateResponse("A server error occured. Data could not be processed.",
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Query/View a email address record
     * @param $record_id
     * @return array
     */
    private function email_address_view($record_id = null) : array
    {
        // Null check on record id parameter, to use this method for both POST and GET requests
        if (null === $record_id && request()->method() === 'POST') {

            // Get Request Data and attempt to apply some minimal validation for empty values
            $request_data = request()->only(['id']);

            // Basic validation for invalid input
            if (empty($request_data) || (!isset($request_data['id']))) {
                return $this->generateResponse('A valid record ID is required for this request.');
            }

            // Set Record ID from post request data
            $record_id = $request_data['id'];
        }

        // Retrieve Contact ID
        $record_id = intval($record_id);

        // Query Data, including relationships
        $result = EmailAddress::where('id','=',$record_id)
            ->with(['contact'])
            ->first();

        // Null check for record not found
        if (null === $result) {
            return $this->generateResponse('Record Not Found.',
                Response::HTTP_NOT_FOUND);
        }

        // Return Generated Response including data
        return $this->generateResponse('Request Successful', Response::HTTP_OK, $result->toArray());
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
            return $this->generateResponse('Invalid Request Input Parameters');
        }

        // Get Phone Number and Validate for Empty
        $phone_number = trim($request_data['phone_number']);
        if (empty($phone_number)) {
            return $this->generateResponse('A phone number is required to create a new record');
        }

        // Get Contact ID and validate for Empty
        $contact_id = trim($request_data['contact_id']);
        if (empty($contact_id)) {
            return $this->generateResponse('A Contact ID is required to create a new record');
        }

        try {
            // Create Record
            $record = PhoneNumber::create([
                'contact_id' => $contact_id,
                'phone_number' => $phone_number,
            ]);

            // Return successful response
            return $this->generateResponse('Phone Number Record Successfully Created',
                Response::HTTP_CREATED,
                $record->toArray());

        } catch(\Exception $e) {
            // Log for visibility
            Log::error($e->getMessage());

            // Return response with Error
            return $this->generateResponse("A server error occured. Data could not be processed.",
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Query/View a phone number record
     * @param $record_id
     * @return array
     */
    private function phone_number_view($record_id = null) : array
    {
        // Null check on record id parameter, to use this method for both POST and GET requests
        if (null === $record_id && request()->method() === 'POST') {

            // Get Request Data and attempt to apply some minimal validation for empty values
            $request_data = request()->only(['id']);

            // Basic validation for invalid input
            if (empty($request_data) || (!isset($request_data['id']))) {
                return $this->generateResponse('A valid record ID is required for this request.');
            }

            // Set Record ID from post request data
            $record_id = $request_data['id'];
        }

        // Retrieve Contact ID
        $record_id = intval($record_id);

        // Query Data, including relationships
        $result = PhoneNumber::where('id','=',$record_id)
            ->with(['contact'])
            ->first();

        // Null check for record not found
        if (null === $result) {
            return $this->generateResponse('Record Not Found.',
                Response::HTTP_NOT_FOUND);
        }

        // Return Generated Response including data
        return $this->generateResponse('Request Successful', Response::HTTP_OK, $result->toArray());
    }

    /**
     * Create a new group record
     * @return array
     */
    private function group_create() : array
    {
        // Get Request Data and attempt to apply some minimal validation for empty values
        $request_data = request()->only(['name']);
        if (empty($request_data) || (!isset($request_data['name']))) {
            return $this->generateResponse('Invalid Request Input Parameters');
        }

        // Get first name and validate for Empty
        $group_name = trim($request_data['name']);
        if (empty($group_name)) {
            return $this->generateResponse('A valid group name is required to create a new group.');
        }

        try {
            // Create new Record
            $record = Groups::create([
                'name' => $group_name
            ]);

            // Return successful response
            return $this->generateResponse(
                'Contact Record Successfully Created',
                Response::HTTP_CREATED,
                $record->toArray());

        } catch(\Exception $e) {
            // Log for visibility
            Log::error($e->getMessage());

            // Return response with Error
            return $this->generateResponse("A server error occured. Data could not be processed.",
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Query/View a group record
     * @param $record_id
     * @return array
     */
    private function group_view($record_id = null) : array
    {
        // Null check on record id parameter, to use this method for both POST and GET requests
        if (null === $record_id && request()->method() === 'POST') {

            // Get Request Data and attempt to apply some minimal validation for empty values
            $request_data = request()->only(['id']);

            // Basic validation for invalid input
            if (empty($request_data) || (!isset($request_data['id']))) {
                return $this->generateResponse('A valid record ID is required for this request.');
            }

            // Set Record ID from post request data
            $record_id = $request_data['id'];
        }

        // Retrieve Contact ID
        $record_id = intval($record_id);

        // Query Data, including relationships
        $result = Groups::where('id','=',$record_id)
            ->first();

        // Null check for record not found
        if (null === $result) {
            return $this->generateResponse('Record Not Found.',
                Response::HTTP_NOT_FOUND);
        }

        // Return Generated Response including data
        return $this->generateResponse('Request Successful', Response::HTTP_OK, $result->toArray());
    }

    /**
     * Create a new contact group record
     * @return array
     */
    private function contact_group_create() : array
    {
        // Get Request Data and attempt to apply some minimal validation for empty values
        $request_data = request()->only(['contact_id','group_id']);
        if (empty($request_data) || (!isset($request_data['contact_id']) || !isset($request_data['group_id']))) {
            return $this->generateResponse('Invalid Request Input Parameters');
        }

        // Get contact_id
        $contact_id = trim($request_data['contact_id']);
        if (empty($contact_id)) {
            return $this->generateResponse('A valid contact id is required to add a group to a contact.');
        }
        
        // Get group_id
        $group_id = trim($request_data['group_id']);
        if (empty($group_id)) {
            return $this->generateResponse('A valid group id is required to add a group to a contact.');
        }

        try {
            // Create new Record
            $contact_record = Contact::where('id','=',$contact_id)
                ->first();

            // Get current ids before detaching
            $group_ids_current = $contact_record->groups()
                ->pluck('id')
                ->toArray();

            // Check if the group id is already part of existing data
            if (!in_array($group_id,$group_ids_current)) {
                $group_ids_current[] = $group_id;
            }

            // Detach Contact Groups
            $contact_record->groups()->detach();

            // Attach Contact Groups
            $contact_record->groups()->attach($group_ids_current);

            // Return successful response
            return $this->generateResponse(
                'Contact Group Successfully Attached to Contact',
                Response::HTTP_CREATED);

        } catch(\Exception $e) {
            // Log for visibility
            Log::error($e->getMessage());

            // Return response with Error
            return $this->generateResponse("A server error occured. Data could not be processed.",
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    /**
     * Query/View a groups record
     * @param $record_id
     * @return array
     */
    private function contact_group_view($record_id = null) : array
    {
        // Null check on record id parameter, to use this method for both POST and GET requests
        if (null === $record_id && request()->method() === 'POST') {

            // Get Request Data and attempt to apply some minimal validation for empty values
            $request_data = request()->only(['id']);

            // Basic validation for invalid input
            if (empty($request_data) || (!isset($request_data['id']))) {
                return $this->generateResponse('A valid record ID is required for this request.');
            }

            // Set Record ID from post request data
            $record_id = $request_data['id'];
        }

        // Retrieve Contact ID
        $record_id = intval($record_id);

        // Query Data, including relationships
        $result = Groups::where('id','=',$record_id)
            ->with(['contacts'])
            ->first();

        // Null check for record not found
        if (null === $result) {
            return $this->generateResponse('Record Not Found.',
                Response::HTTP_NOT_FOUND);
        }

        // Return Generated Response including data
        return $this->generateResponse('Request Successful', Response::HTTP_OK, $result->toArray());
    }

    /**
     * Search Address Book by first name, last name or email
     * @return array
     */
    private function search_address_book() : array
    {
        // Get Request Data and attempt to apply some minimal validation for empty values
        $request_data = request()->only(['query','type']);
        if (empty($request_data) || (!isset($request_data['query']) || !isset($request_data['type']))) {
            return $this->generateResponse('Invalid Request Input Parameters');
        }

        // Get query Validate for Empty
        $query = trim($request_data['query']);
        if (empty($query)) {
            return $this->generateResponse('A query is required to search records');
        }

        // Get type Validate for Empty
        $type = trim($request_data['type']);
        if (empty($type)) {
            return $this->generateResponse('A query type is required to search records');
        }

        // Define initial Result Array
        $query_results = [];
        switch ($type) {
            case "email":
                // Query Address Book Data on Relationship Objects
                $query_results = AddressBook::with(['contacts' => function($q) use ($query) {
                    $q->with(['email_addresses' => function($q) use($query) {
                        $q->where('email_address','like', '%'.$query.'%');
                    }])->whereHas('email_addresses', function($q) use ($query) {
                        $q->where('email_address','like', '%'.$query.'%');
                    });
                }])->whereHas('contacts', function($q) use ($query) {
                    $q->with(['email_addresses' => function($q) use($query) {
                        $q->where('email_address','like', '%'.$query.'%');
                    }])->whereHas('email_addresses', function($q) use ($query) {
                        $q->where('email_address','like', '%'.$query.'%');
                    });
                })->get();
                break;

            case "contact":
                // Query Address Book Data on Relationship Objects
                $query_results = AddressBook::with(['contacts' => function($q) use ($query) {
                    $q->where('first_name','like', '%'.$query.'%')
                        ->orWhere('last_name','like','%'.$query.'%');
                }])->whereHas('contacts', function($q) use ($query) {
                    $q->where('first_name','like', '%'.$query.'%')
                        ->orWhere('last_name','like','%'.$query.'%');
                })->get();
                break;

            default:
                return $this->generateResponse('Invalid Query Type Parameter',
                    Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Return Generated Response including data
        return $this->generateResponse('Request Successful', Response::HTTP_OK, $query_results->toArray());
    }

    /**
     * handleApiQuery
     *
     * @param null|string $query The request scope query
     * @param null|int $id Should $action be set to id, this is the ID of the record to query
     * @return mixed
     */
    public function handleApiQuery($query = null, $id = null)
    {
        // Get request method to check what we need to do with the request data
        $request_method = request()->method();

        // Toggle Query
        switch(strtolower(trim($query))) {
            case "address_book":
                // Toggle Request Methods to decide how to deal with the request data
                switch ($request_method) {
                    case "PUT":
                        return $this->respondToRequest($this->address_book_create());

                    case "POST":
                        if (null !== $id) {
                            return $this->respondToRequest(
                                $this->generateResponse('Invalid API Endpoint.',
                                    Response::HTTP_BAD_REQUEST));
                        }
                        return $this->respondToRequest($this->address_book_view());

                    case "GET":
                        if (null !== $id) {
                            return $this->respondToRequest($this->address_book_view($id));
                        }
                        return $this->respondToRequest($this->generateResponse('A valid address book record ID is required for this request'));

                    default:
                        return $this->respondToRequest(
                            $this->generateResponse('Invalid Request Method for the specified API Endpoint entity.',
                                Response::HTTP_METHOD_NOT_ALLOWED));
                }
                break;

            case "contact":
                // Toggle Request Methods to decide how to deal with the request data
                switch ($request_method) {
                    case "PUT":
                        return $this->respondToRequest($this->contact_create());

                    case "POST":
                        if (null !== $id) {
                            return $this->respondToRequest(
                                $this->generateResponse('Invalid API Endpoint.',
                                    Response::HTTP_BAD_REQUEST));
                        }
                        return $this->respondToRequest($this->contact_view());

                    case "GET":
                        if (null !== $id) {
                            return $this->respondToRequest($this->contact_view($id));
                        }
                        return $this->respondToRequest($this->generateResponse('A contact record ID is required for this request'));

                    default:
                        return $this->respondToRequest(
                            $this->generateResponse('Invalid Request Method for the specified API Endpoint entity.',
                                Response::HTTP_METHOD_NOT_ALLOWED));
                }
                break;

            case "address":
                // Toggle Request Methods to decide how to deal with the request data
                switch ($request_method) {
                    case "PUT":
                        return $this->respondToRequest($this->address_create());

                    case "POST":
                        if (null !== $id) {
                            return $this->respondToRequest(
                                $this->generateResponse('Invalid API Endpoint.',
                                    Response::HTTP_BAD_REQUEST));
                        }
                        return $this->respondToRequest($this->address_view());

                    case "GET":
                        if (null !== $id) {
                            return $this->respondToRequest($this->address_view($id));
                        }
                        return $this->respondToRequest($this->generateResponse('A contact record ID is required for this request'));

                    default:
                        return $this->respondToRequest(
                            $this->generateResponse('Invalid Request Method for the specified API Endpoint entity.',
                                Response::HTTP_METHOD_NOT_ALLOWED));
                }
                break;

            case "email_address":
                // Toggle Request Methods to decide how to deal with the request data
                switch ($request_method) {
                    case "PUT":
                        return $this->respondToRequest($this->email_address_create());

                    case "POST":
                        if (null !== $id) {
                            return $this->respondToRequest(
                                $this->generateResponse('Invalid API Endpoint.',
                                    Response::HTTP_BAD_REQUEST));
                        }
                        return $this->respondToRequest($this->email_address_view());

                    case "GET":
                        if (null !== $id) {
                            return $this->respondToRequest($this->email_address_view($id));
                        }
                        return $this->respondToRequest($this->generateResponse('A contact record ID is required for this request'));

                    default:
                        return $this->respondToRequest(
                            $this->generateResponse('Invalid Request Method for the specified API Endpoint entity.',
                                Response::HTTP_METHOD_NOT_ALLOWED));
                }
                break;

            case "phone_number":
                // Toggle Request Methods to decide how to deal with the request data
                switch ($request_method) {
                    case "PUT":
                        return $this->respondToRequest($this->phone_number_create());

                    case "POST":
                        if (null !== $id) {
                            return $this->respondToRequest(
                                $this->generateResponse('Invalid API Endpoint.',
                                    Response::HTTP_BAD_REQUEST));
                        }
                        return $this->respondToRequest($this->phone_number_view());

                    case "GET":
                        if (null !== $id) {
                            return $this->respondToRequest($this->phone_number_view($id));
                        }
                        return $this->respondToRequest($this->generateResponse('A contact record ID is required for this request'));

                    default:
                        return $this->respondToRequest(
                            $this->generateResponse('Invalid Request Method for the specified API Endpoint entity.',
                                Response::HTTP_METHOD_NOT_ALLOWED));
                }
                break;

            case "group":
                // Toggle Request Methods to decide how to deal with the request data
                switch ($request_method) {
                    case "PUT":
                        return $this->respondToRequest($this->group_create());

                    case "POST":
                        if (null !== $id) {
                            return $this->respondToRequest(
                                $this->generateResponse('Invalid API Endpoint.',
                                    Response::HTTP_BAD_REQUEST));
                        }
                        return $this->respondToRequest($this->group_view());

                    case "GET":
                        if (null !== $id) {
                            return $this->respondToRequest($this->group_view($id));
                        }
                        return $this->respondToRequest($this->generateResponse('A contact record ID is required for this request'));

                    default:
                        return $this->respondToRequest(
                            $this->generateResponse('Invalid Request Method for the specified API Endpoint entity.',
                                Response::HTTP_METHOD_NOT_ALLOWED));
                }
                break;

            case "contact_group":
                // Toggle Request Methods to decide how to deal with the request data
                switch ($request_method) {
                    case "PUT":
                        return $this->respondToRequest($this->contact_group_create());

                    case "POST":
                        if (null !== $id) {
                            return $this->respondToRequest(
                                $this->generateResponse('Invalid API Endpoint.',
                                    Response::HTTP_BAD_REQUEST));
                        }
                        return $this->respondToRequest($this->contact_group_view());

                    case "GET":
                        if (null !== $id) {
                            return $this->respondToRequest($this->contact_group_view($id));
                        }
                        return $this->respondToRequest($this->generateResponse('A contact record ID is required for this request'));

                    default:
                        return $this->respondToRequest(
                            $this->generateResponse('Invalid Request Method for the specified API Endpoint entity.',
                                Response::HTTP_METHOD_NOT_ALLOWED));
                }
                break;

            case "find":
                // Toggle Request Methods to decide how to deal with the request data
                switch ($request_method) {
                    case "POST":
                        return $this->respondToRequest($this->search_address_book());

                    default:
                        return $this->respondToRequest(
                            $this->generateResponse('Invalid Request Method for the specified API Endpoint entity.',
                                Response::HTTP_METHOD_NOT_ALLOWED));
                }
                break;

            default:
                return $this->respondToRequest(
                    $this->generateResponse('Invalid API Endpoint.',
                        Response::HTTP_BAD_REQUEST));
        }
    }
}
