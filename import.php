<?php
	// INITIALIZE DATE VARIABLES
	$invoice_date = date('m/d/Y');
	$due_date = date('m/d/Y',strtotime('+30 days',strtotime($invoice_date)));

	// HEADER OBJECT FOR DISPLAY ACROSS VIEWS
	$header = '<div class="header-container">
		<h1 class="logo">PHP Invoice Builder</h1>
		<form id="nav-form" class="btn-container">
			<input type="button" onclick="submitForm()" class="nav-btn btn" id="/index.php" value="Create Invoice">
			<input type="button" onclick="submitForm()" class="nav-btn btn" id="/invoice_list.php" value="Invoice List">
		</form>
	</div>';

	// CONNECT TO MYSQL DATABASE
    $servername = 'localhost';
	$username = 'root';
	$password = 'root';

	$db = new mysqli($servername, $username, $password, 'challenge');
	if($db->connect_error){
		die('Connection failed:' . $conn->connect_error);
    }
	
	// FIND CURRENT INVOICE ID BASED ON INVOICES IN MYSQL DB
	$current_invoice_res = mysqli_query($db, "SELECT MAX(id) AS current FROM invoice");
	$result = mysqli_fetch_array($current_invoice_res);
	$current_invoice = $result['current'] + 1;
	
	// QUERY DB FOR CATEGORY NAMES TO ADD OPTIONS TO THE SELECT ELEMENT
	$category_res = mysqli_query($db, "SELECT category_name FROM invoice_category");

	// AJAX REQUESTS FOR UPDATING ITEM NAME OPTIONS IN SELECT ELEMENT BASED ON CATEGORY SELECTED
	$selected_category = $_GET['category'];
	if($selected_category){
		$list_res = mysqli_query($db, "SELECT item_name FROM invoice_item i LEFT JOIN invoice_category c ON i.category_id = c.id WHERE category_name = '".$selected_category."'");
		echo "<option value=''>Select Item</option>";
		while($item = mysqli_fetch_array($list_res)){
			echo "<option>".$item['item_name']."</option>";
		}
		echo "<option value='new'>Add An Item</option>";
	}
	
	// AJAX REQUESTS FOR UPDATING ITEM DESCRIPTION AND PRICE BASED ON ITEM NAME SELECTED
	$selected_item = $_GET['item'];
	$category_name = $_GET['category'];
	if($selected_item){
		// $item_info_res = mysqli_query($db, "SELECT item_desc, unit_price FROM invoice_item WHERE item_name LIKE '".$selected_item."'");
		$item_info_res = mysqli_query($db, "SELECT item_desc, unit_price FROM invoice_item i LEFT JOIN invoice_category c ON i.category_id = c.id WHERE item_name LIKE '".$selected_item."' AND category_name = '".$category_name."'");
		while($item_info = mysqli_fetch_array($item_info_res)){
			echo $item_info['item_desc'];
			echo ',';
			echo $item_info['unit_price'];
		}
	}

	// POST INVOICE DATA TO MYSQL DB
	if(count($_POST) == 18){
		$invoice_number = $_POST['invoice_number'];
		$invoice_date = $_POST['invoice_date'];
		$due_date = $_POST['due_date'];
		$customer_name = $_POST['customer_name'];
		$email = $_POST['email'];
		$phone = $_POST['phone'];
		$address = $_POST['address'];
		$comments = $_POST['comments'];
		$subtotal = $_POST['subtotal'];
		$tax_rate = $_POST['tax_rate'];
		$est_tax = $_POST['est_tax'];
		$amt_due = $_POST['amt_due'];

		$category_names = $_POST['category_name'];
		$item_names = $_POST['item_name'];
		$item_descriptions = $_POST['description'];
		$item_quantities = $_POST['quantity'];
		$item_prices = $_POST['price'];
		$item_totals = $_POST['total'];

		if(mysqli_query($db, "INSERT INTO invoice (invoice_number, customer_name, address, phone, email, comments, invoice_date, due_date, subtotal, tax_rate, est_tax, amt_due) VALUES ('".$invoice_number."', '".$customer_name."', '".$address."', '".$phone."', '".$email."', '".$comments."', '".$invoice_date."', '".$due_date."', ".$subtotal.", ".$tax_rate.", ".$est_tax.", ".$amt_due.")")){
			$latest_invoice = mysqli_query($db, "SELECT MAX(id) AS max from invoice");
			$result = mysqli_fetch_array($latest_invoice);
			$invoice_id = +$result['max'];
			for ($i=0; $i < count($item_descriptions); $i++) {
				$category_name = $category_names[$i];
				mysqli_query($db, "INSERT INTO invoice_category (category_name) SELECT * FROM (SELECT '".$category_name."') AS tmp WHERE NOT EXISTS ( SELECT category_name FROM invoice_category WHERE category_name LIKE '".$category_name."') LIMIT 1");
				$category_id_query = mysqli_query($db, "SELECT id FROM invoice_category WHERE category_name LIKE '".$category_name."'");
				$result = mysqli_fetch_array($category_id_query);
				$category_id = +$result['id'];

				$item_name = $item_names[$i]; 
				$item_price = $item_prices[$i]; 
				$item_description = $item_descriptions[$i];
				mysqli_query($db, "INSERT INTO invoice_item (item_name, unit_price, item_desc, category_id) SELECT * FROM (SELECT '".$item_name."', ".$item_price.", '".$item_description."', ".$category_id.") AS tmp WHERE NOT EXISTS (SELECT item_name FROM invoice_item WHERE item_name LIKE '".$item_name."' AND category_id = ".$category_id.") LIMIT 1");
				$item_id_query = mysqli_query($db, "SELECT id FROM invoice_item WHERE item_name LIKE '".$item_name."' AND category_id = ".$category_id);
				$result = mysqli_fetch_array($item_id_query);
				$item_id = +$result['id'];

				$item_quantity = $item_quantities[$i]; 
				$item_total = $item_totals[$i];
				mysqli_query($db, "INSERT INTO invoice_items_join (invoice_id, item_id, item_quantity, item_desc_act, item_price_act, item_total) VALUES (".$invoice_id.", ".$item_id.", ".$item_quantity.", '".$item_description."', ".$item_price.", ".$item_total.")");
			}
		}
	}

	// QUERY DB FOR INVOICE INFORMATION TO DISPLAY IN THE INVOICE LIST VIEW
	$invoice_row = mysqli_query($db, "SELECT invoice_number, invoice_date, customer_name, email, phone, ROUND(amt_due,2) as amt_due FROM invoice");

	// QUERY DB FOR THE TOTAL TAX AMOUNT INVOICED
	$tax_total_query = mysqli_query($db, "SELECT ROUND(SUM(est_tax),2) AS total FROM invoice");
	$result = mysqli_fetch_array($tax_total_query);
	$tax_total = $result['total'];

	// QUERY DB FOR THE TOTAL INVOICED AMOUNT
	$invoice_total_query = mysqli_query($db, "SELECT ROUND(SUM(amt_due),2) AS total FROM invoice");
	$result = mysqli_fetch_array($invoice_total_query);
	$invoice_total = $result['total'];
?>