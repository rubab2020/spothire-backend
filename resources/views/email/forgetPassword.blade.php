<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<style type="text/css">
.button {
    background-color: #4CAF50;
    border: none;
    color: white;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
}
a{
	color:white;
	text-decoration: none;
}
a:hover{
	text-decoration: underline;
}
</style>
</head>
<body style="margin: 0; padding: 0;">
	<table border="0" 
		cellpadding="0" 
		cellspacing="0" 
		width="100%">	
		<tr>
			<td style="padding: 10px 0 30px 0;">
				<table align="center" 
					border="0" 
					cellpadding="0" 
					cellspacing="0" 
					width="600" 
					style="border: 1px solid #cccccc; border-collapse: collapse;">
					<tr>
						<td align="center" 
							style="padding: 10px 0 10px 0; color: #153643; font-size: 28px;">
							<h2>
								<a href="{{ $site_url }}" 
									target="_blank" 
									style="color: #00b0f0">
									Yaywerk
								</a>
							</h2>
						</td>
					</tr>
					<tr>
						<td align="center" 
							style="background-color: #00b0f0; padding: 10px 0 10px 0; color: white; font-size: 28px; font-weight: bold;">
							Reset Password
						</td>
					</tr>
					<tr>
						<td bgcolor="#ffffff" 
							style="padding: 40px 30px 40px 30px;">
							<table 
								border="0" 
								cellpadding="0" 
								cellspacing="0" 
								width="100%">
								<tr>
									<td style="padding: 20px 0 30px 0; color: #153643; font-size: 16px; line-height: 20px;">
										Hi {{ $name }},
									    <br>
									    <br>
									    You have requested a password reset, please follow the link below to reset your password.
									    <br>
									    <br>
									    <a href="{{ $site_url }}/recover-password?token={{ $verification_code }}&email={{ $email }}" 
									    	target="_blank"
									    	style="color: #15c; text-decoration: underline;">
									    	Reset my password.
									    </a>
									    <br>
									    <br>
									    Having trouble? paste the URL below into your web browser:
									    <br>
										<span style="color: #15c; font-size: 12px;">
											{{ $site_url }}/recover-password?token={{ $verification_code }}&email={{ $email }}
										</span>
									</td>
								</tr>
								<tr>
									<td>
										Regards,
										<br>
										<a href="{{ $site_url }}" 
											style="color: #153643; font-weight: bold" 
											target="_blank">
											Yaywerk
										</a>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<!-- <tr>
						<td style="padding: 30px 30px 30px 30px; background-color: #00b0f0">
							<table border="0" 
								cellpadding="0" 
								cellspacing="0" 
								width="100%">
								<tr>
									<td style="color:white; font-size: 14px;" 
										width="75%">
										<a href="{{ $site_url }}" 
											target="_blank" 
											style="color:white;">© spothire.co 2019</a>
										<br/>
									</td>
								</tr>
							</table>
						</td>
					</tr>
 -->				</table>
			</td>
		</tr>
	</table>
</body>
</html>