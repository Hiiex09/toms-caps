<?php
include('../database/dbconnect.php');
if (isset($_GET['updateId'])) {
  $teacher_id = $_GET['updateId'];

  echo "Teacher ID: " . $teacher_id;

  function updateTeacher($conn, $teacher_id, $school_id, $fname, $lname, $department_id, $file)
  {
    $name = $fname . " " . $lname;

    if ($file['error'] === 4) {
      echo "<script>alert('Image not uploaded');</script>";
      return;
    }

    $imgname = $file['name'];
    $imgsize = $file['size'];
    $imgtmp = $file['tmp_name'];
    $imgvalid = ['jpeg', 'jpg', 'png', 'svg'];
    $imgEx = strtolower(pathinfo($imgname, PATHINFO_EXTENSION));

    if (!in_array($imgEx, $imgvalid)) {
      echo "<script>alert('Invalid file extension');</script>";
      return;
    } elseif ($imgsize > 1000000) {
      echo "<script>alert('Image is too large');</script>";
      return;
    }

    $newimg = uniqid() . '.' . $imgEx;
    if (!move_uploaded_file($imgtmp, "../pic/pics/$newimg")) {
      echo "<script>alert('Image upload failed');</script>";
      return;
    }

    $sql = "UPDATE tblteacher 
            SET school_id = ?, name = ?, department_id = ?, image = ? 
            WHERE teacher_id = ?";

    if ($stmt = $conn->prepare($sql)) {
      $stmt->bind_param('ssssi', $school_id, $name, $department_id, $newimg, $teacher_id);

      if ($stmt->execute()) {
        echo "<script>alert('Teacher updated successfully!');</script>";
      } else {
        echo "<script>alert('Error updating teacher: " . $stmt->error . "');</script>";
      }

      $stmt->close();
    } else {
      echo "<script>alert('Error: Could not prepare query.');</script>";
    }
  }
  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    updateTeacher(
      $conn,
      $_POST['teacher_id'],  // Assuming teacher_id is passed in the form data
      $_POST['school_id'],
      $_POST['fname'],
      $_POST['lname'],
      $_POST['department_id'],
      $_FILES['hen']
    );
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Teacher</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
  <div class="fixed top-20 z-10 w-3/4 h-4/6 mx-5 p-2 rounded-md bg-slate-900 text-white update_modal">
    <div class="m-1">
      <h1 class="text-white text-lg font-semibold">UPDATE TEACHER</h1>
    </div>
    <form
      action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"
      method="post"
      enctype="multipart/form-data">
      <input type="hidden" name="teacher_id" value="<?php echo htmlspecialchars($teacher_id); ?>">
      <div class="flex justify-evenly items-center">
        <div class="mt-4">
          <div class="m-1 flex justify-between items-center">
            <div>
              <h1 class="text-white text-2xl">Image Preview</h1>
            </div>
          </div>
          <div>
            <img id="image-preview"
              src="../pic/pics/<?php echo htmlspecialchars($current_image ? $current_image : 'Student.png'); ?>"
              alt="Image Preview"
              class="w-64 h-64 object-cover mt-4 rounded-md">
          </div>
          <div class="mt-7">
            <label for="hen"
              class="px-20 py-3 border rounded cursor-pointer shadow relative">
              Upload Image
            </label>
            <input
              type="file"
              id="hen"
              class="hidden w-full"
              accept=".jpeg, .jpg, .png, .svg"
              onchange="previewImage(event)"
              name="hen">
          </div>
        </div>
        <div class="grid grid-cols-2 gap-6">
          <div class="mt-8">
            <div class="m-1 flex justify-between items-center">
              <div>
                <label>School ID</label>
              </div>
              <div>
                <span>
                  <img src="../admin/Images/id.svg" alt="School ID"
                    class="w-9 h-9">
                </span>
              </div>
            </div>
            <div>
              <input
                type="text"
                class="w-full px-3 py-2 border-s-4 text-black border-blue-900 rounded-sm"
                placeholder="School ID"
                minlength="7" maxlength="7" name="school_id"
                autocomplete="off" value="<?php echo htmlspecialchars($school_id); ?>" required>
            </div>
            <div class="m-1 flex justify-between items-center">
              <div>
                <label>First Name</label>
              </div>
              <div>
                <span>
                  <img src="../admin/Images/user.svg" alt="First Name"
                    class="w-7 h-7">
                </span>
              </div>
            </div>
            <div>
              <input
                type="text"
                class="w-full px-3 py-2 border-s-4 text-black border-blue-900 rounded-sm"
                placeholder="First Name"
                name="fname" autocomplete="off" value="<?php echo htmlspecialchars($fname); ?>" required>
            </div>
            <div class="m-1 flex justify-between items-center">
              <div>
                <label>Last Name</label>
              </div>
              <div>
                <span>
                  <img src="../admin/Images/user.svg" alt="Last Name"
                    class="w-7 h-7">
                </span>
              </div>
            </div>
            <div>
              <input
                type="text"
                class="w-full px-3 py-2 border-s-4 text-black border-blue-900 rounded-sm"
                placeholder="Last Name"
                name="lname" autocomplete="off" value="<?php echo htmlspecialchars($lname); ?>" required>
            </div>
          </div>
          <div class="mt-5">
            <div class="mt-2 flex flex-col justify-center items-start">
              <div class="m-1 w-full flex justify-between items-start">
                <div>
                  <label class="text-white text-lg">Department</label>
                </div>
                <div>
                  <span>
                    <img src="../admin/Images/department.svg" alt="Department"
                      class="w-9 h-9">
                  </span>
                </div>
              </div>
              <div>
                <select
                  name="department_id"
                  required
                  class="px-3 py-2 w-full border-s-4 border-blue-900 text-black rounded-sm cursor-pointer">
                  <option value="" disabled>Select Department</option>
                  <?php
                  $department = $conn->query("SELECT * FROM tbldepartment");
                  while ($row = $department->fetch_assoc()):
                    // Check if this department is selected
                    $selected = ($row['department_id'] == $department_id) ? 'selected' : '';
                  ?>
                    <option value="<?php echo htmlspecialchars($row['department_id']); ?>" <?php echo $selected; ?>>
                      <?php echo htmlspecialchars($row['department_name']); ?>
                    </option>
                  <?php endwhile; ?>
                </select>
              </div>
            </div>
            <!-- If you need to handle sections or other fields, ensure they are correctly implemented -->
          </div>
        </div>
      </div>
      <div class="mt-8">
        <div class="absolute top-5 right-5 cursor-pointer js-close">
          <img
            src="../admin/Images/close.svg"
            alt="close"
            class="w-8 h-8">
        </div>
        <div>
          <button type="submit" name="update_submit"
            class="w-full relative text-center bg-blue-900 hover:bg-blue-500 py-2 mt-3 rounded-md hover:border-s-4 border-white">
            Submit
            <img src="../admin/Images/send.svg" alt="Submit"
              class="w-7 h-7 absolute top-2 left-16">
          </button>
        </div>
        <div>
          <div class="w-full text-center bg-red-900 hover:bg-red-500 relative py-2 mt-3 rounded-md hover:border-s-4 border-white
                              cursor-pointer js-close">Cancel
            <img src="../admin/Images/cancel.svg" alt="Cancel"
              class="w-7 h-7 absolute top-2 left-16">
          </div>
        </div>
      </div>
    </form>
  </div>
</body>
<script>
  const btnModal = document.querySelector('.js-modal');
  const modal = document.querySelector('.modal');
  const btnClose = document.querySelectorAll('.js-close');

  btnModal.addEventListener('click', () => {
    modal.classList.remove('hidden');
  });


  btnClose.forEach((button) => {
    button.addEventListener('click', () => {
      modal.classList.add('hidden'); // Nya diri mawala ang modal
    });
  });


  function previewImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('image-preview');

    if (file) {
      const reader = new FileReader();
      reader.onload = () => {
        preview.src = reader.result;
      };
      reader.readAsDataURL(file);
    } else {
      preview.src = ""; // Clear the preview if no file is selected
    }
  }

  document.addEventListener("DOMContentLoaded", function() {
    const sectionSelect = document.getElementById("section");
    const isRegularCheckbox = document.querySelector('input[name="is_regular"]');

    // Hide the section dropdown if the student is irregular
    function toggleSectionVisibility() {
      if (isRegularCheckbox.checked) {
        sectionSelect.disabled = false; // Enable section selection if regular
      } else {
        sectionSelect.disabled = true; // Disable section selection if irregular
      }
    }

    // Initialize visibility based on the checkbox state
    toggleSectionVisibility();

    // Listen for changes to the checkbox
    isRegularCheckbox.addEventListener('change', toggleSectionVisibility);
  });
</script>

</html>