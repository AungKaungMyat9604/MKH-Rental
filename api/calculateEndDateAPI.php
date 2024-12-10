<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    // Create DateTime object with the provided date, and set the time to 9:00 AM
    $start_date = new DateTime($_POST['start_date']);
    $start_date->setTime(9, 0, 0); // Set the time to 9:00 AM

    // Clone the start_date to preserve the original date and time
    $start_datetime = clone $start_date;

    // Create beforeStart_datetime which is 1 hour before start_datetime
    $beforeStart_datetime = clone $start_datetime;
    $beforeStart_datetime->modify('-1 hour');

    $renting_hours = intval($_POST['renting_hours']);
    $cost_per_duty = floatval($_POST['cost_per_duty']);

    $hours_left = $renting_hours;
    $current_time = $start_date->format('H:i');

    // Adjust start date to next working day if not within working hours
    if ($current_time < '09:00' || $current_time >= '17:00') {
        if ($current_time < '09:00') {
            $start_date->setTime(9, 0);
        } else {
            $start_date->modify('+1 day');
            $start_date->setTime(9, 0);
        }
    }

    // Loop through each day and account for working hours
    while ($hours_left > 0) {
        $current_time = $start_date->format('H:i');
        if ($current_time >= '09:00' && $current_time < '17:00') {
            $end_of_day = clone $start_date;
            $end_of_day->setTime(17, 0);
            $hours_today = min($end_of_day->diff($start_date)->h, $hours_left);
            $start_date->modify("+$hours_today hours");
            $hours_left -= $hours_today;
        }

        if ($hours_left > 0) {
            $start_date->modify('+1 day');
            $start_date->setTime(9, 0);
        }
    }

    // Calculate cost
    if ($renting_hours <= 8) {
        $cost = $cost_per_duty;
    } else {
        $cost = $cost_per_duty + (($renting_hours - 8) * ($cost_per_duty / 8));
    }

    $available_date = clone $start_date;
    $available_date->modify('+1 hour');

    require 'dbinfo.php';
    $sql = "SELECT available_datetime FROM products_status WHERE product_id = '$product_id'";
    $result = $conn->query($sql);
    
    if ($result && $row = $result->fetch_assoc()) {
        $available_datetime = $row['available_datetime'];
    } else {
        $available_datetime = null; // or set to a default DateTime or handle as needed
    }


    // Return the calculated end date, available date, cost, start datetime, and beforeStart_datetime
    echo json_encode([
        'beforeStart_datetime' => $beforeStart_datetime->format('Y-m-d H:i:s'), // 1 hour before the start
        'start_datetime' => $start_datetime->format('Y-m-d H:i:s'), // Includes the 9:00 AM time
        'end_date' => $start_date->format('Y-m-d H:i:s'),
        'available_date' => $available_date->format('Y-m-d H:i:s'),
        'cost' => $cost,
        'available_datetime' => $available_datetime ? (new DateTime($available_datetime))->format('Y-m-d H:i:s') : ''
    ]);
}
?>
