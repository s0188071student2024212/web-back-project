<?php
// index.php
session_start();
require_once 'db_connection.php';

// Проверка авторизации
$is_logged_in = isset($_SESSION['user_id']);
$is_admin = isset($_SESSION['admin_logged_in']);

// Обработка формы контактов
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['contact_form'])) {
    $name = htmlspecialchars($_POST['name']);
    $phone = htmlspecialchars($_POST['phone']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);
    
    // Здесь можно добавить обработку данных (сохранение в БД, отправка email и т.д.)
    $contact_success = true; // Флаг успешной отправки
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>WEB Project</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <meta http-equiv="Content-type" content="text/html;charset=UTF-8"/>
</head>
<body>
    <div class="header_wrapper">
        <img src="img/logo.svg" alt="" class="logo">
        <div class="menu">
            <ul class="nav_list">
                <li class="dropdown">
                    About Us
                    <ul class="dropdown-menu">
                        <li>Our Story</li>
                        <li>Team</li>
                        <li>Careers</li>
                    </ul>
                </li>
                <li>Services</li>
                <li>Work</li>
                <li>News</li>
                <li>Contacts</li>
            </ul>
        </div>
        
        <div class="contacts">
            <div class="phone">
                <img src="img/iPhone.svg" alt="" class="phone_logo">
                <div class="phone_message">
                    <span class="call">Call us</span>
                    <span class="phone_number">(405) 555-0128</span>
                </div>
            </div>
            <div class="message">
                <img src="img/Chat.svg" alt="" class="mes_logo">
                <div class="mes_text">
                    <span class="talk">Talk to us</span>
                    <span class="mail">hello@createx.com</span>
                </div>
            </div>
        </div>
        <div class="auth-buttons">
            <?php if ($is_logged_in || $is_admin): ?>
                <a href="edit.php" class="login-btn">Profile</a>
                <?php if ($is_admin): ?>
                    <a href="admin.php" class="login-btn">Admin</a>
                <?php endif; ?>
                <a href="logout.php" class="login-btn">Logout</a>
            <?php else: ?>
                <a href="login.php" class="login-btn">Login</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="back_img1_4">
			<img class="c1" src="img/bg-image.svg" alt="">
			<div class="top_info">
				<h1 class="high_info">
					Create<span class="createx_last_letter">x</span> Construction
				</h1>
				<div class="mid_info">Cras ultrices leo vitae non viverra. Fringilla nisi quisque consequat, dignissim vitae proin ipsum sed. Pellentesque nec turpis purus eget pellentesque integer ipsum elementum felis. </div>
				<div class="buttons_learn_submit">
					<button class="button1">Learn more about us</button>
					<button class="button2">Submit request</button>
				</div>
			</div>
		</div>
		<main>
			<div class="video_introduction">
				<h2>We are Createx Construction Bureau</h2>
				<div class="introduction">We are rightfully considered to be the best construction company in the USA.</div>
				<div class="video_intro">
					<img src="img/image.svg" alt=""></img>
					<img class="play_button" src="img/play-btn_large.svg" alt="">
				</div>
			</div>
			<div class="values_block">
				<div class="value">Our core values</div>
				<div class="our_mission">Our mission is to set the highest standards for construction sphere.</div>
				<div class="icon_boxes">
					<div class="quality">
						<img src="img/ic-like.svg" width="48px" height="48px" alt="">
						<div class="advantages">Quality</div>
						<span>Culpa nostrud commodo ea consequat aliquip reprehenderit. Veniam velit nostrud aliquip sunt.</span>
					</div>
					<div class="safety">
						<img src="img/ic-hand.svg" width="48px" height="48px" alt="">
						<div class="advantages">Safety</div>
						<span class="mid_special" >Anim reprehenderit sint voluptate exercitation adipisicing laborum adipisicing. Minim empor est ea.</span>
					</div>
					<div class="comfort">
						<img src="img/ic-slippers.svg" width="48px" height="48px" alt="">
						<div class="advantages">Comfort</div>
						<span>Sit veniam aute dolore adipisicing nulla sit culpa. Minim mollit voluptate ullamco proident ea ad.</span>
					</div>
				</div>
			</div>
            <div class="form_about">
                <h3 class="want_more">Want to know more? Ask us a question:</h3>
                <form class="three_forms" method="post" action="#">
                    <input type="hidden" name="contact_form" value="1">
                    <div class="form1">
                        <div class="form_top_text">Name</div>
                        <input type="text" name="name" placeholder="Your name" required>
                    </div>                        
                    <div class="form2">
                        <div class="form_top_text">Phone</div>
                        <input type="tel" name="phone" placeholder="Your phone" required>
                    </div>
                    <div class="form3" width="414px" height="73px">
                        <div class="form_top_text">Message</div>
                        <input type="text" name="message" placeholder="Your message" required>
                    </div>
                    <button type="submit" class="form_send"><span class="form_send_text">Send</span></button>
                </form>
                <?php if (isset($contact_success)): ?>
                    <div class="success-message">Thank you! Your message has been sent.</div>
                <?php endif; ?>
            </div>
            v<img class="services_back" src="img/background.svg" alt="">
			<div class="services">
				<h2>Our cervices</h2>
				<h3>Createx Construction Bureau is a construction giant with a full range of construction services.</h3>
				<div class="service_tools">
					<div class="constr">
						<img class="constr_img" src="img/inner.svg" alt="">
					</div>
					<div class="pdev">
						<img class="pdev_img" src="img/inner2.svg" alt="">
					</div>
					<div class="ides">
						<img class="ides_img" src="img/inner3.svg" alt="">
					</div>
					<div class="rep">
						<img class="rep_img" src="img/inner4.svg" alt="">
					</div>
				</div>
				<div class="services_bot">
					<div class="sb_text">Learn more about our services</div>
					<a href="#" class="sb_button"><span>View services</span></a>
				</div>
			</div>
			<div class="buildings">
				<div class="buildings_text">
					Browse our selected projects and learn more about our work
				</div>
				<div class="carousel">
					<div class="card1">
						<img src="img/build1.svg" alt="">
						<div class="card_title_text">Red Finger Building</div>
						<div class="type_building">Business Centers</div>
					</div>
					<div class="card2">
						<img src="img/build2.svg" alt="">
						<div class="card_title_text">Cubes Building</div>
						<div class="type_building">Business Centers</div>
						<a href="#" class="view_project"><span>View project</span></a>
					</div>
					<div class="card3">
						<img src="img/build3.svg" alt="">
						<div class="card_title_text">The Pencil Building</div>
						<div class="type_building">Stores & Malls</div>
					</div>
				</div>
				<div class="portfolio_str">
					<div class="portfolio_text">Explore all our works</div>
					<a href="#" class="portfolio_button"><span>View portfolio</span></a>
				</div>
			</div>
			<div class="figures_news">
				    <div class="fn_back">
						<img src="img/bricks.svg" alt="">
					</div>
					<h2>Some facts and figures</h2>
					<div class="diagramms">
						<div class="d1">
							<img src="img/d1.svg" alt="">
							<span>Totally satisfied clients</span>
						</div>
						<div class="d2">
							<img src="img/d2.svg" alt="">
							<span>Years of experience</span>
						</div>
						<div class="d3">
							<img src="img/d3.svg" alt="">
							<span>Working hours spent</span>
						</div>
						<div class="d4">
							<img src="img/d4.svg" alt="">
							<span>Succeeded projects</span>
						</div>
					</div>
					<div class="recent_news">
						<h2>Recent news</h2>
						<div class="news">
							<div class="first_news_col">
								<img src="img/new1.svg" alt="">
								<h3>How to Build Climate Change-Resilient Infrastructure</h3>
								<span class="article_info">Industry News | June 24, 2020 | <img class="news_logo_comments" src="img/news_comments_logo.svg" alt=""> 4 comments</span>
								<span class="new_comment">Ipsum aliquet nisi, hendrerit rhoncus quam tortor, maecenas faucibus. Tincidunt aliquet sit vel, venenatis nulla. Integer bibendum turpis convallis enim, nibh convallis...</span>
							</div>
							<div class="second_news_col">
								<div class="top_new">
									<img src="img/new2.svg" alt="">
									<h3>How Construction Can Help Itself</h3>
									<span class="article_info">Innovation | June 12, 2020 | <img class="news_logo_comments" src="img/news_comments_logo.svg" alt=""> No comments</span>
								</div>
								<div class="bottom_new">
									<img src="img/new3.svg" alt="">
									<h3>Types of Flooring Materials</h3>
									<span class="article_info">Company News | December 1, 2019 | <img class="news_logo_comments" src="img/news_comments_logo.svg" alt=""> No comments</span>
								</div>
							</div>
						</div>
						<div class="news_bot_wrapper">
							<span>Explore all our news posts</span>
							<div class="news_button_wrapper">
								<a href="#">View all news</a>
							</div>
						</div>
					</div>
			</div>
			<div class="pre_footer">
			    <div class="pre_footer_form_wrapper">
			        <h2>A quick way to discuss details</h2>
			        <form id="contact-form" method="post" action="process_form.php">
			            <?php if (isset($_SESSION['form_error'])): ?>
			                <div class="error-message"><?php echo $_SESSION['form_error']; unset($_SESSION['form_error']); ?></div>
			            <?php endif; ?>
			            
			            <?php if (isset($_SESSION['form_success'])): ?>
			                <div class="success-message"><?php echo $_SESSION['form_success']; unset($_SESSION['form_success']); ?></div>
			            <?php endif; ?>
			            
			            <span>Name*</span>
			            <input type="text" name="name" placeholder="Your name" value="<?php echo isset($_SESSION['form_data']['name']) ? htmlspecialchars($_SESSION['form_data']['name']) : ''; ?>" required>
			            
			            <span>Phone*</span>
			            <input type="tel" name="phone_number" placeholder="Your phone number" value="<?php echo isset($_SESSION['form_data']['phone_number']) ? htmlspecialchars($_SESSION['form_data']['phone_number']) : ''; ?>" required>
			            
			            <span>Email*</span>
			            <input type="email" name="email" placeholder="Your working email" value="<?php echo isset($_SESSION['form_data']['email']) ? htmlspecialchars($_SESSION['form_data']['email']) : ''; ?>" required>
			            
			            <span>Message</span>
			            <textarea name="message" placeholder="Your message"><?php echo isset($_SESSION['form_data']['message']) ? htmlspecialchars($_SESSION['form_data']['message']) : ''; ?></textarea>
			            
			            <div class="pre_footer_form_check">
			                <input type="checkbox" name="agreement" <?php echo isset($_SESSION['form_data']['agreement']) ? 'checked' : ''; ?> required>
			                <span>I agree to receive communications from Createx Construction Bureau.</span>
			            </div>
			            
			            <div class="form_button">
			                <button type="submit"><span>Send request</span></button>
			            </div>
			        </form>
			    </div>
			</div>
		</main>
		<footer>
			<div class="footer_wrapper">
				<div class="footer_first_col">
					<div class="first_row">
						<img class="footer_logo" src="img/footer_logo.svg" alt="">
						<img src="img/footer_socials.svg" alt="">
					</div>
					<div class="second_row">
						<span>Createx Construction Bureau has been successfully operating in the USA construction market since 2000. We are proud to offer you quality construction and exemplary service. Our mission is to set the highest standards for construction sphere.</span>
					</div>
					<div class="third_row">
						<div class="first_half">
							<h4>Head office</h4>
							<span>Address: </span>
							<span>8502 Preston Rd. Inglewood, New York</span><br>
							<span>Call: </span>
							<span>(405) 555-0128</span><br>
							<span>E-mail: </span>
							<span>hello@createx.com</span>
						</div>
						<div class="second_half">
							<h4>Who we are</h4>
							<a href="#">About us</a><br>
							<a href="#">Available Positions</a><br>
							<a href="#">Contacts</a>
						</div>
					</div>
					<div class="forth_row">
						<span>© All rights reserved. Made with <img src="img/heart.svg" alt=""> by Createx Studio</span> 
					</div>
				</div>
				<div class="footer_second_col">
					<div class="subscribe_wrapper">
						<h3>Let's stay in touch</h3>
						<div class="subscribe_form">
							<input type="email" placeholder="Your email address">
							<button><span>Subscribe</span></button>
						</div>
					</div>
					<div class="experience">
						<h4>Our experience</h4>
						<a href="#">Services</a><br>
						<a href="#">Work</a><br>
						<a href="#">News</a>
					</div>
					<div class="to_the_top">
						<span>Go to top</span>
						<a href="#"><img src="img/go_up.svg" alt=""></a>
					</div>
				</div>
			</div>
		</footer>
	</body>
</html>
    
