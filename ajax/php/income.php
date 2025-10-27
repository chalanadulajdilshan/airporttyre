<?php

include '../../class/include.php';
header('Content-Type: application/json; charset=UTF-8');

// Create new income
if (isset($_POST['create'])) {

    $INCOME = new Income(NULL);

    $INCOME->name = $_POST['name'];
    $INCOME->amount = $_POST['amount'];
    $INCOME->date = $_POST['date'];

    $res = $INCOME->create();

    if ($res) {
        echo json_encode(["status" => 'success']);
    } else {
        echo json_encode(["status" => 'error']);
    }
    exit();
}

// Update income
if (isset($_POST['update'])) {

    $INCOME = new Income($_POST['income_id']);

    $INCOME->name = $_POST['name'];
    $INCOME->amount = $_POST['amount'];
    $INCOME->date = $_POST['date'];

    $res = $INCOME->update();

    if ($res) {
        echo json_encode(["status" => 'success']);
    } else {
        echo json_encode(["status" => 'error']);
    }
    exit();
}

// Delete income
if (isset($_POST['delete']) && isset($_POST['id'])) {
    $INCOME = new Income($_POST['id']);
    $res = $INCOME->delete();

    if ($res) {
        echo json_encode(["status" => 'success']);
    } else {
        echo json_encode(["status" => 'error']);
    }
    exit();
}
