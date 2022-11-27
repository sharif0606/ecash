<!DOCTYPE html>
<html>
<head>
<title>Page Title</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body style="margin: 0; padding: 0;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%"> 
        <tr>
            <td style="padding: 10px 0 30px 0;">
                <table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border: 1px solid #cccccc; border-collapse: collapse;">
                    <tr>
                        <td align="center" bgcolor="#70bbd9" style="padding: 20px 0 30px 0; color: #153643; font-size: 28px; font-weight: bold; font-family: Arial, sans-serif;">
                            <img alt="Logo" src="{{asset('/')}}assets/media/logos/logo-short.png" alt="Creating Email Magic" width="200" height="130" style="display: block;"/>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#ffffff" style="padding: 40px 10px 40px 30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td style="color: #153643; font-family: Arial, sans-serif; font-size: 24px;">
                                        <b>Medbill</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 10px;">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td style="padding: 10px;color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 10px;">Shop Id</td><td>{{$shopCode}}</td>
                                                @php
                                                $roleType = array(1=>'superadmin',2=>'admin',3=>'dataentry',4=>'executive',5=>'accountmanager',6=>'marketingmanager',7=>'owner',8=>'salesmanager',9=>'salesman');
                                                @endphp
                                                <td style="padding: 10px;color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 10px;">User Role</td><td> 
                                               
                                               {{$roleType[$role]}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px;color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 10px;">User Name</td><td><b>{{$username}}</b></td>
                                                <td style="padding: 10px;color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 10px;">Password</td><td> As Chosen</td>
                                            </tr>
                                             <tr>
                                                <td style="padding: 10px;color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 10px;">Login Url</td><td><a href="https://www.medbill.com.bd/login"></a></td>
                
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#ee4c50" style="padding: 30px 30px 30px 30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td style="color: #ffffff; font-family: Arial, sans-serif; font-size: 14px;" width="75%">
                                        &reg;medbill.com.bd 2020<br/>
                                        <a href="https://medbill.com.bd/" style="color: #ffffff;"><font color="#ffffff">medbill.com.bd</font></a>
                                    </td>
                                    <td align="right" width="25%">
                                        <table border="0" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="font-family: Arial, sans-serif; font-size: 12px; font-weight: bold;">
                                                    <a href="http://www.twitter.com/" style="color: #ffffff;">
                                                        <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/210284/tw.gif" alt="Twitter" width="38" height="38" style="display: block;" border="0" />
                                                    </a>
                                                </td>
                                                <td style="font-size: 0; line-height: 0;" width="20">&nbsp;</td>
                                                <td style="font-family: Arial, sans-serif; font-size: 12px; font-weight: bold;">
                                                    <a href="http://www.twitter.com/" style="color: #ffffff;">
                                                        <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/210284/fb.gif" alt="Facebook" width="38" height="38" style="display: block;" border="0" />
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
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
