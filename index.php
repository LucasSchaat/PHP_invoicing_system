<?php
	require('import.php');

	// UPDATING INVOICE NUMBER DISPLAY LAYOUT
	if($current_invoice < 10){
		$invoice_id = "00$current_invoice";
	} else if ($current_invoice < 100){
		$invoice_id = "0$current_invoice";
	} else {
		$invoice_id = $current_invoice;
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>PHP Invoice Builder</title>
	<!-- <link rel="stylesheet" href="styles.css"> -->
	<link href="https://fonts.googleapis.com/css2?family=Lato:wght@300&display=swap" rel="stylesheet">
</head>
<body>
	<div class='application'>
		<header><?=$header?></header>
		<form action='/invoice_list.php' id='invoice-form' method='POST' class='invoice-form'>
			<div class='invoice-number-container'>
				<h3>INVOICE ID: </h3>
				<input class='invoice-number' name='invoice_number' readonly value=<?="'00000-".$invoice_id."'"?>>
			</div>
			<div class='date-inputs'>
				<div class='date-container'>
					<h3>Invoice Date: </h3>
					<input class='date' name='invoice_date' value=<?=$invoice_date?>>
				</div>
				<div class='date-container'>
					<h3>Due Date: </h3>
					<input class='date' name='due_date' value=<?=$due_date?>>
				</div>
			</div>
			<div class='basic-info'>
				<div class='sender-details'>
					<h3>From:</h3>
					<p id='business-name'>Jolt Software, Inc.</p>
					<p>invoicing@jolt.com</p>
					<p>(555) 555-5555</p>
					<p>2901 Ashton Blvd. Suite #300</p>
					<p>Lehi, UT 84043</p>
					<p>United States</p>
				</div>
				<div class='receiver-details'>
					<h3>To:</h3>
					<input placeholder='Company Name' name='customer_name' type='text'/>
					<input placeholder='Email' name='email' type='text'/>
					<input placeholder='(555) 555-5555' name='phone' type='tel'/>
					<textarea placeholder='Address' name='address'></textarea>
				</div>
			</div>
			<div class='table-container'>
				<table class='invoice-table'>
					<tbody id='invoice_items'>
						<tr>
							<th class='invoice-form-checkbox'></th>
							<th class='invoice-form-select'>Category Name</th>
							<th class='invoice-form-select'>Item Name</th>
							<th class='invoice-form-description'>Item Description</th>
							<th class='invoice-form-num'>Quantity</th>
							<th class='invoice-form-num'>Price</th>
							<th class='invoice-form-num'>Total</th>
						</tr>
						<tr id="invoice_line_item">
							<td><input class="itemRow invoice-form-input" type="checkbox"></td>
							<td>
								<select id="category_name" name="category_name[]" class="invoice-form-input" onchange="selectCategory()">
									<option value=''>Select Category</option>
									<?php
										while($category = mysqli_fetch_array($category_res)){
											echo "<option>".$category['category_name']."</option>";
										}
									?>
									<option value='new'>Add A Category</option>
								</select>
								<input id='category_input' type='text' name="category_name[]" class="invoice-form-input select-input" style="display:none">
							</td>
							<td>
								<select id="item_name" name="item_name[]" class="invoice-form-input" onchange="selectItem()" disabled>
									<option value=''>Select Item</option>
									<option value='new'>Add An Item</option>
								</select>
								<input id='item_input' type='text' name="item_name[]" class="invoice-form-input select-input" style="display:none">
							</td>
							<td><input type="text" name="description[]" class="invoice-form-input description" disabled></td>
							<td><input type="number" name="quantity[]" step="any" class="invoice-form-input num-input" disabled onchange="calculateTotal()"></td>
							<td><input type="number" name="price[]" step="any" class="invoice-form-input num-input" disabled onchange="calculateTotal()"></td>
							<td><input type="number" name="total[]" step="any" class="invoice-form-input num-input total" value=0 readonly onchange="addDecimals()"></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class='comments-totals-container'>
				<div class='comments-container'>
					<div class='add-remove-btn-container'>
						<button onclick='deleteRow()' class='btn delete-btn'>- Delete</button>
						<button onclick='addRow()' class='btn submit-btn'>+ Add Row</button>
					</div>
					<h3>Comments:</h3>
					<textarea name='comments' value=null placeholder='Comment Here'></textarea>
				</div>
				<div class='totals-container'>
					<div class='totals-row'>
						<p>Subtotal:</p>
						<input id='subtotal_input' class='total' type='number' step='any' name='subtotal' value=0 onchange='addDecimals()' readonly>
					</div>
					<div class='totals-row'>
						<p>Tax Rate:</p>
						<div class='tax-input-container'>
							<input id='tax_rate_input' class='total' type='number' step='any' name='tax_rate' onchange='updateTotal()'>
							<div class='tax-percent-symbol'>%</div>
						</div>
					</div>
					<div class='totals-row'>
						<p>Estimated Tax:</p>
							<input id='est_tax_input' class='total tax-input' type='number' step='any' name='est_tax' value=0 onchange='addDecimals()' readonly>
					</div>
					<div class='totals-row'>
						<p class='ovr-total'>Amount Due:</p>
						<input id='amt_due_input' class='total ovr-total' type='number' step='any' name='amt_due' value=0 onchange='addDecimals()' readonly>
					</div>
				</div>
			</div>
			<button class='btn submit-btn' type='submit'>Process Invoice</button>
		</form>
    </div>
</body>
<script type="text/javascript" src="script.js"></script>
</html>