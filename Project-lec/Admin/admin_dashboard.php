<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../Project-lec/auth/login.php'); // Redirect if not logged in as admin
    exit;
}

// Fetch events or any other data needed for the admin dashboard
$stmt = $pdo->prepare("SELECT * FROM events");
$stmt->execute();
$events = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GatherCraft Admin Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">

    <!-- Hamburger menu for mobile -->
    <div class="fixed top-0 left-0 z-40 p-4 block md:hidden">
        <button id="menu-toggle" class="text-gray-500 hover:text-gray-700 focus:outline-none bg-white p-2 rounded-md shadow-lg">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </div>

    <!-- Overlay for mobile -->
    <div id="overlay" class="fixed inset-0 bg-black opacity-0 invisible md:hidden transition-opacity duration-300 ease-in-out z-30"></div>

    <div class="flex min-h-screen relative">

        <!-- Sidebar for Admin -->
        <div id="sidebar" class="bg-blue-900 text-white w-64 fixed h-full overflow-y-auto z-30 transition-transform duration-300 ease-in-out transform -translate-x-full md:translate-x-0">
            <div class="p-5">
                <h1 class="text-3xl font-bold mb-10 text-center">Admin Dashboard</h1>
                <nav class="space-y-4">
                    <a href="#dashboard" class="block text-lg py-3 px-4 rounded hover:bg-blue-800 transition duration-200 scroll-link" data-section="dashboard">Dashboard</a>
                    <a href="#event-management" class="block text-lg py-3 px-4 rounded hover:bg-blue-800 transition duration-200 scroll-link" data-section="event-management">Event Management</a>
                    <a href="#view-registrations" class="block text-lg py-3 px-4 rounded hover:bg-blue-800 transition duration-200 scroll-link" data-section="view-registrations">View Event Registrations</a>
                    <a href="#user-management" class="block text-lg py-3 px-4 rounded hover:bg-blue-800 transition duration-200 scroll-link" data-section="user-management">User Management</a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 w-full md:ml-64 p-4 md:p-8 mt-16 md:mt-0">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl md:text-3xl font-semibold">Admin Dashboard</h2>
            </div>

            <!-- Dashboard Section -->
            <div id="dashboard" class="mb-12 pt-16 -mt-16">
                <h3 class="text-xl md:text-2xl font-semibold mb-6">1. Dashboard</h3>
                <p>Here you can view an overview of events and registrations.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-white shadow-lg rounded-lg p-6">
                        <h4 class="text-lg font-semibold mb-2">Total Events</h4>
                        <p class="text-gray-600">X Events Available</p>
                    </div>
                    <div class="bg-white shadow-lg rounded-lg p-6">
                        <h4 class="text-lg font-semibold mb-2">Total Registrants</h4>
                        <p class="text-gray-600">Y Total Registrants</p>
                    </div>
                </div>
            </div>

            <!-- Event Management Section -->
            <div id="event-management" class="mb-12 pt-16 -mt-16">
                <h3 class="text-xl md:text-2xl font-semibold mb-6">2. Event Management</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                    <!-- Create New Event -->
                    <div class="bg-white shadow-lg rounded-lg p-6">
                        <h4 class="text-lg font-semibold mb-4">Create New Event</h4>
                        <form>
                            <div class="mb-4">
                                <label class="block text-gray-700">Event Name</label>
                                <input type="text" class="w-full border-2 border-gray-300 rounded-md">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700">Date</label>
                                <input type="date" class="w-full border-2 border-gray-300 rounded-md">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700">Location</label>
                                <input type="text" class="w-full border-2 border-gray-300 rounded-md">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700">Maximum Participants</label>
                                <input type="number" class="w-full border-2 border-gray-300 rounded-md">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700">Upload Image</label>
                                <input type="file" class="w-full">
                            </div>
                            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded">Create Event</button>
                        </form>
                    </div>

                    <!-- Edit Event -->
                    <div class="bg-white shadow-lg rounded-lg p-6">
                        <h4 class="text-lg font-semibold mb-4">Edit Event</h4>
                        <p class="text-gray-600">Select an event to modify its details, change status, or update slots.</p>
                    </div>

                    <!-- Delete Event -->
                    <div class="bg-white shadow-lg rounded-lg p-6">
                        <h4 class="text-lg font-semibold mb-4">Delete Event</h4>
                        <p class="text-gray-600">Confirm before deleting any event to avoid accidental removal.</p>
                    </div>
                </div>
            </div>

            <!-- View Event Registrations Section -->
            <div id="view-registrations" class="mb-12 pt-16 -mt-16">
                <h3 class="text-xl md:text-2xl font-semibold mb-6">3. View Event Registrations</h3>
                <p>See who has registered for each event and export the list of registrants.</p>
                <button class="bg-green-600 text-white py-2 px-4 rounded">Export to CSV</button>
            </div>

            <!-- User Management Section -->
            <div id="user-management" class="mb-12 pt-16 -mt-16">
                <h3 class="text-xl md:text-2xl font-semibold mb-6">4. User Management</h3>
                <p>Manage user accounts, view registered events, and delete users if necessary.</p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menu-toggle');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            const links = document.querySelectorAll('.scroll-link');

            menuToggle.addEventListener('click', function() {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('opacity-0');
                overlay.classList.toggle('invisible');
                overlay.classList.toggle('opacity-50');
            });

            overlay.addEventListener('click', function() {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('opacity-0', 'invisible');
                overlay.classList.remove('opacity-50');
            });

            links.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();

                    const targetId = this.getAttribute('href').substring(1);
                    const targetSection = document.getElementById(targetId);

                    if (targetSection) {
                        targetSection.scrollIntoView({
                            behavior: 'smooth'
                        });

                        links.forEach(l => l.classList.remove('bg-blue-800'));
                        this.classList.add('bg-blue-800');

                        if (window.innerWidth < 768) {
                            sidebar.classList.add('-translate-x-full');
                            overlay.classList.add('opacity-0', 'invisible');
                            overlay.classList.remove('opacity-50');
                        }
                    }
                });
            });

            function highlightNavigation() {
                let scrollPosition = window.scrollY;

                document.querySelectorAll('div[id]').forEach(section => {
                    const sectionTop = section.offsetTop - 100;
                    const sectionBottom = sectionTop + section.offsetHeight;

                    if (scrollPosition >= sectionTop && scrollPosition < sectionBottom) {
                        const currentId = section.getAttribute('id');
                        links.forEach(link => {
                            link.classList.remove('bg-blue-800');
                            if (link.getAttribute('href') === `#${currentId}`) {
                                link.classList.add('bg-blue-800');
                            }
                        });
                    }
                });
            }

            window.addEventListener('scroll', highlightNavigation);
            highlightNavigation();
        });
    </script>
</body>
</html>
