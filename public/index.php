<?php
//This is just a quick bit of code to get this thing working so we
//can start making money. Should switch to a proper MVC design later.
ini_set('mail.log', '/srv/www/megatongraphics.com/logs/mail.log');

//Contact Form
if(isset($_POST['contact']['submit'])) {

	$Name = $_POST['contact']['name'];
	$email = $_POST['contact']['email'];
	$recipient = 'info@megatongraphics.com';
	$subject = "Megaton Contact Form - " . $_POST['contact']['name']; 

	// main header
	$header  = "From: " . $_POST['contact']['name'] . " <" . $_POST['contact']['email'] . ">" . $eol;
	$header .= "MIME-Version: 1.0".$eol; 
	$header .= "Content-Type: multipart/mixed; boundary=\"".$separator."\"";

	// no more headers after this, we start the body! //
	$body = "--".$separator.$eol;
	$body .= "Content-Transfer-Encoding: 7bit".$eol.$eol;

	// message
	$body .= "--".$separator.$eol;
	$body .= "Content-Type: text/html; charset=\"iso-8859-1\"".$eol;
	$body .= "Content-Transfer-Encoding: 8bit".$eol.$eol;
	$body .= $_POST['contact']['message'].$eol;
	mail($recipient, $subject, $body, $header);
}
?>
<!DOCTYPE HTML>
<html>
<head>
	<title>Megaton Graphics</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta name="description" content="" />
	<meta name="keywords" content="jquery, horizontal, scrolling, scroll, smooth"/>
	<link rel="stylesheet" href="assets/css/main.css" type="text/css" media="screen"/>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
</head>
<body>
	<section id="home">
		<div id="logo">
			<img src="assets/images/logo.png" />
			<nav>
				<ul>
					<li><a href="#aboutus">about us</a></li><li><a href="#products">products</a></li><li><a href="#contactus">contact us</a></li>
				</ul>
			</nav>
		</div>
	</section>
	<nav id="mainnav">
		<ul>
			<li><a href="#home">home</a></li><li><a href="#aboutus">about us</a></li><li><a href="#products">products</a></li><li><a href="#contactus">contact us</a></li>
		</ul>
	</nav>
	<section id="aboutus">
		<h1>About Us</h1>
		<p>
			We are a small diverse group of designers, programmers, and craftsmen that specialize in custom projects from design to production.  We can create everything from etched glassware, to vinyl decals, major signs and banners, to small window displays and posters.  No project is too small or too ambitious for us and if you ever have any questions feel free to send us an email.  For the professional photographer and artist, we can offer high resolution photo printing and canvas fine art prints.  Need something designed? or already have a design in mind?  We can help make your ideas reality.  From a single custom product to small and medium batches, we can help out your personal or small business lifestyle from beginning to end.  We are about design and are looking to help you out along the way.
		</p>
		<h2>Who we are and What we do</h2>
		<div class="profile lstrebel">
			<img src="assets/images/people/lstrebel.png" alt="Louis Strebel" title="Louis Strebel" />
			<img src="assets/images/icon-56x56.png" />
			<h3>Louis Strebel</h3>
			<p>I am an experienced craftsman with 15 years experience in manufacturing, fabrication, woodworking, metalworking, and leather working.</p>
		</div>
		<div class="profile jtobak">
			<img src="assets/images/people/jtobak.png" alt="Jake Tobak" title="Jake Tobak" />
			<img src="assets/images/icon-56x56.png" />
			<h3>Jake Tobak</h3>
			<p>I am a software engineer and web developer with 7 years experience and a Bachelors in Computer Science working on a variety of technologies.</p>
		</div>
		<div class="profile kdonovan">
			<img src="assets/images/people/kdonovan.png" alt="Kyle Donovan" title="Kyle Donovan" />
			<img src="assets/images/icon-56x56.png" />
			<h3>Kyle Donovan</h3>
			<p>I am an Integrated designer, who specializes in developing the design process.  From initial sketches, to development, finalization, and production, immersion of design is what I believe to be key.</p>
		</div>

	</section>
	<section id="products">
		<h1>Products</h1>
		<div style="background-image: url('assets/images/products/fightsticks/fightstick.jpg');" class="col4-3left row3-2 box">
			<p>Custom Fightstick<br /><span style="font-size: 0.5em;">360/PS3/PC</span></p>
		</div>
		<div style="background-image: url('assets/images/products/drinkware/strebel.jpg');" class="col4-1right row3-2 box">
			<p>Etched Glassware</p>
		</div>


		<div style="background-image: url('assets/images/products/mirrors/rarity.jpg');" class="col4-1left row3-1 box">
			<p>Rarity Mirror<br /><span style="font-size: 0.5em;">For Charity Auction</span></p>
		</div>
		<div style="background-image: url('assets/images/products/drinkware/guilded.jpg');" class="col4-1left row3-1 box">
			<p>Etched Pewter Mugs</p>
		</div>
		<div style="background-image: url('assets/images/products/drinkware/trio.jpg');" class="col4-1left row3-1 box">
			<p>Etched Glassware</p>
		</div>
		<div style="background-image: url('assets/images/products/drinkware/tiki.jpg');" class="col4-1right row3-2 box">
			<p>Etched Glassware</p>
		</div>
	</section>

	<section id="contactus">
		<h1>Contact Us</h1>
		
		<form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">
			<div class="col2left">
				<input type="text" name="contact[name]" placeholder="Name" /><br />
				<input type="text" name="contact[email]" placeholder="e-mail" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}" /><br />
				<textarea name="contact[message]" placeholder="Message"></textarea><br />
				<!--input type="file" name="contact[file]" id="file"-->
			</div>
			<div class="col2right">
				<input type="submit" name="contact[submit]" value="submit" /><br />
				<p>Have a question or just curious about the process?<br />
				   Drop us a line, we’d love to start a chat.</p>
			</div>
		</form>
	</section>

	<footer>
		&copy;2013 Megaton Graphics
		<a href="http://www.facebook.com/MegatonGraphics"><img src="assets/images/facebook.png" title="Facebook" alt="Facebook" /></a>
	</footer>
	<br />
	<!-- 
	Putting Javascript down here instead of the head
	will help mobile browsers render the page quicker
	See http://bit.ly/12GFKDE
	-->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
	<script type="text/javascript" src="assets/js/jquery.easing.min.1.3.js"></script>
	<script type="text/javascript">
		$(function() {
			$('a').bind('click',function(event){
				var $anchor = $(this);
				$('html, body').stop().animate({
					scrollTop: $($anchor.attr('href')).offset().top
					}, 1000,'easeInOutQuad');
				event.preventDefault();
			});
		});
	</script>
</body>
</html>
