<?php
    require('import.php');
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
        <div class='invoice-history'>
            <h2 class='history-title'>Invoice History:</h2>
            <table class='invoice-table'>
                <tbody id='invoice_items'>
                    <tr>
                        <th class='invoice-history-display'>Invoice No.</th>
                        <th class='invoice-history-display'>Invoice Date</th>
                        <th class='invoice-history-display'>Customer Name</th>
                        <th class='invoice-history-display'>Customer Email</th>
                        <th class='invoice-history-display'>Customer Phone</th>
                        <th class='invoice-history-display'>Amount Due</th>
                    </tr>
                    <?php
                        while($invoice = mysqli_fetch_array($invoice_row)){
                            echo "<tr>";
                                echo "<td class='invoice-history-data-items'>".$invoice['invoice_number']."</td>";
                                echo "<td class='invoice-history-data-items'>".$invoice['invoice_date']."</td>";
                                echo "<td class='invoice-history-data-items'>".$invoice['customer_name']."</td>";
                                echo "<td class='invoice-history-data-items'>".$invoice['email']."</td>";
                                echo "<td class='invoice-history-data-items'>".$invoice['phone']."</td>";
                                echo "<td class='invoice-history-data-items'>".$invoice['amt_due']."</td>";
                            echo "</tr>";
                        }
                    ?>
                </tbody>
            </table>
            <div class='totals'>
                <h3 class='total-header'>Amount Due By Category:</h3>
                <?php
                    while($category = mysqli_fetch_array($category_res)){
                        $category_name = $category['category_name'];
                        $category_total_query = mysqli_query($db, "SELECT ROUND(SUM(item_total),2) AS total FROM invoice_items_join j JOIN invoice_item ii ON j.item_id = ii.id JOIN invoice_category c ON ii.category_id = c.id WHERE category_name = '".$category_name."'");
                        $result = mysqli_fetch_array($category_total_query);
                        $category_total = $result['total'];
                        if(!$category_total){
                            $category_total = 0;
                        }
    
                        echo "<div class='category-totals-container'>";
                            echo "<h4 class='category-name'>".$category_name."</h4>";
                            echo "<h4 class='category-total'>$".$category_total."</h4>";
                        echo "</div>";
                    }
                ?>
                <div class='category-totals-container est-tax-total'>
                    <h3>Estimated Tax Balance:</h3>
                    <h4 class='category-total'><?="$$tax_total"?></h4>
                </div>
                <div class='category-totals-container est-tax-total'>
                    <h2 class='invoice-total'>Total Invoiced Amount:</h2>
                    <h2 class='category-total'><?="$$invoice_total"?></h2>
                </div>
            </div>
        </div>
    </div>
</body>
<script type="text/javascript" src="script.js"></script>
</html>