<?php
use App\Controllers\PaymentController;
$router->get('/payment/vnpay/{order_id}/{amount}', 'PaymentController@showVNPayPayment');
$router->post('/payment/create-vnpay-url', 'PaymentController@createVNPayUrl');
$router->get('/payment/vnpay-return', 'PaymentController@handleVNPayReturn');
$router->get('/payment/success', 'PaymentController@showPaymentSuccess');
$router->get('/payment/failed', 'PaymentController@showPaymentFailed');
$router->post('/payment/process', 'PaymentController@processPayment');