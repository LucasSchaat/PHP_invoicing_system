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
    <title>Invoicing</title>
</head>
<body>
	<div class='application'>
		<header><?=$header?></header>
		<form action='/invoice_list.php' id='invoice-form' method='POST' class='invoice-form'>
			<div class='invoice-number-container'>
				<h3>INVOICE ID: </h3>
				<input class='invoice_number' name='invoice_number' readonly value=<?="'00000-".$invoice_id."'"?>>
			</div>
			<div class='date-container'>
				<h3>Invoice Date: </h3>
				<input class='date' name='invoice_date' readonly value=<?=$invoice_date?>>
			</div>
			<div class='date-container'>
				<h3>Due Date: </h3>
				<input class='date' name='due_date' readonly value=<?=$due_date?>>
			</div>
			<div class='sender-details'>
				<h3>From:</h3>
				<p>ABCD Enterprises</p>
				<p>info@abcdEnterprises.com</p>
				<p>(555) 555-5555</p>
				<p>123 Digital Dr.</p>
				<p>Lehi, UT</p>
				<p>55555-5555</p>
			</div>
			<div class='receiver-details'>
				<h3>To:</h3>
				<input placeholder='Company Name' name='customer_name'/>
				<input placeholder='Email' name='email'/>
				<input placeholder='(555) 555-5555' name='phone' type='tel'/>
				<textarea placeholder='Address' name='address'></textarea>
			</div>
			<div class='table-container'>
				<table class='invoice-table'>
					<tbody id='invoice_items'>
						<tr>
							<th></th>
							<th>Category Name</th>
							<th>Item Name</th>
							<th>Item Description</th>
							<th>Quantity</th>
							<th>Price</th>
							<th>Total</th>
						</tr>
						<tr id="invoice_line_item">
							<td><input class="itemRow" type="checkbox"></td>
							<td>
								<select id="category_name" name="category_name[]" class="invoice-form" onchange="selectCategory()">
									<option value=''>Select Category</option>
									<?php
										while($category = mysqli_fetch_array($category_res)){
											echo "<option>".$category['category_name']."</option>";
										}
									?>
								</select>
							</td>
							<td>
								<select id="item_name" name="item_name[]" class="invoice-form" onchange="selectItem()" disabled>
									<option value=''>Select Item</option>
								</select>
							</td>
							<td><input type="text" name="description[]" class="invoice-form" disabled></td>
							<td><input type="number" name="quantity[]" step="any" class="invoice-form" disabled onchange="calculateTotal()"></td>
							<td><input type="number" name="price[]" step="any" class="invoice-form" disabled onchange="calculateTotal()"></td>
							<td><input type="number" name="total[]" step="any" class="invoice-form total" value=0 readonly></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class='add-remove-btn-container'>
				<button onclick='deleteRow()' class='btn delete'>- Delete</button>
				<button onclick='addRow()' class='btn'>+ Add Row</button>
			</div>
			<div class='comments-container'>
				<h3>Comments:</h3>
				<textarea name='comments' value=null></textarea>
			</div>
			<div class='totals-container'>
				<div class='totals-row'>
					<p>Subtotal:</p>
					<input id='subtotal_input' class='total' type='number' step='any' name='subtotal' value=0 readonly>
				</div>
				<div class='totals-row'>
					<p>Tax Rate:</p>
					<input id='tax_rate_input' class='total' type='number' step='any' name='tax_rate' onchange='updateTotal()'>
				</div>
				<div class='totals-row'>
					<p>Estimated Tax:</p>
					<input id='est_tax_input' class='total' type='number' step='any' name='est_tax' value=0 readonly>
				</div>
				<div class='totals-row'>
					<p>Amount Due:</p>
					<input id='amt_due_input' class='total' type='number' step='any' name='amt_due' value=0 readonly>
				</div>
			</div>
			<button class='btn submit-btn' type='submit'>Process Invoice</button>
		</form>
    </div>
</body>
<script type="text/javascript" src="script.js"></script>
</html>