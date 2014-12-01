var userAgent = navigator.userAgent;
if (userAgent.search("Chrome") == -1)
{
	var htmlMessage = "<div class='text-center browser-detect-message'><p>Distrosharp uses special features available only on 'Google Chrome'. So we do not let you use your current browser to access our system. You can download Google Chrome by clicking the following icon.</p><p><a href='http://www.google.com/chrome/browser/' target='_blank'><img src='images/google_chrome.png'/></a></p></div>";
	$('.input-area').empty();
	$('.input-area').append(htmlMessage);
}

if ($(window).width() < 1350)
{
	var htmlMessage = "<div class='text-center' style='color:#C80000;'><p>Your browser resolution is not good enough for best experience our system.<br/>Please maximize your browser window.</p></div>";
	$('.input-area').append(htmlMessage);
}
