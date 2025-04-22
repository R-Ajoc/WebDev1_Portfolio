<?php
require_once __DIR__ . '/../../config/session.php';


if (!isset($_SESSION['firstname'], $_SESSION['lastname'], $_SESSION['user_id'])) {
  http_response_code(401);
  header("Location: /kardex_system/src/views/login.html");
}

$fullName = $_SESSION['lastname'] . ', ' . $_SESSION['firstname'];
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Change Task Passcode</title>
  <script src="https://cdn.tailwindcss.com"></script>

  <script src="../public/jS/passcode.js" defer></script>
  <script src="../public/jS/changePasscodeHandler.js" defer></script>

  <style>
    @keyframes fadeScaleIn {
      0% {
        opacity: 0;
        transform: scale(0.9);
      }

      100% {
        opacity: 1;
        transform: scale(1);
      }
    }

    .animate-fadeScaleIn {
      animation: fadeScaleIn 0.3s ease-out forwards;
    }
  </style>

</head>


<body data-user-id="<?php echo $_SESSION['user_id']; ?>" class="relative min-h-screen bg-cover bg-center bg-no-repeat" style="background-image: url('../public/images/bg3.png');">
  <!-- Overlay -->
  <div class="fixed inset-0 bg-white bg-opacity-80 z-0 pointer-events-none"></div>

  <!-- Main Container -->
  <div class="relative z-10 flex flex-col justify-center items-center min-h-screen px-4 animate-fadeScaleIn">

    <!-- Flex row layout -->
    <div class="flex flex-col sm:flex-row p-8 sm:p-12 space-y-10 sm:space-y-0 sm:space-x-12 w-full max-w-5xl ml-[-50px]">

      <!-- LEFT SIDE: Profile and Nurse Hat -->
      <div class="relative flex flex-col items-center w-full sm:w-auto flex-shrink-0">
        <img src="../public/images/new-nurse-icon.png" alt="Nurse Hat" class="absolute -top-6 w-60 h-auto max-w-full" />
        <img src="../public/images/profile.png" alt="Profile" class="mt-20 w-40 h-auto object-cover rounded-md border-4 border-white shadow-lg" />
      </div>

      <!-- RIGHT SIDE: Name, Department, and Passcode Box -->
      <div class="flex flex-col items-center sm:items-start w-full space-y-6">
        <div class="text-center sm:text-left ">
          <h1 class="text-5xl sm:text-5xl font-extrabold"><?= htmlspecialchars($fullName) ?></h1>
        </div>

        <div id="passcodeContainer" class="relative bg-[#97b5c4] p-6 rounded-xl shadow-xl w-[500px] space-y-6 animate-fadeScaleIn hidden">

        </div>


      </div>
    </div>


    <div id="errorModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex justify-center items-center">
      <div class="bg-white p-8 rounded-xl text-center shadow-lg w-[90%] max-w-md animate-fadeScaleIn border-4 border-red-500 ">
        <div class="flex justify-center mb-4">
          <img src="error-icon.png" alt="Error Icon" class="w-12 h-12" />
        </div>
        <h2 class="text-lg sm:text-3xl font-semibold text-red-600">Passcode is incorrect, please try again.</h2>
        <button
          onclick="closeErrorModal()"
          class="mt-6 px-6 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 hover:shadow-xl transform hover:scale-105 transition duration-300">OK</button>
      </div>
    </div>

    <div id="successModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex justify-center items-center">
      <div class="bg-white p-8 rounded-xl text-center shadow-lg w-[90%] max-w-md animate-fadeScaleIn border-4 border-green-500">
        <div class="flex justify-center mb-4">
          <img src="success-icon.png" alt="Success Icon" class="w-13 h-12" />
        </div>
        <h2 class="text-lg sm:text-3xl font-semibold text-green-600">Changes saved Succesfully!</h2>
        <button
          onclick="closeSuccessModel()"
          class="mt-6 px-6 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 hover:shadow-xl transform hover:scale-105 transition duration-300 ">OK</button>
      </div>
    </div>

</body>

</html>