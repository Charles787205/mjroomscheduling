<?php 
  require_once 'Database/BookingDatabase.php';
  if(!isset($_GET['booking_id'])){
    header('Location: index.php');
  }
  $booking_id = $_GET['booking_id'];
  $booking_date = $_GET['schedule'];
  $room_type = $_GET['room_type'];
  $bDatabase = new BookingDatabase();
  $bDatabase->updateBooking($booking_id,$booking_date, $room_type);
  header('Location: admin.php');
  echo $booking_id;
?>