<?php
function updateOverdueStatusAndFines($conn, $user_id) {
    // Update overdue status in loans table for the current user
    $updateOverdueSql = "
        UPDATE loans 
        SET status = 'overdue' 
        WHERE due_date < CURDATE() 
        AND status = 'active'
        AND user_id = ?
    ";
    $stmt = $conn->prepare($updateOverdueSql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // Fine calculation based on overdue days for the current user
    $amount_per_day = 0.50;

    // Insert new fines
    $fineInsertSql = "
        INSERT INTO fines (user_id, loan_id, amount, reason, issued_date)
        SELECT 
            l.user_id, 
            l.loan_id, 
            (DATEDIFF(IF(l.return_date IS NULL, CURDATE(), l.return_date), l.due_date) * ?) AS fine_amount,
            CONCAT('Overdue ', DATEDIFF(IF(l.return_date IS NULL, CURDATE(), l.return_date), l.due_date), ' days') AS reason,
            CURDATE() AS issued_date
        FROM 
            loans l
        LEFT JOIN 
            fines f ON l.loan_id = f.loan_id
        WHERE 
            l.user_id = ?
            AND l.status IN ('overdue', 'returned')
            AND (l.return_date > l.due_date OR (l.status = 'overdue' AND l.return_date IS NULL))
            AND f.loan_id IS NULL;
    ";

    $stmt = $conn->prepare($fineInsertSql);
    $stmt->bind_param("di", $amount_per_day, $user_id);
    $stmt->execute();
    $stmt->close();

    // Update old fines
    $fineUpdateSql = "
        UPDATE fines f
        JOIN loans l ON f.loan_id = l.loan_id
        SET 
            f.amount = (DATEDIFF(IF(l.return_date IS NULL, CURDATE(), l.return_date), l.due_date) * ?),
            f.reason = CONCAT('Overdue ', DATEDIFF(IF(l.return_date IS NULL, CURDATE(), l.return_date), l.due_date), ' days'),
            f.issued_date = CURDATE()
        WHERE 
            l.status IN ('overdue', 'returned')
            AND f.loan_id IS NOT NULL
            AND l.user_id = ?
            AND (l.return_date > l.due_date OR (l.status = 'overdue' AND l.return_date IS NULL))
            AND f.paid = false;";

    $stmt = $conn->prepare($fineUpdateSql);
    $stmt->bind_param("di", $amount_per_day, $user_id);
    $stmt->execute();
    $stmt->close();
}

?>
