<?php 
  require_once 'Database/BookingDatabase.php';
  $room_type = null;
  if($_SERVER['REQUEST_METHOD']=='GET'){
    $room_type = $_GET['room_type'];
    
    if(!isset($_GET['room_type'])){
      header('Location: index.php');
      
    }
    
  }else{
      $room_type = $_POST['room_type'];
      $name = $_POST['name'];
      $email = $_POST['email'];
      $contact_number = $_POST['contact_number'];
      $date = $_POST['book-date'];
      $bDatabase = new BookingDatabase();
      $availableRooms = $bDatabase->getAvailableRooms($date, $room_type);
      $reference_id = $bDatabase->createBooking($availableRooms[0]['room_id'], $date, $name, $contact_number,$email);
      
    }

  


  $title='Booking';
  require_once 'components/head.php';
?>
<body>
  
<?php require_once 'components/navbar.php'?>
  <div class="booking_page page">

  <form action="booking.php" method="POST" class="booking_form">
    <h2>Book a room</h2>
    <label>Name:</label>
    <input type="text" name="name" required>
    <label>Email:</label>
    <input type="email" name="email" required>
    <label>Contact Number:</label>
    <input type="tel" name="contact_number" id="contact_number">
    <label>Choose Schedule:</label>
    <input type="date" id="book-date" name="book-date" min="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d', strtotime('+7 days')); ?>" required>
    <input type="text" name="room_type" value="<?php echo isset($room_type) ? $room_type : 'none'; ?>" hidden>
    <button class="booking_button">Book Room</button>
  </form>
  
  <div class="reference_popup <?php if(!isset($reference_id)){echo 'hidden';} ?>">
    <h2>Booking Successfull</h2>
    <p>Please show your reference number at the front desk on the day of check in</p>
    <p>Reference Number: <?php if(isset($reference_id))echo $reference_id ?></p>
    <a href="index.php" class="button" id="reference_button">Go back</a>
  </div>
</div>

<?php 
require_once 'components/footer.php';
?>


</body>
</html>