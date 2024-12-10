<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $rent_form_id = $_POST['rent_form_id'];
    $additionalHours = intval($_POST['additional_hours']);

    // Fetch current end date, renting hours, and cost_per_duty from the database
    require 'dbinfo.php';
    $sql = "SELECT rf.end_date, rf.renting_hours, p.cost_per_unit FROM rent_forms rf
            JOIN products AS p ON rf.product_id = p.product_id
            WHERE rf.rent_forms_id = ? AND rf.product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $rent_form_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();

    if ($data) {
        $endDate = new DateTime($data['end_date']);
        $currentRentingHours = intval($data['renting_hours']);
        $costPerDuty = floatval($data['cost_per_unit']);

        // Calculate the new end date
        $newEndDate = clone $endDate;
        $hoursLeft = $additionalHours;
        while ($hoursLeft > 0) {
            $currentHour = (int)$newEndDate->format('H');
            
            // If we are within working hours, calculate how much time can be added today
            if ($currentHour >= 9 && $currentHour < 17) {
                $endOfWorkDay = (clone $newEndDate)->setTime(17, 0);
                $hoursAvailableToday = min(8 - ($currentHour - 9), $hoursLeft);
                
                $newEndDate->modify("+$hoursAvailableToday hours");
                $hoursLeft -= $hoursAvailableToday;
            } 
            
            // If there are still hours left to add, move to the next working day
            if ($hoursLeft > 0) {
                $newEndDate->modify('+1 day')->setTime(9, 0);
            }
        }

        // Calculate the new total cost
        $totalHours = $currentRentingHours + $additionalHours;
        if ($totalHours <= 8) {
            $newCost = $costPerDuty;
        } else {
            $newCost = $costPerDuty + (($totalHours - 8) * ($costPerDuty / 8));
        }

        echo json_encode([
            'success' => true,
            'totalHours' => $totalHours,
            'new_end_date' => $newEndDate->format('Y-m-d H:i:s'),
            'new_cost' => $newCost
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Product not found.']);
    }
}
?>
