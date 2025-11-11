<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Panel - Weight Management System</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <style>
    /* === ADMIN HEADER FIXES & RESPONSIVENESS === */
    header nav {
      background: rgba(255, 46, 99, 0.9);
      padding: 15px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.5);
      position: sticky;
      top: 0;
      z-index: 1000;
    }

    .nav-left {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .nav-logo {
      width: 45px;
      height: 45px;
      border-radius: 50%;
      object-fit: cover;
      box-shadow: 0 0 15px rgba(255, 46, 99, 0.6);
    }

    header nav h2 {
      font-size: 20px;
      font-weight: 600;
      letter-spacing: 1px;
    }

    header nav ul {
      list-style: none;
      display: flex;
      gap: 25px;
      transition: max-height 0.3s ease-in-out;
    }

    header nav ul li a {
      color: #fff;
      text-decoration: none;
      font-weight: 500;
      transition: 0.3s;
    }

    header nav ul li a:hover {
      color: #feca57;
    }

    /* === HAMBURGER BUTTON === */
    .menu-toggle {
      display: none;
      flex-direction: column;
      cursor: pointer;
      gap: 5px;
    }

    .menu-toggle span {
      width: 25px;
      height: 3px;
      background: #fff;
      border-radius: 5px;
      transition: 0.3s;
    }

    /* === MOBILE MODE === */
    @media (max-width: 820px) {
      header nav ul {
        position: absolute;
        top: 70px;
        right: 0;
        background: rgba(17, 17, 17, 0.98);
        flex-direction: column;
        align-items: center;
        width: 100%;
        max-height: 0;
        overflow: hidden;
      }

      header nav ul.active {
        max-height: 400px;
        padding: 20px 0;
      }

      .menu-toggle {
        display: flex;
      }
    }

    /* === ANIMATION EFFECT === */
    .menu-toggle.active span:nth-child(1) {
      transform: rotate(45deg) translate(5px, 5px);
    }

    .menu-toggle.active span:nth-child(2) {
      opacity: 0;
    }

    .menu-toggle.active span:nth-child(3) {
      transform: rotate(-45deg) translate(6px, -6px);
    }
  </style>
</head>
<body>

<header>
  <nav>
    <div class="nav-left">
     <img src="/weight_management_system/assets/img/logo.jpg" alt="Logo" class="nav-logo">


      <h2>Admin Dashboard</h2>
    </div>

    <!-- Hamburger -->
    <div class="menu-toggle" id="menuToggle">
      <span></span>
      <span></span>
      <span></span>
    </div>

    <!-- Navigation Links -->
    <ul id="navMenu">
      <li><a href="dashboard.php">🏠 Dashboard</a></li>
      <li><a href="manage_users.php">👤 Users</a></li>
      <li><a href="manage_meals.php">🍽 Meals</a></li>
      <li><a href="manage_workouts.php">💪 Workouts</a></li>
      <li><a href="manage_progress.php">📊 Progress</a></li>
      <li><a href="manage_reminders.php">⏰ Reminders</a></li>
      <li><a href="logout.php">🚪 Logout</a></li>
      <li><a href="../index.php" style="color:#feca57;">⬅️ Home</a></li>
    </ul>
  </nav>
</header>

<script>
  // Toggle mobile navigation
  const menuToggle = document.getElementById("menuToggle");
  const navMenu = document.getElementById("navMenu");

  menuToggle.addEventListener("click", () => {
    navMenu.classList.toggle("active");
    menuToggle.classList.toggle("active");
  });
</script>

</body>
</html>
