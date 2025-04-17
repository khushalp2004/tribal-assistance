<?php
session_start();
$conn = new mysqli("localhost", "root", "", "user_data");
$username=$_SESSION['auth_user']['username'];
$email=$_SESSION['auth_user']['email'];

if($email==="abcbank29@gmail.com"){
    header("location: admin.php");
    exit();
}
if(!$username){
    $username="Guest";
}
$language="English";
$language=$_SESSION['auth_user']['language'];
$is_logged_in=$_SESSION['auth_user']['verify_status'];
if ($is_logged_in===1) {
    $user_id = $_SESSION['user_id'];
    $result = $conn->query("SELECT username,language FROM users WHERE id = '$user_id'");

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $username = $user['username'];
    } else {
        echo "Error fetching user details!";
        exit;
    }
}

$conn->close();
?>

<?php if($language==="English"): ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/locomotive-scroll@3.5.4/dist/locomotive-scroll.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <!-- Header with navigation -->
    <header>
        <nav>
            <div class="logo">Tribal<span> Assistance</span></div>
            <div class="menu-icon" id="menu-icon" aria-label="Toggle navigation menu" role="button" tabindex="0">
                <span class="nav-icon"></span>
            </div>
            <ul class="nav-links" id="nav-links">
                <?php if ($is_logged_in==="1"): ?>
                    <li><a href="index.php">Home</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login/Register</a></li>
                <?php endif; ?>
                <?php if($is_logged_in==="1"): ?>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="#" onclick="scrollToFooter()">Help</a></li>
                <li><a href="profile.php">My Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <br><br><br><br><br>
    <h1>Welcome, <?= htmlspecialchars($username) ?>!</h1>
    
        <!-- Main content -->
        <main>
            <section>
                <div class='section-img'>
                    <div class='texto'>
                        <h1> Bridging Dreams with Financial Support.</h1>
                        <p> "Your dreams matter, and we're here to help you make them real. With every step you take, we’ll be right there beside
                        you, cheering you on. Together, we can turn hopes into achievements and challenges into victories. Let’s create a future
                        full of promise, side by side.". </p>
                    </div>
                    <div class='imag'>
                        <img
                            src='images/mb6g_nsfm_230519.jpg'>
                    </div>
                </div>
            </section>
            
            <section>
                <div class='section-tres-columnas'>
            
                    <div class='card yellow'>
                        <svg class='icon-card' viewBox="0 0 352 512" width="100" title="lightbulb">
                            <path
                                d="M96.06 454.35c.01 6.29 1.87 12.45 5.36 17.69l17.09 25.69a31.99 31.99 0 0 0 26.64 14.28h61.71a31.99 31.99 0 0 0 26.64-14.28l17.09-25.69a31.989 31.989 0 0 0 5.36-17.69l.04-38.35H96.01l.05 38.35zM0 176c0 44.37 16.45 84.85 43.56 115.78 16.52 18.85 42.36 58.23 52.21 91.45.04.26.07.52.11.78h160.24c.04-.26.07-.51.11-.78 9.85-33.22 35.69-72.6 52.21-91.45C335.55 260.85 352 220.37 352 176 352 78.61 272.91-.3 175.45 0 73.44.31 0 82.97 0 176zm176-80c-44.11 0-80 35.89-80 80 0 8.84-7.16 16-16 16s-16-7.16-16-16c0-61.76 50.24-112 112-112 8.84 0 16 7.16 16 16s-7.16 16-16 16z" />
                        </svg>
                        <DIV class='title-card'>Tractor / Trolly Loan</DIV>
                        <DIV class='p-card'>Regarding granting loans to
                        tribal communites under the
                        tractor with trolley scheme
                        of NSTFDC</DIV>
                        <button class='btn-card'><span>know more</span> <svg viewBox="0 0 448 512" width="100"
                                title="long-arrow-alt-right">
                                <path
                                    d="M313.941 216H12c-6.627 0-12 5.373-12 12v56c0 6.627 5.373 12 12 12h301.941v46.059c0 21.382 25.851 32.09 40.971 16.971l86.059-86.059c9.373-9.373 9.373-24.569 0-33.941l-86.059-86.059c-15.119-15.119-40.971-4.411-40.971 16.971V216z" />
                            </svg></button>
                    </div>
            
                    <div class='card green'>
                        <svg class='icon-card' viewBox="0 0 576 512" width="100" title="credit-card">
                            <path
                                d="M0 432c0 26.5 21.5 48 48 48h480c26.5 0 48-21.5 48-48V256H0v176zm192-68c0-6.6 5.4-12 12-12h136c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12H204c-6.6 0-12-5.4-12-12v-40zm-128 0c0-6.6 5.4-12 12-12h72c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12H76c-6.6 0-12-5.4-12-12v-40zM576 80v48H0V80c0-26.5 21.5-48 48-48h480c26.5 0 48 21.5 48 48z" />
                        </svg>
                        <DIV class='title-card'>Higher Study Loan</DIV>
                        <DIV class='p-card'>Regarding providing IELTS
                        and TOEFL training to
                        TRIBAL students for study
                        abroad.</DIV>
                        <button class='btn-card'><span>know more</span> <svg viewBox="0 0 448 512" width="100"
                                title="long-arrow-alt-right">
                                <path
                                    d="M313.941 216H12c-6.627 0-12 5.373-12 12v56c0 6.627 5.373 12 12 12h301.941v46.059c0 21.382 25.851 32.09 40.971 16.971l86.059-86.059c9.373-9.373 9.373-24.569 0-33.941l-86.059-86.059c-15.119-15.119-40.971-4.411-40.971 16.971V216z" />
                            </svg></button>
                    </div>
            
            
                    <div class='card violet'>
                        <svg class='icon-card' viewBox="0 0 640 512" width="100" title="user-clock">
                            <path
                                d="M496 224c-79.6 0-144 64.4-144 144s64.4 144 144 144 144-64.4 144-144-64.4-144-144-144zm64 150.3c0 5.3-4.4 9.7-9.7 9.7h-60.6c-5.3 0-9.7-4.4-9.7-9.7v-76.6c0-5.3 4.4-9.7 9.7-9.7h12.6c5.3 0 9.7 4.4 9.7 9.7V352h38.3c5.3 0 9.7 4.4 9.7 9.7v12.6zM320 368c0-27.8 6.7-54.1 18.2-77.5-8-1.5-16.2-2.5-24.6-2.5h-16.7c-22.2 10.2-46.9 16-72.9 16s-50.6-5.8-72.9-16h-16.7C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h347.1c-45.3-31.9-75.1-84.5-75.1-144zm-96-112c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128z" />
                        </svg>
                        <DIV class='title-card'>Home Business Loan</DIV>
                        <DIV class='p-card'>Loan for the purpose of
                        home industry, masala,
                        grinding, equipment,
                        athana-papad, wadi under
                        SWAROJGARI YOJANA to
                        TRIBAL people.</DIV>
                        <button class='btn-card'><span>know more</span> <svg viewBox="0 0 448 512" width="100"
                                title="long-arrow-alt-right">
                                <path
                                    d="M313.941 216H12c-6.627 0-12 5.373-12 12v56c0 6.627 5.373 12 12 12h301.941v46.059c0 21.382 25.851 32.09 40.971 16.971l86.059-86.059c9.373-9.373 9.373-24.569 0-33.941l-86.059-86.059c-15.119-15.119-40.971-4.411-40.971 16.971V216z" />
                            </svg></button>
                    </div>
            
            
                </div>
            </section>
            
            
            
            <section>
                <div class='section-img'>
                    <!-- <div class='texto'>
                        <h1> How To Apply for Loan?</h1>
                        <p> Get started to know how to Login , Sign-up , How to know for which Loan you are applying , how to know you are eligible for that particular loan or not. How to fill the form for it and etc through The Tutorial video of our website.  </p>
                        <button class='brutal-btn'> Watch Video</button>
                    </div> -->
                    <div class='imag'>
                        <img class='clip-path-square'
                            src='images/2302.q894.029.P.m009.c20.Cossack flat set.jpg '>
                    </div>
                    <div class='texto'>
                        <h1> How To Apply for Loan?</h1>
                        <p> Get started to know how to Login , Sign-up , How to know for which Loan you are applying , how to know you are
                            eligible for that particular loan or not. How to fill the form for it and etc through The Tutorial video of our
                            website. </p>
                        <button class='brutal-btn'> Watch Video</button>
                    </div>
                </div>
                <div id="page2">
                
                    <div id="middle">
                        <h1>LOANS</h1>
                    </div>
                
                    <hr>
                    <div class="loan-section">
                
                        <div class="box">
                            <audio id="hoverAudio" src="audio/One Love - Shubh 128 Kbps.mp3"></audio>
                            <div class="play" id="hoverDiv"><i class="fa-solid fa-volume-high fa-beat"></i></div>
                            <div class="image"><img class="boxim"
                                    src="https://images.unsplash.com/photo-1530267981375-f0de937f5f13?w=800&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTV8fHRyYWN0b3IlMjB0cm9sbHl8ZW58MHx8MHx8fDA%3D"
                                    alt=""></div>
                            <div class="content">
                                <div id="eli">
                                    <h3 class="tit">Eligibility</h3>
                                    <hr>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> <b>Age </b> <i
                                        class="fa-solid fa-arrow-right" style="color: #000000;"></i> 18 years - 65 years<br>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> <b>Income </b> <i
                                        class="fa-solid fa-arrow-right" style="color: #000000;"></i> Based on size of land
                                    holdings and per acre yield<br>
                                </div>
                                <div id="doc">
                                    <h3 class="tit">Document</h3>
                                    <hr>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> Aadhaar /PAN card <br>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> Voter's ID card / Driving License
                                    / Passport<br>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> Agricultural land document​<br>
                                </div>
                            </div>
                            <div class="niche">
                                <div id="niche1"><a href="form.php">Apply Now <i class="fa-solid fa-arrow-up-right-from-square"
                                            style="color: #000000;"></i></a></div>
                                <div id="niche2"><a href="video.html">Play Video <i class="fa-brands fa-youtube fa-lg"
                                            style="color: #ffffff;"></i></a></div>
                            </div>
                        </div>
                
                
                 
                        <div class="box">
                            <div class="play"><i class="fa-solid fa-volume-high fa-beat"></i></div>
                            <div class="image"><img class="boxim"
                                    src="https://images.unsplash.com/photo-1589391886645-d51941baf7fb?w=800&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTF8fGxhd3llcnxlbnwwfHwwfHx8MA%3D%3D"
                                    alt=""></div>
                            <div class="content">
                                <div id="eli">
                                    <h3 class="tit">Eligibility</h3>
                                    <hr>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> <b>Age </b> <i
                                        class="fa-solid fa-arrow-right" style="color: #000000;"></i> Not Required<br>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> <b>Income </b> <i
                                        class="fa-solid fa-arrow-right" style="color: #000000;"></i> not Required<br>
                                </div>
                                <div id="doc">
                                    <h3 class="tit">Document</h3>
                                    <hr>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> Aadhaar /PAN card <br>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> Cast Cirtificate<br>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> Voter ID Card​<br>
                                </div>
                            </div>
                            <div class="niche">
                                <div id="niche1"><a href="form2.php">Apply Now <i class="fa-solid fa-arrow-up-right-from-square"
                                            style="color: #000000;"></i></a></div>
                                <div id="niche2"><a href="#">Play Video <i class="fa-brands fa-youtube fa-lg"
                                            style="color: #ffffff;"></i></a></div>
                            </div>
                        </div>
                
                        <div class="box">
                            <div class="play"><i class="fa-solid fa-volume-high fa-beat"></i></div>
                            <div class="image"><img class="boxim"
                                    src="https://images.unsplash.com/photo-1581590289958-0a34272868dc?w=800&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTR8fGdyb2NlcnklMjBzdG9yZSUyMHZlbmRvciU1Q3xlbnwwfHwwfHx8MA%3D%3D"
                                    alt=""></div>
                            <div class="content">
                                <div id="eli">
                                    <h3 class="tit">Eligibility</h3>
                                    <hr>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> <b>Age </b> <i
                                        class="fa-solid fa-arrow-right" style="color: #000000;"></i> 18 years - 55 years<br>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> <b>Income </b> <i
                                        class="fa-solid fa-arrow-right" style="color: #000000;"></i> 120000<br>
                                </div>
                                <div id="doc">
                                    <h3 class="tit">Document</h3>
                                    <hr>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> Voter ID card* & Aadhar card*
                                    <br>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> Certificated of Experience
                                    Business <br>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> Signature Commisinor​<br>
                                </div>
                            </div>
                            <div class="niche">
                                <div id="niche1"><a href="#">Apply Now <i class="fa-solid fa-arrow-up-right-from-square"
                                            style="color: #000000;"></i></a></div>
                                <div id="niche2"><a href="#">Play Video <i class="fa-brands fa-youtube fa-lg"
                                            style="color: #ffffff;"></i></a></div>
                            </div>
                        </div>
                
                        <div class="box">
                            <div class="play"><i class="fa-solid fa-volume-high fa-beat"></i></div>
                            <div class="image"><img class="boxim"
                                    src="https://images.unsplash.com/photo-1443188631128-a1b6b1c5c207?w=800&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTh8fGZvcmVpZ24lMjBzdHVkaWVzfGVufDB8fDB8fHww"
                                    alt=""></div>
                            <div class="content">
                                <div id="eli">
                                    <h3 class="tit">Eligibility</h3>
                                    <hr>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> <b>Age </b> <i
                                        class="fa-solid fa-arrow-right" style="color: #000000;"></i> greater Than 16<br>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> <b>Income </b> <i
                                        class="fa-solid fa-arrow-right" style="color: #000000;"></i> Not Required <br>
                                </div>
                                <div id="doc">
                                    <h3 class="tit">Document</h3>
                                    <hr>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> Cast Certificated <br>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> 10th / 12th Pass<br>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> Passport​<br>
                                </div>
                            </div>
                            <div class="niche">
                                <div id="niche1"><a href="#">Apply Now <i class="fa-solid fa-arrow-up-right-from-square"
                                            style="color: #000000;"></i></a></div>
                                <div id="niche2"><a href="#">Play Video <i class="fa-brands fa-youtube fa-lg"
                                            style="color: #ffffff;"></i></a></div>
                            </div>
                        </div>
                
                        <div class="box">
                            <div class="play"><i class="fa-solid fa-volume-high fa-beat"></i></div>
                            <div class="image"><img class="boxim"
                                    src="https://images.unsplash.com/photo-1524441952603-cdc2993d53eb?w=800&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTl8fHBpbG90JTIwdHJhbmluZ3xlbnwwfHwwfHx8MA%3D%3D"
                                    alt=""></div>
                            <div class="content">
                                <div id="eli">
                                    <h3 class="tit">Eligibility</h3>
                                    <hr>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> <b>Age </b> <i
                                        class="fa-solid fa-arrow-right" style="color: #000000;"></i> 17 years - 65 years<br>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> <b>Income </b> <i
                                        class="fa-solid fa-arrow-right" style="color: #000000;"></i> N0t Required<br>
                                </div>
                                <div id="doc">
                                    <h3 class="tit">Document</h3>
                                    <hr>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> Aadhaar /PAN card <br>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> Class 1 & 2 medical
                                    certificate<br>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> Police verification
                                    Certificate​<br>
                                </div>
                            </div>
                            <div class="niche">
                                <div id="niche1"><a href="#">Apply Now <i class="fa-solid fa-arrow-up-right-from-square"
                                            style="color: #000000;"></i></a></div>
                                <div id="niche2"><a href="#">Play Video <i class="fa-brands fa-youtube fa-lg"
                                            style="color: #ffffff;"></i></a></div>
                            </div>
                        </div>
                
                        <div class="box">
                            <div class="play"><i class="fa-solid fa-volume-high fa-beat"></i></div>
                            <div class="image"><img class="boxim"
                                    src="https://plus.unsplash.com/premium_photo-1682147256217-350022635b9f?w=800&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTd8fHNlbGYlMjBlbXBsb3ltZW50fGVufDB8fDB8fHww"
                                    alt=""></div>
                            <div class="content">
                                <div id="eli">
                                    <h3 class="tit">Eligibility</h3>
                                    <hr>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> <b>Age </b> <i
                                        class="fa-solid fa-arrow-right" style="color: #000000;"></i> 18 years - 65 years<br>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> <b>Income </b> <i
                                        class="fa-solid fa-arrow-right" style="color: #000000;"></i> Based on size of land
                                    holdings and per acre yield<br>
                                </div>
                                <div id="doc">
                                    <h3 class="tit">Document</h3>
                                    <hr>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> Aadhaar /PAN card <br>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> Voter's ID card / Driving License
                                    / Passport<br>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> Agricultural land document​<br>
                                </div>
                            </div>
                            <div class="niche">
                                <div id="niche1"><a href="#">Apply Now <i class="fa-solid fa-arrow-up-right-from-square"
                                            style="color: #000000;"></i></a></div>
                                <div id="niche2"><a href="#">Play Video <i class="fa-brands fa-youtube fa-lg"
                                            style="color: #ffffff;"></i></a></div>
                            </div>
                        </div>
                
                        <div class="box">
                            <div class="play"><i class="fa-solid fa-volume-high fa-beat"></i></div>
                            <div class="image"><img class="boxim"
                                    src="https://media.istockphoto.com/id/1492356492/photo/pink-business-card-with-pte-text-stands-on-a-yellow-background-with-red-marker.webp?b=1&s=170667a&w=0&k=20&c=Go5VEXUi3s-5JRJ6GcK1fs24zNyzA1qHQ8Dv5IvECmA="
                                    alt=""></div>
                            <div class="content">
                                <div id="eli">
                                    <h3 class="tit">Eligibility</h3>
                                    <hr>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> <b>Age </b> <i
                                        class="fa-solid fa-arrow-right" style="color: #000000;"></i> greater Than 16<br>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> <b>Income </b> <i
                                        class="fa-solid fa-arrow-right" style="color: #000000;"></i> Not Required<br>
                                </div>
                                <div id="doc">
                                    <h3 class="tit">Document</h3>
                                    <hr>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> 10th & 12th Pass <br>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> Addmison letter from Foreign
                                    University<br>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> Caste Certificate​<br>
                                </div>
                            </div>
                            <div class="niche">
                                <div id="niche1"><a href="#">Apply Now <i class="fa-solid fa-arrow-up-right-from-square"
                                            style="color: #000000;"></i></a></div>
                                <div id="niche2"><a href="#">Play Video <i class="fa-brands fa-youtube fa-lg"
                                            style="color: #ffffff;"></i></a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            </div>
            </main>
    
        <!-- Enhanced Footer -->
        <footer id="contact">
            <div class="footer-container">
                <div class="footer-section about">
                    <h2>About Us</h2>
                    <p>At our core, we are driven by a mission to uplift and empower individuals by providing a platform for opportunities and
                    growth. We understand the importance of trust and aim to bridge the gap between dreams and reality by creating a space
                    where aspirations are nurtured. With a focus on accessibility and simplicity, our platform is designed to serve with
                    integrity, ensuring every step is guided with care and respect. Together, we envision a brighter future built on
                    support, hope, and possibilities.</p>
                </div>
                <div class="footer-section links">
                    <h2>Quick Links</h2>
                    <ul>
                        <li><a href="#introduction">Home</a></li>
                        <li><a href="#theory"></a></li>
                        <li><a href="#mixer">Schemes</a></li>
                        <li><a href="#primary">Policies</a></li>
                        <li><a href="#secondary">Help</a></li>
                        <li><a href="#harmony">Language</a></li>
                        <li><a href="#psychology">Login</a></li>
                        <li><a href="#contact">Contact Us</a></li>
                    </ul>
                </div>
                <div class="footer-section contact-form">
                    <h2>Contact Us</h2>
                    <form action="help.php" method="POST">
                        <input type="email" name="email" placeholder="" value="<?php echo $email?>" disabled>
                        <textarea name="message" rows="4" placeholder="Your message"></textarea>
                        <button type="submit" name='submit'>Send</button>
                    </form>
                    
                </div>
                <div class="footer-section social-media">
                    <h2>Follow Us</h2>
                    <ul class="social-icons">
                        <li><a href="#"><img src="https://img.icons8.com/ios-filled/24/ffffff/facebook--v1.png"
                                    alt="Facebook"></a></li>
                        <li><a href="#"><img src="https://img.icons8.com/ios-filled/24/ffffff/twitter.png"
                                    alt="Twitter"></a></li>
                        <li><a href="#"><img src="https://img.icons8.com/ios-filled/24/ffffff/instagram-new.png"
                                    alt="Instagram"></a></li>
                        <li><a href="#"><img src="https://img.icons8.com/ios-filled/24/ffffff/linkedin.png"
                                    alt="LinkedIn"></a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Tribal Assistance | Designed for helping with Love.</p>
            </div>
        </footer>
    
    </body>
    <script src='js/help.js'></script>
    <script src="js/tryyy.js"></script>
    </html>

    <!-- for gujarati -->
    <?php else: ?>
        
        <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Gujarati-assist</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/locomotive-scroll@3.5.4/dist/locomotive-scroll.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="css/trryyyy.css">
        <link rel="stylesheet" href="styles.css">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
        <!-- Header with navigation -->
        <header>
            <nav>
            <div class="logo">Tribal<span> Assistance</span></div>
            <div class="menu-icon" id="menu-icon" aria-label="Toggle navigation menu" role="button" tabindex="0">
                <span class="nav-icon"></span>
            </div>
            <ul class="nav-links" id="nav-links">
                <?php if ($is_logged_in==="1"): ?>
                    <li><a href="index.php">ઘર</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login/Register</a></li>
                <?php endif; ?>
                <?php if($is_logged_in==="1"): ?>
                <li><a href="dashboard.php">ડેશબોર્ડ</a></li>
                <li><a href="#" onclick="scrollToFooter()">મદદ</a></li>
                <li><a href="profile.php">મારું પ્રોફાઇલ</a></li>
                <li><a href="logout.php">લૉગ આઉટ</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        </header>
        <br><br><br><br>
    <h1>
    તમારું સ્વાગત છે, <?= htmlspecialchars($username) ?>!</h1><br>

    
        <!-- Main content -->
        <main>
            <section>
                <div class='section-img'>
                    <div class='texto'>
                        <h1> નાણાકીય સહાય સાથે સપનાને પૂર્ણ કરો.</h1>
                        <p> "તમારા સપનાઓ મહત્વના છે, અને તે સાકાર કરવામાં અમે તમારી સાથે છીએ. તમે આગળ વધતા જશો, અમે સતત તમારી સાથે રહીશું અને તમને
                        પ્રોત્સાહન આપતા રહીશું. સાથે મળીને, આપણે આશાઓને હકીકતમાં બદલી શકીએ અને પડકારોને જીતમાં ફેરવી શકીએ. ચાલો, સાથે મળીને
                        ઉজ্জવળ ભવિષ્ય બનાવીએ.". </p>
                    </div>
                    <div class='imag'>
                        <img
                            src='images/mb6g_nsfm_230519.jpg'>
                    </div>
                </div>
            </section>
            
            <section>
                <div class='section-tres-columnas'>
            
                    <div class='card yellow'>
                        <svg class='icon-card' viewBox="0 0 352 512" width="100" title="lightbulb">
                            <path
                                d="M96.06 454.35c.01 6.29 1.87 12.45 5.36 17.69l17.09 25.69a31.99 31.99 0 0 0 26.64 14.28h61.71a31.99 31.99 0 0 0 26.64-14.28l17.09-25.69a31.989 31.989 0 0 0 5.36-17.69l.04-38.35H96.01l.05 38.35zM0 176c0 44.37 16.45 84.85 43.56 115.78 16.52 18.85 42.36 58.23 52.21 91.45.04.26.07.52.11.78h160.24c.04-.26.07-.51.11-.78 9.85-33.22 35.69-72.6 52.21-91.45C335.55 260.85 352 220.37 352 176 352 78.61 272.91-.3 175.45 0 73.44.31 0 82.97 0 176zm176-80c-44.11 0-80 35.89-80 80 0 8.84-7.16 16-16 16s-16-7.16-16-16c0-61.76 50.24-112 112-112 8.84 0 16 7.16 16 16s-7.16 16-16 16z" />
                        </svg>
                        <DIV class='title-card'>ટ્રેક્ટર/ટ્રોલી લોન</DIV>
                        <DIV class='p-card'>NSTFDC ની ટ્રેક્ટર વિથ ટ્રોલી યોજના હેઠળ આદિવાસી સમુદાયોને લોન મંજૂર કરવા અંગે</DIV>
                        <button class='btn-card'><span>વધુ જાણો</span> <svg viewBox="0 0 448 512" width="100"
                                title="long-arrow-alt-right">
                                <path
                                    d="M313.941 216H12c-6.627 0-12 5.373-12 12v56c0 6.627 5.373 12 12 12h301.941v46.059c0 21.382 25.851 32.09 40.971 16.971l86.059-86.059c9.373-9.373 9.373-24.569 0-33.941l-86.059-86.059c-15.119-15.119-40.971-4.411-40.971 16.971V216z" />
                            </svg></button>
                    </div>
            
                    <div class='card green'>
                        <svg class='icon-card' viewBox="0 0 576 512" width="100" title="credit-card">
                            <path
                                d="M0 432c0 26.5 21.5 48 48 48h480c26.5 0 48-21.5 48-48V256H0v176zm192-68c0-6.6 5.4-12 12-12h136c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12H204c-6.6 0-12-5.4-12-12v-40zm-128 0c0-6.6 5.4-12 12-12h72c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12H76c-6.6 0-12-5.4-12-12v-40zM576 80v48H0V80c0-26.5 21.5-48 48-48h480c26.5 0 48 21.5 48 48z" />
                        </svg>
                        <DIV class='title-card'>ઉચ્ચ અભ્યાસ લોન</DIV>
                        <DIV class='p-card'>આદિવાસી વિદ્યાર્થીઓને વિદેશમાં અભ્યાસ માટે IELTS અને TOEFL તાલીમ પ્રદાન કરવાની વ્યવસ્થા અંગે.</DIV>
                        <button class='btn-card'><span>વધુ જાણો</span> <svg viewBox="0 0 448 512" width="100"
                                title="long-arrow-alt-right">
                                <path
                                    d="M313.941 216H12c-6.627 0-12 5.373-12 12v56c0 6.627 5.373 12 12 12h301.941v46.059c0 21.382 25.851 32.09 40.971 16.971l86.059-86.059c9.373-9.373 9.373-24.569 0-33.941l-86.059-86.059c-15.119-15.119-40.971-4.411-40.971 16.971V216z" />
                            </svg></button>
                    </div>
            
            
                    <div class='card violet'>
                        <svg class='icon-card' viewBox="0 0 640 512" width="100" title="user-clock">
                            <path
                                d="M496 224c-79.6 0-144 64.4-144 144s64.4 144 144 144 144-64.4 144-144-64.4-144-144-144zm64 150.3c0 5.3-4.4 9.7-9.7 9.7h-60.6c-5.3 0-9.7-4.4-9.7-9.7v-76.6c0-5.3 4.4-9.7 9.7-9.7h12.6c5.3 0 9.7 4.4 9.7 9.7V352h38.3c5.3 0 9.7 4.4 9.7 9.7v12.6zM320 368c0-27.8 6.7-54.1 18.2-77.5-8-1.5-16.2-2.5-24.6-2.5h-16.7c-22.2 10.2-46.9 16-72.9 16s-50.6-5.8-72.9-16h-16.7C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h347.1c-45.3-31.9-75.1-84.5-75.1-144zm-96-112c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128z" />
                        </svg>
                        <DIV class='title-card'>હોમ બિઝનેસ લોન</DIV>
                        <DIV class='p-card'>આદિવાસી લોકોને સ્વરોજગારી યોજના હેઠળ ઘરઉદ્યોગ, મસાલા પીસવાની સાધનો, અથાણા-પાપડ, અને વડી જેવા વ્યવસાય માટે લોન.</DIV>
                        <button class='btn-card'><span>વધુ જાણો</span> <svg viewBox="0 0 448 512" width="100"
                                title="long-arrow-alt-right">
                                <path
                                    d="M313.941 216H12c-6.627 0-12 5.373-12 12v56c0 6.627 5.373 12 12 12h301.941v46.059c0 21.382 25.851 32.09 40.971 16.971l86.059-86.059c9.373-9.373 9.373-24.569 0-33.941l-86.059-86.059c-15.119-15.119-40.971-4.411-40.971 16.971V216z" />
                            </svg></button>
                    </div>
            
            
                </div>
            </section>
            
            
            <section>
                <div class='section-img'>
                    <!-- <div class='texto'>
                        <h1> How To Apply for Loan?</h1>
                        <p> Get started to know how to Login , Sign-up , How to know for which Loan you are applying , how to know you are eligible for that particular loan or not. How to fill the form for it and etc through The Tutorial video of our website.  </p>
                        <button class='brutal-btn'> Watch Video</button>
                    </div> -->
                    <div class='imag'>
                        <img class='clip-path-square'
                            src='images/2302.q894.029.P.m009.c20.Cossack flat set.jpg'>
                    </div>
                    <div class='texto'>
                        <h1> લોન માટે કેવી રીતે અરજી કરવી?</h1>
                        <p> આરંભ કરો અને જાણો કે કેવી રીતે લૉગિન, સાઇન-અપ કરવું, કયા લોન માટે અરજી કરી રહ્યા છો, તમે તે લોન માટે પાત્ર છો કે નહીં,
                        અને ફોર્મ કેવી રીતે ભરવું વગેરે વિશે સંપૂર્ણ માહિતી મેળવવા માટે અમારી વેબસાઈટના ટ્યુટોરીયલ વિડિયો જુઓ. </p>
                        <button class='brutal-btn'> વિડીયો જુઓ</button>
                    </div>
                </div>

                <div id="page2">
                
                    <div id="middle">
                        <h1>લોન</h1>
                    </div>
                
                    <hr>
                    <div class="loan-section">
                
                        <div class="box">
                            <audio id="hoverAudio" src="One Love - Shubh 128 Kbps.mp3"></audio>
                            <div class="play" id="hoverDiv"><i class="fa-solid fa-volume-high fa-beat"></i></div>
                            <div class="image"><img class="boxim"
                                    src="https://images.unsplash.com/photo-1530267981375-f0de937f5f13?w=800&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTV8fHRyYWN0b3IlMjB0cm9sbHl8ZW58MHx8MHx8fDA%3D"
                                    alt=""></div>
                            <div class="content">
                                <div id="eli">
                                    <h3 class="tit">પાત્રતા</h3>
                                    <hr>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> <b>ઉંમર </b> <i
                                        class="fa-solid fa-arrow-right" style="color: #000000;"></i> 18 વર્ષ - 65 વર્ષ<br>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> <b>આવક </b> <i
                                        class="fa-solid fa-arrow-right" style="color: #000000;"></i> જમીનના કદના આધારે
                                        હોલ્ડિંગ અને પ્રતિ એકર ઉપજ<br>
                                </div>
                                <div id="doc">
                                    <h3 class="tit">દસ્તાવેજ</h3>
                                    <hr>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> આધાર/પાન કાર્ડ <br>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> મતદારનું આઈડી કાર્ડ / ડ્રાઈવિંગ લાઇસન્સ
                                    / પાસપોર્ટ<br>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> ખેતીની જમીનનો દસ્તાવેજ​<br>
                                </div>
                            </div>
                            <div class="niche">
                                <div id="niche1"><a href="form.php">અરજી કરો <i class="fa-solid fa-arrow-up-right-from-square"
                                            style="color: #000000;"></i></a></div>
                                <div id="niche2"><a href="video.html">વિડિઓ ચલાવો <i class="fa-brands fa-youtube fa-lg"
                                            style="color: #ffffff;"></i></a></div>
                            </div>
                        </div>
                
                
                 
                        <div class="box">
                            <div class="play"><i class="fa-solid fa-volume-high fa-beat"></i></div>
                            <div class="image"><img class="boxim"
                                    src="https://images.unsplash.com/photo-1589391886645-d51941baf7fb?w=800&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTF8fGxhd3llcnxlbnwwfHwwfHx8MA%3D%3D"
                                    alt=""></div>
                            <div class="content">
                                <div id="eli">
                                    <h3 class="tit">પાત્રતા</h3>
                                    <hr>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> <b>ઉંમર </b> <i
                                        class="fa-solid fa-arrow-right" style="color: #000000;"></i>જરૂરી નથી<br>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> <b>આવક </b> <i
                                        class="fa-solid fa-arrow-right" style="color: #000000;"></i> જરૂરી નથી<br>
                                </div>
                                <div id="doc">
                                    <h3 class="tit">દસ્તાવેજ</h3>
                                    <hr>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> આધાર/પાન કાર્ડ <br>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> કાસ્ટ પ્રમાણપત્ર<br>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> મતદાર આઈડી કાર્ડ​<br>
                                </div>
                            </div>
                            <div class="niche">
                                <div id="niche1"><a href="form2.php">અરજી કરો <i class="fa-solid fa-arrow-up-right-from-square"
                                            style="color: #000000;"></i></a></div>
                                <div id="niche2"><a href="#">વિડિઓ ચલાવો <i class="fa-brands fa-youtube fa-lg"
                                            style="color: #ffffff;"></i></a></div>
                            </div>
                        </div>
                
                        <div class="box">
                            <div class="play"><i class="fa-solid fa-volume-high fa-beat"></i></div>
                            <div class="image"><img class="boxim"
                                    src="https://images.unsplash.com/photo-1581590289958-0a34272868dc?w=800&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTR8fGdyb2NlcnklMjBzdG9yZSUyMHZlbmRvciU1Q3xlbnwwfHwwfHx8MA%3D%3D"
                                    alt=""></div>
                            <div class="content">
                                <div id="eli">
                                    <h3 class="tit">પાત્રતા</h3>
                                    <hr>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> <b>ઉંમર </b> <i
                                        class="fa-solid fa-arrow-right" style="color: #000000;"></i> 18 વર્ષ - 55 વર્ષ<br>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> <b>આવક </b> <i
                                        class="fa-solid fa-arrow-right" style="color: #000000;"></i> 120000<br>
                                </div>
                                <div id="doc">
                                    <h3 class="tit">દસ્તાવેજ</h3>
                                    <hr>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i>મતદાર ઓળખ કાર્ડ* અને આધાર કાર્ડ*
                                    <br>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> વ્યવસાયના અનુભવનું પ્રમાણપત્ર <br>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> સહી કમિશનર​<br>
                                </div>
                            </div>
                            <div class="niche">
                                <div id="niche1"><a href="#">હવે અરજી કરો <i class="fa-solid fa-arrow-up-right-from-square"
                                            style="color: #000000;"></i></a></div>
                                <div id="niche2"><a href="#">વિડિઓ ચલાવો <i class="fa-brands fa-youtube fa-lg"
                                            style="color: #ffffff;"></i></a></div>
                            </div>
                        </div>
                
                        <div class="box">
                            <div class="play"><i class="fa-solid fa-volume-high fa-beat"></i></div>
                            <div class="image"><img class="boxim"
                                    src="https://images.unsplash.com/photo-1443188631128-a1b6b1c5c207?w=800&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTh8fGZvcmVpZ24lMjBzdHVkaWVzfGVufDB8fDB8fHww"
                                    alt=""></div>
                            <div class="content">
                                <div id="eli">
                                    <h3 class="tit">પાત્રતા</h3>
                                    <hr>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> <b>ઉંમર </b> <i
                                        class="fa-solid fa-arrow-right" style="color: #000000;"></i> 16 થી વધુ<br>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> <b>આવક </b> <i
                                        class="fa-solid fa-arrow-right" style="color: #000000;"></i> જરૂરી નથી <br>
                                </div>
                                <div id="doc">
                                    <h3 class="tit">દસ્તાવેજ</h3>
                                    <hr>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> કાસ્ટ સર્ટિફિકેટ <br>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> 10/12 પાસ<br>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> પાસપોર્ટ<br>
                                </div>
                            </div>
                            <div class="niche">
                                <div id="niche1"><a href="#"> અરજી કરો <i class="fa-solid fa-arrow-up-right-from-square"
                                            style="color: #000000;"></i></a></div>
                                <div id="niche2"><a href="#">વિડિઓ ચલાવો <i class="fa-brands fa-youtube fa-lg"
                                            style="color: #ffffff;"></i></a></div>
                            </div>
                        </div>
                
                        <div class="box">
                            <div class="play"><i class="fa-solid fa-volume-high fa-beat"></i></div>
                            <div class="image"><img class="boxim"
                                    src="https://images.unsplash.com/photo-1524441952603-cdc2993d53eb?w=800&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTl8fHBpbG90JTIwdHJhbmluZ3xlbnwwfHwwfHx8MA%3D%3D"
                                    alt=""></div>
                            <div class="content">
                                <div id="eli">
                                    <h3 class="tit">પાત્રતા</h3>
                                    <hr>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> <b>ઉંમર </b> <i
                                        class="fa-solid fa-arrow-right" style="color: #000000;"></i> 17 વર્ષ - 65 વર્ષ<br>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> <b>આવક </b> <i
                                        class="fa-solid fa-arrow-right" style="color: #000000;"></i> જરૂરી નથી<br>
                                </div>
                                <div id="doc">
                                    <h3 class="tit">દસ્તાવેજ</h3>
                                    <hr>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> આધાર/પાન કાર્ડ <br>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> વર્ગ 1 અને 2 તબીબી
                                    પ્રમાણપત્ર<br>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> પોલીસ વેરિફિકેશન
                                    પ્રમાણપત્ર​<br>
                                </div>
                            </div>
                            <div class="niche">
                                <div id="niche1"><a href="#">અરજી કરો <i class="fa-solid fa-arrow-up-right-from-square"
                                            style="color: #000000;"></i></a></div>
                                <div id="niche2"><a href="#">વિડિઓ ચલાવો <i class="fa-brands fa-youtube fa-lg"
                                            style="color: #ffffff;"></i></a></div>
                            </div>
                        </div>
                
                        <div class="box">
                            <div class="play"><i class="fa-solid fa-volume-high fa-beat"></i></div>
                            <div class="image"><img class="boxim"
                                    src="https://plus.unsplash.com/premium_photo-1682147256217-350022635b9f?w=800&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTd8fHNlbGYlMjBlbXBsb3ltZW50fGVufDB8fDB8fHww"
                                    alt=""></div>
                            <div class="content">
                                <div id="eli">
                                    <h3 class="tit">પાત્રતા</h3>
                                    <hr>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> <b>ઉંમર </b> <i
                                        class="fa-solid fa-arrow-right" style="color: #000000;"></i> 18 વર્ષ - 65 વર્ષ<br>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> <b>આવક </b> <i
                                        class="fa-solid fa-arrow-right" style="color: #000000;"></i> જમીનના કદના આધારે
                                        હોલ્ડિંગ અને પ્રતિ એકર ઉપજ<br>
                                </div>
                                <div id="doc">
                                    <h3 class="tit">દસ્તાવેજ</h3>
                                    <hr>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> આધાર/પાન કાર્ડ <br>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> મતદારનું આઈડી કાર્ડ / ડ્રાઈવિંગ લાઇસન્સ
                                    / પાસપોર્ટ<br>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> ખેતીની જમીનનો દસ્તાવેજ​<br>
                                </div>
                            </div>
                            <div class="niche">
                                <div id="niche1"><a href="#"> અરજી કરો<i class="fa-solid fa-arrow-up-right-from-square"
                                            style="color: #000000;"></i></a></div>
                                <div id="niche2"><a href="#">વિડિઓ ચલાવો <i class="fa-brands fa-youtube fa-lg"
                                            style="color: #ffffff;"></i></a></div>
                            </div>
                        </div>
                
                        <div class="box">
                            <div class="play"><i class="fa-solid fa-volume-high fa-beat"></i></div>
                            <div class="image"><img class="boxim"
                                    src="https://media.istockphoto.com/id/1492356492/photo/pink-business-card-with-pte-text-stands-on-a-yellow-background-with-red-marker.webp?b=1&s=170667a&w=0&k=20&c=Go5VEXUi3s-5JRJ6GcK1fs24zNyzA1qHQ8Dv5IvECmA="
                                    alt=""></div>
                            <div class="content">
                                <div id="eli">
                                    <h3 class="tit">પાત્રતા</h3>
                                    <hr>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> <b>Age </b> <i
                                        class="fa-solid fa-arrow-right" style="color: #000000;"></i> 16 થી વધુ<br>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> <b>આવક </b> <i
                                        class="fa-solid fa-arrow-right" style="color: #000000;"></i> જરૂરી નથી<br>
                                </div>
                                <div id="doc">
                                    <h3 class="tit">દસ્તાવેજ</h3>
                                    <hr>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> 10 અને 12 પાસ <br>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> વિદેશી તરફથી એડમિસન પત્ર
                                    યુનિવર્સિટી<br>
                                    <i class="fa-solid fa-circle fa-2xs" style="color: #000000;"> </i> જાતિ પ્રમાણપત્ર​<br>
                                </div>
                            </div>
                            <div class="niche">
                                <div id="niche1"><a href="#"> અરજી કરો <i class="fa-solid fa-arrow-up-right-from-square"
                                            style="color: #000000;"></i></a></div>
                                <div id="niche2"><a href="#">વિડિઓ ચલાવો <i class="fa-brands fa-youtube fa-lg"
                                            style="color: #ffffff;"></i></a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            </div>
            </main>
    
        <!-- Enhanced Footer -->
        <footer id="contact">
            <div class="footer-container">
                <div class="footer-section about">
                    <h2>અમારા વિશે</h2>
                    <p>અમારા મૂળમાં, અમે તકો અને
                    વૃદ્ધિ અમે વિશ્વાસના મહત્વને સમજીએ છીએ અને સ્પેસ બનાવીને સપના અને વાસ્તવિકતા વચ્ચેના અંતરને દૂર કરવાનું લક્ષ્ય રાખીએ છીએ
                    જ્યાં આકાંક્ષાઓને પોષવામાં આવે છે. સુલભતા અને સરળતા પર ધ્યાન કેન્દ્રિત કરીને, અમારું પ્લેટફોર્મ સેવા આપવા માટે રચાયેલ છે
                    અખંડિતતા, દરેક પગલું કાળજી અને આદર સાથે માર્ગદર્શન આપવામાં આવે તેની ખાતરી કરવી. સાથે મળીને, અમે ઉજ્જવળ ભવિષ્યની કલ્પના
                    કરીએ છીએ
                    આધાર, આશા અને શક્યતાઓ.</p>
                </div>
                <div class="footer-section links">
                    <h2>ઝડપી લિંક્સ</h2>
                    <ul>
                        <li><a href="#introduction">ઘર</a></li>
                        <li><a href="#theory"></a></li>
                        <li><a href="#mixer">યોજનાઓ</a></li>
                        <li><a href="#primary">નીતિઓ</a></li>
                        <li><a href="#secondary">મદદ</a></li>
                        <li><a href="#harmony">English</a></li>
                        <li><a href="#psychology">લૉગિન કરો</a></li>
                        <li><a href="#contact">અમારો સંપર્ક કરો</a></li>
                    </ul>
                </div>
                <div class="footer-section contact-form">
                    <h2>અમારો સંપર્ક કરો</h2>
                    <form action="help.php" method="post">
                        <input type="email" name="email" placeholder="Your Email" value="<?php echo $email?>" disabled>
                        <textarea name="message" rows="4" placeholder="Your message"></textarea>
                        <button type="submit" name='submit'>મોકલો</button>
                    </form>
                </div>
                <div class="footer-section social-media">
                    <h2>અમને અનુસરો</h2>
                    <ul class="social-icons">
                        <li><a href="#"><img src="https://img.icons8.com/ios-filled/24/ffffff/facebook--v1.png"
                                    alt="Facebook"></a></li>
                        <li><a href="#"><img src="https://img.icons8.com/ios-filled/24/ffffff/twitter.png"
                                    alt="Twitter"></a></li>
                        <li><a href="#"><img src="https://img.icons8.com/ios-filled/24/ffffff/instagram-new.png"
                                    alt="Instagram"></a></li>
                        <li><a href="#"><img src="https://img.icons8.com/ios-filled/24/ffffff/linkedin.png"
                                    alt="LinkedIn"></a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Tribal Assistance | Designed for helping with Love.</p>
            </div>
        </footer>
    
    </body>
    
    </html>
    <script src='js/help.js'></script>
    <script src="js/tryyy.js"></script>
</body>
</html>
    
    <?php endif; ?>
