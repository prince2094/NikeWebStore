<?php
session_start();
require_once("db_connection.php");
require_once('tcpdf/tcpdf.php');

$name = $_POST["name"];
$phone = $_POST["phone"];
$address = $_POST["address"];
$cardNumber = $_POST["cardNumber"];
$expiryMonth = $_POST["expiryMonth"];
$expiryYear = $_POST["expiryYear"];
$cvv = $_POST["cvv"];

$sql = "INSERT INTO payment_data (name, phone, address, cardNumber, expiryMonth, expiryYear, cvv) 
        VALUES ('$name', '$phone', '$address', '$cardNumber', '$expiryMonth', '$expiryYear', '$cvv')";

$result = $conn->query($sql);

if ($result) {
    $orderId = $conn->insert_id;

    $productId = isset($_SESSION['chosenProduct']['id']) ? $_SESSION['chosenProduct']['id'] : 0;

    $productSql = "SELECT title FROM products WHERE id = $productId";
    $productResult = $conn->query($productSql);
    
    $productName = "Unknown Product";
    if ($productResult->num_rows > 0) {
        $productRow = $productResult->fetch_assoc();
        $productName = $productRow['title'];
    }
    $pdf = new TCPDF();
    $pdf->AddPage();
    $pdf->SetFont('times', 'B', 16);

    // Order Information
    $pdf->Cell(40, 10, 'Order Receipt');
    $pdf->Ln(); 
    $pdf->Cell(40, 10, 'Order Number: ' . $orderId);
    $pdf->Ln(); 
    $pdf->Cell(40, 10, 'Date of Purchase: ' . date('Y-m-d H:i:s'));
    $pdf->Ln(); 
    $pdf->Cell(40, 10, 'Payment Method: Cash On Delivery'); 
    $pdf->Ln(); 

    // Customer Information
    $pdf->Cell(40, 10, 'Customer Information:');
    $pdf->Ln(); 
    $pdf->Cell(40, 10, 'Full Name: ' . $name);
    $pdf->Ln(); 
    $pdf->Cell(40, 10, 'Shipping Address: ' . $address);
    $pdf->Ln(); 
    $pdf->Cell(40, 10, 'Phone Number: ' . $phone);
    $pdf->Ln(); 

    // Product Information
    $pdf->Cell(40, 10, 'Product Information:');
    $pdf->Ln(); 
    $pdf->Cell(40, 10, 'Product ID: ' . $productId);
    $pdf->Ln(); 
    $pdf->Cell(40, 10, 'Product Name: ' . $productName);  // Added this line
    $pdf->Ln(); 
    $pdf->Cell(40, 10, 'Quantity: [Quantity]'); 
    $pdf->Ln(); 
    $pdf->Cell(40, 10, 'Price per unit: 10,000'); 
    $pdf->Ln();  
    
    $pdf->Cell(40, 10, 'Total Order Amount: 10,000'); 
    $pdf->Ln(); 

    // Estimated Delivery Information
    $pdf->Cell(40, 10, 'Estimated Delivery Information:');
    $pdf->Ln(); 
    $pdf->Cell(40, 10, 'Estimated Delivery Date: ') . date('Y-m-d H:i:s', strtotime('+3 days')); 
    $pdf->Ln(); 

    // Return and Refund Policy
    $pdf->Cell(40, 10, 'Return and Refund Policy:');
    $pdf->Ln(); 
    $pdf->Cell(40, 10, '7 Days Return and Refund Policy'); 
    $pdf->Ln(); 

    // Contact Information
    $pdf->Cell(40, 10, 'Contact Information:');
    $pdf->Ln(); 
    $pdf->Cell(40, 10, 'Customer Support Email: prince2094qs@gmail.com');
    $pdf->Ln(); 
    $pdf->Cell(40, 10, 'Customer Support Phone Number:8377897910'); 
    $pdf->Ln(); 

    // Additional Notes
    $pdf->Cell(40, 10, 'Additional Notes:');
    $pdf->Ln(); 
    $pdf->Cell(40, 10, '[Special Notes or Instructions]'); 
    $pdf->Ln();

    // Save the PDF to a file
    $pdfPath = 'C:\xampp\htdocs\order_receipt.pdf';
    $pdf->Output($pdfPath, 'F');

    // Redirect the user to the thank-you page with the PDF path
    header("Location: thank_you.php?pdf_path=$pdfPath");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
