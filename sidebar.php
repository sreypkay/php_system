<aside class="w-64 h-screen fixed left-0 bg-white shadow-lg">
    <div class="flex flex-col h-full">
        <!-- Dashboard Header -->
    <div class="p-6 border-b">
            <h1 class="text-xl font-semibold text-gray-800">Library System</h1>
        </div>
        
        <!-- Navigation Menu -->
        <nav class="flex-1 p-4">
            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-4">Main</p>
            
            <!-- Dashboard Link -->
            <a href="index.php" class="flex items-center px-4 py-3 text-sm font-medium text-indigo-600 bg-indigo-50 rounded-lg mb-2">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Dashboard
            </a>

            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mt-6 mb-4">Management</p>
                         <!-- Inventory Link -->
            <a href="course.php" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-indigo-600 rounded-lg transition-colors duration-150">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Course
            </a>
        
            <a href="studentlist.php" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-indigo-600 rounded-lg mb-2 transition-colors duration-150">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H2v-2a3 3 0 015.356-1.857M17 20v-2c0-.923-.21-1.828-.6-2.653M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                studentlist
            </a>
            <!-- Add Books Link -->
            <a href="Add_Books.php" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-indigo-600 rounded-lg mb-2 transition-colors duration-150">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                Add Books
            </a>

            <!-- Borrowers Link -->
            <a href="Borrow.php" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-indigo-600 rounded-lg mb-2 transition-colors duration-150">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Borrowers
            </a>

            <!-- Returns Link -->
            <a href="Return.php" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-indigo-600 rounded-lg mb-2 transition-colors duration-150">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"></path>
                </svg>
                Returns
            </a>



<!-- Divider for System Menu -->
<p class="text-xs font-medium text-gray-400 uppercase tracking-wider mt-6 mb-4">System</p>

<!-- Profile Link -->
<a href="profile.php" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-indigo-600 rounded-lg mb-2 transition-colors duration-150">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
    </svg>
    Profile
</a>
<a href="logout.php" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-red-600 rounded-lg mb-2 transition-colors duration-150">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7"></path>
    </svg>
    Logout
</a>

<!-- Settings Link -->
<a href="setting.php" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-indigo-600 rounded-lg mb-2 transition-colors duration-150">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
    Settings
            </a>
        </nav>
        <!-- Footer -->
        <div class="p-4 border-t">
            <div class="flex items-center">
                <img src="https://ui-avatars.com/api/?name=Admin&background=6366f1&color=fff" alt="Admin" class="w-8 h-8 rounded-full">
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-700">Admin User</p>
                    <p class="text-xs text-gray-500">administrator</p>
                </div>
            </div>
            <div class="mt-4 text-xs text-gray-500">
                <p>© 2024 Library System</p>
            <p class="mt-1">Version 1.0.0</p>
            </div>
        </div>
    </div>
</aside>

<style>
    /* Active state styling */
    .sidebar-link.active {
        background-color: #EEF2FF;
        color: #4F46E5;
    }

    /* Hover state styling */
    .sidebar-link:hover {
        background-color: #F9FAFB;
    }

    /* Transition effects */
    .sidebar-link {
        transition: all 0.2s ease;
    }

    /* Custom scrollbar for sidebar */
    aside {
        scrollbar-width: thin;
        scrollbar-color: #E5E7EB transparent;
    }

    aside::-webkit-scrollbar {
        width: 4px;
    }

    aside::-webkit-scrollbar-track {
        background: transparent;
    }

    aside::-webkit-scrollbar-thumb {
        background-color: #E5E7EB;
        border-radius: 20px;
    }
</style>