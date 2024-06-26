<!--<div style="text-align: center;">
    <div style="border-top: 3px solid #7F5DE6; background-color: #F7F5FF; padding: 20px; text-align: center;">
        <p>Dear {{ $data['name'] }},</p>
        <p>We wish you all the best for {{ $data['program_name'] }}. To continue and get access to the course, request you to click the link below.</p>
        <p>AAFT Online is requesting you to make an online payment for:</p>
        <h2 style="color: #7F5DE6;">{{ $data['program_name'] }}</h2>
    </div>
    <div style="margin: 30px 0px;">
        <p>DESCRIPTION</p>
        <p>Total Payment with Installments</p>
    </div>
    <a href="{{ $data['link'] }}" style="background-color: #7F5DE6; padding: 10px 15px; min-width: 150px; text-align: center; text-decoration: none; color: #fff;">Pay Now</a>
    <div style="text-align: center; margin-top: 50px;">
        <p style="margin: 8px 0px;">Regards,</p>
        <p style="margin: 8px 0px;">Admission Team,</p>
        <p style="margin: 8px 0px;">AAFT Online</p>
    </div>
</div>-->

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Mailer</title>
<link rel="stylesheet" href="webfont/stylesheet.css">
<style>
@font-face {
    font-family: 'spock_ess_alt_1regular';
    src: url('https://aaftonline.com/assets/fonts/webfont/los_andes_-_spock_ess_alt_1_regular-webfont.woff2') format('woff2'),
         url('https://aaftonline.com/assets/fonts/webfont/los_andes_-_spock_ess_alt_1_regular-webfont.woff') format('woff');
    font-weight: normal;
    font-style: normal;
}

@font-face 
{
    font-family: 'spock_essregular';
    src: url('https://aaftonline.com/assets/fonts/webfont/los_andes_-_spock_ess_regular-webfont.woff2') format('woff2'),
         url('https://aaftonline.com/assets/fonts/webfont/los_andes_-_spock_ess_regular-webfont.woff') format('woff');
    font-weight: normal;
    font-style: normal;
}
@font-face {
  font-family: 'Roboto';
  font-style: normal;
  font-weight: 400;
  mso-font-alt: 'Arial';
  src: url(https://fonts.gstatic.com/s/roboto/v27/KFOmCnqEu92Fr1Mu4mxKKTU1Kg.woff2) format('woff2');
}
</style>
</head>

<body>

<table align="center" width="700px" style="font-family: Roboto; font-size:16px; line-height: 26px;">
	<tbody>
		<tr style="background-color: #141414;">
			<td style="padding: 10px 0; font-size:14px; color: #FFFFFF; text-align: center;">
				<img src="https://aaftonline.com/sales/mailer-logo.png" alt="AAFT Online">
			</td>
		</tr>
		
		<tr>
			<td style="display: flex; flex-direction: row; align-items: flex-end; justify-content: space-between; border-bottom: 1px solid #FF531C; padding-bottom: 20px; padding-top: 20px;">
				<p style="background-color: #FF531C; padding: 10px 0; font-size:14px; border-radius: 22px; padding: 40px;">
					<span style="font-size: 22px; line-height: 38px; color:#FFFFFF;">Congratulations on your<br>decision!</span><br>
					<span style="font-size: 24px; line-height: 33px; color:#FFFFFF;">PAYMENT REQUEST</span>
				</p>
				
				<img src="https://aaftonline.com/sales/payment.png" alt="payment">
			</td>
		</tr>
		
		<tr>
			<td style="padding-top: 40px;">
				<p style="margin: 5px 0;">Dear {{ $data['name'] }},</p>
				<p style="margin: 0;">&nbsp;</p>
				<p style="margin: 5px 0;">We wish you all the best for {{ $data['program_name'] }}. To continue and get access to the course, request you to click the link below.</p>
				<p style="margin: 5px 0;">AAFT Online is requesting you to make an online payment for:</p>
			</td>
		</tr>
			
		<tr>
			<td style="padding-bottom: 40px;">
				<p style="margin: 0;">&nbsp;</p>
				<p style="margin: 0;"><strong>Your Purchase Details</strong></p>
				<p style="margin: 0;">&nbsp;</p>
				<p style="margin: 5px 0;">Program name : {{ $data['program_name'] }}</p>
				<p style="margin: 5px 0;">Student Name: {{ $data['name'] }}</p>
				<!--<p style="margin: 5px 0;">Email: <span style="color:#FF531C; text-decoration: underline;">email</span></p>
				<p style="margin: 5px 0;">Phone:</p>-->
				<!--<p style="margin: 5px 0;">Comment: Referral Discount</p>-->
				<!--<p style="margin: 5px 0;">Amount: <span style="color:#000000; font-size: 22px;"><strong>5399 INR</strong></span></p>-->
				<p style="width:170px; border-radius:5px; background-color:#FF531C; padding: 7px 0; text-align: center; border:1px solid #FF531C;">
					<a href="{{ $data['link'] }}" style="color:#FFFFFF; text-decoration: none;">PROCEED TO PAY</a>
				</p>
				<p style="margin: 0;">&nbsp;</p>
				<p style="margin: 5px 0;">Thank You,</p>
				<p style="margin: 5px 0;">AAFT ONLINE</p>
			</td>
		</tr>
		
		<tr style="background-color: #141414;">
			<td style="padding: 20px 0; font-size:16px; color: #FFFFFF; text-align: center;">
				<p style="margin: 5px 0;"><a href="https://aaftonline.com/" style="text-decoration:none; color:#FFFFFF;">www.aaftonline.com</a></p>
				<p style="margin: 5px 0;">AAFT Online</p>
				<p style="margin: 5px 0;">B-122, Udhyog Marg, B Block, Sector 5, Noida, Uttar Pradesh 201301</p>
				<p style="text-align: center;">
					<a href="https://www.facebook.com/AAFTonline" style="text-decoration:none;"><img src="https://aaftonline.com/sales/icon-facebook.png" alt="facebook"></a> &nbsp; &nbsp; &nbsp;
					<a href="https://www.instagram.com/aaftonline/" style="text-decoration:none;"><img src="https://aaftonline.com/sales/icon-instagram.png" alt="instagram"></a> &nbsp; &nbsp; &nbsp;
					<a href="https://www.linkedin.com/company/aaft-online/" style="text-decoration:none;"><img src="https://aaftonline.com/sales/icon-linkedin.png" alt="linkedin"></a>
				</p>
			</td>
		</tr>

	</tbody>
</table>
	
</body>
</html>
