<?php
require_once __DIR__ . '/../../config/session.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(403);
    header("Location: /kardex_system/src/views/login.php");
}

if (!isset($_SESSION['firstname'], $_SESSION['lastname'], $_SESSION['user_id'])) {
    http_response_code(401);
    header("Location: /kardex_system/src/views/login.html");
}

$department = $_SESSION['deptname'];
$fullName = $_SESSION['lastname'] . ', ' . $_SESSION['firstname'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profile Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="relative h-screen bg-cover bg-center" style="background-image: url('../public/images/bg3.png');">
    <div class="absolute inset-0 bg-white bg-opacity-80 z-0"></div>
    <div class="relative z-10 flex flex-col items-center justify-center min-h-screen px-4 text-center">
        <div class="flex flex-col sm:flex-row items-center bg-white bg-opacity-90 p-8 sm:p-12 rounded-xl shadow-2xl max-w-5xl w-full space-y-6 sm:space-y-0 sm:space-x-12">
            <div class="relative flex flex-col items-center">
                <img src="../public/images/new-nurse-icon.png" alt="Nurse Hat" class="absolute -top-10 w-28 h-20" />
                <img src="../public/images/profile.svg" alt="Profile" class="mt-12 w-36 h-36 object-cover rounded-md border-4 border-white shadow-lg" />
            </div>
            <div class="text-center sm:text-left">
                <h1 class="text-3xl sm:text-4xl font-extrabold"><?= htmlspecialchars($fullName) ?></h1>
                <p class="text-xl font-semibold text-gray-700 mt-2"><?= htmlspecialchars($department) ?></p>
                <div class="mt-8 flex flex-col space-y-4 sm:items-start items-center">
                    <a href="./changePasscode.php"><button class="bg-sky-500 text-white font-semibold px-6 py-3 text-lg rounded-md hover:bg-sky-600 transition w-60">Change Task Passcode</button></a>
                    <a href="./endorsement.php"><button class="bg-sky-500 text-white font-semibold px-8 py-3 text-lg rounded-md hover:bg-sky-600 transition w-60">Endorsement</button></a>
                </div>
            </div>
        </div>
        <div class="absolute bottom-6 sm:bottom-8 right-6 sm:right-10 sm:left-auto left-1/2 sm:translate-x-0 -translate-x-1/2">

            <a href="/kardex_system/src/views/logout.php"><button class="bg-sky-500 text-white px-6 py-2 rounded-md font-bold">Logout</button></a>
        </div>
    </div>
</body>

</html>