<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../Project-lec/auth/login.php'); // Redirect to login if not authenticated
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM events");
$stmt->execute();
$events = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GatherCraft Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">

    <!-- Ini hamburger menu -->
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

        <!-- Tampilan sidebar sama isinya -->
        <div id="sidebar" class="bg-blue-900 text-white w-64 fixed h-full overflow-y-auto z-30 transition-transform duration-300 ease-in-out transform -translate-x-full md:translate-x-0">
            <div class="p-5">
                <h1 class="text-3xl font-bold mb-10 text-center">GatherCraft</h1>
                <nav class="space-y-4">
                    <a href="#event-browsing" 
                       class="block text-lg py-3 px-4 rounded hover:bg-blue-800 transition duration-200 scroll-link"
                       data-section="event-browsing">Profile</a>
                    <a href="#event-browsing" 
                       class="block text-lg py-3 px-4 rounded hover:bg-blue-800 transition duration-200 scroll-link"
                       data-section="event-browsing">Event Browsing</a>
                    <a href="#event-registration" 
                       class="block text-lg py-3 px-4 rounded hover:bg-blue-800 transition duration-200 scroll-link"
                       data-section="event-registration">Event Registration</a>
                    <a href="#registered-events" 
                       class="block text-lg py-3 px-4 rounded hover:bg-blue-800 transition duration-200 scroll-link"
                       data-section="registered-events">Registered Events</a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 w-full md:ml-64 p-4 md:p-8 mt-16 md:mt-0">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl md:text-3xl font-semibold">Features for Users</h2>
            </div>

            <!-- Event Browsing Section -->
            <div id="event-browsing" class="mb-12 pt-16 -mt-16">
                <h3 class="text-xl md:text-2xl font-semibold mb-6">1. Event Browsing</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                    <!-- ini sample cardnya -->
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                        <img src="https://via.placeholder.com/400x200" alt="Event Image" class="w-full h-48 object-cover">
                        <div class="p-4 md:p-6">
                            <h4 class="text-lg md:text-xl font-semibold mb-2">Music Festival 2024</h4>
                            <p class="text-gray-600 mb-4">Join us for an unforgettable evening of live music, food, and fun!</p>
                            <p class="text-gray-500 text-sm mb-2">Date: October 30, 2024</p>
                            <p class="text-gray-500 text-sm mb-4">Location: Central Park</p>
                            <a href="#" class="text-blue-600 hover:text-blue-800">View Details</a>
                        </div>
                    </div>

                    <!-- sample tambahan -->
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                        <img src="https://via.placeholder.com/400x200" alt="Event Image" class="w-full h-48 object-cover">
                        <div class="p-4 md:p-6">
                            <h4 class="text-lg md:text-xl font-semibold mb-2">Art Exhibition 2024</h4>
                            <p class="text-gray-600 mb-4">Explore contemporary artworks from various artists.</p>
                            <p class="text-gray-500 text-sm mb-2">Date: November 15, 2024</p>
                            <p class="text-gray-500 text-sm mb-4">Location: Art Gallery, Downtown</p>
                            <a href="#" class="text-blue-600 hover:text-blue-800">View Details</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Event Registration Section -->
            <div id="event-registration" class="mb-12 pt-16 -mt-16">
                <h3 class="text-xl md:text-2xl font-semibold mb-6">2. Event Registration</h3>
                <div class="space-y-4">
                    <div class="bg-white shadow-md rounded-lg p-4 md:p-6">
                        <h4 class="text-lg md:text-xl font-semibold mb-2">Register for Event</h4>
                        <p class="text-gray-600">Click the "Register" button on the event detail page to sign up.</p>
                    </div>
                    <div class="bg-white shadow-md rounded-lg p-4 md:p-6">
                        <h4 class="text-lg md:text-xl font-semibold mb-2">View Registered Events</h4>
                        <p class="text-gray-600">See the list of events you've registered for along with the details.</p>
                    </div>
                    <div class="bg-white shadow-md rounded-lg p-4 md:p-6">
                        <h4 class="text-lg md:text-xl font-semibold mb-2">Cancel Registration</h4>
                        <p class="text-gray-600">Cancel an event registration with confirmation.</p>
                    </div>
                </div>
            </div>

            <!-- Registered Events Section -->
            <div id="registered-events" class="mb-12 pt-16 -mt-16">
                <h3 class="text-xl md:text-2xl font-semibold mb-6">3. Registered Events</h3>
                <div class="bg-white shadow-md rounded-lg p-4 md:p-6">
                    <p class="text-gray-600">You have not registered for any events yet. Start exploring events to participate in exciting activities!</p>
                </div>
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