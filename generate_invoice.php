<?php
require('fpdf/fpdf.php');
include 'db.php';

if (isset($_GET['trackingNumber'])) {
    $trackingNumber = $_GET['trackingNumber'];

    // Fetch parcel details along with item names
    $stmt = $conn->prepare("SELECT p.tracking_number, p.sender_name, p.receiver_name, 
                                   p.status, p.total_amount, 
                                   b_from.branch_name AS from_branch, 
                                   b_to.branch_name AS to_branch,
                                   GROUP_CONCAT(pi.item_name SEPARATOR ', ') AS items
                            FROM parcels p
                            LEFT JOIN branches b_from ON p.from_branch_id = b_from.id
                            LEFT JOIN branches b_to ON p.to_branch_id = b_to.id
                            LEFT JOIN parcel_items pi ON pi.parcel_id = p.id
                            WHERE p.tracking_number = ?
                            GROUP BY p.id");

    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    $stmt->bind_param("s", $trackingNumber);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $parcel = $result->fetch_assoc();

        class PDF extends FPDF
        {
            function Header()
            {
                // Blue Header
                $this->SetFillColor(0, 51, 153);
                $this->Rect(0, 0, 210, 30, 'F');

                // Logo
                $this->Image('logo.png', 10, 10, 25);
                $this->SetTextColor(255, 255, 255);
                $this->SetFont('Arial', 'B', 14);
                $this->Cell(190, 10, 'Nova Courier - Invoice', 0, 1, 'C');
                $this->SetFont('Arial', '', 10);
                $this->Cell(190, 5, '123 Courier Street, City, Country | Phone: +123 456 7890', 0, 1, 'C');
                $this->Ln(10);
                $this->SetTextColor(0, 0, 0);
            }

            function Footer()
            {
                $this->SetY(-25);
                $this->SetFillColor(0, 51, 153);
                $this->Rect(0, 272, 210, 25, 'F');

                $this->SetTextColor(255, 255, 255);
                $this->SetFont('Arial', 'I', 9);
                $this->Cell(190, 10, 'Thank you for choosing Nova Courier!', 0, 1, 'C');
                $this->Cell(190, 5, 'For inquiries, visit www.novacourier.com', 0, 1, 'C');
            }
        }

        // Initialize PDF
        $pdf = new PDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 12);

        // Invoice title
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(190, 10, 'Parcel Invoice', 0, 1, 'C');
        $pdf->Ln(5);

        // Parcel Details Table
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetFillColor(0, 51, 153); // Set background color
        $pdf->SetTextColor(255, 255, 255); // White text

        $pdf->Cell(95, 8, 'Tracking Number:', 1, 0, 'L', true);
        $pdf->Cell(95, 8, $parcel['tracking_number'], 1, 1, 'L', true);

        $pdf->Cell(95, 8, 'Sender:', 1, 0, 'L', true);
        $pdf->Cell(95, 8, $parcel['sender_name'], 1, 1, 'L', true);

        $pdf->Cell(95, 8, 'Receiver:', 1, 0, 'L', true);
        $pdf->Cell(95, 8, $parcel['receiver_name'], 1, 1, 'L', true);

        $pdf->Cell(95, 8, 'From Branch:', 1, 0, 'L', true);
        $pdf->Cell(95, 8, $parcel['from_branch'], 1, 1, 'L', true);

        $pdf->Cell(95, 8, 'To Branch:', 1, 0, 'L', true);
        $pdf->Cell(95, 8, $parcel['to_branch'], 1, 1, 'L', true);

        $pdf->Cell(95, 8, 'Current Status:', 1, 0, 'L', true);
        $pdf->Cell(95, 8, $parcel['status'], 1, 1, 'L', true);

        $pdf->Cell(95, 8, 'Total Amount:', 1, 0, 'L', true);
        $pdf->Cell(95, 8, number_format($parcel['total_amount'], 2), 1, 1, 'L', true);

        // Reset Text Color for content
        $pdf->SetTextColor(0, 0, 0);

        // Add Item Names Section
        if (!empty($parcel['items'])) {
            $pdf->Ln(5);
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->SetFillColor(0, 51, 153);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->Cell(190, 8, 'Item Details', 1, 1, 'C', true);

            // Item table with alternating row colors
            $pdf->SetFont('Arial', '', 12);
            $pdf->SetTextColor(0, 0, 0);
            $items = explode(', ', $parcel['items']);
            $fill = false; // Alternate row colors
            foreach ($items as $item) {
                $pdf->SetFillColor(230, 230, 230);
                $pdf->Cell(190, 8, $item, 1, 1, 'L', $fill);
                $fill = !$fill; // Toggle fill color
            }
        }

        // Signature & Footer Section
        $pdf->Ln(15);
        $pdf->Cell(90, 8, 'Authorized Signature:', 0, 0, 'L');
        $pdf->Cell(90, 8, 'Date:', 0, 1, 'L');
        $pdf->Ln(15);
        $pdf->Cell(90, 8, '____________________', 0, 0, 'L');
        $pdf->Cell(90, 8, '____________________', 0, 1, 'L');

        // Output PDF
        $pdf->Output('D', 'Invoice_' . $trackingNumber . '.pdf');
    } else {
        echo "Tracking number not found.";
    }
} else {
    echo "No tracking number provided.";
}
?>
