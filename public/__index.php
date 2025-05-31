<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>We'll Be Right Back | ISP Name</title>
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(to right, #eef1f5, #f9fbfc);
      color: #333;
      height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
    }

    .logo {
      width: 120px;
      margin-bottom: 25px;
    }

    h1 {
      font-size: 2.8rem;
      margin-bottom: 15px;
      color: #F28D24;
    }

    p {
      font-size: 1.1rem;
      max-width: 700px;
      line-height: 1.6;
      color: #555;
      margin: 0 20px 30px;
    }

    .loader {
      border: 6px solid #e0e0e0;
      border-top: 6px solid #0078D7;
      border-radius: 50%;
      width: 50px;
      height: 50px;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    footer {
      position: absolute;
      bottom: 20px;
      font-size: 0.9rem;
      color: #888;
    }

    footer b {
      color: #444;
    }

    @media (max-width: 600px) {
      h1 {
        font-size: 2rem;
      }

      p {
        font-size: 1rem;
      }
    }
  </style>
</head>
<body>

  <img src="./assets/librify-logo.png" alt="ISP Logo" class="logo">

  <h1>We'll Be Right Back</h1>
  <p>
    The developer is currently performing scheduled maintenance to improve the overall functionality, stability, and performance of the system. <br><br>
    This includes resolving known issues, refining existing features, and introducing new enhancements that aim to deliver a smoother and more efficient user experience. <br><br>
    Thank you for your patience and understanding during this time.
  </p>

  <div class="loader"></div>

  <footer>
    &copy; 2025 <b>Librify IT. Solutions</b>. All rights reserved.
  </footer>

</body>
</html>
