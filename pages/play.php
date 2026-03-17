<?php
include "../config/db.php";
$res = $conn->query("SELECT schedules.*, sounds.filename FROM schedules JOIN sounds ON schedules.sound_id = sounds.id");
$data = [];
while ($r = $res->fetch_assoc()) $data[] = $r;
?>

<script>
    
setInterval(() => {
    const now = new Date();
    const currentTime = now.toTimeString().slice(0,5);
    const days = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
    const today = days[now.getDay()];

    const schedules = <?= json_encode($data) ?>;

    schedules.forEach(s => {
        if (s.bell_time === currentTime && s.day === today) {
            const audio = new Audio('../uploads/' + s.filename);
            audio.play();
        }
    });
}, 1000);

</script>
