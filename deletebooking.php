<?php 
  require_once 'Database/BookingDatabase.php';
  if(!isset($_GET['booking_id'])){
    header('Location: index.php');
  }
  $booking_id = $_GET['booking_id'];
  $bDatabase = new BookingDatabase();
  $bDatabase->deleteBooking($booking_id);
  header('Location: admin.php');
?>