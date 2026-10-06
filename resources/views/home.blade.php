<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Indexfurniture - Timeless Furniture & Modern Living. Our new digital experience is coming soon.">
    <title>Coming Soon | Indexfurniture</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,400&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-primary: #0a0b0e;
            --bg-card: rgba(22, 25, 34, 0.65);
            --border-card: rgba(255, 255, 255, 0.08);
            --gold-accent: #d4a373;
            --gold-glow: rgba(212, 163, 115, 0.25);
            --amber-accent: #e09f3e;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --text-dim: #64748b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-main);
            min-height: 100vh;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
        }

        /* Ambient Glowing Background Elements */
        .ambient-glow {
            position: fixed;
            border-radius: 50%;
            filter: blur(120px);
            pointer-events: none;
            z-index: 0;
            opacity: 0.45;
            animation: pulse-glow 9s ease-in-out infinite alternate;
        }

        .glow-1 {
            width: 500px;
            height: 500px;
            top: -100px;
            left: -100px;
            background: radial-gradient(circle, #e09f3e 0%, rgba(224, 159, 62, 0) 70%);
        }

        .glow-2 {
            width: 550px;
            height: 550px;
            bottom: -120px;
            right: -120px;
            background: radial-gradient(circle, #3b82f6 0%, rgba(59, 130, 246, 0) 70%);
            opacity: 0.25;
            animation-duration: 12s;
        }

        .glow-3 {
            width: 350px;
            height: 350px;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: radial-gradient(circle, #d4a373 0%, rgba(212, 163, 115, 0) 70%);
            opacity: 0.18;
        }

        @keyframes pulse-glow {
            0% { transform: scale(1) translate(0, 0); opacity: 0.35; }
            50% { transform: scale(1.15) translate(30px, -20px); opacity: 0.55; }
            100% { transform: scale(0.95) translate(-20px, 15px); opacity: 0.35; }
        }

        /* Subtle Grid Pattern Overlay */
        .bg-grid {
            position: fixed;
            inset: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px);
            background-size: 32px 32px;
            pointer-events: none;
            z-index: 0;
            mask-image: radial-gradient(circle at center, black 40%, transparent 85%);
            -webkit-mask-image: radial-gradient(circle at center, black 40%, transparent 85%);
        }

        /* Header */
        header {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 2.25rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: var(--text-main);
        }

        .logo-symbol {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, #d4a373, #e09f3e);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 20px var(--gold-glow);
            color: #0f1115;
            font-weight: 700;
            font-size: 1.25rem;
            letter-spacing: -0.05em;
        }

        .logo-text {
            display: flex;
            flex-direction: column;
        }

        .logo-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #ffffff;
            line-height: 1.1;
        }

        .logo-tagline {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.28em;
            color: var(--gold-accent);
            font-weight: 500;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--border-card);
            backdrop-filter: blur(12px);
            padding: 0.45rem 1rem;
            border-radius: 9999px;
            font-size: 0.8rem;
            color: var(--text-muted);
            letter-spacing: 0.05em;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #22c55e;
            box-shadow: 0 0 10px #22c55e;
            animation: blink 2s infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(0.85); }
        }

        /* Main Content */
        main {
            position: relative;
            z-index: 10;
            max-width: 960px;
            margin: auto;
            padding: 2rem 1.5rem;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .badge-announcement {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 1.1rem;
            border-radius: 9999px;
            background: rgba(212, 163, 115, 0.1);
            border: 1px solid rgba(212, 163, 115, 0.28);
            color: var(--gold-accent);
            font-size: 0.85rem;
            font-weight: 500;
            letter-spacing: 0.04em;
            margin-bottom: 1.75rem;
            transition: all 0.3s ease;
        }

        .badge-announcement:hover {
            background: rgba(212, 163, 115, 0.16);
            border-color: rgba(212, 163, 115, 0.45);
        }

        h1.hero-title {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: clamp(2.5rem, 6.5vw, 4.75rem);
            font-weight: 700;
            line-height: 1.1;
            margin-bottom: 1.25rem;
            letter-spacing: -0.01em;
            color: #ffffff;
        }

        .gradient-text {
            background: linear-gradient(135deg, #ffffff 30%, #d4a373 75%, #e09f3e 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }

        p.hero-description {
            font-size: clamp(1rem, 2.2vw, 1.25rem);
            line-height: 1.7;
            color: var(--text-muted);
            max-width: 650px;
            margin-bottom: 2.75rem;
            font-weight: 300;
        }

        /* Countdown Grid */
        .countdown-wrap {
            display: flex;
            gap: 1rem;
            margin-bottom: 3.25rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .countdown-item {
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 16px;
            padding: 1.25rem 1.5rem;
            min-width: 105px;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.3s ease;
        }

        .countdown-item:hover {
            transform: translateY(-4px);
            border-color: rgba(212, 163, 115, 0.35);
        }

        .countdown-value {
            font-family: 'Outfit', sans-serif;
            font-size: 2.5rem;
            font-weight: 700;
            color: #ffffff;
            line-height: 1;
            margin-bottom: 0.35rem;
        }

        .countdown-label {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.18em;
            color: var(--gold-accent);
            font-weight: 500;
        }

        /* Notify Form */
        .notify-container {
            width: 100%;
            max-width: 520px;
            margin-bottom: 3rem;
        }

        .notify-form {
            display: flex;
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            backdrop-filter: blur(20px);
            border-radius: 16px;
            padding: 0.4rem;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .notify-form:focus-within {
            border-color: rgba(212, 163, 115, 0.6);
            box-shadow: 0 0 0 3px rgba(212, 163, 115, 0.15), 0 20px 45px rgba(0, 0, 0, 0.5);
        }

        .notify-input {
            flex: 1;
            background: transparent;
            border: none;
            outline: none;
            padding: 0.85rem 1.25rem;
            font-size: 0.95rem;
            color: #ffffff;
            font-family: inherit;
        }

        .notify-input::placeholder {
            color: var(--text-dim);
        }

        .notify-button {
            background: linear-gradient(135deg, #d4a373, #e09f3e);
            color: #0f1115;
            font-weight: 600;
            border: none;
            border-radius: 12px;
            padding: 0.85rem 1.75rem;
            cursor: pointer;
            font-size: 0.95rem;
            letter-spacing: 0.02em;
            transition: all 0.25s ease;
            white-space: nowrap;
            box-shadow: 0 4px 15px var(--gold-glow);
        }

        .notify-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 22px rgba(212, 163, 115, 0.4);
            filter: brightness(1.05);
        }

        .notify-button:active {
            transform: translateY(0);
        }

        .form-feedback {
            margin-top: 0.9rem;
            font-size: 0.88rem;
            display: none;
            animation: fadeIn 0.4s ease forwards;
        }

        .form-feedback.success {
            display: block;
            color: #4ade80;
        }

        .form-feedback.error {
            display: block;
            color: #f87171;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Pillars / Features Showcase */
        .features-strip {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
            justify-content: center;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            padding-top: 2rem;
            width: 100%;
        }

        .feature-badge {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            color: var(--text-muted);
            font-size: 0.88rem;
            letter-spacing: 0.02em;
        }

        .feature-icon {
            color: var(--gold-accent);
            font-size: 1.1rem;
        }

        /* Footer */
        footer {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1.5rem 2.5rem;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            align-items: center;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        @media (min-width: 768px) {
            footer {
                flex-direction: row;
                justify-content: space-between;
            }
        }

        .copyright-text {
            color: var(--text-dim);
            font-size: 0.85rem;
        }

        .social-links {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .social-btn {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-card);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            text-decoration: none;
            transition: all 0.25s ease;
        }

        .social-btn:hover {
            color: #ffffff;
            border-color: rgba(212, 163, 115, 0.4);
            background: rgba(212, 163, 115, 0.1);
            transform: translateY(-2px);
        }

        .social-btn svg {
            width: 18px;
            height: 18px;
            fill: currentColor;
        }

        @media (max-width: 640px) {
            .countdown-wrap {
                gap: 0.6rem;
            }
            .countdown-item {
                min-width: 75px;
                padding: 1rem 0.8rem;
            }
            .countdown-value {
                font-size: 1.85rem;
            }
            .notify-form {
                flex-direction: column;
                padding: 0.6rem;
                gap: 0.5rem;
            }
            .notify-button {
                width: 100%;
                padding: 0.85rem;
            }
        }
    </style>
</head>
<body>

    <!-- Ambient Glowing Blobs -->
    <div class="ambient-glow glow-1"></div>
    <div class="ambient-glow glow-2"></div>
    <div class="ambient-glow glow-3"></div>
    <div class="bg-grid"></div>

    <!-- Navigation / Header -->
    <header>
        <a href="/" class="brand-logo" title="Indexfurniture">
            <div class="logo-symbol">I</div>
            <div class="logo-text">
                <span class="logo-title">Indexfurniture</span>
                <span class="logo-tagline">Living Reimagined</span>
            </div>
        </a>

        <div class="status-pill">
            <span class="status-dot"></span>
            <span>Coming Soon</span>
        </div>
    </header>

    <!-- Main Content Area -->
    <main>
        <div class="badge-announcement">
            <span>✨</span>
            <span>Something Special is in the Works</span>
        </div>

        <h1 class="hero-title">
            Crafting Elegance for <br>
            <span class="gradient-text">Your Modern Living</span>
        </h1>

        <p class="hero-description">
            We are designing a new digital shopping experience showcasing the pinnacle of contemporary furniture, smart interiors, and sustainable craftsmanship.
        </p>

        <!-- Dynamic Live Countdown -->
        <div class="countdown-wrap" id="countdown">
            <div class="countdown-item">
                <span class="countdown-value" id="days">24</span>
                <span class="countdown-label">Days</span>
            </div>
            <div class="countdown-item">
                <span class="countdown-value" id="hours">18</span>
                <span class="countdown-label">Hours</span>
            </div>
            <div class="countdown-item">
                <span class="countdown-value" id="minutes">45</span>
                <span class="countdown-label">Minutes</span>
            </div>
            <div class="countdown-item">
                <span class="countdown-value" id="seconds">30</span>
                <span class="countdown-label">Seconds</span>
            </div>
        </div>

        <!-- Notification Signup Form -->
        <div class="notify-container">
            <form class="notify-form" id="notifyForm" onsubmit="handleNotifySubmit(event)">
                <input 
                    type="email" 
                    id="notifyEmail"
                    class="notify-input" 
                    placeholder="Enter your email for early access..." 
                    required
                    autocomplete="email"
                >
                <button type="submit" class="notify-button">
                    Notify Me
                </button>
            </form>
            <div id="formFeedback" class="form-feedback"></div>
        </div>

        <!-- Key Pillars Strip -->
        <div class="features-strip">
            <div class="feature-badge">
                <span class="feature-icon">✦</span>
                <span>Artisan Craftsmanship</span>
            </div>
            <div class="feature-badge">
                <span class="feature-icon">✦</span>
                <span>Ergonomic Modern Design</span>
            </div>
            <div class="feature-badge">
                <span class="feature-icon">✦</span>
                <span>Nationwide Delivery</span>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <p class="copyright-text">
            &copy; {{ date('Y') }} Indexfurniture Limited. All rights reserved.
        </p>

        <div class="social-links">
            <!-- Facebook -->
            <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" class="social-btn" title="Facebook">
                <svg viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
            </a>
            <!-- Instagram -->
            <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="social-btn" title="Instagram">
                <svg viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
            </a>
            <!-- LinkedIn -->
            <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer" class="social-btn" title="LinkedIn">
                <svg viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
            </a>
        </div>
    </footer>

    <!-- Interactive Script -->
    <script>
        // Set launch target date (30 days from now)
        const launchDate = new Date();
        launchDate.setDate(launchDate.getDate() + 24);
        launchDate.setHours(launchDate.getHours() + 18);

        function updateCountdown() {
            const now = new Date().getTime();
            const distance = launchDate.getTime() - now;

            if (distance < 0) {
                document.getElementById('days').textContent = '00';
                document.getElementById('hours').textContent = '00';
                document.getElementById('minutes').textContent = '00';
                document.getElementById('seconds').textContent = '00';
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById('days').textContent = String(days).padStart(2, '0');
            document.getElementById('hours').textContent = String(hours).padStart(2, '0');
            document.getElementById('minutes').textContent = String(minutes).padStart(2, '0');
            document.getElementById('seconds').textContent = String(seconds).padStart(2, '0');
        }

        setInterval(updateCountdown, 1000);
        updateCountdown();

        // Handle early access notify submit
        function handleNotifySubmit(e) {
            e.preventDefault();
            const emailInput = document.getElementById('notifyEmail');
            const feedback = document.getElementById('formFeedback');
            const submitBtn = e.target.querySelector('button');

            const email = emailInput.value.trim();
            if (!email) return;

            submitBtn.disabled = true;
            submitBtn.textContent = 'Submitting...';

            setTimeout(() => {
                feedback.className = 'form-feedback success';
                feedback.innerHTML = '🎉 Thank you! You have been added to our exclusive launch list.';
                emailInput.value = '';
                submitBtn.disabled = false;
                submitBtn.textContent = 'Notify Me';
            }, 600);
        }
    </script>
</body>
</html>
