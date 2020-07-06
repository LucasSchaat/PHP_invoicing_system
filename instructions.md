Initialization Steps/Configuration - 

* Uses a local mySQL db that I have called "challenge" in this project
    - These setting can be reconfigured in the import.php file

* Included in the init.sql file are the SQL commands for populating the mySQL db
    -The db is comprised of 4 tables: invoice, invoice_category, invoice_item, invoice_items_join
    
    - The invoice table holds the basic data associated with the invoice
    - The invoice_category table holds the names of each of the categories created by the user
    - The invoice_item table holds the names, original descriptions, original prices and categories associated with the invoice items created by the user
    - The invoice_items_join table holds the actual price, quantity, description and total associated with each item included in the invoice

* In order to access data from the api.php file in the browser, the user should navigate to the api.php file and include the following information in the url (both are GET requests):

    - URL: '../api.php?invoices' - this returns a JSON array of all of the invoices and their associated invoice information

    - URL: '../api.php?invoice=<INVOICE_NUMBER>' - this returns a JSON object with the invoice information associated with the requested invoice number that was placed in the stead of the '<INVOICE_NUMBER>' placeholder above

    - All other requests should return a 404 status code and an associated error view in the browser

* Navigate to the index.php file to access the project