<?php
session_start();
include ("../dbconn/conn.php"); 

// Check if stockin_id is provided
if (isset($_GET['req_id'])) {
    $stockin_id = intval($_GET['req_id']);

    $sql = "UPDATE request SET is_posted = 1 WHERE req_number = (SELECT req_number FROM request WHERE req_id = ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $stockin_id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Stock-in successfully finalized!";
    } else {
        $_SESSION['error_message'] = "Failed to finalize stock-in.";
    }

    $stmt->close();
    $conn->close();

    // Redirect back to stock-in page
    header("Location: requisitions.php");
    exit();
} else {
    $_SESSION['error_message'] = "Invalid stock-in request.";
    header("Location: requisitions.php");
    exit();
}

?>
