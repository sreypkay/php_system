
<!DOCTYPE html>
    <html lang="en">
    <head>
        <title>setting</title>
        <?php include 'components/head.php'; // Include the common head section ?>
    </head>
    <body>
        <?php include 'components/sidebar.php'; // Include the sidebar ?>
        <div class="main-container">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6; /* Light gray background */
        }
        /* Custom scrollbar for sidebar */
        aside::-webkit-scrollbar {
            width: 8px;
        }
        aside::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        aside::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }
        aside::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Custom toggle switch styling (basic, can be enhanced with more Tailwind) */
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 24px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
            border-radius: 24px; /* Rounded slider */
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
            border-radius: 50%; /* Circular handle */
        }

        input:checked + .toggle-slider {
            background-color: #3B82F6; /* blue-500 */
        }

        input:focus + .toggle-slider {
            box-shadow: 0 0 1px #3B82F6;
        }

        input:checked + .toggle-slider:before {
            -webkit-transform: translateX(20px);
            -ms-transform: translateX(20px);
            transform: translateX(20px);
        } 

      
      
      
      
       #pkay{
        width: 250px;
       }
       
       
    </style>
</head>
    <!-- Sidebar (replicated from the dashboard for navigation context) -->

    <!-- Main Content Area for Settings -->
    <main class="flex-1 p-8 overflow-y-auto">
        <div class="bg-white p-8 rounded-xl shadow-md max-w-3xl mx-auto" >
            <form id="settingsForm" id='pkay'>
                <!-- Library System Settings Section -->
                <div class="mb-8 pb-8 border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-700 mb-6">Library System Settings</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="libraryName" class="block text-sm font-medium text-gray-700 mb-1">Library Name</label>
                            <input type="text" id="libraryName" name="libraryName" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="e.g., Central City Library" value="Library System">
                        </div>
                        <div>
                            <label for="libraryAddress" class="block text-sm font-medium text-gray-700 mb-1">Library Address</label>
                            <input type="text" id="libraryAddress" name="libraryAddress" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="e.g., 123 Main St, Anytown">
                        </div>
                        <div>
                            <label for="defaultLoanPeriod" class="block text-sm font-medium text-gray-700 mb-1">Default Loan Period (Days)</label>
                            <input type="number" id="defaultLoanPeriod" name="defaultLoanPeriod" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="e.g., 14" value="14">
                        </div>
                        <div>
                            <label for="overdueFineRate" class="block text-sm font-medium text-gray-700 mb-1">Overdue Fine Rate (per day)</label>
                            <input type="number" step="0.01" id="overdueFineRate" name="overdueFineRate" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="e.g., 0.25" value="0.25">
                        </div>
                        <div>
                            <label for="currencySymbol" class="block text-sm font-medium text-gray-700 mb-1">Currency Symbol</label>
                            <input type="text" id="currencySymbol" name="currencySymbol" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="e.g., $" value="$">
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Enable Member Registration</span>
                            <label class="toggle-switch">
                                <input type="checkbox" id="memberRegistrationToggle" name="memberRegistration" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Account Settings Section (Remains the same, but for contextual clarity) -->
                <div class="mb-8 pb-8 border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-700 mb-6">Account Settings</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <input type="email" id="email" name="email" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="your.email@example.com" value="admin@example.com">
                        </div>
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                            <input type="text" id="username" name="username" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="yourusername" value="administrator">
                        </div>
                        <div class="md:col-span-2 flex justify-start">
                             <button type="button" class="py-2 px-6 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                                Change Password
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Notification Settings Section (Remains the same) -->
                <div class="mb-8 pb-8 border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-700 mb-6">Notification Settings</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Email Notifications</span>
                            <label class="toggle-switch">
                                <input type="checkbox" id="emailNotifications" name="emailNotifications" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Push Notifications</span>
                            <label class="toggle-switch">
                                <input type="checkbox" id="pushNotifications" name="pushNotifications">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">SMS Alerts</span>
                            <label class="toggle-switch">
                                <input type="checkbox" id="smsAlerts" name="smsAlerts">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Save Changes Button -->
                <div class="flex justify-end">
                    <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        Save Changes
                    </button>
                </div>
            </form>
            <div id="messageBox" class="mt-4 p-3 rounded-md text-sm hidden"></div>
        </div>
    </main>

    <script>
        document.getElementById('settingsForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            // Library System Settings
            const libraryName = document.getElementById('libraryName').value;
            const libraryAddress = document.getElementById('libraryAddress').value;
            const defaultLoanPeriod = document.getElementById('defaultLoanPeriod').value;
            const overdueFineRate = document.getElementById('overdueFineRate').value;
            const currencySymbol = document.getElementById('currencySymbol').value;
            const memberRegistrationToggle = document.getElementById('memberRegistrationToggle').checked;

            // Account Settings
            const email = document.getElementById('email').value;
            const username = document.getElementById('username').value;
            
            // Notification Settings
            const emailNotifications = document.getElementById('emailNotifications').checked;
            const pushNotifications = document.getElementById('pushNotifications').checked;
            const smsAlerts = document.getElementById('smsAlerts').checked;

            // In a real application, you would send this data to a backend API
            console.log('Settings Saved:', {
                libraryName,
                libraryAddress,
                defaultLoanPeriod,
                overdueFineRate,
                currencySymbol,
                memberRegistrationToggle,
                email,
                username,
                emailNotifications,
                pushNotifications,
                smsAlerts
            });

            const messageBox = document.getElementById('messageBox');
            messageBox.textContent = 'Settings saved successfully!';
            messageBox.classList.remove('hidden');
            messageBox.classList.add('bg-green-100', 'text-green-800'); // Style for success

            // Hide message after a few seconds
            setTimeout(() => {
                messageBox.classList.add('hidden');
                messageBox.classList.remove('bg-green-100', 'text-green-800');
            }, 3000);
        });

        // Update sidebar link to point to this new page
        document.querySelector('a[href="settings.html"]').classList.add('text-blue-600', 'bg-blue-50');
        document.querySelector('a[href="settings.html"]').classList.remove('text-gray-700', 'hover:text-blue-600', 'hover:bg-blue-50');
    </script>
</div>
<?php 
 include 'components/top_bar.php';
?>
    </body>
    </html>
    