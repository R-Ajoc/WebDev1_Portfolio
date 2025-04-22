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
    <title>Kardex Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#5EACDE'
                    }
                }
            }
        }
    </script>
</head>

<body class="h-screen flex bg-gray-100 relative overflow-hidden">
    <!-- Sidebar Backdrop (Mobile Only) -->
    <div id="backdrop" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden transition-opacity duration-300 md:hidden"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="w-64 bg-[#5EACDE] text-white shadow-md p-4 fixed top-0 left-0 h-full z-40 transform -translate-x-full md:translate-x-0 md:static transition-transform duration-300 ease-in-out">
        <!-- Close Button (Mobile Only) -->
        <button id="closeBtn" class="md:hidden mb-4 text-white focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <h1 class="text-xl font-bold mb-6">Admin Panel</h1>
        <nav class="space-y-2">
            <button class="w-full text-left px-4 py-2 rounded hover:bg-white/20" onclick="showSection('nurses')">Manage Nurses</button>
            <button class="w-full text-left px-4 py-2 rounded hover:bg-white/20" onclick="showSection('patients')">Manage Patients</button>
            <button class="w-full text-left px-4 py-2 rounded hover:bg-white/20" onclick="showSection('beds')">Manage Beds</button>
            <button class="w-full text-left px-4 py-2 rounded hover:bg-white/20" onclick="showSection('departments')">Manage Departments</button>
        </nav>
    </aside>

    <!-- Nurse Modal Form -->
    <div id="nurseModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 hidden flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg w-full max-w-2xl shadow-lg relative">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Add New Nurse</h2>
            <img onclick="closeNurseModal()" src="../public/images/return-button.svg" class="absolute hover:scale-[1.04] w-12 h-12 -right-16 top-4 cursor-pointer z-10">
            <form id="nurse-form" class="flex flex-col gap-2" novalidate>
                <!-- Personal Information -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Personal Information</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <input type="text" id="nurse_first-name" name="nurse_firstName" placeholder="First Name" required
                                class="w-full px-4 py-1 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                        </div>
                        <div>
                            <input type="text" id="nurse_middle-initial" name="nurse_middleInitial" placeholder="M.I." maxlength="1"
                                class="w-full px-4 py-1 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 uppercase">
                        </div>
                        <div>
                            <input type="text" id="nurse_last-name" name="nurse_lastName" placeholder="Last Name" required
                                class="w-full px-4 py-1 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                        </div>
                    </div>
                </div>

                <div>
                    <label for="nurse_email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input type="email" id="nurse_email" name="nurse_email" placeholder="Email" required
                        class="mt-1 w-full px-4 py-1 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                <div>
                    <label for="nurse_department" class="block text-sm font-medium text-gray-700">Assigned Department</label>
                    <select id="nurse_department" name="nurse_department" required
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
                    <label for="nurse_contact" class="block text-sm font-medium text-gray-700">Contact Number</label>
                    <input type="tel" id="nurse_contact" name="nurse_contact" placeholder="Phone Number" required
                        class="mt-1 w-full px-4 py-1 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                <div>
                    <label for="nurse_password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="nurse_password" name="nurse_password" placeholder="Password" required
                        class="mt-1 w-full px-4 py-1 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                <div>
                    <label for="nurse_confirm-password" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input type="password" id="nurse_confirm-password" name="nurse_confirm-password" placeholder="Confirm Password" required
                        class="mt-1 w-full px-4 py-1 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 mt-2 rounded-lg transition duration-300"
                    type="submit">
                    Add Nurse
                </button>
            </form>

        </div>
    </div>


    <!-- Main Content -->
    <main class="flex-1 p-6 overflow-auto w-full md:ml-0">
        <section id="nurses" class="hidden">
            <div class="flex items-center justify-between mb-4">
                <button class="hamburger-btn md:hidden text-white bg-primary p-2 rounded focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <h2 class="text-2xl font-semibold text-primary ml-2">Nurses Management</h2>
            </div>

            <div class="flex items-center justify-between mb-4 flex-wrap gap-4">
                <div>
                    <label class="block mb-1 text-sm text-gray-700">Filter by Department:</label>
                    <select class="p-2 border rounded w-48">
                        <option>All</option>
                        <?php foreach ($departments as $dept): ?>
                            <option value="<?= htmlspecialchars($dept['department_id']) ?>">
                                <?= htmlspecialchars($dept['deptname']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button onclick="openNurseModal()" id="new-nurse" class="flex items-center text-black font-semibold">
                    <div class="w-8 h-8 bg-sky-500 rounded-full flex items-center justify-center mr-2 text-white text-xl">+</div>
                    New Nurse
                </button>
            </div>
            <div class="h-[550px] overflow-y-auto shadow-xl rounded">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-primary text-white sticky top-0 z-10">
                        <tr>
                            <th class="px-2 py-4 uppercase text-center">#</th>
                            <th class="px-4 py-4 uppercase">Full Name</th>
                            <th class="px-4 py-4 uppercase">Email</th>
                            <th class="px-4 py-4 uppercase">Assigned Department</th>
                            <th class="px-4 py-4 uppercase">Contact</th>
                            <th class="px-4 py-4 uppercase">Created At</th>
                            <th class="px-4 py-4 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="nurseTableBody" class="bg-white text-gray-700">
                        <!-- Dynamic rows will go here -->
                    </tbody>
                </table>
            </div>
        </section>


        <section id="patients" class="hidden">
            <div class="flex items-center justify-between mb-4">
                <button class="hamburger-btn md:hidden text-white bg-primary p-2 rounded focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <h2 class="text-2xl font-semibold text-primary ml-2">Patients Managment</h2>
            </div>

            <div class="flex items-center justify-between mb-4 flex-wrap gap-4">
                <!-- Filter Dropdown -->
                <div>
                    <label class="block mb-1 text-sm text-gray-700">Filter by Department:</label>
                    <select class="p-2 border rounded w-48">
                        <option>All</option>
                        <?php foreach ($departments as $dept): ?>
                            <option value="<?= htmlspecialchars($dept['department_id']) ?>">
                                <?= htmlspecialchars($dept['deptname']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- New Patient Button -->
                <button id="new-patient" class="flex items-center text-black font-semibold">
                    <div class="w-8 h-8 bg-sky-500 rounded-full flex items-center justify-center mr-2 text-white text-xl">+</div>
                    New Patient
                </button>
            </div>
            <div class="h-[550px] overflow-y-auto shadow-xl rounded">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-primary text-white sticky top-0 z-10">
                        <tr>
                            <th class="px-2 py-4 uppercase text-center">#</th>
                            <th class="px-4 py-4 uppercase">Full Name</th>
                            <th class="px-4 py-4 uppercase">Gender</th>
                            <th class="px-4 py-4 uppercase">Physician</th>
                            <th class="px-4 py-4 uppercase">Bed Assigned</th>
                            <th class="px-4 py-4 uppercase">Admission Date/Time</th>
                            <th class="px-4 py-4 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="patientBody" class="bg-white">
                        <!-- Dynamic rows go here -->
                    </tbody>
                </table>
            </div>


        </section>


        <section id="beds" class="hidden">
            <div class="flex items-center justify-between mb-4">
                <button class="hamburger-btn md:hidden text-white bg-primary p-2 rounded focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <h2 class="text-2xl font-semibold text-primary ml-2">Beds Management</h2>
            </div>

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                <div>
                    <label class="block mb-1 text-sm text-gray-700">Filter by Department:</label>
                    <select id="bedDepartmentFilter" class="p-2 border rounded w-48">
                        <option>All</option>
                        <?php foreach ($departments as $dept): ?>
                            <option value="<?= htmlspecialchars($dept['department_id']) ?>">
                                <?= htmlspecialchars($dept['deptname']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button onclick="openBedModal()"
                    class="flex items-center text-black font-semibold">
                    <div class="w-8 h-8 bg-sky-500 rounded-full flex items-center justify-center mr-2 text-white text-xl">+</div>
                    New Bed
                </button>
            </div>

            <div class="h-[550px] overflow-y-auto shadow-xl rounded">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-primary text-white sticky top-0 z-10">
                        <tr>
                            <th class="px-4 py-4 uppercase">Bed Number</th>
                            <th class="px-4 py-4 uppercase">Department</th>
                            <th class="px-4 py-4 uppercase">Status</th>
                            <th class="px-4 py-4 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="bedTableBody" class="bg-white text-gray-700">
                        <!-- Dynamic bed rows go here -->
                    </tbody>
                </table>
            </div>
        </section>


        <section id="departments" class="hidden">
            <div class="flex items-center justify-between mb-4">
                <button class="hamburger-btn md:hidden text-white bg-primary p-2 rounded focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <h2 class="text-2xl font-semibold text-primary ml-2">Departments Managment</h2>
            </div>

            <form id="department-form" class="mb-4 flex justify-between items-center">
                <input type="text" id="department-name" placeholder="Department Name"
                    class="p-2 border rounded mr-2 w-full max-w-xs" />
                <button onclick=""
                    class="flex items-center text-black font-semibold">
                    <div class="w-8 h-8 bg-sky-500 rounded-full flex items-center justify-center mr-2 text-white text-xl">+</div>
                    New Department
                </button>
            </form>

            <div class="h-[600px] overflow-y-auto shadow-xl rounded">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-primary text-white sticky top-0 z-10">
                        <tr>
                            <th class="px-2 py-4 uppercase text-center">#</th>
                            <th class="px-4 py-4 uppercase">Department Name</th>
                            <th class="px-4 py-4 uppercase text-center">No. of Beds</th>
                            <th class="px-4 py-4 uppercase text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="department-table-body" class="bg-white">
                        <!-- Dynamic rows go here -->
                    </tbody>
                </table>
            </div>
        </section>



        <!-- Modal Background -->
        <div id="bedModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-sm">
                <h3 class="text-lg font-semibold mb-4 text-primary">Add New Bed</h3>
                <form id="bedForm" novalidate>
                    <div class="mb-3">
                        <label class="block mb-2 text-sm">Bed Number</label>
                        <input type="number" id="bedNumberInput" class="w-full p-2  border rounded" required>
                    </div>

                    <div class="mb-3">
                        <label class="block mb-2 text-sm">Department</label>
                        <select id="departmentSelect" class="w-full p-2 border rounded" required>
                            <option value="">Select Department</option>
                            <?php foreach ($departments as $dept): ?>
                                <option value="<?= htmlspecialchars($dept['department_id']) ?>">
                                    <?= htmlspecialchars($dept['deptname']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeBedModal()" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded hover:bg-blue-600">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div id="newPatientPopup" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden ">
            <div class="bg-white p-6 rounded-2xl shadow-lg w-full max-w-md sm:max-w-lg md:max-w-xl lg:max-w-2xl relative">

                <!-- Close Button -->
                <button id="close-patientForm" class="absolute top-3 right-3 text-xl font-bold text-gray-600 hover:text-gray-800">×</button>

                <h2 class="text-center text-sky-600 font-semibold text-sm mb-4">NEW PATIENT</h2>

                <form id="patientForm" class="space-y-3" novalidate>
                    <div>
                        <label class="block text-xs font-bold text-black mb-1">PERSONAL INFORMATION:</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <input type="text" id="first-name" name="firstName" placeholder="First Name" required
                                    class="w-full border px-2 py-1 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                            </div>
                            <div>
                                <input type="text" id="middle-initial" name="middleInitial" placeholder="M.I." maxlength="1"
                                    class="w-full border px-2 py-1 rounded uppercase focus:outline-none focus:ring-2 focus:ring-blue-400">
                            </div>
                            <div>
                                <input type="text" id="last-name" name="lastName" placeholder="Last Name" required
                                    class="w-full border px-2 py-1 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="bed" class="block text-xs font-bold text-black">BED #:</label>
                        <select id="bed" class="w-full border px-2 py-1 rounded" required>

                        </select>
                    </div>

                    <div>
                        <label for="dob" class="block text-xs font-bold text-black">DATE OF BIRTH:</label>
                        <input id="dob" type="date" class="w-full border px-2 py-1 rounded" required />
                    </div>
                    <div>
                        <label for="gender" class="block text-xs font-bold text-black">GENDER:</label>
                        <select id="gender" class="w-full border px-2 py-1 rounded" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Non-binary">Non-binary</option>
                            <option value="Prefer not to say">Prefer not to say</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label for="status" class="block text-xs font-bold text-black">STATUS:</label>
                        <select id="status" class="w-full border px-2 py-1 rounded" required>
                            <option value="">Select Civil Status</option>
                            <option value="Single">Single</option>
                            <option value="Married">Married</option>
                            <option value="Widowed">Widowed</option>
                            <option value="Divorced">Divorced</option>
                            <option value="Separated">Separated</option>
                            <option value="Annulled">Annulled</option>
                            <option value="Prefer not to say">Prefer not to say</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label for="nationality" class="block text-xs font-bold text-black">NATIONALITY:</label>
                        <input id="nationality" type="text" class="w-full border px-2 py-1 rounded" placeholder="e.g. Filipino, American" />
                    </div>
                    <div>
                        <label for="religion" class="block text-xs font-bold text-black">RELIGION:</label>
                        <input id="religion" type="text" class="w-full border px-2 py-1 rounded" placeholder="e.g. Catholic, Islam" />
                    </div>
                    <div>
                        <label for="physician" class="block text-xs font-bold text-black">PHYSICIAN:</label>
                        <input id="physician" type="text" class="w-full border px-2 py-1 rounded" />
                    </div>
                    <div>
                        <label for="diagnosis" class="block text-xs font-bold text-black">DIAGNOSIS:</label>
                        <input id="diagnosis" type="text" class="w-full border px-2 py-1 rounded" required />
                    </div>

                    <button type="submit" class="mt-3 w-full bg-sky-500 text-white py-2 rounded hover:bg-sky-600 transition">
                        Save
                    </button>
                </form>


            </div>
        </div>
        <!-- Edit Profile Popup -->
        <div id="editProfileModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-lg p-6 w-full max-w-xl space-y-4 relative">
                <h2 class="text-xl font-bold mb-4">Edit Patient Profile</h2>
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <label class="w-48 font-semibold">BED #:</label>
                        <input type="text" id="editBedNumber" class="flex-1 border px-2 py-1 rounded-md ">
                        <button onclick="enableField(this)" class="text-blue-600">✏️</button>
                    </div>

                    <div class="flex items-center gap-2">
                        <label class="w-48 font-semibold">Gender:</label>
                        <select id="editGender" class="flex-1 border px-2 py-1 rounded-md ">
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Non-binary">Non-binary</option>
                            <option value="Prefer not to say">Prefer not to say</option>
                            <option value="Other">Other</option>
                        </select>
                        <button onclick="enableField(this)" class="text-blue-600">✏️</button>
                    </div>

                    <div class="flex items-center gap-2">
                        <label class="w-48 font-semibold">Status:</label>
                        <select id="editStatus" class="flex-1 border px-2 py-1 rounded-md disabled:bg-gray-100">
                            <option value="">Select Civil Status</option>
                            <option value="Single">Single</option>
                            <option value="Married">Married</option>
                            <option value="Widowed">Widowed</option>
                            <option value="Divorced">Divorced</option>
                            <option value="Separated">Separated</option>
                            <option value="Annulled">Annulled</option>
                            <option value="Prefer not to say">Prefer not to say</option>
                            <option value="Other">Other</option>
                        </select>
                        <button onclick="enableField(this)" class="text-blue-600">✏️</button>
                    </div>

                    <div class="flex items-center gap-2">
                        <label class="w-48 font-semibold">Date of Birth:</label>
                        <input type="date" id="editDOB" class="flex-1 border px-2 py-1 rounded-md disabled:bg-gray-100">
                        <button onclick="enableField(this)" class="text-blue-600">✏️</button>
                    </div>

                    <div class="flex items-center gap-2">
                        <label class="w-48 font-semibold">Nationality:</label>
                        <input type="text" id="editNationality" class="flex-1 border px-2 py-1 rounded-md disabled:bg-gray-100">
                        <button onclick="enableField(this)" class="text-blue-600">✏️</button>
                    </div>

                    <div class="flex items-center gap-2">
                        <label class="w-48 font-semibold">Religion:</label>
                        <input type="text" id="editReligion" class="flex-1 border px-2 py-1 rounded-md disabled:bg-gray-100">
                        <button onclick="enableField(this)" class="text-blue-600">✏️</button>
                    </div>

                    <div class="flex items-center gap-2">
                        <label class="w-48 font-semibold">Physician:</label>
                        <input type="text" id="editPhysician" class="flex-1 border px-2 py-1 rounded-md disabled:bg-gray-100">
                        <button onclick="enableField(this)" class="text-blue-600">✏️</button>
                    </div>

                    <div class="flex items-center gap-2">
                        <label class="w-48 font-semibold">Diagnosis:</label>
                        <input type="text" id="editDiagnosis" class="flex-1 border px-2 py-1 rounded-md disabled:bg-gray-100">
                        <button onclick="enableField(this)" class="text-blue-600">✏️</button>
                    </div>
                    <div class="flex justify-end space-x-3 pt-4">
                        <button onclick="closeEditProfileModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-4 py-2 rounded">
                            Cancel
                        </button>
                        <button onclick="saveEditedProfile()" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">
                            Okay
                        </button>
                    </div>
                </div>

            </div>
            <script>
                function enableField(button) {
                    const input = button.previousElementSibling;
                    input.disabled = false;
                    input.classList.remove('bg-gray-100');
                }
            </script>

    </main>


    <!-- Scripts -->
    <script src="/kardex_system/src/public/js/validationUtils.js"></script>
    <script src="/kardex_system/src/public/js/newPatientHandler.js"></script>
    <script src="../public/js/adminPanelJs/managePatients.js"></script>
    <script src="../public/js/adminPanelJs/manageNurses.js"></script>
    <script src="../public/js/adminPanelJs/manageBeds.js"></script>
    <script src="../public/js/adminPanelJs/manageDepartments.js"></script>
    <script src="../public/js/adminPanelJs/adminUtils.js"></script>
</body>


</html>