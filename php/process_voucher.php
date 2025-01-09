<?php
session_start();

// Stel voorbeeldvouchers in
$valid_vouchers = [
    'DISCOUNT10' => 0.10, // 10% korting
    'DISCOUNT20' => 0.20, // 20% korting
];

// Haal de voucher op
$voucher_code = strtoupper(trim($_POST['voucher']));

if (array_key_exists($voucher_code, $valid_vouchers)) {
    $_SESSION['voucher'] = [
        'code' => $voucher_code,
        'discount' => $valid_vouchers[$voucher_code],
    ];
    header('Location: checkout.php?voucher_applied=true');
} else {
    header('Location: checkout.php?error=invalid_voucher');
}
exit;
