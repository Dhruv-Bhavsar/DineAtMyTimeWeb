<?php
require_once '../includes/DbOperation.php';

function isTheseParametersAvailable($params)
{
    $available = true;
    $missingparams = "";
    foreach ($params as $param) {
        if (!isset($_POST[$param]) || strlen($_POST[$param]) <= 0) {
            $available = false;
            $missingparams = $missingparams . ", " . $param;

        }
    }

    if (!$available) {
        $response = array();
        $response['error'] = true;
        $response['message'] = 'Parameters ' . substr($missingparams, 1, strlen($missingparams)) . ' missing';

        echo json_encode($response);
        die();

    }
}

$response = array();


if (isset($_GET['apicall'])) {

    switch ($_GET['apicall']) {

        /*---------------------------------------------------------------------------------------------------------------------*/

        case 'cust_register':
            isTheseParametersAvailable(array('cust_name', 'cust_email', 'cust_password', 'cust_contact', 'cust_address'));
            $db = new DbOperation();

            $result = $db->cust_insert(
            //$_POST['cust_id'],
                $_POST['cust_name'],
                $_POST['cust_email'],
                base64_encode($_POST['cust_password']),
                $_POST['cust_contact'],
                $_POST['cust_address']
            // $_POST['registration_time']
            );

            if ($result) {
                $response['error'] = false;
                $response['message'] = 'Registered successfully';
            } else {
                $response['error'] = true;
                $response['message'] = 'Already Registered';
            }

            break;

        /*---------------------------------------------------------------------------------------------------------------------*/

        case 'cust_login':

            isTheseParametersAvailable(array('cust_email', 'cust_password'));
            $db = new DbOperation();
            $result = $db->Login($_POST['cust_email'], base64_encode($_POST['cust_password']));

            if ($result) {
                $response['error'] = false;
                $response['message'] = 'Login successfully';
                $response['user'] = $db->getUData($_POST['cust_email']);
            } else {
                $response['error'] = true;
                $response['message'] = 'Email or password is invalid';
            }

            break;

        /*---------------------------------------------------------------------------------------------------------------------*/

        case 'res_register':

            isTheseParametersAvailable(array('res_name', 'res_email', 'res_password', 'res_contact', 'res_address', 'registration_status', 'res_image'));

            $db = new DbOperation();

            $result = $db->res_insert(
                $_POST['res_name'],
                $_POST['res_email'],
                base64_encode($_POST['res_password']),
                $_POST['res_contact'],
                $_POST['res_address'],
                $_POST['registration_status'],
                $_POST['res_image']
            // $_POST['registration_time']
            );

            if ($result) {
                $response['error'] = false;
                $response['message'] = 'Registered successfully';
            } else {
                $response['error'] = true;
                $response['message'] = 'Already Registered';
            }


            break;

        /*---------------------------------------------------------------------------------------------------------------------*/

        case 'res_login':
            isTheseParametersAvailable(array('res_email', 'res_password'));
            $db = new DbOperation();
            $result = $db->resLogin($_POST['res_email'], base64_encode($_POST['res_password']));

            if ($result) {
                $response['error'] = false;
                $response['message'] = 'Login successfully';
                $response['records'] = $db->getRData($_POST['res_email']);
            } else {
                $response['error'] = true;
                $response['message'] = 'Email or password is invalid';
            }

            break;


        /*----------------------------------------------- GET Restaurant List---------------------------------------------------*/


            case 'get_restaurant_list':


            $db = new DbOperation();

            $response['error'] = false;
            $response['restaurants'] = $db->getRestaurantList();


            break;


        /*---------------------------------------------------------------------------------------------------------------------*/

        case 'admin_login':
            isTheseParametersAvailable(array('admin_email', 'admin_password'));
            $db = new DbOperation();
            $result = $db->adminLogin($_POST['admin_email'], $_POST['admin_password']);

            if ($result) {
                $response['error'] = false;
                $response['message'] = 'Login successfully';
                $response['records'] = $db->getAData($_POST['admin_email']);
            } else {
                $response['error'] = true;
                $response['message'] = 'Email or password is invalid';
            }

            break;

        /*---------------------------------------------------------------------------------------------------------------------*/

        case 'manageOrder':
            isTheseParametersAvailable(array('Customer_id', 'Res_T_id', 'Res_id', 'Order_items', 'Order_type', 'Order_status', 'Order_bill', 'Payment_status'));
            $db = new DbOperation();

            $result = $db->placeOrder(
                $_POST['Customer_id'],
                $_POST['Res_T_id'],
                $_POST['Res_id'],
                $_POST['Order_items'],
                $_POST['Order_type'],
                $_POST['Order_status'],
                $_POST['Order_bill'],
                $_POST['Payment_status']
            // $_POST['registration_time']
            );

            if ($result) {
                $response['error'] = false;
                $response['message'] = 'Order Received successfully';
            } else {
                $response['error'] = true;
                $response['message'] = 'Already Placed';
            }

            break;


        /*---------------------------------------------------------------------------------------------------------------------*/

        case 'manageOffers':
            isTheseParametersAvailable(array('Promo_code', 'Res_id', 'Offer_details', 'status'));
            $db = new DbOperation();

            $result = $db->manageOffers(
                $_POST['Promo_code'],
                $_POST['Res_id'],
                $_POST['Offer_details'],
                $_POST['status']
            );

            if ($result) {
                $response['error'] = false;
                $response['message'] = 'Offer Applied successfully';
            } else {
                $response['error'] = true;
                $response['message'] = 'Already Used';
            }

            break;


        default:
            # code...
            break;


    }
} else {
    //if it is not api call
    //pushing appropriate values to response array
    $response['error'] = true;
    $response['message'] = 'Invalid API Call';
}

//displaying the response in json structure
echo json_encode($response);


?>