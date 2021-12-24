<?php
//set_error_handler("customError");

function shutdown() {
    http_response_code(400);
    $error = error_get_last();
    if ($error['type'] === E_ERROR) {
        // fatal error has occured
        $output['success'] = false;
        $output['message'] = "Sorry, there are error when receiving request. Please contact the Administrator.";
        echo (json_encode($output, JSON_PRETTY_PRINT));
        die();
    }
}

register_shutdown_function('shutdown');

?>