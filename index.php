<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Calendar</title>
<meta property="og:title" content="Calendar">
<meta property="og:url" content="https://ellen.li/calendar">
<meta property="og:description" content="A simple printable calendar.">
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
.labels {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.date {
  display: inline-block;
  height: 1.2em;
}
.day {
  display: inline-block;
  text-align: center;
  width: 1.2em;
  color: #888;
  margin-right: 0.5em;
  height: 1.2em;
}
.weekend {
  background: #eee;
  font-weight: 400;
}
.habit {
  display: inline-block;
  text-align: center;
  text-transform: none;
  color: #888;
}
.habit-fields {
  display: flex;
  flex-direction: row;
  gap: 3em;
  margin: 1em 0;
  flex-wrap: wrap;
}
.habit-field {
  display: flex;
  align-items: center;
  gap: 0.3em;
  flex: 1;
  min-width: 0;
}
.habit-input {
  flex: 1;
  min-width: 0;
  padding: 0.3em;
  font-size: 0.9em;
  border: 1px solid #555;
  background: #444;
  color: #eee;
  border-radius: 0.2em;
  font-family: 'Gothic A1', sans-serif;
}
.habit-delete {
  cursor: pointer;
  color: #999;
  font-size: 1em;
  line-height: 1;
  padding: 0.5em 0.2em;
  user-select: none;
}
.habit-delete:hover {
  color: #fff;
}
.habit-add-button {
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  color: #999;
  font-size: 1em;
  padding: 0.3em;
  border: 1px dashed #555;
  background: #444;
  border-radius: 0.2em;
  user-select: none;
  flex: 1;
  min-width: 0;
}
.habit-add-button:hover {
  color: #fff;
  border-color: #777;
  background: #555;
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
  margin: 4.5em 2em;
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
<script async src="https://cdn.seline.com/seline.js" data-token="c2f4463c668e7be"></script>
<script defer src="https://cloud.umami.is/script.js" data-website-id="f698a641-b61c-434b-be84-74492fc3b326"></script>
</head>
<body>
<div id="info">
<p>👋 Hello!</p>
<p>If you print this page, you’ll get a nifty calendar that displays the year’s dates on a single page. It will automatically fit on a single sheet of paper of any size. For best results, adjust your print settings to landscape orientation and disable the header and footer.</p>
<p>Take in the year all at once. Tape it on your wall. Fold it up and carry it with you. Jot your notes on it. Plan things out and observe the passage of time. Above all else, be kind to others.</p>
<?php
if(isset($_REQUEST['layout']) && $_REQUEST['layout'] == 'aligned-weekdays') {
  // Currently using aligned-weekdays, offer to revert
  $revert_params = $_GET;
  unset($revert_params['layout']);
  $revert_link = '?' . http_build_query($revert_params);
  echo '<p>Want to align days to the top? <a href="' . htmlspecialchars($revert_link) . '">Switch it back.</a></p>';
} else {
  // Not using aligned-weekdays, offer to enable it
  $layout_params = $_GET;
  $layout_params['layout'] = 'aligned-weekdays';
  $layout_link = '?' . http_build_query($layout_params);
  echo '<p>Want to align the weekdays? <a href="' . htmlspecialchars($layout_link) . '">Click here!</a></p>';
}
?>
<?php
$current_year = date('Y');
$displayed_year = isset($_REQUEST['year']) ? intval($_REQUEST['year']) : $current_year;
$target_year = ($displayed_year == $current_year) ? $current_year + 1 : $current_year;
// Preserve other query parameters
$query_params = $_GET;
$query_params['year'] = $target_year;
$year_link = '?' . http_build_query($query_params);
?>
<p>Looking for <?php echo $target_year; ?>? <a href="<?php echo htmlspecialchars($year_link); ?>">Sure!</a></p>

<?php if(isset($_REQUEST['habits']) && $_REQUEST['habits'] == 'true') {
  // echo 'Changed your mind? <a href="?habits=false">No problem.</a>';
    
  // Default habit values
  $default_habits = ['☀️', '💪', '🧘‍♂️', '💧'];
  echo '<div class="habit-fields">';
  foreach($default_habits as $index => $habit) {
    echo '<div class="habit-field">';
    echo '<input type="text" class="habit-input" data-index="' . $index . '" value="' . htmlspecialchars($habit) . '" placeholder="">';
    echo '<span class="habit-delete" data-index="' . $index . '">×</span>';
    echo '</div>';
  }
  echo '<div class="habit-add-button" title="Add habit">+</div>';
  echo '</div>';
  $no_habits_params = $_GET;
  $no_habits_params['habits'] = 'false';
  $no_habits_link = '?' . http_build_query($no_habits_params);
  echo '<p>Mark habits off as you go. Happy tracking!</p><p><a href="' . htmlspecialchars($no_habits_link) . '">Click here</a> to remove the habits.</p>';
}

else { 
  $habits_params = $_GET;
  $habits_params['habits'] = 'true';
  $habits_link = '?' . http_build_query($habits_params);
  echo '<p>Want habit tracking? <a href="' . htmlspecialchars($habits_link) . '">Here you go!</a></p>';
} ?>

<p style="font-size: 100%; color: #999;">By <a href="https://ellen.li">Ellen</a> &#183; <a href="https://github.com/ellenli/calendar">Source Code</a> &#183; Based on <a href="https://neatnik.net/calendar">Neatnik's Calendar</a></p>
</div>
<?php
date_default_timezone_set('UTC');
$now = isset($_REQUEST['year']) ? strtotime($_REQUEST['year'].'-01-01') : time();
$dates = array();
$month = 1;
$day = 1;
echo '<p><strong>'.date('Y', $now).'</strong></p>';
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
        // Display the date
        echo '<div class="labels">';
        echo '<span class="date">'.$dates[$month][$day].'</span>';
        // Display habit labels only if ?habits=true
        if(isset($_REQUEST['habits']) && $_REQUEST['habits'] == 'true') {
        // Moved to script below to avoid rendering issues when adding new habits
        }
        echo '</div></td>';
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
      // Display the date
      echo '<div class="labels">';
      echo '<span class="date">'.$day.'</span>';
      // Display habit labels only if ?habits=true
      if(isset($_REQUEST['habits']) && $_REQUEST['habits'] == 'true') {
      // Moved to script below to avoid rendering issues when adding new habits
      }
      echo '</div></td>';
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

<?php if(isset($_REQUEST['habits']) && $_REQUEST['habits'] == 'true') { ?>
<script>
(function() {
  const habitFields = document.querySelector('.habit-fields');
  if (!habitFields) return;
  
  // Function to ensure calendar cells have enough habit spans
  function ensureHabitSpans() {
    const habitInputs = habitFields.querySelectorAll('.habit-input');
    const maxHabits = habitInputs.length;
    
    const labelsContainers = document.querySelectorAll('.labels');
    labelsContainers.forEach(container => {
      const habitSpans = container.querySelectorAll('.habit');
      const currentCount = habitSpans.length;
      
      // Add more habit spans if needed
      if (currentCount < maxHabits) {
        for (let i = currentCount; i < maxHabits; i++) {
          const newSpan = document.createElement('span');
          newSpan.className = 'habit';
          newSpan.style.display = 'none';
          container.appendChild(newSpan);
        }
      }
    });
  }
  
  // Function to update all habit labels in the calendar
  function updateHabitLabels() {
    const habitInputs = habitFields.querySelectorAll('.habit-input');
    const habitValues = Array.from(habitInputs)
      .map(input => input.value.trim());
    
    // Get all labels containers (one per calendar cell)
    const labelsContainers = document.querySelectorAll('.labels');
    
    labelsContainers.forEach(container => {
      const habitSpans = Array.from(container.querySelectorAll('.habit'));
      const numFields = habitValues.length;
      
      // Update existing habit spans - render all, even if empty (for left-alignment)
      habitSpans.forEach((span, index) => {
        if (index < numFields) {
          // Field exists (even if empty) - keep span visible
          span.textContent = habitValues[index];
          span.style.display = 'inline-block';
        } else {
          // Field was deleted - remove the span entirely
          span.remove();
        }
      });
    });
  }
  
  // Use event delegation for input fields
  habitFields.addEventListener('input', function(e) {
    if (e.target.classList.contains('habit-input')) {
      updateHabitLabels();
    }
  });
  
  habitFields.addEventListener('change', function(e) {
    if (e.target.classList.contains('habit-input')) {
      updateHabitLabels();
    }
  });
  
  // Use event delegation for delete buttons and add button
  habitFields.addEventListener('click', function(e) {
    if (e.target.classList.contains('habit-delete')) {
      const habitField = e.target.closest('.habit-field');
      
      // Remove the field
      habitField.remove();
      
      // Update indices for remaining fields
      const remainingFields = habitFields.querySelectorAll('.habit-field');
      remainingFields.forEach((field, newIndex) => {
        const fieldInput = field.querySelector('.habit-input');
        const fieldDelete = field.querySelector('.habit-delete');
        fieldInput.setAttribute('data-index', newIndex);
        fieldDelete.setAttribute('data-index', newIndex);
      });
      
      // Update habit labels
      updateHabitLabels();
    } else if (e.target.classList.contains('habit-add-button')) {
      // Add a new habit field
      const existingFields = habitFields.querySelectorAll('.habit-field');
      const newIndex = existingFields.length;
      
      // Create new habit field
      const newField = document.createElement('div');
      newField.className = 'habit-field';
      newField.innerHTML = '<input type="text" class="habit-input" data-index="' + newIndex + '" value="" placeholder="">' +
                          '<span class="habit-delete" data-index="' + newIndex + '">×</span>';
      
      // Insert before the add button
      habitFields.insertBefore(newField, e.target);
      
      // Ensure calendar cells have enough habit spans
      ensureHabitSpans();
      
      // Focus the new input
      const newInput = newField.querySelector('.habit-input');
      newInput.focus();
      
      // Update habit labels
      updateHabitLabels();
    }
  });
  
  // Initialize habit labels on page load
  ensureHabitSpans();
  updateHabitLabels();
})();
</script>
<?php } ?>
</body>
</html>