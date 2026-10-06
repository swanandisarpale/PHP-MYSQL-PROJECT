<?php

/* =========================================================
   EMPLOYEE MANAGEMENT SYSTEM
   ADMIN LOGIN
========================================================= */

session_start();

/*
   IMPORTANT:
   Clear the previous login whenever login.php is opened.

   This means the login form will ALWAYS be shown,
   even if the user logged in previously.
*/

$_SESSION = array();

if (ini_get("session.use_cookies")) {

    $params = session_get_cookie_params();

    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

session_destroy();

/*
   Start a new session
*/

session_start();

include "db.php";

$error = "";


/* =========================================================
   LOGIN PROCESS
========================================================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    /* Get form values */

    $username = trim($_POST["username"] ?? "");
    $password = trim($_POST["password"] ?? "");


    /* =====================================================
       VALIDATION
    ===================================================== */

    if ($username == "" || $password == "") {

        $error = "Please enter username and password.";

    } else {


        /* =================================================
           FIND ADMIN
        ================================================= */

        $sql = "
            SELECT
                id,
                admin_name,
                username,
                email,
                phone,
                role,
                password,
                status
            FROM admin
            WHERE username = ?
            LIMIT 1
        ";

        $stmt = $conn->prepare($sql);


        /* =================================================
           CHECK SQL
        ================================================= */

        if (!$stmt) {

            $error = "Database error: " . $conn->error;

        } else {


            /* Bind username */

            $stmt->bind_param("s", $username);

            $stmt->execute();

            $result = $stmt->get_result();


            /* =================================================
               CHECK USERNAME
            ================================================= */

            if ($result->num_rows == 1) {

                $user = $result->fetch_assoc();


                /* =================================================
                   CHECK STATUS

                   Your current database contains:
                   status = yes

                   We accept both:
                   yes
                   active
                ================================================= */

                $status = strtolower(trim($user["status"]));


                if ($status != "yes" && $status != "active") {

                    $error = "Your account is inactive. Please contact administrator.";

                } else {


                    /* =================================================
                       CHECK PASSWORD

                       Your current database contains:
                       password = 1234

                       So we compare it directly.
                    ================================================= */

                    if ($password === $user["password"]) {


                        /* =============================================
                           LOGIN SUCCESS
                        ============================================= */

                        /*
                           Create a new session ID
                           for better security.
                        */

                        session_regenerate_id(true);


                        /* =============================================
                           STORE ADMIN INFORMATION
                        ============================================= */

                        $_SESSION["login_user"] = $user["id"];

                        $_SESSION["admin_name"] = $user["admin_name"];

                        $_SESSION["username"] = $user["username"];

                        $_SESSION["email"] = $user["email"];

                        $_SESSION["phone"] = $user["phone"];

                        $_SESSION["role"] = $user["role"];


                        /* =============================================
                           REDIRECT TO EMPLOYEE PAGE
                        ============================================= */

                        header("Location: employee.php");

                        exit();


                    } else {

                        /* Wrong password */

                        $error = "Invalid username or password.";

                    }
                }


            } else {

                /* Username does not exist */

                $error = "Invalid username or password.";

            }


            $stmt->close();
        }
    }
}

?>


<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Admin Login | Employee Management System</title>


<style>

/* =========================================================
   RESET
========================================================= */

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;

    font-family:
        "Segoe UI",
        Arial,
        Helvetica,
        sans-serif;
}


/* =========================================================
   BODY
========================================================= */

body {

    min-height: 100vh;

    display: flex;

    align-items: center;

    justify-content: center;

    padding: 25px;

    background:
        radial-gradient(
            circle at top left,
            rgba(37, 99, 235, 0.18),
            transparent 35%
        ),

        radial-gradient(
            circle at bottom right,
            rgba(18, 63, 115, 0.20),
            transparent 35%
        ),

        linear-gradient(
            135deg,
            #071d3a,
            #123f73
        );
}


/* =========================================================
   MAIN LOGIN CONTAINER
========================================================= */

.login-container {

    width: 100%;

    max-width: 1000px;

    min-height: 600px;

    display: flex;

    overflow: hidden;

    background: rgba(255,255,255,0.97);

    border-radius: 25px;

    box-shadow:
        0 30px 80px rgba(0,0,0,0.30);

    animation: containerAnimation 0.6s ease;
}


/* =========================================================
   LEFT SECTION
========================================================= */

.left-section {

    width: 50%;

    padding: 60px 55px;

    display: flex;

    flex-direction: column;

    justify-content: center;

    color: white;

    position: relative;

    overflow: hidden;

    background:
        linear-gradient(
            135deg,
            #061a33,
            #123f73 55%,
            #2563eb
        );
}


/* Decorative circles */

.left-section::before {

    content: "";

    position: absolute;

    width: 300px;

    height: 300px;

    border-radius: 50%;

    background:
        rgba(255,255,255,0.06);

    top: -120px;

    right: -100px;
}


.left-section::after {

    content: "";

    position: absolute;

    width: 250px;

    height: 250px;

    border-radius: 50%;

    background:
        rgba(255,255,255,0.05);

    bottom: -100px;

    left: -100px;
}


/* =========================================================
   LOGO
========================================================= */

.logo {

    width: 80px;

    height: 80px;

    border-radius: 22px;

    display: flex;

    align-items: center;

    justify-content: center;

    background:
        rgba(255,255,255,0.13);

    border:
        1px solid rgba(255,255,255,0.20);

    font-size: 38px;

    margin-bottom: 30px;

    backdrop-filter: blur(10px);

    box-shadow:
        0 10px 25px rgba(0,0,0,0.15);

    position: relative;

    z-index: 2;
}


/* =========================================================
   LEFT HEADING
========================================================= */

.left-section h1 {

    font-size: 40px;

    line-height: 1.15;

    font-weight: 800;

    margin-bottom: 22px;

    letter-spacing: -1px;

    position: relative;

    z-index: 2;
}


/* =========================================================
   LEFT DESCRIPTION
========================================================= */

.left-section p {

    font-size: 15px;

    line-height: 1.8;

    color: rgba(255,255,255,0.88);

    max-width: 400px;

    position: relative;

    z-index: 2;
}


/* =========================================================
   FEATURES
========================================================= */

.features {

    margin-top: 35px;

    position: relative;

    z-index: 2;
}


.feature {

    display: flex;

    align-items: center;

    gap: 13px;

    margin-bottom: 17px;

    font-size: 14px;

    color: rgba(255,255,255,0.94);
}


.feature-icon {

    width: 31px;

    height: 31px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 50%;

    background:
        rgba(255,255,255,0.13);

    border:
        1px solid rgba(255,255,255,0.18);

    font-size: 13px;
}


/* =========================================================
   RIGHT SECTION
========================================================= */

.right-section {

    width: 50%;

    padding: 65px 60px;

    display: flex;

    flex-direction: column;

    justify-content: center;

    background: #ffffff;
}


/* =========================================================
   LOGIN TITLE
========================================================= */

.login-title {

    margin-bottom: 35px;
}


.login-title h2 {

    font-size: 32px;

    color: #071d3a;

    font-weight: 800;

    margin-bottom: 9px;

    letter-spacing: -0.5px;
}


.login-title p {

    color: #718198;

    font-size: 14px;

    line-height: 1.6;
}


/* =========================================================
   ERROR MESSAGE
========================================================= */

.error {

    padding: 14px 16px;

    margin-bottom: 22px;

    border-radius: 10px;

    background:
        #fff1f2;

    border:
        1px solid #fecdd6;

    border-left:
        4px solid #dc264f;

    color:
        #be123c;

    font-size: 13px;

    font-weight: 600;

    animation: errorAnimation 0.3s ease;
}


/* =========================================================
   FORM GROUP
========================================================= */

.form-group {

    margin-bottom: 23px;
}


.form-group label {

    display: block;

    margin-bottom: 8px;

    color: #263b56;

    font-size: 13px;

    font-weight: 700;
}


/* =========================================================
   INPUT BOX
========================================================= */

.input-box {

    position: relative;

    width: 100%;
}


/* =========================================================
   INPUT ICON
========================================================= */

.input-icon {

    position: absolute;

    left: 15px;

    top: 50%;

    transform:
        translateY(-50%);

    font-size: 17px;

    color: #7890aa;

    pointer-events: none;
}


/* =========================================================
   INPUT
========================================================= */

.input-box input {

    width: 100%;

    height: 54px;

    padding:
        0 45px;

    border:
        1px solid #d3deea;

    border-radius: 11px;

    background:
        #f9fbfd;

    color:
        #172033;

    font-size: 14px;

    outline: none;

    transition:
        0.25s ease;
}


.input-box input::placeholder {

    color: #9aaabd;
}


.input-box input:hover {

    border-color:
        #8da5c1;

    background:
        #ffffff;
}


.input-box input:focus {

    border-color:
        #2563eb;

    background:
        #ffffff;

    box-shadow:
        0 0 0 4px rgba(37,99,235,0.10);

    transform:
        translateY(-1px);
}


/* =========================================================
   PASSWORD TOGGLE
========================================================= */

.password-toggle {

    position: absolute;

    right: 15px;

    top: 50%;

    transform:
        translateY(-50%);

    cursor: pointer;

    color:
        #718198;

    font-size: 17px;

    user-select: none;

    transition:
        0.2s ease;
}


.password-toggle:hover {

    color:
        #2563eb;

    transform:
        translateY(-50%)
        scale(1.1);
}


/* =========================================================
   LOGIN BUTTON
========================================================= */

.login-btn {

    width: 100%;

    height: 54px;

    border: none;

    border-radius: 11px;

    background:
        linear-gradient(
            135deg,
            #071d3a,
            #123f73 55%,
            #2563eb
        );

    color: white;

    font-size: 15px;

    font-weight: 700;

    cursor: pointer;

    letter-spacing: 0.2px;

    box-shadow:
        0 10px 25px rgba(7,29,58,0.22);

    transition:
        0.25s ease;

    position: relative;

    overflow: hidden;
}


/* Button shine */

.login-btn::before {

    content: "";

    position: absolute;

    top: 0;

    left: -100%;

    width: 100%;

    height: 100%;

    background:
        linear-gradient(
            90deg,
            transparent,
            rgba(255,255,255,0.20),
            transparent
        );

    transition:
        0.5s ease;
}


.login-btn:hover::before {

    left: 100%;
}


.login-btn:hover {

    transform:
        translateY(-2px);

    box-shadow:
        0 15px 30px rgba(7,29,58,0.28);
}


.login-btn:active {

    transform:
        translateY(0);
}


/* =========================================================
   FOOTER
========================================================= */

.footer-text {

    text-align: center;

    margin-top: 27px;

    color:
        #9aa8b8;

    font-size: 12px;
}


/* =========================================================
   ANIMATIONS
========================================================= */

@keyframes containerAnimation {

    from {

        opacity: 0;

        transform:
            translateY(20px)
            scale(0.98);
    }

    to {

        opacity: 1;

        transform:
            translateY(0)
            scale(1);
    }
}


@keyframes errorAnimation {

    from {

        opacity: 0;

        transform:
            translateY(-8px);
    }

    to {

        opacity: 1;

        transform:
            translateY(0);
    }
}


/* =========================================================
   TABLET
========================================================= */

@media (max-width: 850px) {

    .login-container {

        max-width: 520px;

        min-height: auto;
    }

    .left-section {

        display: none;
    }

    .right-section {

        width: 100%;

        padding:
            55px 45px;
    }
}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 500px) {

    body {

        padding: 12px;
    }

    .login-container {

        border-radius: 18px;
    }

    .right-section {

        padding:
            40px 25px;
    }

    .login-title h2 {

        font-size: 27px;
    }

    .login-title p {

        font-size: 13px;
    }

    .input-box input {

        height: 50px;
    }

    .login-btn {

        height: 50px;
    }
}

</style>

</head>


<body>


<!-- =====================================================
     LOGIN CONTAINER
===================================================== -->

<div class="login-container">


    <!-- =================================================
         LEFT SIDE
    ================================================= -->

    <div class="left-section">


        <div class="logo">
            👤
        </div>


        <h1>
            Employee<br>
            Management System
        </h1>


        <p>

            Manage your complete employee
            information from one secure,
            professional management system.

        </p>


        <div class="features">


            <div class="feature">

                <div class="feature-icon">
                    ✓
                </div>

                <span>
                    Employee Management
                </span>

            </div>


            <div class="feature">

                <div class="feature-icon">
                    ✓
                </div>

                <span>
                    Attendance Management
                </span>

            </div>


            <div class="feature">

                <div class="feature-icon">
                    ✓
                </div>

                <span>
                    Education & Certificates
                </span>

            </div>


            <div class="feature">

                <div class="feature-icon">
                    ✓
                </div>

                <span>
                    Salary Management
                </span>

            </div>


        </div>


    </div>



    <!-- =================================================
         RIGHT SIDE
    ================================================= -->

    <div class="right-section">


        <div class="login-title">

            <h2>
                Welcome Back 👋
            </h2>

            <p>
                Please login to access your employee
                management dashboard.
            </p>

        </div>



        <!-- =================================================
             ERROR MESSAGE
        ================================================= -->

        <?php if ($error != "") { ?>

            <div class="error">

                ⚠️

                <?php
                echo htmlspecialchars($error);
                ?>

            </div>

        <?php } ?>



        <!-- =================================================
             LOGIN FORM
        ================================================= -->

        <form
            method="POST"
            action=""
            autocomplete="off"
        >


            <!-- USERNAME -->

            <div class="form-group">

                <label>
                    Username
                </label>


                <div class="input-box">


                    <span class="input-icon">
                        👤
                    </span>


                    <input
                        type="text"
                        name="username"
                        placeholder="Enter your username"
                        value="<?php
                        echo htmlspecialchars(
                            $_POST["username"] ?? ""
                        );
                        ?>"
                        autocomplete="off"
                        required
                    >


                </div>

            </div>



            <!-- PASSWORD -->

            <div class="form-group">

                <label>
                    Password
                </label>


                <div class="input-box">


                    <span class="input-icon">
                        🔒
                    </span>


                    <input
                        type="password"
                        name="password"
                        id="password"
                        placeholder="Enter your password"
                        autocomplete="new-password"
                        required
                    >


                    <span
                        class="password-toggle"
                        id="toggleIcon"
                        onclick="togglePassword()"
                    >
                        👁
                    </span>


                </div>

            </div>



            <!-- LOGIN BUTTON -->

            <button
                type="submit"
                class="login-btn"
            >

                Login to Dashboard

            </button>


        </form>



        <!-- FOOTER -->

        <div class="footer-text">

            Employee Management System © 2026

        </div>


    </div>

</div>



<!-- =====================================================
     JAVASCRIPT
===================================================== -->

<script>

function togglePassword() {

    const password =
        document.getElementById("password");

    const icon =
        document.getElementById("toggleIcon");


    if (password.type === "password") {

        password.type = "text";

        icon.textContent = "🙈";

    } else {

        password.type = "password";

        icon.textContent = "👁";

    }

}

</script>


</body>

</html>