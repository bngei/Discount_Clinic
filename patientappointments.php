<?php
  ob_start();
  session_start();
?>

<!DOCTYPE html>
<html>
    <header>
        <div class="logo">
          <h1>Discount Clinic</h1>
        </div>
        <nav>
          <ul>
            <li class ="active"><a href="index.php">Home</a></li>
				    <li><a href="patient_profile.php">Profile</a></li>
				    <li><a href="patientappointments.php">Schedule Appointment</a></li>
        		<li><a href="transactions.php">Transactions</a></li>
				    <li><a href="logout.php">Logout</a></li>
          </ul>
        </nav>
      </header>
<head>
  <style>
    .container {
      width: 500px;
      margin: 0 auto;
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 10px;
      box-shadow: 0 0 10px #ccc;
      margin-top: 60px;

    }
  </style>
	<title>Appointment Making System</title>
	<link rel="stylesheet" type="text/css" href="styles.css">
</head>

<script src="patient_appointments_script.js" defer></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
  
  function my_fun(str) {
    if(window.XMLHttpRequest) {
      xmlhttp = new XMLHttpRequest();
    }
    else{
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
      if (this.readyState==4 && this.status==200) {
        document.getElementById('office').innerHTML = this.responseText;
      }
    }
    xmlhttp.open("GET","helper.php?value="+str, true);
    xmlhttp.send();
  }


  
  function my_other_fun(str) {
    if(window.XMLHttpRequest) {
      xmlhttp = new XMLHttpRequest();
    }
    else{
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.onreadystatechange = function() {
      if (this.readyState==4 && this.status==200) {
        document.getElementById('doctor').innerHTML = this.responseText;
      }
    }
    xmlhttp.open("GET","other_helper.php?value="+str, true);
    xmlhttp.send();

  }
</script>
<body>



	<div class="container">
		<h2>Appointment Form</h2>
		<form action="#" method="POST">
      <label for="date">Date:</label>
      <input type="date" id="date" name="date" required>
      <br>
      <label for="time">Time:</label>
            <select id="time" name="time" required>
            <option value=""></option>
            </select>

      <br>
        <label for="state">Select a State:</label>
				<select id="state" name="state" onchange="my_fun(this.value);">

            <option value=""></option>
            <option value="AL">Alabama</option>
            <option value="AK">Alaska</option>
            <option value="AZ">Arizona</option>
            <option value="AR">Arkansas</option>
            <option value="CA">California</option>
            <option value="CO">Colorado</option>
            <option value="CT">Connecticut</option>
            <option value="DE">Delaware</option>
            <option value="DC">District Of Columbia</option>
            <option value="FL">Florida</option>
            <option value="GA">Georgia</option>
            <option value="HI">Hawaii</option>
            <option value="ID">Idaho</option>
            <option value="IL">Illinois</option>
            <option value="IN">Indiana</option>
            <option value="IA">Iowa</option>
            <option value="KS">Kansas</option>
            <option value="KY">Kentucky</option>
            <option value="LA">Louisiana</option>
            <option value="ME">Maine</option>
            <option value="MD">Maryland</option>
            <option value="MA">Massachusetts</option>
            <option value="MI">Michigan</option>
            <option value="MN">Minnesota</option>
            <option value="MS">Mississippi</option>
            <option value="MO">Missouri</option>
            <option value="MT">Montana</option>
            <option value="NE">Nebraska</option>
            <option value="NV">Nevada</option>
            <option value="NH">New Hampshire</option>
            <option value="NJ">New Jersey</option>
            <option value="NM">New Mexico</option>
            <option value="NY">New York</option>
            <option value="NC">North Carolina</option>
            <option value="ND">North Dakota</option>
            <option value="OH">Ohio</option>
            <option value="OK">Oklahoma</option>
            <option value="OR">Oregon</option>
            <option value="PA">Pennsylvania</option>
            <option value="RI">Rhode Island</option>
            <option value="SC">South Carolina</option>
            <option value="SD">South Dakota</option>
            <option value="TN">Tennessee</option>
            <option value="TX">Texas</option>
            <option value="UT">Utah</option>
            <option value="VT">Vermont</option>
            <option value="VA">Virginia</option>
            <option value="WA">Washington</option>
            <option value="WV">West Virginia</option>
            <option value="WI">Wisconsin</option>
            <option value="WY">Wyoming</option>
        </select>
        <br>
          <label for="office">Select an Office:</label>
					<select id="office" name="office" onchange="my_other_fun(this.value);">
						<option value="">Select location</option>
					</select>
          <br>
          <label for="doctor">Select a Doctor:</label>
          <select id="doctor" name="doctor" required>
            <option value="">Select doctor</option>
          </select>
          <div></div>
          <br>
			<button type="submit" value = "Submit" id="submitBtn">Submit</button>
		</form>
	</div>
</body>
</html>


<?php

include("dbh-inc.php");
include("functions.php");

$user_data = check_login($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $date = $_POST['date'];
        $date = date('Y-m-d', strtotime($date));
        $time = $_POST['time'];
        $time = date('H:i', strtotime($time));
        $office_id = $_POST['office'];
        $doctor_id = $_POST['doctor'];

    $username = $user_data['username'];

  $query = "SELECT user_id FROM user WHERE username = '$username'";
  $result = mysqli_query($conn, $query);

  if($result && mysqli_num_rows($result) > 0) {
    $user_data = mysqli_fetch_assoc($result);
    $user_id = $user_data['user_id'];
  } else {
  }

  $query = "SELECT patient_id FROM patient WHERE user_id = '$user_id'";
  $result = mysqli_query($conn, $query);

  if($result && mysqli_num_rows($result) > 0) {
    $patient_data = mysqli_fetch_assoc($result);
    $patient_id = $patient_data['patient_id'];
    
    $sql = "INSERT INTO appointment (patient_id, doctor_id, office_id, time, date, deleted) VALUES ('$patient_id','$doctor_id','$office_id','$time','$date', 0)";
        if (mysqli_query($conn, $sql)) 
        {
            $appointment_status = "SELECT * FROM approval WHERE specialist_doctor_id = '$doctor_id' AND patient_id = '$patient_id' AND approval_bool=1";
            //echo $sql_doctor;
            $result = mysqli_query($conn, $appointment_status);
            if ($result && mysqli_num_rows($result) > 0) 
            {
              echo "Thank you for scheduling your appointment!";
            } 
            else 
            {
                $sql_specialist = "SELECT * FROM doctor WHERE doctor_id = '$doctor_id' AND specialty <> 'primary'";
                $res = mysqli_query($conn, $sql_specialist);
                if ($res && mysqli_num_rows($res) > 0) 
                {
                  echo "You need approval from a primary doctor.";
                }
          
                else 
                {
                  echo "Thank you for scheduling your appointment!";
                }
            }
        }
        else 
        {
          echo "You need approval from your primary doctor.";
        }
  } 
  else {
    echo "Patient not found";
  }
} 
?>