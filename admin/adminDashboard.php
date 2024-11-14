<?php
include('../database/dbconnect.php');
session_start();

if (!isset($_SESSION['username']) && !isset($_SESSION['school_year']) && !isset($_SESSION['semester']) && !isset($_SESSION['is_status'])) {
  // Redirect to login page or display an error message
  header("Location: ../login.php");
  exit();
}

// Query to get the active academic year and semester if set to 'Started'
$schoolyear_query = $conn->query("SELECT school_year, semester, is_status FROM tblschoolyear WHERE is_status = 'Started'");
$schoolyear = $schoolyear_query->fetch_assoc();

// Set session variables only if there is an active academic year
if ($schoolyear) {
  $_SESSION['school_year'] = $schoolyear['school_year'];
  $_SESSION['semester'] = $schoolyear['semester'];
  $_SESSION['is_status'] = $schoolyear['is_status'];
} else {
  // Set default values if no academic year is active
  $_SESSION['school_year'] = "Not Set";
  $_SESSION['semester'] = "Not Yet Started";
  $_SESSION['is_status'] = "Inactive";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>

  <aside>
    <div class="container">
      <div class="fixed bottom-0 left-0 top-0 z-50 w-[270px] bg-white shadow">
        <div class=" text-2xl text-center flex justify-center items-center hover:border py-1 rounded-sm cursor-pointer">
          <div class="mx-1">
            <img
              src="../admin/Images/CEC.png"
              alt="school_year-image"
              class="h-10 w-10">
          </div>
          <div class="mx-3">
            <a href="adminDashboard.php" class="cursor-pointer">Dashboard</a>
          </div>
        </div>
        <div class="flex flex-col justify-evenly item-center text-center gap-2 mt-5">
          <div class="flex justify-center item-center gap-2 hover:bg-gray-400 py-2 hover:text-black cursor-pointer">
            <div class="h-10 w-10">
              <img src="../admin/img_side/student_side.svg" alt="student_sidebar">
            </div>
            <div class="mt-2 text-lg">
              <a href="../admin/student_view.php">Manage Student</a>
            </div>
          </div>
          <div class="flex justify-center item-center gap-2 hover:bg-gray-400 py-2 hover:text-black cursor-pointer">
            <div class="h-10 w-10">
              <img src="../admin/img_side/teacher-svgrepo-com.svg" alt="teacher_sidebar">
            </div>
            <div class="mt-2 text-lg">
              <a href="../admin/teacher_view.php">Manage Teacher</a>
            </div>
          </div>
          <div class="flex justify-center item-center gap-2 hover:bg-gray-400 py-2 hover:text-black cursor-pointer ms-[-25px]">
            <div class="h-10 w-10">
              <img src="../admin/img_side/user_side.svg" alt="user_sidebar">
            </div>
            <div class="mt-2 text-lg">
              <a href="">Manage User</a>
            </div>
          </div>
          <div class="flex justify-center item-center gap-2  hover:bg-gray-400 py-2 hover:text-black cursor-pointer ms-[18px]">
            <div class="h-10 w-10">
              <img src="../admin/img_side/academic_side.svg" alt="academic_sidebar">
            </div>
            <div class="mt-2 text-lg">
              <a href="../admin/academic_create.php">Manage Academic</a>
            </div>
          </div>
          <div class="flex justify-center item-center gap-2 hover:bg-gray-400 
            py-2 hover:text-black cursor-pointer ms-[35px]">
            <div class="h-10 w-10">
              <img src="../admin/img_side/department_side.svg" alt="department_sidebar">
            </div>
            <div class="mt-2 text-lg">
              <a href="../admin/department_create.php">Manage Department</a>
            </div>
          </div>
          <div class="flex justify-center item-center gap-2 hover:bg-gray-400 py-2 hover:text-black cursor-pointer">
            <div class="h-10 w-10">
              <img src="../admin/img_side/section_side.svg" alt="section_sidebar">
            </div>
            <div class="mt-2 text-lg">
              <a href="../admin/section_create.php">Manage Section</a>
            </div>
          </div>
          <div class="flex justify-center item-center gap-2 hover:bg-gray-400 py-2 hover:text-black cursor-pointer">
            <div class="h-10 w-10">
              <img src="../admin/img_side/criteria_side.svg" alt="criteria_sidebar">
            </div>
            <div class="mt-2 text-lg">
              <a href="../admin/criteria_create.php">Manage Criteria</a>
            </div>
          </div>
          <div class="flex justify-center item-center gap-2 hover:bg-gray-400 py-2 hover:text-black cursor-pointer">
            <div class="h-10 w-10">
              <img src="../admin/img_side/subject_side.svg" alt="subject_sidebar">
            </div>
            <div class="mt-2 text-lg">
              <a href="../admin/subject_create.php">Manage Subject</a>
            </div>
          </div>
          <div class="flex justify-center item-center gap-2 hover:bg-gray-400 py-2 hover:text-black cursor-pointer">
            <div class="h-10 w-10 ms-[-63px]">
              <img src="../admin/img_side/archive_side.svg" alt="archive_sidebar">
            </div>
            <div class="mt-2 text-lg mx-1">
              <a href="">Archive</a>
            </div>
          </div>
          <div class="flex justify-center 
            item-center gap-2 hover:bg-gray-400 
              py-2 hover:text-black ms-[-73px]">
            <div class="h-10 w-10">
              <img src="../admin/img_side/logout_side.svg" alt="logout_sidebar">
            </div>
            <div class="mt-2 text-lg">
              <a href="../logout.php">Logout</a>
            </div>
          </div>

        </div>

      </div>
    </div>
  </aside>
  <main>
    <div class="fixed bottom-0 left-0 right-0 top-0 z-10 w-full px-80">
      <?php if (isset($_SESSION['username'])): ?>
        <div class=" h-[700px] p-4">
          <div class="p-4 border bg-slate-900 w-full text-white rounded-md ">
            <h2 class="text-4xl mb-4">Welcome, Admin <?= htmlspecialchars($_SESSION['username']) ?></h2>

            <!-- Display academic year or default "Not Set" message -->
            <p class="text-2xl m-1">Academic Year:
              <?= htmlspecialchars($_SESSION['school_year'] === "Not Set" ? "Not Set" : $_SESSION['school_year']) ?>
            </p>

            <!-- Display semester information -->
            <p class="text-2xl m-1">Semester:
              <?= $_SESSION['semester'] == '1' ? 'First Semester' : ($_SESSION['semester'] == '2' ? 'Second Semester' : 'Not Yet Started') ?>
            </p>

            <!-- Display status -->
            <p class="text-2xl m-1 relative">Status: <?= htmlspecialchars($_SESSION['is_status']) ?></p>
          <?php else: ?>
            <p>Academic year and semester not set. Please set the active semester.</p>
          <?php endif; ?>
          </div>

          <div class="grid grid-cols-3 gap-5 w-full mt-20">
            <div class="relative h-[150px] border-4 border-blue-900 bg-blue-100 rounded-md shadow-md  shadow-blue-200">
              <div class="p-3 flex justify-between items-center">
                <div>
                  <h1 class="text-4xl">Student</h1>
                </div>
                <div>
                  <img src="../admin/img_side/student_side.svg" alt="student" class="w-8 h-8">
                </div>
              </div>
              <div class="absolute bottom-[30px] left-5 h-10 w-10 rounded-full bg-blue-900"></div>
              <div class="absolute bottom-[30px] left-20 text-4xl">
                <span>
                  <?php
                  $count = 0;
                  $sql = "SELECT COUNT(*) AS total FROM tblstudent";
                  $result = mysqli_query($conn, $sql);

                  if ($result) {
                    $row = mysqli_fetch_assoc($result);
                    $count = $row['total'];
                  }
                  echo $count;
                  ?>

                </span>
              </div>
            </div>
            <div class="relative h-[150px] border-4 border-green-900 bg-green-100 rounded-md shadow-md  shadow-green-200">
              <div class="p-3 flex justify-between items-center">
                <div>
                  <h1 class="text-4xl">Teacher</h1>
                </div>
                <div>
                  <img src="../admin/img_side/teacher-svgrepo-com.svg" alt="teacher" class="w-8 h-8">
                </div>
              </div>
              <div class="absolute bottom-[30px] left-5 h-10 w-10 rounded-full bg-green-900"></div>
              <div class="absolute bottom-[30px] left-12 text-4xl">
                <span style="padding: 0.5em 1em;">
                  <?php
                  $count = 0;
                  $sql = "SELECT COUNT(*) AS total FROM tblteacher";
                  $result = mysqli_query($conn, $sql);

                  if ($result) {
                    $row = mysqli_fetch_assoc($result);
                    $count = $row['total'];
                  }
                  echo $count;
                  ?>

                </span>
              </div>
            </div>
            <div class="relative h-[150px] border-4 border-pink-900 bg-pink-100 rounded-md shadow-md  shadow-pink-200">
              <div class="p-3 flex justify-between items-center">
                <div>
                  <h1 class="text-4xl">Department</h1>
                </div>
                <div>
                  <img src="../admin/img_side/department_side.svg" alt="Department" class="w-8 h-8">
                </div>
              </div>
              <div class="absolute bottom-[30px] left-5 h-10 w-10 rounded-full bg-pink-900"></div>
              <div class="absolute bottom-[30px] left-20 text-4xl">
                <span>
                  <?php
                  $count = 0;
                  $sql = "SELECT COUNT(*) AS total FROM tbldepartment";
                  $result = mysqli_query($conn, $sql);

                  if ($result) {
                    $row = mysqli_fetch_assoc($result);
                    $count = $row['total'];
                  }
                  echo $count;
                  ?>

                </span>
              </div>
            </div>
            <div class="relative h-[150px] border-4 border-orange-900 bg-orange-100 rounded-md shadow-md  shadow-orange-200">
              <div class="p-3 flex justify-between items-center">
                <div>
                  <h1 class="text-4xl">Section</h1>
                </div>
                <div>
                  <img src="../admin/img_side/section_side.svg" alt="section" class="w-8 h-8">
                </div>
              </div>
              <div class="absolute bottom-[30px] left-5 h-10 w-10 rounded-full bg-orange-900"></div>
              <div class="absolute bottom-[30px] left-20 text-4xl">
                <span>
                  <?php
                  $count = 0;
                  $sql = "SELECT COUNT(*) AS total FROM tbldepartment";
                  $result = mysqli_query($conn, $sql);

                  if ($result) {
                    $row = mysqli_fetch_assoc($result);
                    $count = $row['total'];
                  }
                  echo $count;
                  ?>

                </span>
              </div>
            </div>
            <div class="relative h-[150px] border-4 border-indigo-900 bg-indigo-100 rounded-md shadow-md  shadow-indigo-200">
              <div class="p-3 flex justify-between items-center">
                <div>
                  <h1 class="text-4xl">Criteria</h1>
                </div>
                <div>
                  <img src="../admin/img_side/criteria_side.svg" alt="criteria" class="w-8 h-8">
                </div>
              </div>
              <div class="absolute bottom-[30px] left-5 h-10 w-10 rounded-full bg-indigo-900"></div>
              <div class="absolute bottom-[30px] left-20 text-4xl">
                <span>
                  <?php
                  $count = 0;
                  $sql = "SELECT COUNT(*) AS total FROM tblcriteria";
                  $result = mysqli_query($conn, $sql);

                  if ($result) {
                    $row = mysqli_fetch_assoc($result);
                    $count = $row['total'];
                  }
                  echo $count;
                  ?>

                </span>
              </div>
            </div>
            <div class="relative h-[150px] border-4 border-gray-900 bg-gray-100 rounded-md shadow-md  shadow-gray-200">
              <div class="p-3 flex justify-between items-center">
                <div>
                  <h1 class="text-4xl">Subject</h1>
                </div>
                <div>
                  <img src="../admin/img_side/subject_side.svg" alt="subject" class="w-8 h-8">
                </div>
              </div>
              <div class="absolute bottom-[30px] left-5 h-10 w-10 rounded-full bg-gray-900"></div>
              <div class="absolute bottom-[30px] left-20 text-4xl">
                <span>
                  <?php
                  $count = 0;
                  $sql = "SELECT COUNT(*) AS total FROM tblsubject";
                  $result = mysqli_query($conn, $sql);

                  if ($result) {
                    $row = mysqli_fetch_assoc($result);
                    $count = $row['total'];
                  }
                  echo $count;
                  ?>

                </span>
              </div>
            </div>
          </div>
        </div>
    </div>
  </main>

</body>

</html>