<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechVision - Innovating Tomorrow</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .bg-white {
            background-color: white;
        }
        .shadow-md {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .z-50 {
            z-index: 50;
        }
        .bg-gradient-to-r {
            background-image: linear-gradient(to right, blue, purple);
        }
        .from-blue-500 {
            background-color: #60a5fa;
        }
        .to-blue-600 {
            background-color: #4299e1;
        }
        .text-2xl {
            font-size: 2rem;
        }
        .sm:text-3xl {
            @apply sm:text-3xl;
        }
        .font-bold {
            font-weight: bold;
        }
        .bg-blue-600 {
            background-color: #4299e1;
        }
        .text-white {
            color: white;
        }
        .px-6 {
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }
        .py-3 {
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }
        .rounded-lg {
            border-radius: 0.5rem;
        }
        .hover:bg-blue-700 {
            @apply hover:bg-blue-700;
        }
        .transition {
            transition-property: background-color, transform;
            transition-duration: 0.2s;
            transition-timing-function: ease-in-out;
        }
        .bg-gradient-to-r.from-blue-500.to-blue-600 {
            @apply bg-gradient-to-r from-blue-500 to-blue-600;
        }
        .py-20 {
            padding-top: 5rem;
            padding-bottom: 5rem;
        }
        .bg-gray-50 {
            background-color: #f9fafb;
        }
        .text-4xl {
            font-size: 2.5rem;
        }
        .md:grid-cols-2 {
            @apply md:grid-cols-2;
        }
        .gap-12 {
            gap: 3rem;
        }
        .items-center {
            align-items: center;
        }
        .bg-white.rounded-lg.shadow-lg {
            @apply bg-white rounded-lg shadow-lg;
        }
        .hover:shadow-xl {
            @apply hover:shadow-xl;
        }
        .md:grid-cols-3 {
            @apply md:grid-cols-3;
        }
        .text-center {
            text-align: center;
        }
        .mb-8 {
            margin-bottom: 2rem;
        }
        .flex.items-center.text-gray-700 {
            @apply flex items-center text-gray-700;
        }
        .md:text-5xl {
            @apply md:text-5xl;
        }
        .text-blue-100 {
            color: #b3e6ff;
        }
        .hover:bg-gray-100 {
            @apply hover:bg-gray-100;
        }
        .bg-gradient-to-r.from-white.to-purple {
            background-image: linear-gradient(to right, white, purple);
        }
        .text-blue-600:hover {
            color: #3b82f6;
        }
        .text-blue-600 {
            color: #4299e1;
        }
        .bg-gray-900 {
            background-color: #1a202c;
        }
        .py-12 {
            padding-top: 3rem;
            padding-bottom: 3rem;
        }
        .border-t.border-gray-800 {
            @apply border-t border-gray-800;
        }
        .mt-4 {
            margin-top: 1rem;
        }
        .bg-gradient-to-r.from-blue-600.to-purple {
            @apply bg-gradient-to-r from-blue-600 to-purple;
        }
    </style>
</head>
<body class="bg-white">
    <!-- Navigation -->
    <nav class="fixed w-full shadow-md z-50 bg-gradient-to-r from-blue-600 to-purple">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">
            <span class="text-2xl font-bold text-blue-600">TechVision</span>
            <div class="hidden md:flex space-x-8">
                <a href="#home" class="text-gray-700 hover:text-blue-600 transition">Home</a>
                <a href="#about" class="text-gray-700 hover:text-blue-600 transition">About</a>
                <a href="#services" class="text-gray-700 hover:text-blue-600 transition">Services</a>
                <a href="#team" class="text-gray-700 hover:text-blue-600 transition">Team</a>
                <a href="#contact" class="text-gray-700 hover:text-blue-600 transition">Contact</a>
            </div>
            <button class="bg-white text-blue-600 px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                Get Started
            </button>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="pt-32 pb-20 px-4 sm:px-6 lg:px-8 bg-gradient-to-r from-white to-purple">
        <div class="max-w-7xl mx-auto text-center text-white">
            <h1 class="text-5xl md:text-6xl font-bold mb-6">Innovating Tomorrow</h1>
            <p class="text-xl md:text-2xl mb-8 text-blue-100">Transforming ideas into reality with cutting-edge solutions</p>
            <button class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition mr-4 mb-4">
                Explore Now
            </button>
            <button class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition">
                Learn More
            </button>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 px-4 sm:px-6 lg:px-8 bg-gray-50">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-4xl font-bold text-center text-gray-900 mb-12">About Us</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div>
                    <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?w=600&h=400&fit=crop" alt="About" class="rounded-lg shadow-lg">
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Who We Are</h3>
                    <p class="text-gray-700 mb-4 leading-relaxed">
                        TechVision is a leading technology company dedicated to delivering innovative solutions that drive business growth and transformation.
                    </p>
                    <p class="text-gray-700 mb-6 leading-relaxed">
                        With over 10 years of experience, we've helped hundreds of companies achieve their digital goals through cutting-edge technology and strategic partnerships.
                    </p>
                    <ul class="space-y-3">
                        <li class="flex items-center text-gray-700">
                            <span class="text-blue-600 mr-3">✓</span> Innovative Solutions
                        </li>
                        <li class="flex items-center text-gray-700">
                            <span class="text-blue-600 mr-3">✓</span> Expert Team
                        </li>
                        <li class="flex items-center text-gray-700">
                            <span class="text-blue-600 mr-3">✓</span> 24/7 Support
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-4xl font-bold text-center text-gray-900 mb-12">Our Services</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Service Card 1 -->
                <div class="bg-white rounded-lg shadow-lg p-8 hover:shadow-xl transition">
                    <div class="text-4xl mb-4">💻</div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Web Development</h3>
                    <p class="text-gray-700">
                        Build powerful, scalable web applications with modern technologies and best practices.
                    </p>
                </div>
                <!-- Service Card 2 -->
                <div class="bg-white rounded-lg shadow-lg p-8 hover:shadow-xl transition">
                    <div class="text-4xl mb-4">🎨</div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">UI/UX Design</h3>
                    <p class="text-gray-700">
                        Create beautiful, user-friendly interfaces that engage and delight your audience.
                    </p>
                </div>
                <!-- Service Card 3 -->
                <div class="bg-white rounded-lg shadow-lg p-8 hover:shadow-xl transition">
                    <div class="text-4xl mb-4">📱</div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Mobile Apps</h3>
                    <p class="text-gray-700">
                        Develop responsive mobile applications for iOS and Android platforms.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section id="team" class="py-20 px-4 sm:px-6 lg:px-8 bg-gray-50">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-4xl font-bold text-center text-gray-900 mb-12">Our Team</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Team Member 1 -->
                <div class="text-center">
                    <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=300&h=300&fit=crop" alt="Team Member" class="w-32 h-32 rounded-full mx-auto mb-4 object-cover">
                    <h3 class="text-xl font-bold text-gray-900">John Smith</h3>
                    <p class="text-blue-600">CEO & Founder</p>
                </div>
                <!-- Team Member 2 -->
                <div class="text-center">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=300&h=300&fit=crop" alt="Team Member" class="w-32 h-32 rounded-full mx-auto mb-4 object-cover">
                    <h3 class="text-xl font-bold text-gray-900">Sarah Johnson</h3>
                    <p class="text-blue-600">Lead Designer</p>
                </div>
                <!-- Team Member 3 -->
                <div class="text-center">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=300&h=300&fit=crop" alt="Team Member" class="w-32 h-32 rounded-full mx-auto mb-4 object-cover">
                    <h3 class="text-xl font-bold text-gray-900">Mike Chen</h3>
                    <p class="text-blue-600">Tech Lead</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 px-4 sm:px-6 lg:px-8 bg-blue-600">
        <div class="max-w-4xl mx-auto text-center text-white">
            <h2 class="text-4xl font-bold mb-8">Get In Touch</h2>
            <p class="text-xl mb-12 text-blue-100">Ready to start your next project? Contact us today!</p>
            <form class="space-y-6">
                <input type="text" placeholder="Your Name" class="w-full px-6 py-3 rounded-lg text-gray-900" required>
                <input type="email" placeholder="Your Email" class="w-full px-6 py-3 rounded-lg text-gray-900" required>
                <textarea placeholder="Your Message" rows="5" class="w-full px-6 py-3 rounded-lg text-gray-900" required></textarea>
                <button type="submit" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                    Send Message
                </button>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div>
                    <h4 class="text-lg font-bold mb-4">TechVision</h4>
                    <p class="text-gray-400">Innovating tomorrow, today.</p>
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-4">Services</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition">Web Development</a></li>
                        <li><a href="#" class="hover:text-white transition">UI/UX Design</a></li>
                        <li><a href="#" class="hover:text-white transition">Mobile Apps</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-4">Company</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition">About</a></li>
                        <li><a href="#" class="hover:text-white transition">Blog</a></li>
                        <li><a href="#" class="hover:text-white transition">Careers</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-4">Contact</h4>
                    <p class="text-gray-400">Email: info@techvision.com</p>
                    <p class="text-gray-400">Phone: +1 (555) 123-4567</p>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8">
                <p class="text-center text-gray-400">© 2025 TechVision. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>