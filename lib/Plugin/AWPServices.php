<?php

/* Apptivo Lead functionality for Contacts form */
require_once AWP_LIB_DIR . '/Plugin.php';
require_once AWP_INC_DIR . '/apptivo_services/labelDetails.php';
require_once AWP_INC_DIR . '/apptivo_services/noteDetails.php';
require_once AWP_INC_DIR . '/apptivo_services/LeadDetails.php';

//require_once AWP_ASSETS_DIR.'/captcha/simple-captcha/simple-captcha.php';
/**
 * Class AWPAPIServices
 */
Class AWPAPIServices {

    /**
     * To Get All Countries name and country code.
     *
     * @return unknown
     */
    public function getAllCountries() {
        if (_isCurl()) {
            $params = array(
                "a" => "getLocations",
                "apiKey" => APPTIVO_BUSINESS_API_KEY,
                "accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
            );
            $response = getRestAPICall("POST", APPTIVO_SIGNUP_API, $params);
        } else {
            $params = array(
                "arg0" => APPTIVO_BUSINESS_API_KEY,
                "arg1" => APPTIVO_BUSINESS_ACCESS_KEY
            );
            $response = getsoapCall(APPTIVO_BUSINESS_SERVICES, 'getAllCountries', $params);
            $response = $response->return;
        }
        return $response;
    }

    /**
     * To Get All Target Lists from Apptivo.
     *
     * @return unknown
     */
    public function getTargetListcategory() {
        if (_isCurl()) {
            $params = array(
                "a" => "getTargetList",
                "apiKey" => APPTIVO_BUSINESS_API_KEY,
                "accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
            );
            $response = getRestAPICall("POST", APPTIVO_TARGETS_API, $params);
            $response = $response->aaData;
        } else {
            $params = array(
                "arg0" => APPTIVO_BUSINESS_API_KEY,
                "arg1" => APPTIVO_BUSINESS_ACCESS_KEY
            );
            $response = getsoapCall(APPTIVO_BUSINESS_SERVICES, 'getAllTargetLists', $params);
            $response = $response->return->targetList;
        }
        return $response;
    }

    /**
     * To SaveContact Lead details.
     *
     * @param unknown_type $firstName
     * @param unknown_type $lastName
     * @param unknown_type $emailId
     * @param unknown_type $jobTitle
     * @param unknown_type $company
     * @param unknown_type $address1
     * @param unknown_type $address2
     * @param unknown_type $city
     * @param unknown_type $state
     * @param unknown_type $zipCode
     * @param unknown_type $bestWayToContact
     * @param unknown_type $country
     * @param unknown_type $leadSource
     * @param unknown_type $phoneNumber
     * @param unknown_type $comments
     * @param unknown_type $noteDetails
     * @return unknown
     */
    public function saveLeadDetails($firstName, $lastName, $emailId, $jobTitle, $company, $address1, $address2, $city, $state, $zipCode, $bestWayToContact, $countryId, $countryName, $leadSource, $leadSourceId, $phoneNumber, $comments, $noteDetails, $targetlistid, $customerAccountId, $customerAccountName, $contact_status, $contact_type, $contact_rank, $contact_status_id, $contact_type_id, $contact_rank_id, $assigneeName, $assigneeObjId, $assigneeObjRefId) {
        $customassigneevalues = '';
        if ($contact_type_id != '') {
            $customassigneevalues = '"leadTypeName":"' . $contact_type . '","leadTypeId":' . $contact_type_id . ',';
        }
        if ($contact_status_id != '') {
            $customassigneevalues .= '"leadStatus":"' . $contact_status_id . '","leadStatusMeaning":"' . $contact_status . '",';
        }
        if ($contact_rank_id != '') {
            $customassigneevalues .= '"leadRank":"' . $contact_rank_id . '","leadRankMeaning":"' . $contact_rank . '",';
        }
        if ($assigneeObjRefId != '') {
            $customassigneevalues .= '"assigneeObjectRefName":"' . $assigneeName . '","assigneeObjectRefId":' . $assigneeObjRefId . ',"assigneeObjectId":' . $assigneeObjId . ',';
        }
        if ($customerAccountId != '') {
            $customassigneevalues .= '"accountName":"' . $customerAccountName . '","accountId":' . $customerAccountId . ',';
        }
        $leads = '{"firstName":"' . addslashes($firstName) . '","lastName":"' . addslashes($lastName) . '","jobTitle":"' . addslashes($jobTitle) . '","easyWayToContact":"' . $bestWayToContact . '","wayToContact":"' . $bestWayToContact . '","leadSource":"' . $leadSourceId . '","leadSourceMeaning":"' . $leadSource . '",' . $customassigneevalues . '"description":"' . addslashes($comments) . '","companyName":"' . addslashes($company) . '","phoneNumbers":[{"phoneNumber":"' . addslashes($phoneNumber) . '","phoneType":"Business","phoneTypeCode":"PHONE_BUSINESS","id":"lead_phone_input"}],"emailAddresses":[{"emailAddress":"' . $emailId . '","emailTypeCode":"BUSINESS","emailType":"Business"}],"addresses":[{"addressAttributeId":"address_section_attr_id","addressTypeCode":"1","addressType":"Billing Address","addressLine1":"' . addslashes($address1) . '","addressLine2":"' . addslashes($address2) . '","city":"' . addslashes($city) . '","stateCode":"","state":"' . addslashes($state) . '","zipCode":"' . addslashes($zipCode) . '","countryId":' . $countryId . ',"countryName":"' . addslashes($countryName) . '"}]}';
        $params = array(
            "a" => "createLead",
            "leadData" => $leads,
            "apiKey" => APPTIVO_BUSINESS_API_KEY,
            "accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
        );

        $response = getRestAPICall("POST", APPTIVO_LEAD_API, $params);

        return $response;
    }

    public function saveLeadNotes($leadId, $noteText) {

        $leads = '{"noteText":"' . $noteText . '"}';
        $param = array(
            "a" => "save",
            "objectId" => APPTIVO_LEAD_OBJECT_ID,
            "objRefId" => "$leadId",
            "noteData" => "$leads",
            "apiKey" => APPTIVO_BUSINESS_API_KEY,
            "accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
        );
        $notesResponse = getRestAPICall("POST", APPTIVO_NOTES_API, $param);
        $noteid = $notesResponse->noteId;
    }

    /**
     * Save Notes Details TargetList */
    public function createTargetListNotes($comments, $targetId, $notesLabel) {
        $commentText = "<b>" . $notesLabel . " : </b>" . $comments;
        $notesParams = '{"noteText":"' . $commentText . '"}';
        $noteParam = array(
            "a" => "save",
            "noteData" => $notesParams,
            "objRefId" => $targetId,
            "objectId" => APPTIVO_NOTE_OBJECT_ID,
            "apiKey" => APPTIVO_BUSINESS_API_KEY,
            "accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
        );
        $noteResponse = getRestAPICall("POST", APPTIVO_NOTES_API, $noteParam);
        return $noteResponse;
    }

    /**
     * Notes Details..
     *
     * @param unknown_type $label
     * @param unknown_type $nodeDetails
     * @param unknown_type $noteId
     * @return unknown
     */
    public function notes($label, $nodeDetails, $noteId) {
        $labelDetails = new AWP_labelDetails($labelId = null, $label);
        $notetextDetails = new AWP_noteDetails($labelDetails, $noteId, addslashes($nodeDetails));
        return $notetextDetails;
    }

    /* Save Case Details */

    public function createCases($caseNumber, $caseStatus, $caseStatusId, $caseType, $caseTypeId, $casePriority, $casePriorityId, $assigneeName, $assigneeObjId, $assigneeObjRefId, $caseSummary, $caseDescription, $customerAccountName, $customerAccountId, $contactAccountName, $contactAccountId, $emailId) {

        $customassigneevalues = '';
        if ($caseStatusId != '') {
            $customassigneevalues = '"caseStatus":"' . htmlspecialchars($caseStatus) . '","caseStatusId":"' . $caseStatusId . '",';
        }
        if ($caseTypeId != '') {
            $customassigneevalues .= '"caseType":"' . htmlspecialchars($caseType) . '","caseTypeId":"' . $caseTypeId . '",';
        }
        if ($casePriorityId != '') {
            $customassigneevalues .= '"casePriority":"' . htmlspecialchars($casePriority) . '","casePriorityId":"' . $casePriorityId . '",';
        }
        if ($assigneeObjRefId != '') {
            $customassigneevalues .= '"assignedObjectRefName":"' . htmlspecialchars($assigneeName) . '","assignedObjectId":' . $assigneeObjId . ',"assignedObjectRefId":' . $assigneeObjRefId . ',';
        }
        if ($customerAccountId != '') {
            $customassigneevalues .= '"caseCustomer":"' . $customerAccountName . '","caseCustomerId":' . $customerAccountId . ',';
        }
        if ($contactAccountId != '') {
            $customassigneevalues .= '"caseContact":"' . $contactAccountName . '","caseContactId":' . $contactAccountId . ',';
        }
        $caseData = '{"caseNumber":"' . stripslashes(trim($caseNumber)) . '",'.$customassigneevalues.'"caseSummary":"' . addslashes($caseSummary) . '","description":"' . addslashes($caseDescription) . '","caseEmail":"' . $emailId . '","addresses":[]}';
        $params = array(
            "a" => "createCase",
            "caseData" => $caseData,
            "apiKey" => APPTIVO_BUSINESS_API_KEY,
            "accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
        );
        $response = getRestAPICall("POST", APPTIVO_CASES_API, $params);
        return $response;
    }

    /* Save Notes with Cases Creation */

    function caseSaveNotes($caseId, $noteText) {

        $caseNotes = '{"noteText":"' . $noteText . '"}';
        $param = array(
            "a" => "save",
            "objectId" => APPTIVO_CASES_OBJECT_ID,
            "objRefId" => "$caseId",
            "noteData" => "$caseNotes",
            "apiKey" => APPTIVO_BUSINESS_API_KEY,
            "accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
        );
        $notesResponse = getRestAPICall("POST", APPTIVO_NOTES_API, $param);
        $noteid = $notesResponse->noteId;
        return $noteid;
    }

    /*
     * To associate Cases with Contact and Customer
     *
     */

    public function awpContactAssociates($emailId, $option) {
        $associatesDetails = array();
        $customerAccountId = "";
        $customerAccountName = "";

        if ($option == "Customer") {
            $searchData = '{"emailAddresses":[{"emailAddress":"' . $emailId . '","emailTypeCode":"-1","emailType":"","id":"cont_email_input"}]}';
            $customerParams = array(
                "a" => "getAllCustomersByAdvancedSearch",
                "objectId" => APPTIVO_CUSTOMER_OBJECT_ID,
                "startIndex" => "0",
                "numRecords" => "1",
                "sortColumn" => "_score",
                "sortDir" => "desc",
                "searchData" => $searchData,
                "multiSelectData" => "{}",
                "apiKey" => APPTIVO_BUSINESS_API_KEY,
                "accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
            );
            $customerResponse = getRestAPICall("POST", APPTIVO_CUSTOMER_API, $customerParams);

            if (isset($customerResponse->customers)) {
                foreach ($customerResponse->customers as $key => $customerData) {
                    if (isset($customerData->emailAddresses)) {
                        foreach ($customerData->emailAddresses as $key1 => $emailData) {
                            if ($emailData->emailAddress == $emailId) {
                                $customerAccountId = $customerData->customerId;
                                $customerAccountName = $customerData->customerName;
                            }
                        }
                    }
                }
            }
            $associatesDetails['leadCustomerId'] = $customerAccountId;
            $associatesDetails['leadCustomer'] = $customerAccountName;
            if ($option == "Customer") {
                return $associatesDetails;
            }
        }
        return $associatesDetails;
    }

    /* Create Customer */

    function createCustomer($lastName, $assigneeName, $assigneeObjId, $assigneeObjRefId, $phoneNumber, $emailId) {

        $createCustomerDetails = array();


        $customerData = '{"customerName":"' . $lastName . '","customerNumber":"Auto generated number","assigneeObjectRefName":"' . $assigneeName . '","assigneeObjectId":' . $assigneeObjId . ',"assigneeObjectRefId":' . $assigneeObjRefId . ',"phoneNumbers":[{"phoneNumber":"' . $phoneNumber . '","phoneTypeCode":"PHONE_BUSINESS","phoneType":"Business","id":"cust_phone_input"}],"emailAddresses":[{"emailAddress":"' . $emailId . '","emailTypeCode":"BUSINESS","emailType":"Business"}]}';
        $customerParams = array(
            "a" => "createCustomer",
            "customerData" => $customerData,
            "apiKey" => APPTIVO_BUSINESS_API_KEY,
            "accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
        );
        $customerResponse = getRestAPICall("POST", APPTIVO_CUSTOMER_API, $customerParams);

        if ($customerResponse->customer->customerId != "") {
            $customerAccountId = $customerResponse->customer->customerId;
            $customerAccountName = $customerResponse->customer->customerName;
        }
        
        $createCustomerDetails['leadCustomerId'] = $customerAccountId;
        $createCustomerDetails['leadCustomer'] = $customerAccountName;

        return $createCustomerDetails;
    }

    /* Create Contact */

    function createContact($firstName, $lastName, $assigneeName, $assigneeObjRefId, $assigneeObjId, $phoneNumber, $emailId) {

        $createContactDetails = array();
        $contactData = '{"firstName":"' . $firstName . '","lastName":"' . $lastName . '","assigneeObjectRefName":"' . $assigneeName . '","assigneeObjectRefId":' . $assigneeObjRefId . ',"assigneeObjectId":' . $assigneeObjId . ',"phoneNumbers":[{"phoneNumber":"' . $phoneNumber . '","phoneType":"Business","phoneTypeCode":"PHONE_BUSINESS","id":"contact_phone_input"}],"emailAddresses":[{"emailAddress":"' . $emailId . '","emailTypeCode":"BUSINESS","emailType":"Business","id":"cont_email_input"}],"addresses":[{"addressAttributeId":"address_section_attr_id","addressTypeCode":"1","addressType":"Billing Address","addressLine1":"","addressLine2":"","city":"","stateCode":"","state":"","zipCode":"","countryId":176,"countryName":"","countryCode":"US"}],"syncToGoogle":"Y"}';
        $contactParams = array("a" => "saveContact",
            "contactData" => $contactData,
            "apiKey" => APPTIVO_BUSINESS_API_KEY,
            "accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
        );
        $contactResponse = getRestAPICall("POST", APPTIVO_CONTACTS_API, $contactParams);

        if ($contactResponse->contact->contactId != "") {
            $contactAccountId = $contactResponse->contact->contactId;
            $contactAccountName = $contactResponse->contact->fullName;
        }
        $createContactDetails['leadContactId'] = $contactAccountId;
        $createContactDetails['leadContact'] = $contactAccountName;
        return $createContactDetails;
    }

    /*
     * To associate Cases with Contact and Customer
     *
     */

    function awpCaseAssocciates($emailId, $option) {
        $associatesDetails = array();
        $customerAccountId = "";
        $contactAccountId = "";
        $contactAccountName = "";
        $customerAccountName = "";
        if ($option == "Contact" || $option == "Both") {
            $searchData = '{"emailAddresses":[{"emailAddress":"' . $emailId . '","emailTypeCode":"-1","emailType":"","id":"cont_email_input"}]}';
            $contactParams = array(
                "a" => "getAllContactsByAdvancedSearch",
                "objectId" => APPTIVO_CONTACT_OBJECT_ID,
                "startIndex" => "0",
                "numRecords" => "1",
                "sortColumn" => "_score",
                "sortDir" => "desc",
                "searchData" => $searchData,
                "multiSelectData" => "{}",
                "apiKey" => APPTIVO_BUSINESS_API_KEY,
                "accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
            );
            $contactResponse = getRestAPICall("POST", APPTIVO_CONTACTS_API, $contactParams);

            if (isset($contactResponse->contacts)) {
                foreach ($contactResponse->contacts as $key => $contactData) {
                    if (isset($contactData->emailAddresses)) {
                        foreach ($contactData->emailAddresses as $key1 => $emailData) {
                            if ($emailData->emailAddress == $emailId) {
                                $contactAccountId = $contactData->contactId;
                                $contactAccountName = $contactData->fullName;
                            }
                        }
                    }
                }
            }
            
            $associatesDetails['caseContactId'] = $contactAccountId;
            $associatesDetails['caseContact'] = $contactAccountName;
            if ($option == "Both" && $contactResponse->contacts[0]->accountId != "") {
                $associatesDetails['caseCustomerId'] = $contactResponse->contacts[0]->accountId;
                $associatesDetails['caseCustomer'] = $contactResponse->contacts[0]->accountName;
                return $associatesDetails;
            }
            if ($option == "Contact") {
                return $associatesDetails;
            }
        }
        if ($option == "Customer" || $option == "Both") {
            $searchData = '{"emailAddresses":[{"emailAddress":"' . $emailId . '","emailTypeCode":"-1","emailType":"","id":"cont_email_input"}]}';
            $customerParams = array(
                "a" => "getAllCustomersByAdvancedSearch",
                "objectId" => APPTIVO_CUSTOMER_OBJECT_ID,
                "startIndex" => "0",
                "numRecords" => "1",
                "sortColumn" => "_score",
                "sortDir" => "desc",
                "searchData" => $searchData,
                "multiSelectData" => "{}",
                "apiKey" => APPTIVO_BUSINESS_API_KEY,
                "accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
            );
            $customerResponse = getRestAPICall("POST", APPTIVO_CUSTOMER_API, $customerParams);

            if (isset($customerResponse->customers)) {
                foreach ($customerResponse->customers as $key => $customerData) {
                    if (isset($customerData->emailAddresses)) {
                        foreach ($customerData->emailAddresses as $key1 => $emailData) {
                            if ($emailData->emailAddress == $emailId) {
                                $customerAccountId = $customerData->customerId;
                                $customerAccountName = $customerData->customerName;
                            }
                        }
                    }
                }
            }
            $associatesDetails['caseCustomerId'] = $customerAccountId;
            $associatesDetails['caseCustomer'] = $customerAccountName;
            if ($option == "Customer") {
                return $associatesDetails;
            }
        }
        return $associatesDetails;
    }
}