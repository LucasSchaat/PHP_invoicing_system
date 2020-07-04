<?php
    require('import.php');
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
        <table class='invoice-table'>
            <tbody id='invoice_items'>
                <tr>
                    <th>Invoice No.</th>
                    <th>Invoice Date</th>
                    <th>Customer Name</th>
                    <th>Customer Email</th>
                    <th>Customer Phone</th>
                    <th>Amount Due</th>
                </tr>
                <?php
                    while($invoice = mysqli_fetch_array($invoice_row)){
                        echo "<tr>";
                            echo "<td>".$invoice['invoice_number']."</td>";
                            echo "<td>".$invoice['invoice_date']."</td>";
                            echo "<td>".$invoice['customer_name']."</td>";
                            echo "<td>".$invoice['email']."</td>";
                            echo "<td>".$invoice['phone']."</td>";
                            echo "<td>".$invoice['amt_due']."</td>";
                        echo "</tr>";
                    }
                ?>
            </tbody>
        </table>
        <div class='totals'>
            <?php
                while($category = mysqli_fetch_array($category_res)){
                    $category_name = $category['category_name'];
                    $category_total_query = mysqli_query($db, "SELECT SUM(item_total) AS total FROM invoice_items_join j JOIN invoice_item ii ON j.item_id = ii.id JOIN invoice_category c ON ii.category_id = c.id WHERE category_name = '".$category_name."'");
                    $result = mysqli_fetch_array($category_total_query);
                    $category_total = $result['total'];
                    if(!$category_total){
                        $category_total = 0;
                    }

                    echo "<h3 class='category-total'>Total ".$category_name." Amount Due: $".$category_total."</h3>";
                }
            ?>
            <h3 class='category-total'><?="Estimated Tax Balance: $$tax_total"?></h3>
            <h3 class='invoice-total'><?="Total Invoiced Amount: $$invoice_total"?></h3>
        </div>
    </div>
</body>
<script type="text/javascript" src="script.js"></script>
</html>