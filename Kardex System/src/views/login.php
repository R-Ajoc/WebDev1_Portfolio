<?php
require_once __DIR__ . '/../../config/database.php'; // your PDO connection
require_once __DIR__ . '/../models/Department.php';

$departmentModel = new Department($pdo);
$departments = $departmentModel->getAllDepartments();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>USPF Hospital Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex flex-col items-center justify-center bg-cover bg-center relative" style="background-image: url('../public/images/bg2.png');">

  <!-- Background Overlay -->
  <div class="absolute top-0 left-0 w-full h-full bg-white/30 z-0"></div>

  <!-- Page Content Wrapper -->
  <div class="z-10 flex flex-col items-center w-full px-4 relative">

    <!-- Title -->
    <div class="mt-12">
      <h1 class="text-4xl sm:text-5xl md:text-5xl lg:text-6xl font-bold font-serif text-[#154161] text-center">
        Welcome, USPF Hospital!
      </h1>
    </div>

    <!-- Login Form -->
    <div class="bg-white shadow-xl rounded-md px-8 py-8 w-full max-w-md mt-12 relative">
      <a href="mainMenu.html"><img src="../public/images/return-button.svg" class="absolute hover:scale-[1.04] w-12 h-12 -right-16 top-4 cursor-pointer z-10"></a>

      <form id="login-form" class="flex flex-col gap-8" novalidate>

        <!-- Title -->
        <div class="text-center pb-2">
          <label class="text-4xl font-bold text-gray-800">LOGIN</label>
        </div>

        <div>
          <label class="block text-sm font-bold mb-1 text-gray-700" for="email">E-MAIL:</label>
          <input type="email" id="email" name="email" required class="w-full px-3 py-2 border border-black rounded focus:outline-none focus:ring-2 focus:ring-blue-400" />
        </div>

        <div>
          <label class="block text-sm font-bold mb-1 text-gray-700" for="password">PASSWORD:</label>
          <input type="password" id="password" name="password" required class="w-full px-3 py-2 border border-black rounded focus:outline-none focus:ring-2 focus:ring-blue-400" />
        </div>

        <button type="submit" class="w-full bg-blue-500 hover:bg-blue-400 text-white mt-4 font-bold py-2 rounded transition">Log-In</button>
      </form>

    </div>

  </div>
  <script src="../public/js/validationUtils.js"></script>
  <script src="../public/js/loginHandler.js"></script>
</body>

</html>