<?php
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/database.php'; // your PDO connection
require_once __DIR__ . '/../models/Department.php';

if (!isset($_SESSION['firstname'], $_SESSION['lastname'], $_SESSION['user_id'])) {
    http_response_code(401);
    header("Location: /kardex_system/src/views/login.php");
}


$departmentModel = new Department($pdo);
$departments = $departmentModel->getAllDepartments();

$fullName = $_SESSION['lastname'] . ', ' . $_SESSION['firstname'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Patient Detail View</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    spacing: {
                        'sidebar': '350px'
                    },
                    zIndex: {
                        'overlay': '40'
                    }
                }
            }
        }
    </script>
</head>
<script>
    const user_id = <?php echo json_encode($_SESSION['user_id']); ?>;
</script>

<body class="h-screen relative overflow-hidden">
    <!-- BACKDROP -->
    <div id="passcodeBackdrop" class="fixed inset-0 bg-black bg-opacity-50 z-[59] hidden"></div>
    <!-- PASSCODE POPUP -->
    <div id="passcodeContainer" class="fixed inset-0 flex items-center justify-center z-[60] hidden"></div>


    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-overlay" onclick="closeSidebar()"></div>
    <div class="flex h-full">
        <!-- Sidebar -->
        <aside id="sidebar" class="bg-sky-700 text-white w-sidebar p-4 space-y-4 hidden md:block fixed md:static z-50 h-full md:h-auto transition-transform transform md:translate-x-0 -translate-x-full">

            <div class="flex justify-between items-center">
                <div class="flex justify-between items-center w-full">
                    <h2 id="displayName" class="text-3xl font-bold"></h2>
                    <img id="editPatientProfile" class="w-12 h-12 p-2 border-round border border-2 border-white rounded-[15px] hover:scale-[1.02] cursor-pointer" src="/kardex_system/src/public/images/edit-profile.svg">
                </div>
                <button class="md:hidden text-white text-2xl font-bold" onclick="closeSidebar()">&times;</button>
            </div>
            <div class="mt-4">
                <p><strong>BED #:</strong></p>
                <p><strong>Age:</strong></p>
                <p><strong>Sex:</strong></p>
                <p><strong>Status:</strong></p>
                <p><strong>Date of Birth:</strong></p>
                <p><strong>Nationality:</strong></p>
                <p><strong>Religion:</strong></p>
                <p><strong>Date of Admission:</strong></p>
                <p><strong>Time of Admission:</strong></p>
                <p><strong>Physician:</strong></p>
                <p><strong>Diagnosis:</strong></p>
            </div>
            <a href="endorsement.php"><button class="mt-8 bg-white text-sky-700 font-bold px-4 py-2 rounded-md">Back</button></a>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col overflow-auto w-full">
            <!-- Top Bar -->
            <header class="bg-sky-500 text-white p-4 flex justify-between items-center sticky top-0 shadow-md z-20">
                <div class="flex items-center">
                    <button class="md:hidden text-white mr-4" onclick="toggleSidebar()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <span class="font-bold text-lg">🔔 A note has been assigned to you</span>
                </div>
                <a href="/kardex_system/src/views/logout.php">
                    <button class="bg-white text-sky-500 px-4 py-1 rounded-md font-bold">Logout</button>
                </a>

            </header>

            <!-- Scrollable Main Content -->
            <div id="mainContent" class="grid grid-cols-4 gap-0 mt-3 border border-black text-sm w-full max-w-4xl mx-auto [grid-auto-rows:minmax(0,auto)]">
                <!-- TREATMENTS + REFERRALS WRAPPED TOGETHER -->
                <div class="col-span-1 flex flex-col border border-black" style="height: 800px;">
                    <!-- TREATMENTS (Top - 550px) -->
                    <div class="h-[550px] border-b border-black overflow-y-auto">
                        <div class="border-b-2 p-1 border-black sticky top-0 bg-white flex justify-between items-center">
                            <span class="font-bold">TREATMENTS</span>
                            <img id="addTreatmentRow" class="w-6 h-6 hover:scale-[1.05] cursor-pointer transition duration-200 ease-in-out"
                                src="../public/images/edit.svg" alt="edit-treatments">
                        </div>
                        <div id="treatmentDetails" class="flex flex-col gap-2 px-[5px] max-w-md bg-white ">
                            <div class="flex flex-col gap-1 w-full max-w-md bg-white ">
                                <div class="flex items-end w-full">
                                    <label for="vs_value" class="w-[20%] font-medium">V/S:</label>
                                    <input id="vs_value" type="text" class="flex-1 w-[50%] border-b border-black focus:outline-none" />
                                </div>

                                <div class="flex items-end w-full">
                                    <label for="io_value" class="w-[20%] font-medium">I & O:</label>
                                    <input id="io_value" type="text" class="flex-1 w-[50%] border-b border-black focus:outline-none" />
                                </div>

                                <div class="flex items-end w-full">
                                    <label for="nvs_value" class="w-[20%] font-medium">NVS:</label>
                                    <input id="nvs_value" type="text" class="flex-1 w-[50%] border-b border-black focus:outline-none" />
                                </div>

                                <div class="flex items-end w-full">
                                    <label for="position_value" class="w-[40%] font-medium">POSITION:</label>
                                    <input id="position_value" type="text" class="flex-1 w-[60%] border-b border-black focus:outline-none" />
                                </div>

                                <div class="h-[100%] px-[5px] flex flex-col">

                                    <textarea id="treatmentOhters"
                                        class="w-full mt-2 py-2 text-sm h-[370px] resize-none overflow-y-auto overflow-x-hidden rounded focus:outline-none focus:ring-0 focus:border-transparent">
    </textarea>
                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- REFERRALS (Bottom - 250px) -->
                    <div class="h-[250px] border-b border-black">
                        <div class="border-b-2 p-1 border-black sticky top-0 bg-white flex justify-between items-center">
                            <span class="font-bold">REFERRALS</span>
                            <img id="openReferrals"
                                class="w-6 h-6 hover:scale-[1.05] cursor-pointer transition duration-200 ease-in-out"
                                src="../public/images/edit.svg"
                                alt="edit-referrals">
                        </div>

                        <!-- Vertical layout: each field 50% -->
                        <div class="p-2 h-[calc(100%-2rem)] flex flex-col justify-between space-y-2">
                            <!-- DEPT Dropdown - takes 50% -->
                            <div class="h-[30%] flex flex-col">
                                <label for="deptSelect" class="text-sm font-medium">DEPT:</label>
                                <select id="deptSelect"
                                    class="w-full mt-1 py-2 text-sm h-full rounded focus:outline-none focus:ring-0 focus:border-transparent">
                                    <option value="">Select Department</option>
                                    <?php foreach ($departments as $dept): ?>
                                        <option value="<?= htmlspecialchars($dept['department_id']) ?>">
                                            <?= htmlspecialchars($dept['deptname']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- RE Textarea - takes 50% -->
                            <div class="h-[70%] flex flex-col">
                                <label for="reason" class="text-sm font-medium">RE:</label>
                                <textarea id="reason"
                                    class="w-full mt-1 p-2 text-sm h-full resize-none overflow-y-auto overflow-x-hidden  rounded focus:outline-none focus:ring-0 focus:border-transparent"></textarea>
                            </div>
                        </div>
                    </div>


                </div>
                <script>
                    function lockPrefix(textarea, prefix) {
                        if (!textarea.value.startsWith(prefix)) {
                            textarea.value = prefix;
                        }
                    }
                </script>





                <!-- ATTACHMENTS Combined Block -->
                <div class="col-span-1 border-b border-black h-[800px] flex flex-col">
                    <!-- Top section (200px) -->
                    <div class="h-[200px] border-b border-t border-black  overflow-auto">
                        <div class="border-b-2 p-1 bg-white z-10 border-black sticky top-0 bg-white flex justify-between items-center">
                            <span class="font-bold ">ATTACHMENTS</span>
                            <img id="addAttachmentTopRow" class="w-6 h-6 hover:scale-[1.05] cursor-pointer transition duration-200 ease-in-out"
                                src="../public/images/edit.svg" alt="edit-treatments">
                        </div>
                        <div id="attachmentTopDetails" class="text-justify flex justify-center flex-col items-center w-full overflow-auto">
                            <!-- Content goes here -->
                            <div class="flex gap-1 flex-col w-full max-w-md bg-white ">
                                <div class="flex  gap-[5px] px-[5px] items-center justify-start w-full">
                                    <input type="checkbox" id="o2-checkbox" />
                                    <label for="o2-checkbox" class="w-[15%] font-medium">O2:</label>
                                    <input type="text" id="o2-value" class="w-[20%] text-[12px] border-b border-black focus:outline-none" />
                                    <div class="w-[50%] flex justify-center items-center">
                                        <label for="o2-lpm" class="w-auto font-medium">L/MIN</label>
                                        <input type="text" id="o2-lpm" class="w-[100%] text-[12px] border-b border-black focus:outline-none" />
                                    </div>
                                </div>

                                <div class="flex  gap-[5px] px-[5px] items-center justify-start w-full">
                                    <input type="checkbox" id="ngt-checkbox" />
                                    <label for="ngt-checkbox" class="w-[15%] font-medium">NGT:</label>
                                    <input type="text" id="ngt-value" class="w-[20%] text-[12px] border-b border-black focus:outline-none" />
                                </div>

                                <div class="flex  gap-[5px] px-[5px] items-center justify-start w-full">
                                    <input type="checkbox" id="ogt-checkbox" />
                                    <label for="ogt-checkbox" class="w-[15%] font-medium">OGT:</label>
                                    <input type="text" id="ogt-value" class="w-[20%] text-[12px] border-b border-black focus:outline-none" />
                                </div>

                                <div class="flex  gap-[5px] px-[5px] items-center justify-start w-full">
                                    <input type="checkbox" id="fbc-checkbox" />
                                    <label for="fbc-checkbox" class="w-[15%] font-medium">FBC:</label>
                                    <input type="text" id="fbc-value" class="w-[20%] text-[12px] border-b border-black focus:outline-none" />
                                </div>

                                <div class="flex  gap-[5px] px-[5px] items-center justify-start w-full">
                                    <input type="checkbox" id="ctt-checkbox" />
                                    <label for="ctt-checkbox" class="w-[15%] font-medium">CTT:</label>
                                    <input type="text" id="ctt-value" class="w-[20%] text-[12px] border-b border-black focus:outline-none" />
                                </div>

                            </div>
                        </div>
                    </div>


                    <!-- Bottom section (600px) -->
                    <div class="h-[600px] border-t border-b border-black overflow-y-auto">
                        <div class="border-b-2 p-1 border-black sticky top-0 bg-white flex justify-end items-center">
                            <img id="addAttachmentBottomRow" class="w-6 h-6 hover:scale-[1.05] cursor-pointer transition duration-200 ease-in-out"
                                src="../public/images/edit.svg" alt="edit-treatments">
                        </div>
                        <div id="attachmentBottomDetails" class="text-justify flex justify-center flex-col items-center">
                            <div class="flex gap-2 flex-col w-full max-w-md bg-white ">
                                <div class="flex gap-[5px] px-[5px] items-center justify-start w-full">
                                    <input type="checkbox" id="ett_size_check" />
                                    <label for="ett_size_check" class="block w-auto font-medium">ETT SIZE:</label>
                                    <input id="ett_size_value" type="text" class="w-[15%] text-[12px] border-b border-black focus:outline-none" />
                                    <div class="w-[40%] flex justify-center items-center">
                                        <label for="ett_level_value" class="w-auto font-medium">LEVEL:</label>
                                        <input id="ett_level_value" type="text" class="w-[100%] text-[12px] border-b border-black focus:outline-none" />
                                    </div>
                                </div>

                                <div class="flex gap-[5px] px-[5px] items-start justify-start w-full">
                                    <input type="checkbox" id="tracheostomy_attached_check" />
                                    <label for="tracheostomy_attached_check" class="w-auto p-0 font-medium">TRACHEOSTOMY<br>ATTACHED TO:</label>
                                </div>

                                <div class="flex gap-[5px] px-[5px] items-center justify-start w-full">
                                    <input type="checkbox" id="ppv_check" />
                                    <label for="ppv_check" class="w-[15%] font-medium">PPV:</label>
                                    <input id="ppv_value" type="text" class="w-[20%] text-[12px] border-b border-black focus:outline-none" />
                                    <div class="w-[50%] flex justify-center items-center">
                                        <label for="ppv_lmin_value" class="w-auto font-medium">L/MIN</label>
                                        <input id="ppv_lmin_value" type="text" class="w-[100%] text-[12px] border-b border-black focus:outline-none" />
                                    </div>
                                </div>

                                <div class="flex gap-[5px] px-[5px] items-center justify-start w-full">
                                    <input type="checkbox" id="mechanical_ventilator" />
                                    <label for="mechanical_ventilator" class="w-auto font-medium">MECHANICAL VENTILATOR:</label>
                                </div>

                                <div class="flex gap-[5px] px-[5px] items-center justify-start w-full">
                                    <input type="checkbox" id="mode_check" />
                                    <label for="mode_check" class="w-[15%] font-medium">MODE:</label>
                                    <input id="mode_value" type="text" class="w-[20%] text-[12px] border-b border-black focus:outline-none" />
                                    <div class="w-[50%] flex justify-center items-center">
                                        <label for="fio2_value" class="w-auto font-medium">FIO2:</label>
                                        <input id="fio2_value" type="text" class="w-[100%] text-[12px] border-b border-black focus:outline-none" />
                                    </div>
                                </div>

                                <div class="flex gap-[5px] px-[5px] items-center justify-start w-full">
                                    <input type="checkbox" id="bur_check" />
                                    <label for="bur_check" class="w-[15%] font-medium">BUR:</label>
                                    <input id="bur_value" type="text" class="w-[20%] text-[12px] border-b border-black focus:outline-none" />
                                    <div class="w-[50%] flex justify-center items-center">
                                        <label for="pfr_value" class="w-auto font-medium">PFR:</label>
                                        <input id="pfr_value" type="text" class="w-[100%] text-[12px] border-b border-black focus:outline-none" />
                                    </div>
                                </div>

                                <div class="flex gap-[5px] px-[5px] items-center justify-start w-full">
                                    <input type="checkbox" id="tv_check" />
                                    <label for="tv_check" class="w-[15%] font-medium">TV:</label>
                                    <input id="tv_value" type="text" class="w-[20%] text-[12px] border-b border-black focus:outline-none" />
                                    <div class="w-[50%] flex justify-center items-center">
                                        <label for="peep_value" class="w-auto font-medium">PEEP:</label>
                                        <input id="peep_value" type="text" class="w-[100%] text-[12px] border-b border-black focus:outline-none" />
                                    </div>
                                </div>

                                <div class="flex mt-10 gap-[5px] px-[5px] items-center justify-start w-full">
                                    <input type="checkbox" id="cardiac_monitor" />
                                    <label for="cardiac_monitor" class="w-auto font-medium">CARDIAC MONITOR</label>
                                </div>

                                <div class="flex gap-[5px] px-[5px] items-center justify-start w-full">
                                    <input type="checkbox" id="pulse_oximeter" />
                                    <label for="pulse_oximeter" class="w-auto font-medium">PULSE OXIMETER</label>
                                </div>

                                <div class="h-[100%] w-full px-[5px] flex flex-col">
                                    <label for="attachmentOthers_value" class="text-sm font-medium">OTHERS:</label>
                                    <textarea id="attachmentOthers_value" class="w-full text-justify h-[200px] mt-1 p-2 text-sm h-full resize-none overflow-y-auto  rounded focus:outline-none focus:ring-0 focus:border-transparent"></textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- IVF/INFUSIONS WRAPPER - Spans same columns but stacks two tables vertically -->
                <div class="col-span-2 col-start-3 flex flex-col h-[800px] overflow-auto">
                    <!-- First IVF Table -->
                    <div class="border border-black h-[400px] overflow-auto">
                        <div class="border-b-2 p-1 border-black sticky top-0 bg-white flex justify-center items-center">
                            <span class=" font-bold">IVF/INFUSIONS</span>
                            <img id="openInfusions" class=" ml-auto w-6 h-6 hover:scale-[1.05] cursor-pointer transition duration-200 ease-in-out" src="../public/images/edit.svg" alt="edit-treatments">
                        </div>
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr>
                                    <th class="border-b border-r border-black p-1">DATE</th>
                                    <th class="border-b border-r border-black p-1">BOTTLE NO.</th>
                                    <th class="border-b border-r border-black p-1">IVF</th>
                                    <th class="border-b border-black p-1">RATE</th>
                                </tr>
                            </thead>
                            <tbody id="infusionTableBody">

                                <tr id="addRow" class="border-b border-black h-6">
                                    <td colspan="4" class="text-center">
                                        <img class="w-8 h-8 mx-auto cursor-pointer hover:scale-105 transition duration-200"
                                            src="../public/images/add.svg"
                                            alt="Add new row"
                                            title="Add new IVF/Infusions"
                                            onclick="addInfusionRow('infusionTableBody', 'addRow')">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Second IVF Table -->
                    <div class="border border-black h-[400px] overflow-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="w-full">
                                    <th class="border-b border-r border-black p-1">DATE</th>
                                    <th class="border-b border-r border-black p-1">BOTTLE NO.</th>
                                    <th class="border-b border-r border-black p-1">IVF</th>
                                    <th class="border-b border-black p-1">RATE</th>
                                </tr>
                            </thead>
                            <tbody id="infusionTableBody2">
                                <tr id="addRow2" class="border-b border-black h-6">
                                    <td colspan="4" class="text-center">
                                        <img class="w-8 h-8 mx-auto cursor-pointer hover:scale-105 transition duration-200"
                                            src="../public/images/add.svg"
                                            alt="Add new row"
                                            title="Add new IVF/Infusions"
                                            onclick="addInfusionRow('infusionTableBody2', 'addRow2')">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- PROCEDURES/OPERATIONS -->
                <div class="col-span-1 border border-black min-h-[800px]">
                    <div class="h-12 border-b-2 p-1 border-black sticky top-0 bg-white flex justify-center items-center">
                        <span class=" font-bold">PROCEDURES/OPERATIONS</span>
                        <img id="addProcedureRow" class=" ml-auto w-6 h-6 hover:scale-[1.05] cursor-pointer transition duration-200 ease-in-out" src="../public/images/edit.svg" alt="edit-treatments">
                    </div>
                    <div id="procedureDetails" class="text-justify flex justify-center flex-col items-center">
                        <div class="h-[100%] w-full px-[5px] flex flex-col">
                            <textarea id="procedure_text"
                                class="w-full py-2 text-sm h-[700px] resize-none overflow-y-auto rounded focus:outline-none focus:ring-0 focus:border-transparent"></textarea>
                        </div>
                    </div>
                </div>

                <!-- DATE CONTRAPTIONS STARTED -->
                <div class="col-span-1 border w-full border-black min-h-[150px]">
                    <div class="h-12 border-b-2 px-1 border-black sticky top-0 bg-white flex justify-center items-center">
                        <span class=" font-bold">DATE CONTRAPTIONS STARTED</span>
                        <img id="addContraptionDateRow" class=" ml-auto w-6 h-6 hover:scale-[1.05] cursor-pointer transition duration-200 ease-in-out" src="../public/images/edit.svg" alt="edit-treatments">
                    </div>
                    <div id="dateContraptionDetails" class="text-justify flex gap-2 justify-center flex-col items-center">
                        <div class="flex gap-[5px] px-[5px] overflow-x-hidden items-center justify-start w-full">
                            <label for="iv_access" class="w-auto font-medium">IV ACCESS:</label>
                            <input type="date" id="iv_access" class="border-b border-black focus:outline-none focus:ring-0" />
                        </div>
                        <div class="flex gap-[5px] px-[5px]   overflow-x-hidden items-center justify-start w-full">
                            <label for="iv_tubing" class="w-auto font-medium">IV TUBING:</label>
                            <input type="date" id="iv_tubing" class="border-b border-black focus:outline-none focus:ring-0" />
                        </div>
                        <div class="flex gap-[5px] px-[5px]   overflow-x-hidden items-center justify-start w-full">
                            <label for="ngt" class="w-auto font-medium">NGT:</label>
                            <input type="text" id="ngt" class="border-b border-black  focus:outline-none focus:ring-0" />
                        </div>
                        <div class="h-[100%] w-full px-[5px]  overflow-x-hidden  flex flex-col">
                            <textarea id="date_contraption_others"
                                class="w-full py-2 text-sm h-[450px] resize-none overflow-y-auto rounded focus:outline-none focus:ring-0 focus:border-transparent"></textarea>

                        </div>

                    </div>
                </div>

                <!-- LABORATORY / DIAGNOSTIC TABLE -->
                <div class="col-span-2 border border-black overflow-x-auto min-h-[200px]">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="w-full">
                                <th class="h-12 border-b border-r border-black p-1">DATE</th>
                                <th class="h-12 border-b w-[50%] border-r border-black p-1">LABORATORY / DIAGNOSTIC</th>
                                <th class="h-12 border-b border-r border-black p-1">STATUS</th>
                            </tr>
                        </thead>
                        <tbody id="laboratoryBody">
                            <tr id="addLaboratoryRow" class="border-b border-black h-6">
                                <td colspan="4" class="text-center">
                                    <img class="w-8 h-8 mx-auto cursor-pointer hover:scale-105 transition duration-200"
                                        src="../public/images/add.svg"
                                        alt="Add new row"
                                        title="Add new Laboratory/Diagnostic"
                                        onclick="addLaboratoryRow()">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Note Popup -->
            <div id="noteContent" class="hidden absolute top-0 left-0 w-full h-full flex items-center justify-center bg-black bg-opacity-30 z-[60]">
                <div class="bg-white border-2 border-gray-400 rounded-xl shadow-2xl w-[80%] max-w-4xl p-6 relative">

                    <!-- Header -->
                    <div class="flex justify-between items-center mb-4">
                        <span class="bg-blue-200 text-blue-800 text-lg font-semibold px-4 py-1 rounded-lg">Notes</span>
                        <button onclick="closeNotePopup()" class="text-xl text-gray-600 hover:text-gray-800">←</button>
                    </div>

                    <!-- Note Content -->
                    <div class="border-[3px] border-blue-500 rounded-lg p-6 mb-6 min-h-[160px]">
                        <p class="text-gray-800 mb-4">
                            Was not able to give px paracetamol due to no stocks. Please follow-up with pharmacy and give to px once available
                        </p>
                        <div class="flex justify-between items-center">
                            <div class="flex items-center">
                                <img src="https://via.placeholder.com/32" alt="Avatar" class="rounded-full mr-2 w-8 h-8" />
                                <span class="font-semibold text-gray-700">Balbuena, Mark Paul</span>
                            </div>
                            <div class="flex items-center space-x-1 text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>2</span>
                            </div>
                        </div>
                    </div>

                    <!-- Reply Button -->
                    <div class="text-right">
                        <button class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded flex items-center gap-1">
                            ↩️ Reply
                        </button>
                    </div>

                </div>
            </div>

            <!-- Footer -->
            <footer class="bg-white border-t border-black p-4 flex justify-between items-center sticky bottom-0 z-20">
                <button class="bg-gray-200 text-black px-4 py-2 rounded-md" onclick="showNotePopup()">Notes</button>
                <button id="saveBtn" class="bg-sky-500 text-white font-bold px-6 py-2 rounded-md hover:bg-sky-600 transition">Save</button>
            </footer>
        </div>



        <script>
            function showNotePopup() {
                document.getElementById('mainContent').classList.add('hidden');
                document.getElementById('noteContent').classList.remove('hidden');
            }

            function closeNotePopup() {
                document.getElementById('noteContent').classList.add('hidden');
                document.getElementById('mainContent').classList.remove('hidden');
            }
        </script>
    </div>

    <div id="errorModal" class="fixed inset-0 z-[100] hidden bg-black bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-8 rounded-xl text-center shadow-lg w-[90%] max-w-md animate-fadeScaleIn border-4 border-red-500 ">
            <div class="flex justify-center mb-4">
                <img src="/kardex_system/src/public/images/error-icon.png" alt="Error Icon" class="w-12 h-12" />
            </div>
            <h2 class="text-lg sm:text-3xl font-semibold text-red-600">Passcode is incorrect, please try again.</h2>
            <button
                onclick="closeErrorModal()"
                class="mt-6 px-6 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 hover:shadow-xl transform hover:scale-105 transition duration-300">OK</button>
        </div>
    </div>

    <div id="successModal" class="fixed inset-0 z-[100] hidden bg-black bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-8 rounded-xl text-center shadow-lg w-[90%] max-w-md animate-fadeScaleIn border-4 border-green-500">
            <div class="flex justify-center mb-4">
                <img src="/kardex_system/src/public/images/success-icon.png" alt="Success Icon" class="w-13 h-12" />
            </div>
            <h2 class="text-lg sm:text-3xl font-semibold text-green-600">Changes saved Succesfully!</h2>
            <button
                onclick="location.reload()"
                class="mt-6 px-6 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 hover:shadow-xl transform hover:scale-105 transition duration-300 ">OK</button>
        </div>
    </div>


    <!-- Edit Popup -->
    <div id="editPopup" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-30 hidden">
        <div class="bg-white p-10 rounded-md shadow-md w-[500px] max-h-[80vh] overflow-y-auto">
            <!-- Title + Add Row Button -->
            <div class="flex justify-between items-center mb-2">
                <h2 id="popupTitle" class="text-lg font-bold "></h2>
                <button
                    id="addRowInputPopup"
                    class="text-sm bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 transition">
                    + Row
                </button>
            </div>

            <!-- Editable rows container -->
            <div id="popupInputRows" class="mt-4 pb-8 border-t border-black">
                <input type="text" class="w-full  border border-black p-2 focus:outline-none focus:ring-0" />
                <input type="text" class="w-full border-b border-l border-r border-black p-2 focus:outline-none focus:ring-0" />
                <input type="text" class="w-full border-b border-l border-r border-black p-2 focus:outline-none focus:ring-0" />
                <input type="text" class="w-full border-b border-l border-r border-black p-2 focus:outline-none focus:ring-0" />
            </div>

            <!-- Action buttons -->
            <div class="flex justify-end space-x-2">
                <button
                    onclick="closePopup(false)"
                    class="bg-sky-500 text-white px-4 py-2 rounded hover:bg-sky-600 transition">
                    Ok
                </button>
                <button
                    onclick="closePopup(true)"
                    class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400 transition">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    <!-- Save Changes Modal -->
    <div id="popupConfirmChanges" class="fixed inset-0 z-50 bg-black hidden bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-8 rounded-xl text-center shadow-lg w-[90%] max-w-md animate-fadeScaleIn border-4 border-[#5eacdd] ">
            <h2 class="text-lg sm:text-3xl text-[#5eacdd] font-semibold border-[#5eacdd]">Save Changes?</h2>
            <div class="flex justify-center space-x-4">
                <button id="saveChanges"
                    class="mt-6 px-6 py-2 bg-[#5eacdd] text-white rounded-lg hover:bg-[#2320d8] hover:shadow-xl transform hover:scale-105 transition duration-300">CONFIRM</button>
                <button id="cancelChanges"
                    class="mt-6 px-6 py-2 bg-[#5eacde] text-white rounded-lg hover:bg-[#2320d8] hover:shadow-xl transform hover:scale-105 transition duration-300">CANCEL</button>
            </div>
        </div>
    </div>




    <!-- Flowbite (for example) -->

    <script src="/kardex_system/src/public/js/utils.js"></script>

    <script src="/kardex_system/src/public/js/patientInformationHandler.js"></script>
    <script src="/kardex_system/src/public/js/patientInformationJs/editInformation.js"></script>
    <script src="/kardex_system/src/public/js/patientInformationJs/treatmentHandler.js"></script>
    <script src="/kardex_system/src/public/js/patientInformationJs/loadPatientInformation.js"></script>
    <script src="/kardex_system/src/public/js/patientInformationJs/submitChanges.js"></script>
    <script src="/kardex_system/src/public/js/passcode.js"></script>
</body>

</html>