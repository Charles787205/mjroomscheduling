<?php 
  require_once 'Database/BookingDatabase.php';
  $bDatabase = new BookingDatabase();
  $bookingsFromNow = $bDatabase->readBookingsFromNow();
  $availableRooms = $bDatabase->getAvailableRooms(date('Y-m-d'), null);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/admin.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;400;500;600;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <title>Admin</title>
  </head>


<body>
  <?php require_once 'components/navbar.php';?>
  <div class="page admin_page">
    <div class="info_container">
      <div class="tab_row">
        <h3 class="tab" onclick="clickedTab('booked')" id='booked_tab'>
          Booked Rooms
        </h3>
        <h3 class="tab" onclick="clickedTab('available')" id='available_tab'>
          Available Rooms
        </h3>
      </div>
      <div class="tab_table"    id='booked_table'>

          <table>
            <thead>
                <tr>
                    <th>Room Number</th>
                    <th>Schedule</th>
                    <th>Name</th>
                    <th>Reference ID</th>
                    <th>Contact Number</th>
                    <th>Room Type</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class='table_body'>
                <?php foreach($bookingsFromNow as $booking): ?>
                    <tr id=booking_<?php echo $booking['booking_id']?>>
    
                        <td><?php echo $booking['room_id']; ?></td>
                        <td id=book_schedule_<?php echo $booking['booking_id']?>>
                        <p>
                          <?php echo $booking['schedule']; ?>
                        
                        </p>
                        <input type="date"  name="booking-date" min="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d', strtotime('+7 days')); ?>" value=<?php echo $booking['schedule']; ?> class='hidden' id="booking_date_<?php echo $booking['booking_id']; ?>">
                        </td>
                        
                        <td><?php echo $booking['guest_name']; ?></td>
                        <td><?php echo $booking['reference_id']; ?></td>
                        <td><?php echo $booking['contact_number']; ?></td>
                        <td id=book_room_type_<?php echo $booking['booking_id']?>>
                          <p>
                            <?php echo $booking['room_type']; ?>
                          </p>
                          <select id="room_type_input_<?php echo $booking['booking_id']; ?>" name="room_type" value=<?php echo $booking['room_type']; ?> class='hidden'>
                            <option value="Regular">Regular</option>
                            <option value="Deluxe">Deluxe</option>
                            <option value="Suite">Suite</option>
                          </select>
                        </td>
                        <td>
                          <button id="edit_button">
                            <span class="material-symbols-outlined" onclick="editBook(<?php echo $booking['booking_id']?>)">edit</span>
                          </button>
                          <button onclick="deleteBook(<?php echo $booking['booking_id']?>)">
                            <span class="material-symbols-outlined" >close</span>
                          </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
      </div>
    <div class="tab_table hidden" id='available_table'>
      <table >
        <thead>
          <tr>
            <th>Room Number</th>
            <th>Room Type</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($availableRooms as $room): ?>
            <tr>
              <td>
  
                <?php echo $room['room_id'] ?>
              </td>
              <td>
  
                <?php echo $room['room_type'] ?>
              </td>
              </tr>
            
          <?php endforeach; ?>
          
        </tbody>
        
      </table>
    </div>
      
    </div>
  </div>
  <?php require_once 'components/footer.php' ?>
  <script>
    function editBook(bookId){
      const schedule_td = document.getElementById(`book_schedule_${bookId}`)
      const room_type_td = document.getElementById(`book_room_type_${bookId}`)
      var editMode = false;
      
      
      if(schedule_td.firstElementChild.classList.contains('hidden')){
        const schedule = document.getElementById(`booking_date_${bookId}`).value
        const room_type = document.getElementById(`room_type_input_${bookId}`).value
        console.log(room_type,schedule);
        const url = `updatebooking.php?booking_id=${bookId}&schedule=${schedule}&room_type=${room_type}`
        window.location.href = url;

      }else{
        schedule_td.firstElementChild.classList.add('hidden');
        schedule_td.lastElementChild.classList.remove('hidden');
        room_type_td.firstElementChild.classList.add('hidden');
        room_type_td.lastElementChild.classList.remove('hidden');
      }
      
    }
    function deleteBook(bookId){
      window.location.href = `deletebooking.php?booking_id=${bookId}`
    }
    function clickedTab(tabClicked){
      const availableTable = document.getElementById('available_table');
      const bookedTable = document.getElementById('booked_table');
      console.log(tabClicked)
      if(tabClicked==='available'){
        bookedTable.classList.add('hidden')
        availableTable.classList.remove('hidden');
      }else{
        bookedTable.classList.remove('hidden');
        availableTable.classList.add('hidden');
      }
    }
  </script>
</body>
</html>