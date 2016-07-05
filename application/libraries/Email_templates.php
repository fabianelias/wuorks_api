<?php
/*******************************************************************************
 * 
 *                          Libreria Email template
 * 
 ******************************************************************************/

Class Email_templates{
    
    /***************************************************************************
     * @email_confirm(), template para envío de confirmación de email.
     **************************************************************************/
    public function email_confirm( $url_confirm = "", $nombre = ""){
       
        $html = '<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Confirmar cuenta creada</title>
</head>
<body>
	<!-- Notification 6 -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="full2" object="drag-module" bgcolor="#fbfbfb" c-style="not6BG">
	<tr mc:repeatable>
		<td align="center" id="not6">
		<div mc:hideable>
			
			<!-- Mobile Wrapper -->
			<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile2">
				<tr>
					<td width="100%" align="center">
					
						<div class="sortable_inner">
						<!-- Space -->
						<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="full2" object="drag-module-small">
							<tr>
								<td width="600" height="0"></td>
							</tr>
						</table><!-- End Space -->
						
						<!-- Space -->
						<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="full2" object="drag-module-small">
							<tr>
								<td width="600" height="0"></td>
							</tr>
						</table><!-- End Space -->
			
						<!-- Start Top -->
						<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile2" bgcolor="#4edeb5" style="-webkit-border-top-left-radius: 5px; -moz-border-top-left-radius: 5px; border-top-left-radius: 5px; -webkit-border-top-right-radius: 5px; -moz-border-top-right-radius: 5px; border-top-right-radius: 5px;" object="drag-module-small" c-style="not6GreenBG">
							<tr>
								<td width="600" valign="middle" align="center" class="logo">
									
									<!-- Header Text --> 
									<table width="540" border="0" cellpadding="0" cellspacing="0" align="center" style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="fullCenter2">
										<tr>
											<td width="100%" height="30"></td>
										</tr>
										<tr>
											<td width="100%"><span object="image-editable"><img editable="true" src="https://www.wuorks.cl/asset/img/logo-cl.png" width="155" alt="" border="0" mc:edit="39"></span></td>
										</tr>
										<tr>
											<td width="100%" height="30"></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						
						</div>
						
						<!-- Mobile Wrapper -->
						<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="full2" object="drag-module-small" style="-webkit-border-bottom-left-radius: 5px; -moz-border-bottom-left-radius: 5px; border-bottom-left-radius: 5px; -webkit-border-bottom-right-radius: 5px; -moz-border-bottom-right-radius: 5px; border-bottom-right-radius: 5px;">
							<tr>
								<td width="600" align="center" style="-webkit-border-bottom-left-radius: 5px; -moz-border-bottom-left-radius: 5px; border-bottom-left-radius: 5px; -webkit-border-bottom-right-radius: 5px; -moz-border-bottom-right-radius: 5px; border-bottom-right-radius: 5px;" bgcolor="#ffffff" c-style="not6Body">
									
									<div class="sortable_inner">
						
									<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile2" bgcolor="#ffffff" c-style="not6Body" object="drag-module-small">
										<tr>
											<td width="600" valign="middle" align="center">
											 
												<table width="540" border="0" cellpadding="0" cellspacing="0" align="center" style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="fullCenter2">
													<tr>
														<td width="100%" height="30"></td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
									
									<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile2" bgcolor="#ffffff" c-style="not6Body" object="drag-module-small">
										<tr>
											<td width="600" valign="middle" align="center">
											 
												<table width="540" border="0" cellpadding="0" cellspacing="0" align="center" style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="fullCenter2">
													<tr>
														<td valign="middle" width="100%" style="text-align: left; font-family: Helvetica, Arial, sans-serif; font-size: 23px; color: #3f4345; line-height: 30px; font-weight: 100;" t-style="not6Headline" mc:edit="40" object="text-editable">
															<!--><span style="font-family: "proxima_novathin", Helvetica; font-weight: normal;"><singleline>Hola '.$nombre.', </singleline><!--[if !mso]><!--></span><!--<![endif]-->
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
									
									<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile2" bgcolor="#ffffff" c-style="not6Body" object="drag-module-small">
										<tr>
											<td width="600" valign="middle" align="center">
											 
												<table width="540" border="0" cellpadding="0" cellspacing="0" align="center" style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="fullCenter2">
													<tr>
														<td width="100%" height="30"></td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
									
									<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile2" bgcolor="#ffffff" c-style="not6Body" object="drag-module-small">
										<tr>
											<td width="600" valign="middle" align="center">
											 
												<table width="540" border="0" cellpadding="0" cellspacing="0" align="center" style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="fullCenter2">
													<tr>
														<td valign="middle" width="100%" style="text-align: left; font-family: Helvetica, Arial, sans-serif; font-size: 14px; color: #3f4345; line-height: 24px;" t-style="not6Text" mc:edit="41" object="text-editable">
															<span style="font-family: "proxima_nova_rgregular", Helvetica; font-weight: normal;">
															<singleline>
															Bienvenid@ a Wuorks el portal de servicios profesionales.	
															<br/>
															Antes de continuar necesitamos validar tu cuenta de email, por favor pincha en siguiente enlace y termina tú registro.
															</singleline><!--[if !mso]><!--></span><!--<![endif]-->
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
									
									<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile2" bgcolor="#ffffff" c-style="not6Body" object="drag-module-small">
										<tr>
											<td width="600" valign="middle" align="center">
											 
												<table width="540" border="0" cellpadding="0" cellspacing="0" align="center" style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="fullCenter2">
													<tr>
														<td width="100%" height="40"></td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
									
									<!---->
									<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile2" bgcolor="#ffffff" c-style="not6Body" object="drag-module-small">
										<tr>
											<td width="600" valign="middle" align="center">
											 
												<table width="540" border="0" cellpadding="0" cellspacing="0" align="center" style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="fullCenter2">
													<tr>
														<td>
															<table border="0" cellpadding="0" cellspacing="0" align="left"> 
																<tr> 
																	<td align="center" height="45" c-style="not6ButBG" bgcolor="#4edeb5" style="-webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; padding-left: 30px; padding-right: 30px; font-weight: bold; font-family: Helvetica, Arial, sans-serif; color: #ffffff;" t-style="not6ButText" mc:edit="42">
																		<multiline><span style="font-family:"proxima_nova_rgbold", Helvetica; font-weight: normal;">
																			<a href="'.$url_confirm.'" style="color: #ffffff; font-size:15px; text-decoration: none; line-height:34px; width:100%;" t-style="not6ButText" object="link-editable">Validar cuenta</a>
																		<!--[if !mso]><!--></span><!--<![endif]--></multiline>
																	</td> 
																</tr> 
															</table> 
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table><!----------------- End Button Center ----------------->
									
									<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile2" bgcolor="#ffffff" c-style="not6Body" object="drag-module-small">
										<tr>
											<td width="600" valign="middle" align="center">
											 
												<table width="540" border="0" cellpadding="0" cellspacing="0" align="center" style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="fullCenter2">
													<tr>
														<td width="100%" height="35"></td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
									
									<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile2" bgcolor="#ffffff" c-style="not6Body" object="drag-module-small">
										<tr>
											<td width="600" valign="middle" align="center">
											 
												<table width="540" border="0" cellpadding="0" cellspacing="0" align="center" style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="fullCenter2">
													<tr>
														<td valign="middle" width="100%" style="text-align: left; font-family: Helvetica, Arial, sans-serif; font-size: 14px; color: #3f4345; line-height: 24px;" t-style="not6Text" mc:edit="43" object="text-editable">
															<span style="font-family: "proxima_nova_rgregular", Helvetica; font-weight: normal;"><multiline>
															Esperamos que tú estadia sea larga,
															y que wuorks de verdad te sirva :)
															<br/><br/>
															Gracias!
															<br/>
															Equipo Wuorks
																
															</multiline><!--[if !mso]><!--></span><!--<![endif]-->
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
									
									<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile2" bgcolor="#ffffff" c-style="not6Body" object="drag-module-small" style="-webkit-border-bottom-left-radius: 5px; -moz-border-bottom-left-radius: 5px; border-bottom-left-radius: 5px; -webkit-border-bottom-right-radius: 5px; -moz-border-bottom-right-radius: 5px; border-bottom-right-radius: 5px;">
										<tr>
											<td width="600" valign="middle" align="center" style="-webkit-border-bottom-left-radius: 5px; -moz-border-bottom-left-radius: 5px; border-bottom-left-radius: 5px; -webkit-border-bottom-right-radius: 5px; -moz-border-bottom-right-radius: 5px; border-bottom-right-radius: 5px;">
											 
												<table width="540" border="0" cellpadding="0" cellspacing="0" align="center" style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="fullCenter2">
													<tr>
														<td width="100%" height="50"></td>
													</tr>
												</table>
																				
											</td>
										</tr>
									</table>
									
								</div>
								</td>
							</tr>
						</table>
						
						<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="full2" object="drag-module-small">
							<tr>
								<td width="600" height="30"></td>
							</tr>
						</table>
						
						<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile2" object="drag-module-small">
							<tr>
								<td valign="middle" width="600" style="text-align: left; font-family: Helvetica, Arial, sans-serif; font-size: 13px; color: #ffffff; line-height: 24px; font-style: italic;" t-style="not6Footer" mc:edit="44" object="text-editable">
									<span style="font-family: "proxima_nova_rgregular", Helvetica; font-weight: normal;"><!--<![endif]-->Copyright Wuorks.com todos los derechos reservados.<!--<![endif]--></span>
									<br/>
									<span style="font-family: "proxima_nova_rgregular", Helvetica;"><a href="#" style="text-decoration: none; color: #ffffff;" t-style="not6Footer" object="link-editable">
										e-mail generado de forma automatica, no responder. Cualquier duda o cunsulta contactanos a contacto@wuorks.com
									</a><!--unsub--><!--[if !mso]><!--></span><!--<![endif]-->
								</td>
							</tr>
						</table>
						
						
						<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile2" object="drag-module-small">
							<tr>
								<td width="600" height="30"></td>
							</tr>
						</table>
						
						<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile2" object="drag-module-small">
							<tr>
								<td width="600" height="29"></td>
							</tr>
							<tr>
								<td width="600" height="1" style="font-size: 1px; line-height: 1px;">&nbsp;</td>
							</tr>
						</table>
						</div>
						
					</td>
				</tr>
			</table>
			
		</div>
		</td>
	</tr>
</table><!-- End Notification 6 -->
</body>
</html>';
        
        return $html;//utf8_decode($html);
    }
    
    
    /***************************************************************************
     * @clave()
     **************************************************************************/
    public function pass($url_pass,$name =""){
        $html = '<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Confirmar cuenta creada</title>
</head>
<body>
	<!-- Notification 6 -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="full2" object="drag-module" bgcolor="#fbfbfb" c-style="not6BG">
	<tr mc:repeatable>
		<td align="center" id="not6">
		<div mc:hideable>
			
			<!-- Mobile Wrapper -->
			<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile2">
				<tr>
					<td width="100%" align="center">
					
						<div class="sortable_inner">
						<!-- Space -->
						<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="full2" object="drag-module-small">
							<tr>
								<td width="600" height="0"></td>
							</tr>
						</table><!-- End Space -->
						
						<!-- Space -->
						<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="full2" object="drag-module-small">
							<tr>
								<td width="600" height="0"></td>
							</tr>
						</table><!-- End Space -->
			
						<!-- Start Top -->
						<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile2" bgcolor="#4edeb5" style="-webkit-border-top-left-radius: 5px; -moz-border-top-left-radius: 5px; border-top-left-radius: 5px; -webkit-border-top-right-radius: 5px; -moz-border-top-right-radius: 5px; border-top-right-radius: 5px;" object="drag-module-small" c-style="not6GreenBG">
							<tr>
								<td width="600" valign="middle" align="center" class="logo">
									
									<!-- Header Text --> 
									<table width="540" border="0" cellpadding="0" cellspacing="0" align="center" style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="fullCenter2">
										<tr>
											<td width="100%" height="30"></td>
										</tr>
										<tr>
											<td width="100%"><span object="image-editable"><img editable="true" src="https://www.wuorks.cl/asset/img/logo-cl.png" width="155" alt="" border="0" mc:edit="39"></span></td>
										</tr>
										<tr>
											<td width="100%" height="30"></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						
						</div>
						
						<!-- Mobile Wrapper -->
						<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="full2" object="drag-module-small" style="-webkit-border-bottom-left-radius: 5px; -moz-border-bottom-left-radius: 5px; border-bottom-left-radius: 5px; -webkit-border-bottom-right-radius: 5px; -moz-border-bottom-right-radius: 5px; border-bottom-right-radius: 5px;">
							<tr>
								<td width="600" align="center" style="-webkit-border-bottom-left-radius: 5px; -moz-border-bottom-left-radius: 5px; border-bottom-left-radius: 5px; -webkit-border-bottom-right-radius: 5px; -moz-border-bottom-right-radius: 5px; border-bottom-right-radius: 5px;" bgcolor="#ffffff" c-style="not6Body">
									
									<div class="sortable_inner">
						
									<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile2" bgcolor="#ffffff" c-style="not6Body" object="drag-module-small">
										<tr>
											<td width="600" valign="middle" align="center">
											 
												<table width="540" border="0" cellpadding="0" cellspacing="0" align="center" style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="fullCenter2">
													<tr>
														<td width="100%" height="30"></td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
									
									<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile2" bgcolor="#ffffff" c-style="not6Body" object="drag-module-small">
										<tr>
											<td width="600" valign="middle" align="center">
											 
												<table width="540" border="0" cellpadding="0" cellspacing="0" align="center" style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="fullCenter2">
													<tr>
														<td valign="middle" width="100%" style="text-align: left; font-family: Helvetica, Arial, sans-serif; font-size: 23px; color: #3f4345; line-height: 30px; font-weight: 100;" t-style="not6Headline" mc:edit="40" object="text-editable">
															<!--><span style="font-family: "proxima_novathin", Helvetica; font-weight: normal;"><singleline>Hola, </singleline><!--[if !mso]><!--></span><!--<![endif]-->
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
									
									<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile2" bgcolor="#ffffff" c-style="not6Body" object="drag-module-small">
										<tr>
											<td width="600" valign="middle" align="center">
											 
												<table width="540" border="0" cellpadding="0" cellspacing="0" align="center" style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="fullCenter2">
													<tr>
														<td width="100%" height="30"></td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
									
									<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile2" bgcolor="#ffffff" c-style="not6Body" object="drag-module-small">
										<tr>
											<td width="600" valign="middle" align="center">
											 
												<table width="540" border="0" cellpadding="0" cellspacing="0" align="center" style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="fullCenter2">
													<tr>
														<td valign="middle" width="100%" style="text-align: left; font-family: Helvetica, Arial, sans-serif; font-size: 14px; color: #3f4345; line-height: 24px;" t-style="not6Text" mc:edit="41" object="text-editable">
															<span style="font-family: "proxima_nova_rgregular", Helvetica; font-weight: normal;">
															<singleline>
															Solicitud cambio contraseña	
															<br/>
															Para cambiar tu contraseña pincha en el siguiente enlace.
															</singleline><!--[if !mso]><!--></span><!--<![endif]-->
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
									
									<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile2" bgcolor="#ffffff" c-style="not6Body" object="drag-module-small">
										<tr>
											<td width="600" valign="middle" align="center">
											 
												<table width="540" border="0" cellpadding="0" cellspacing="0" align="center" style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="fullCenter2">
													<tr>
														<td width="100%" height="40"></td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
									
									<!---->
									<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile2" bgcolor="#ffffff" c-style="not6Body" object="drag-module-small">
										<tr>
											<td width="600" valign="middle" align="center">
											 
												<table width="540" border="0" cellpadding="0" cellspacing="0" align="center" style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="fullCenter2">
													<tr>
														<td>
															<table border="0" cellpadding="0" cellspacing="0" align="left"> 
																<tr> 
																	<td align="center" height="45" c-style="not6ButBG" bgcolor="#4edeb5" style="-webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; padding-left: 30px; padding-right: 30px; font-weight: bold; font-family: Helvetica, Arial, sans-serif; color: #ffffff;" t-style="not6ButText" mc:edit="42">
																		<multiline><span style="font-family:"proxima_nova_rgbold", Helvetica; font-weight: normal;">
																			<a href="'.$url_pass.'" style="color: #ffffff; font-size:15px; text-decoration: none; line-height:34px; width:100%;" t-style="not6ButText" object="link-editable">Cambiar contraseña</a>
																		<!--[if !mso]><!--></span><!--<![endif]--></multiline>
																	</td> 
																</tr> 
															</table> 
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table><!----------------- End Button Center ----------------->
									
									<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile2" bgcolor="#ffffff" c-style="not6Body" object="drag-module-small">
										<tr>
											<td width="600" valign="middle" align="center">
											 
												<table width="540" border="0" cellpadding="0" cellspacing="0" align="center" style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="fullCenter2">
													<tr>
														<td width="100%" height="35"></td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
									
									<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile2" bgcolor="#ffffff" c-style="not6Body" object="drag-module-small">
										<tr>
											<td width="600" valign="middle" align="center">
											 
												<table width="540" border="0" cellpadding="0" cellspacing="0" align="center" style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="fullCenter2">
													<tr>
														<td valign="middle" width="100%" style="text-align: left; font-family: Helvetica, Arial, sans-serif; font-size: 14px; color: #3f4345; line-height: 24px;" t-style="not6Text" mc:edit="43" object="text-editable">
															<span style="font-family: "proxima_nova_rgregular", Helvetica; font-weight: normal;"><multiline>
															Si tú no solicitaste esté cambio por favor comunicate a contacto@wuorks.com
															<br/><br/>
															Gracias!
															<br/>
															Equipo Wuorks
																
															</multiline><!--[if !mso]><!--></span><!--<![endif]-->
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
									
									<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile2" bgcolor="#ffffff" c-style="not6Body" object="drag-module-small" style="-webkit-border-bottom-left-radius: 5px; -moz-border-bottom-left-radius: 5px; border-bottom-left-radius: 5px; -webkit-border-bottom-right-radius: 5px; -moz-border-bottom-right-radius: 5px; border-bottom-right-radius: 5px;">
										<tr>
											<td width="600" valign="middle" align="center" style="-webkit-border-bottom-left-radius: 5px; -moz-border-bottom-left-radius: 5px; border-bottom-left-radius: 5px; -webkit-border-bottom-right-radius: 5px; -moz-border-bottom-right-radius: 5px; border-bottom-right-radius: 5px;">
											 
												<table width="540" border="0" cellpadding="0" cellspacing="0" align="center" style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="fullCenter2">
													<tr>
														<td width="100%" height="50"></td>
													</tr>
												</table>
																				
											</td>
										</tr>
									</table>
									
								</div>
								</td>
							</tr>
						</table>
						
						<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="full2" object="drag-module-small">
							<tr>
								<td width="600" height="30"></td>
							</tr>
						</table>
						
						<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile2" object="drag-module-small">
							<tr>
								<td valign="middle" width="600" style="text-align: left; font-family: Helvetica, Arial, sans-serif; font-size: 13px; color: #ffffff; line-height: 24px; font-style: italic;" t-style="not6Footer" mc:edit="44" object="text-editable">
									<span style="font-family: "proxima_nova_rgregular", Helvetica; font-weight: normal;"><!--<![endif]-->Copyright Wuorks.com todos los derechos reservados.<!--<![endif]--></span>
									<br/>
									<span style="font-family: "proxima_nova_rgregular", Helvetica;"><a href="#" style="text-decoration: none; color: #ffffff;" t-style="not6Footer" object="link-editable">
										e-mail generado de forma automatica, no responder. Cualquier duda o cunsulta contactanos a contacto@wuorks.com
									</a><!--unsub--><!--[if !mso]><!--></span><!--<![endif]-->
								</td>
							</tr>
						</table>
						
						
						<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile2" object="drag-module-small">
							<tr>
								<td width="600" height="30"></td>
							</tr>
						</table>
						
						<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile2" object="drag-module-small">
							<tr>
								<td width="600" height="29"></td>
							</tr>
							<tr>
								<td width="600" height="1" style="font-size: 1px; line-height: 1px;">&nbsp;</td>
							</tr>
						</table>
						</div>
						
					</td>
				</tr>
			</table>
			
		</div>
		</td>
	</tr>
</table><!-- End Notification 6 -->
</body>
</html>';
        
        return $html;//utf8_decode($html);
    }
}