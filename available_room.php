<?php
$title ='booking';
require_once 'components/head.php'; ?>
<body>
  <?php require_once 'components/navbar.php'?>
  <div class="page home_page">
    <div class="room_type_container">
      <div class="room_type_card">
        <h2>Regular</h2>
        <p>A standard room with essential amenities, suitable for budget-conscious travelers.</p>
        <h3>Php 1500.00</h3>
        <a href="booking.php?room_type=Regular" class="button">Book</a>
      </div>

      <div class="room_type_card">
          <h2>Deluxe</h2>
          <p>A room offering additional comfort and amenities, providing a more enjoyable stay.</p>
          <h3>Php 3000.00</h3>
          <a href="booking.php?room_type=Deluxe" class="button">Book</a>
      </div>

      <div class="room_type_card">
          <h2>Suite</h2>
          <p>A spacious and luxurious accommodation, often featuring a separate living area.</p>
          <h3>Php 5000.00</h3>
          
          <a href="booking.php?room_type=Suite" class="button">Book</a>

      </div>
    </div>
  </div>
  <?php require_once 'components/footer.php'?>
  

</body>

