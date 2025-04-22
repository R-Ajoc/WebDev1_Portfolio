<?php
require_once __DIR__ . '/../../config/database.php'; // your PDO connection
require_once __DIR__ . '/../models/Department.php';

$departmentModel = new Department($pdo);
$departments = $departmentModel->getAllDepartments();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Add Nurse</title>

</head>

<body class="min-h-screen bg-cover bg-center flex items-center justify-center relative" style="background-image: url('../public/images/bg2.png');">

  <!-- Overlay Background -->
  <div class="absolute top-0 left-0 w-full h-full bg-white/30 z-0"></div>

  <!-- Foreground Content -->
  <div class="relative z-10 w-full flex flex-col items-center justify-center px-4">

    <!-- Add Nurse Form -->
    <div class="relative bg-white bg-opacity-90 p-8 rounded-2xl shadow-xl max-w-2xl w-full">
      <a href="mainMenu.html">
        <img src="../public/images/return-button.svg" class="absolute hover:scale-[1.04] w-12 h-12 -right-16 top-4 cursor-pointer z-10">
      </a>
      <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Add New Nurse</h2>

      <form id="nurse-form" class="flex flex-col gap-2" novalidate>
        <!-- Personal Information -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Personal Information</label>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <input type="text" id="first-name" name="firstName" placeholder="First Name" required
                class="w-full px-4 py-1 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
              <input type="text" id="middle-initial" name="middleInitial" placeholder="M.I." maxlength="1"
                class="w-full px-4 py-1 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 uppercase">
            </div>
            <div>
              <input type="text" id="last-name" name="lastName" placeholder="Last Name" required
                class="w-full px-4 py-1 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
          </div>
        </div>

        <div>
          <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
          <input type="email" id="email" name="email" placeholder="Email" required
            class="mt-1 w-full px-4 py-1 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>

        <div>
          <label for="department" class="block text-sm font-medium text-gray-700">Assigned Department</label>
          <select id="department" name="department" required
            class="mt-1 w-full px-4 py-1 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            <option value="">Select Department</option>
            <?php foreach ($departments as $dept): ?>
              <option value="<?= htmlspecialchars($dept['department_id']) ?>">
                <?= htmlspecialchars($dept['deptname']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label for="contact" class="block text-sm font-medium text-gray-700">Contact Number</label>
          <input type="tel" id="contact" name="contact" placeholder="Phone Number" required
            class="mt-1 w-full px-4 py-1 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
          <input type="password" id="password" name="password" placeholder="Password" required
            class="mt-1 w-full px-4 py-1 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>

        <div>
          <label for="confirm-password" class="block text-sm font-medium text-gray-700">Confirm Password</label>
          <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm Password" required
            class="mt-1 w-full px-4 py-1 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>

        <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 mt-2  rounded-lg transition duration-300"
          type="submit">
          Add Nurse
        </button>
      </form>
    </div>

    <!-- Passcode Popup -->
    <div id="passcodePopup" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-20">
      <div class="bg-white p-6 rounded-xl shadow-lg max-w-sm w-full text-center space-y-4">
        <h3 class="text-xl font-semibold">Enter 4-Digit Passcode</h3>
        <p class="text-sm text-gray-600">This passcode will be used for verification when making future changes.</p>

        <input type="password" id="passcode" name="passcode" placeholder="Enter 4-digit passcode"
          maxlength="4"
          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 text-center">

        <input type="password" id="confirm-passcode" name="confirm-passcode" placeholder="Confirm passcode"
          maxlength="4"
          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 text-center">

        <div id="passcode-helper" class="text-gray-500 text-sm">Passcode must be exactly 4 digits and match.</div>

        <div class="flex justify-center gap-4 mt-4">
          <button id="submit-passcode" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            Submit
          </button>
          <button id="close-popup" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg">
            Cancel
          </button>
        </div>
      </div>
    </div>

  </div>

  <script src="../public/js/validationUtils.js"></script>
  <script src="../public/js/registerHandler.js"></script>
  <script src="https://cdn.tailwindcss.com"></script>
</body>

</html>