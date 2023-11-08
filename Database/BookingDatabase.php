<?php 
class BookingDatabase {
    private $db;

    public function __construct() {
        try {
            $this->db = new mysqli('sql104.infinityfree.com', 'if0_35389436', 'oQBKIGLQimhv', 'if0_35389436_scheduling_database');

            if ($this->db->connect_error) {
                throw new Exception("Could not connect to the database: " . $this->db->connect_error);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function createBooking($room_id, $schedule, $guest_name, $contact_number, $guest_email) {
    $query = "INSERT INTO booking (room_id, schedule, guest_name, contact_number, guest_email) 
              VALUES (?, ?, ?, ?, ?)";
    $stmt = $this->db->prepare($query);
    $stmt->bind_param("issss", $room_id, $schedule, $guest_name, $contact_number, $guest_email);

    if ($stmt->execute()) {
        // After successful insert, retrieve the generated reference_id
        $query = "SELECT reference_id FROM booking WHERE booking_id = LAST_INSERT_ID()";
        $result = $this->db->query($query);

        if ($result && $row = $result->fetch_assoc()) {
            return $row['reference_id'];
        } else {
            return null; // Handle the case where reference_id retrieval fails
        }
    } else {
        return null; // Handle the case where the insertion fails
    }
    }
    public function getAvailableRooms($date, $room_type){

      $query = "SELECT room.room_id, room.room_type
                FROM room
                LEFT JOIN booking ON room.room_id = booking.room_id AND booking.schedule = DATE(?)
                WHERE booking.room_id IS NULL AND room.room_type = ?;";

      if($room_type != null){
        $query = "SELECT room.room_id, room.room_type
                FROM room
                LEFT JOIN booking ON room.room_id = booking.room_id AND booking.schedule = DATE(?)
                WHERE booking.room_id IS NULL AND room.room_type = ?;";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ss', $date, $room_type);
      }else{
        $query = "SELECT room.room_id, room.room_type
                FROM room
                LEFT JOIN booking ON room.room_id = booking.room_id AND booking.schedule = DATE(?)
                WHERE booking.room_id IS NULL;";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $date);
      }
      

      if ($stmt->execute()) {
          $results = $stmt->get_result();
          $availableRooms = [];

          while ($row = $results->fetch_assoc()) {
              $availableRooms[] = array(
                  'room_id' => $row['room_id'],
                  'room_type' => $row['room_type']
              );
          }

          $stmt->close();
          return $availableRooms;
      } else {
          // Handle query execution errors
          return null;
      }
  }


    public function readBookings() {
        $query = "SELECT * FROM booking";
        $results = $this->db->query($query);

        if ($results) {
            $bookings = [];

            while ($row = $results->fetch_assoc()) {
                $bookings[] = array(
                    'booking_id' => $row['booking_id'],
                    'room_id' => $row['room_id'],
                    'schedule' => $row['schedule'],
                    'guest_name' => $row['guest_name'],
                    'contact_number' => $row['contact_number'],
                    'guest_email' => $row['guest_email']
                );
            }

            $results->free_result();

            return $bookings;
        } else {
            return null;
        }
    }
    public function readBookingsFromNow() {
        $query = "SELECT b.*, r.room_type FROM booking b JOIN room r ON b.room_id = r.room_id WHERE b.schedule >= DATE(NOW()) ORDER BY b.schedule;";
        $results = $this->db->query($query);

        if ($results) {
            $bookings = [];

            while ($row = $results->fetch_assoc()) {
                $datetime = $row['schedule'];
                $date = date('Y-m-d', strtotime($datetime));

                $bookings[] = array(
                    'booking_id' => $row['booking_id'],
                    'room_id' => $row['room_id'],
                    'schedule' => $date,
                    'guest_name' => $row['guest_name'],
                    'contact_number' => $row['contact_number'],
                    'guest_email' => $row['guest_email'],
                    'reference_id' => $row['reference_id'],
                    'room_type' => $row['room_type']
                );
            }

            $results->free_result();

            return $bookings;
        } else {
            return null;
        }
    }
    public function deleteBooking($booking_id) {
        $query = "DELETE FROM booking WHERE booking_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $booking_id);

        if ($stmt->execute()) {
            // Successful deletion
            return true;
        } else {
            // Failed to delete
            return false;
        }
    }
    public function updateBooking($booking_id, $booking_date, $room_type){

        $availableRooms = $this->getAvailableRooms($booking_date, $room_type);
        $room_id = $availableRooms[0]['room_id'];

        $query = "UPDATE booking SET schedule=DATE(?), room_id=? WHERE booking_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sis", $booking_date, $room_id, $booking_id);
        $stmt->execute();
    }

    public function __destruct() {
        $this->db->close();
    }
}
