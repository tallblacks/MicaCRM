<?php
    require_once("./include/config.inc.php");
    require_once("./include/mysql.inc.php");
    require_once("./include/function.inc.php");
    require_once("./include/user.inc.php");
    CheckCookies();

    session_start();
    if (isset($_SESSION["SESS_USERID"])) {
        Header("Location:index_logon.php");
	    exit();
    }

    $feedback=@$_GET['feedback'];
?>
<html>
	<head>
		<title>Mica的客户关系管理系统</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel=stylesheet type=text/css href=style/global.css>
	</head>
	<body bgcolor=#FFFFFF link=#000099 vLink=#000099 topMargin=0 rightmargin=0 leftmargin=0 background=images/bg.gif>
		<table width=82% border=0 cellspacing=0 cellpadding=0 align=center>
			<tr>
				<td align=center><img src=images/title.jpg width=386 height=102></td>
				<td><img src=images/pencil.jpg width=207 height=102></td>
			</tr>
		</table>
		<table width=100% border=0 cellspacing=0 cellpadding=0>
			<tr>
				<td rowspan=2><img src=images/book.jpg width=335 height=338></td>
				<td valign=middle align=left>
					<form action="./user/login.php" name="loginForm" method="post">
					<table width=342 border=0 cellspacing=0 cellpadding=0 background=images/login.gif height=220>
						<tr align=center valign=bottom>
							<td height=46 class=cur>
							<?php
								if ($feedback) {
									echo $feedback;
								}
							?>
							</td>
						</tr>
						
						<tr align=center valign=middle>
							<td class=line><?php echo $strings['USERNAME']?>:<input type=text name=username></td>
						</tr>
						<tr align=center valign=middle>
							<td class=line><?php echo $strings['PASSWORD']?>:<input type=password name=password></td>
						</tr>
						<tr align=center valign=middle>
							<td class=line><input type="checkbox" class="checkbox" name="autologin" checked>十天内自动登录</td>
						</tr>
						<tr align=center valign=top>
							<td><input type=image src=images/search.gif width=34 height=37></td>
						</tr>
					</table>
					</form>
				</td>
			</tr>
			<script language="JavaScript" type="text/javascript">
				<!--
					document.loginForm.username.focus();
				//-->
			</script>
			<tr>
				<td class=line valign=top height=46>欢迎使用Mica的CRM客户关系管理系统！请输入用户名和口令进行身份确认。</td>
			</tr>
		</table>
		<table width=100% border=0 cellspacing=0 cellpadding=0>
			<tr>
				<td class=line align=center><font color=#ffffff>
					<br>电子邮箱:micayao@126.com Mica &copy; 版权所有2019</font>
				</td>
			</tr>
		</table>
	</body>
</html>