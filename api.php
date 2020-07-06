<?php
    // CONNECT TO MYSQL DATABASE
    $servername = 'localhost';
	$username = 'root';
	$password = 'root';

	$db = new mysqli($servername, $username, $password, 'challenge');
	if($db->connect_error){
		die('Connection failed:' . $conn->connect_error);
    }

    // RECEIVING DATA VIA GET REQUEST
    foreach ($_GET as $key => $value) {
        if($key == 'invoices'){
            $all_invoices_query = mysqli_query($db, "SELECT id AS invoice_number, customer_name, address, phone, email, comments, invoice_date, due_date, subtotal, est_tax, amt_due AS invoice_total FROM invoice");

            $data = array();

            while($invoice_data = mysqli_fetch_array($all_invoices_query, MYSQLI_ASSOC)){
                $data[] = $invoice_data;
            }
            
            header('Content-Type: application/json');
            echo json_encode($data);

        } elseif($key == 'invoice' && $value){
            $invoice_query = mysqli_query($db, "SELECT id AS invoice_number, customer_name, address, phone, email, comments, invoice_date, due_date, subtotal, est_tax, amt_due AS invoice_total FROM invoice WHERE id = ".+$value);
            
            $invoice_data = mysqli_fetch_array($invoice_query, MYSQLI_ASSOC);
            
            header('Content-Type: application/json');
            echo json_encode($invoice_data);
               
        } else {
            header("HTTP/1.0 404 Not Found");
        }
    }