<div class="menu-cards-container">
    <!-- Library Catalog Card -->
    <a href="Library.php" class="menu-card green">
        <div class="card-content">
            <div class="icon-wrapper">
                <svg class="card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <h2>Library Catalog</h2>
        </div>
    </a>

    <!-- Borrowers List Card -->
    <a href="borrowlist.php" class="menu-card blue">
        <div class="card-content">
            <div class="icon-wrapper">
                <svg class="card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <h2>Borrowers List</h2>
        </div>
    </a>

    <!-- Generate Reports Card -->
    <a href="Report.php" class="menu-card orange">
        <div class="card-content">
            <div class="icon-wrapper">
                <svg class="card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h2>Generate Reports</h2>
        </div>
    </a>

    <!-- System Updates Card -->
    <a href="Updates.php" class="menu-card purple">
        <div class="card-content">
            <div class="icon-wrapper">
                <svg class="card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
            </div>
            <h2>System Updates</h2>
        </div>
    </a>
</div>

<!-- Add this to your components/dashboard_charts.php -->
<div class="bg-white p-6 rounded-lg shadow-sm">
    <!-- Navigation Links -->
    <div class="flex space-x-4 mb-6 border-b">
        <a href="#circulation" onclick="return showSection('circulation')" class="nav-link active px-4 py-2 text-sm font-medium text-gray-600">
            Book Circulation
        </a>
        <a href="#members" onclick="return showSection('members')" class="nav-link px-4 py-2 text-sm font-medium text-gray-600">
            Member Statistics
        </a>
        <a href="#categories" onclick="return showSection('categories')" class="nav-link px-4 py-2 text-sm font-medium text-gray-600">
            Book Categories
        </a>
        <a href="#overdue" onclick="return showSection('overdue')" class="nav-link px-4 py-2 text-sm font-medium text-gray-600">
            Overdue Books
        </a>
    </div>

    <!-- Chart Sections -->
    <div class="charts-container">
        <section id="circulation" class="chart-section active">
            <h3 class="text-lg font-medium text-gray-800 mb-4">Book Circulation Trend</h3>
            <div class="h-[400px]">
                <canvas id="circulationTrendChart"></canvas>
            </div>
        </section>

        <section id="members" class="chart-section hidden">
            <h3 class="text-lg font-medium text-gray-800 mb-4">Member Statistics</h3>
            <div class="h-[400px]">
                <canvas id="memberStatsChart"></canvas>
            </div>
        </section>

        <section id="categories" class="chart-section hidden">
            <h3 class="text-lg font-medium text-gray-800 mb-4">Book Categories Distribution</h3>
            <div class="h-[400px]">
                <canvas id="bookCategoriesChart"></canvas>
            </div>
        </section>

        <section id="overdue" class="chart-section hidden">
            <h3 class="text-lg font-medium text-gray-800 mb-4">Overdue Books Analysis</h3>
            <div class="h-[400px]">
                <canvas id="overdueBooksChart"></canvas>
            </div>
        </section>
    </div>
</div>


<style>
    /* Navigation Link Styles */
    .nav-link {
        position: relative;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .nav-link::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 100%;
        height: 2px;
        background-color: #4F46E5;
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .nav-link:hover::after {
        transform: scaleX(1);
    }

    .nav-link.active {
        color: #4F46E5;
    }

    .nav-link.active::after {
        transform: scaleX(1);
    }

    /* Chart Section Styles */
    .chart-section {
        opacity: 0;
        transition: opacity 0.3s ease;
        position: absolute;
        width: 100%;
        visibility: hidden;
    }

    .chart-section.active {
        opacity: 1;
        position: relative;
        visibility: visible;
    }

    .charts-container {
        position: relative;
        min-height: 500px; /* Adjust based on your needs */
    }

    /* Animation Classes */
    .fade-in {
        animation: fadeIn 0.3s ease forwards;
    }

    .fade-out {
        animation: fadeOut 0.3s ease forwards;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes fadeOut {
        from { opacity: 1; }
        to { opacity: 0; }
    }

    .menu-cards-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        padding: 1.5rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .menu-card {
        position: relative;
        height: 200px;
        border-radius: 20px;
        overflow: hidden;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .menu-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.1);
        transform: translateY(100%);
        transition: transform 0.3s ease;
    }

    .menu-card:hover::before {
        transform: translateY(0);
    }

    .menu-card.green { background: #34D399; }
    .menu-card.blue { background: #60A5FA; }
    .menu-card.orange { background: #FB923C; }
    .menu-card.purple { background: #A78BFA; }

    .card-content {
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: white;
        padding: 2rem;
        position: relative;
        z-index: 1;
    }

    .icon-wrapper {
        background: rgba(255, 255, 255, 0.2);
        padding: 1.5rem;
        border-radius: 50%;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .card-icon {
        width: 2.5rem;
        height: 2.5rem;
        stroke: white;
    }

    .menu-card h2 {
        font-size: 1.25rem;
        font-weight: 600;
        text-align: center;
        margin: 0;
    }

    /* Hover Animations */
    .menu-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .menu-card:hover .icon-wrapper {
        transform: scale(1.1) rotate(5deg);
        background: rgba(255, 255, 255, 0.3);
    }

    /* Floating Animation */
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0px); }
    }

    .menu-card:hover .card-icon {
        animation: float 2s ease-in-out infinite;
    }

    /* Shine Effect */
    .menu-card::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(
            to bottom right,
            rgba(255, 255, 255, 0.8) 0%,
            rgba(255, 255, 255, 0) 80%
        );
        transform: rotate(30deg);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .menu-card:hover::after {
        opacity: 0.1;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .menu-cards-container {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            padding: 1rem;
        }

        .menu-card {
            height: 180px;
        }

        .icon-wrapper {
            padding: 1rem;
        }

        .card-icon {
            width: 2rem;
            height: 2rem;
        }

        .menu-card h2 {
            font-size: 1.1rem;
        }
    }
</style>

<script>
function showSection(sectionId) {
    // Update URL hash without scrolling
    history.pushState(null, null, `${sectionId}`);

    // Remove active class from all links and sections
    document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.remove('active');
    });
    document.querySelectorAll('.chart-section').forEach(section => {
        section.classList.remove('active');
        section.classList.add('hidden');
    });

    // Add active class to clicked link and corresponding section
    document.querySelector(`a[href="#${sectionId}"]`).classList.add('active');
    const activeSection = document.getElementById(sectionId);
    activeSection.classList.remove('hidden');
    activeSection.classList.add('active');

    // Resize chart if needed
    const chartId = `${sectionId}Chart`;
    if (window[chartId]) {
        window[chartId].resize();
    }

    return false; // Prevent default anchor behavior
}

// Handle initial load and back/forward navigation
window.addEventListener('load', () => {
    const hash = window.location.hash.slice(1) || '';
    showSection(hash);
});

window.addEventListener('hashchange', () => {
    const hash = window.location.hash.slice(1);
    showSection(hash);
});

// Initialize charts when the page loads
document.addEventListener('DOMContentLoaded', () => {
    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        animation: {
            duration: 300
        },
        plugins: {
            legend: {
                position: 'top',
            }
        }
    };

    // Initialize Book Circulation Chart
    const circulationTrendChart = new Chart(
        document.getElementById('circulationTrendChart').getContext('2d'),
        {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Books Borrowed',
                    data: [150, 180, 200, 220, 190, 250],
                    borderColor: '#34D399',
                    backgroundColor: 'rgba(52, 211, 153, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                ...chartOptions,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Books'
                        }
                    }
                }
            }
        }
    );

    // Initialize Member Statistics Chart
    const memberStatsChart = new Chart(
        document.getElementById('memberStatsChart').getContext('2d'),
        {
            type: 'bar',
            data: {
                labels: ['Active Members', 'New Members', 'Inactive Members'],
                datasets: [{
                    label: 'Member Count',
                    data: [450, 120, 80],
                    backgroundColor: [
                        'rgba(96, 165, 250, 0.8)',
                        'rgba(251, 146, 60, 0.8)',
                        'rgba(167, 139, 250, 0.8)'
                    ]
                }]
            },
            options: {
                ...chartOptions,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Members'
                        }
                    }
                }
            }
        }
    );

    // Initialize Book Categories Chart
    const bookCategoriesChart = new Chart(
        document.getElementById('bookCategoriesChart').getContext('2d'),
        {
            type: 'doughnut',
            data: {
                labels: ['Fiction', 'Non-Fiction', 'Reference', 'Periodicals', 'Children'],
                datasets: [{
                    data: [35, 25, 15, 10, 15],
                    backgroundColor: [
                        '#34D399',
                        '#60A5FA',
                        '#FB923C',
                        '#A78BFA',
                        '#F87171'
                    ]
                }]
            },
            options: {
                ...chartOptions,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        }
    );

    // Initialize Overdue Books Chart
    const overdueBooksChart = new Chart(
        document.getElementById('overdueBooksChart').getContext('2d'),
        {
            type: 'bar',
            data: {
                labels: ['1-7 days', '8-14 days', '15-30 days', '>30 days'],
                datasets: [{
                    label: 'Number of Overdue Books',
                    data: [25, 15, 10, 5],
                    backgroundColor: 'rgba(248, 113, 113, 0.8)'
                }]
            },
            options: {
                ...chartOptions,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Books'
                        }
                    }
                }
            }
        }
    );
});
</script>