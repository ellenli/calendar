<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Calendar</title>
<meta property="og:title" content="Calendar">
<!-- <meta property="og:url" content="https://labs.ellen.li/calendar"> -->
<meta property="og:description" content="A simple printable calendar for habit tracking.">
<style>
@import url('https://fonts.bunny.net/css?family=inter:300|Gothic A1:300,400');
@media print {
  @page {
    margin: 0;
    padding: 1em;
  }
  #info {
    display: none;
  }
  td {
    font-size: 8px !important;
  }
  .weekend {
    background: #d8d8d8 !important;
  }
}
html {
  font-family: 'Gothic A1';
}
html, body {
  height: 100%;
  margin: 0;
  padding: 0;
}
table {
  width: 100%;
  height: calc(100% - 2.5em);
  border-collapse: separate;
  border-spacing: .5em 0;
}
td, th {
  font-weight: normal;
  text-transform: uppercase;
  border-bottom: 1px solid #888;
  padding: .3vmin .3vmin;
  font-size: .9vmin;
  font-weight: 300;
  color: #000;
}
th {
  font-size: 1.1vmin;
  padding: 0;
}
td:empty {
  border: 0;
}
.date {
  display: inline-block;
  width: 1.1em;
}
.day {
  display: inline-block;
  text-align: center;
  width: 1em;
  color: #888;
}
.weekend {
  background: #eee;
  font-weight: 400;
}
p {
  margin: 0 0 .5em 0;
  text-align: center;
}
* {
  color-adjust: exact;
  -webkit-print-color-adjust: exact;
}
#info {
  font-family: 'Gothic A1', sans-serif;
  position: absolute;
  top: 0;
  left: 0;
  margin: 5em 2em;
  width: calc(100% - 6em);
  background: #333;
  color: #eee;
  padding: 1em 1em .5em 1em;
  font-size: 1.5vmax;
  border-radius: .2em;
}
#info p {
  text-align: left;
  margin: 0 0 1em 0;
  line-height: 135%;
}
#info a {
  color: inherit;
}
</style>
</head>
<body>
<div id="info">
<p>👋 Hello!</p>
<p>If you print this page, you’ll get a nifty calendar that displays the year’s dates on a single page. It will automatically fit on a single sheet of paper of any size. For best results, adjust your print settings to landscape orientation and disable the header and footer.</p>
<p>Take in the year all at once. Fold it up and carry it with you. Jot down your notes on it. Plan things out and observe the passage of time. Above all else, be kind to others.</p>
<p>Want to align the weekdays? <a href="?layout=aligned-weekdays">Click here!</a></p>
<p>Looking for <?php echo date("Y", strtotime("now + 1 year")); ?>? <a href="?year=<?php echo date("Y", strtotime("now + 1 year")); ?>">Sure!</a></p>
<!-- <p>Want habit tracking? <a href="?habits=true">Here you go!</a></p> -->

<p style="font-size: 100%; color: #999;">Made by <a href="https://ellen.li">Ellen</a> &#183; Based on <a href="https://neatnik.net/calendar">Neatnik's Calendar</a></p>
</div>
<?php
date_default_timezone_set('UTC');
$now = isset($_REQUEST['year']) ? strtotime($_REQUEST['year'].'-01-01') : time();
$dates = array();
$month = 1;
$day = 1;
echo '<p>'.date('Y', $now).'</p>';
echo '<table>';
echo '<thead>';
echo '<tr>';
// Add the month headings
for($i = 1; $i <= 12; $i++) {
  echo '<th>'.DateTime::createFromFormat('!m', $i)->format('M').'</th>';
}
echo '</tr>';
echo '</thead>';
echo '<tbody>';

// Prepare a list of the first weekdays for each month of the year
$date = strtotime(date('Y', $now).'-01-01');
$first_weekdays = array();

for($x = 1; $x <= 12; $x++) {
  $first_weekdays[$x] = date('N', strtotime(date('Y', $now).'-'.$x.'-01'));
  $$x = false; // Set a flag for each month so we can track first days below
}

// Start the loop around 12 months
while($month <= 12) {
  $day = 1;
  for($x = 1; $x <= 42; $x++) {
    if(!$$month) {
      if($first_weekdays[$month] == $x) {
        $dates[$month][$x] = $day;
        $day++;
        $$month = true;
      }
      else {
        $dates[$month][$x] = 0;
      }
    }
    else {
      // Ensure that we have a valid date
      if($day > cal_days_in_month(CAL_GREGORIAN, $month, date('Y', $now))) {
        $dates[$month][$x] = 0;
        
      }
      else {
        $dates[$month][$x] = $day;
      }
      $day++;
    }
  }
  $month++;
}

// Now produce the table

$month = 1;
$day = 1;

if(isset($_REQUEST['sofshavua'])) {
  $weekend_day_1 = 5;
  $weekend_day_2 = 6;
}
else {
  $weekend_day_1 = 6;
  $weekend_day_2 = 7;
}

if(isset($_REQUEST['layout']) && $_REQUEST['layout'] == 'aligned-weekdays') {
  // Start the outer loop around 42 days (6 weeks at 7 days each)
  while($day <= 42) {
    echo '<tr>';
    // Start the inner loop around 12 months
    while($month <= 12) {
      if($dates[$month][$day] == 0) {
        echo '<td></td>';
      }
      else {
        $date = date('Y', $now).'-'.str_pad($month, 2, '0', STR_PAD_LEFT).'-'.str_pad($dates[$month][$day], 2, '0', STR_PAD_LEFT);
        if(date('N', strtotime($date)) == $weekend_day_1 || date('N', strtotime($date)) == $weekend_day_2) {
          echo '<td class="weekend">';
        }
        else {
          echo '<td>';
        }
        echo $dates[$month][$day];
        echo '</td>';
      }
      $month++;
    }
    echo '</tr>';
    $month = 1;
    $day++;
  }
  
}

else {
  // Start the outer loop around 31 days
  while($day <= 31) {
    echo '<tr>';
    // Start the inner loop around 12 months
    while($month <= 12) {
      // If we’ve reached a point in the date matrix where the resulting date would be invalid (e.g. February 30th), leave the cell blank
      if($day > cal_days_in_month(CAL_GREGORIAN, $month, date('Y', $now))) {
        echo '<td></td>';
        $month++;
        continue;
      }
      // If the day falls on a weekend, apply a specific class for styles
      if(DateTime::createFromFormat('!Y-m-d', date('Y', $now).'-'.$month.'-'.$day)->format('N') == $weekend_day_1 || DateTime::createFromFormat('!Y-m-d', date('Y', $now).'-'.$month.'-'.$day)->format('N') == $weekend_day_2) {
        echo '<td class="weekend">';
      }
      else {
        echo '<td>';
      }
      // Display the day number and day of the week
      echo '<span class="date">'.$day.'</span> <span class="day">'.substr(DateTime::createFromFormat('!Y-m-d', date('Y', $now).'-'.$month.'-'.$day)->format('D'), 0, 1).'</span>';
      echo '</td>';
      $month++;
    }
    echo '</tr>';
    $month = 1;
    $day++;
  }
}

?>
</tbody>
</table>
</body>
</html>