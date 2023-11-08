
file = open('insert.txt', 'w+')
mysql_string = 'INSERT INTO room (room_type, room_number) VALUES '
values_string = "('{room_type}', {room_number}) \n"

for floors in range(1, 6):
    for room in range(1, 21):
        if room < 10:
            room_number = "%d0%d" % (floors, room)
        else:
            room_number = "%d%d" % (floors, room)
        if room < 14:

            mysql_string += values_string.format(
                room_type='Regular', room_number=room_number)
        if 14 <= room <= 18:
            mysql_string += values_string.format(
                room_type='Deluxe', room_number=room_number)
        if room > 18:
            mysql_string += values_string.format(
                room_type='Suite', room_number=room_number)
        if room < 20:
            mysql_string += ','
    if floors < 5:
        mysql_string += ','

mysql_string += ';'

file.write(mysql_string)
