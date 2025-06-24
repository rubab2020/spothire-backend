<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
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
						<td align="center" style="padding: 25px 0 25px 0; color: #153643; font-size: 28px;">
							<img src="{{ $message->embed(public_path().'/images/logo/logo_sm.jpg') }}" 
								alt="Yaywerk"
								style="display: block; width: auto; height: 40px" 
							/>
						</td>
					</tr>
					<tr>
						<td align="center" 
							style="background-color: #00b0f0; padding: 10px 0 10px 0; color: white; font-size: 20px; font-weight: bold;">
							{{ $subject }}
						</td>
					</tr>
					<tr>
						<td bgcolor="#ffffff" style="padding: 40px 30px 40px 30px;">
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td style="padding: 0px 0 30px 0; color: #153643; font-size: 16px; line-height: 20px;">
										Hi {{ $receipientName }},
										<br>
										<br>
										{{ $msg }}
										<br>
								    <br>
								    <a href="{{ $siteUrl }}" alt="Click here to Visit yayerk" target="_blank" >
											Click Here to Visit Yaywerk
										</a>
									</td>
								</tr>
								<tr>
									<td>
										Regards,
										<br>
										Yaywerk
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>
</html>