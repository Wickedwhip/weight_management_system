<?php include('includes/header.php'); ?>

<section class="home-section">
  <div class="overlay">
    <img src="assets/img/logo.jpg" alt="Logo" class="logo">
    <div class="home-content">
      <h1>Welcome to the <span>Weight Management System</span></h1>
      <p>Track your meals, workouts, and progress with ease and consistency.</p>
      <div class="home-buttons">
        <a href="pages/register.php" class="btn main-btn">Get Started</a>
        <a href="pages/login.php" class="btn secondary-btn">Login</a>
        <a href="admin/index.php" class="btn admin-btn">Admin Login</a>
      </div>
    </div>
  </div>
</section>

<?php include('includes/footer.php'); ?>

<style>
  /* --- Section Background --- */
  .home-section {
    position: relative;
    background: url('assets/img/background.jpg') no-repeat center center/cover;
    min-height: 90vh;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .overlay {
    background: rgba(0, 0, 0, 0.65);
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 40px 20px;
    text-align: center;
  }

  /* --- Logo --- */
  .logo {
    width: 150px;
    height: auto;
    border-radius: 50%;
    margin-bottom: 25px;
    box-shadow: 0 0 20px rgba(255, 46, 99, 0.4);
  }

  /* --- Text Content --- */
  .home-content h1 {
    font-size: 2.8rem;
    color: #feca57;
    margin-bottom: 15px;
  }

  .home-content h1 span {
    color: #ff2e63;
  }

  .home-content p {
    font-size: 1.1rem;
    color: #ccc;
    max-width: 600px;
    margin: 0 auto 30px;
    line-height: 1.6;
  }

  /* --- Buttons --- */
  .home-buttons {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 15px;
  }

  .btn {
    display: inline-block;
    padding: 12px 28px;
    font-size: 1rem;
    font-weight: 600;
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.3s ease;
  }

  .main-btn {
    background: #ff2e63;
    color: #fff;
    box-shadow: 0 4px 10px rgba(255, 46, 99, 0.3);
  }

  .main-btn:hover {
    background: #feca57;
    color: #000;
    transform: translateY(-3px);
  }

  .secondary-btn {
    background: transparent;
    color: #feca57;
    border: 2px solid #feca57;
  }

  .secondary-btn:hover {
    background: #ff2e63;
    color: #fff;
    border-color: transparent;
    transform: translateY(-3px);
  }

  .admin-btn {
    background: #333;
    color: #00d2ff;
    border: 2px solid #00d2ff;
  }

  .admin-btn:hover {
    background: #00d2ff;
    color: #000;
    transform: translateY(-3px);
  }
</style>
