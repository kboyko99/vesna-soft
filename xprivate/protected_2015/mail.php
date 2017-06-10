<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Email sender</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	<!-- // <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script> -->
	<!-- // <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script> -->
	<style>
		form {
			padding: 20px;
		}
		textarea {
			resize: none;
		}
		tr,th,td {
			padding:10px;
		}
		th {
			width: 170px;
		}
		td {
			width: 600px;
		}
		td input {
			width: 100%;
		}
		td textarea {
			width: 100%;
			height: 200px;
		}
		td div.add {
			border: 1px dotted black;
			padding: 5px;
			margin: 5px 0; 
			width: 200px;
		}
		#result {
			margin: 20px;
			padding: 10px;
			width: 760px;
		}
	</style>
</head>
	<body>
		<?php
			if(isset($_POST['recipient']) && isset($_POST['theme']) && isset($_POST['message'])){
				$recipient = $_POST['recipient'];
				$theme = $_POST['theme'];
				$message = $_POST['message'];

				$message .= "<br><br>З повагою,<br>Команда Vesna-Soft 2015<br>-----------------------<br><a href='http://vesnasoft.org'>vesnasoft.org</a> | <a href='https://vk.com/vesna_soft'>vk</a>";

				require_once '../lib/swift_required.php';

				// Create the mail transport configuration
				$transportForUser = Swift_MailTransport::newInstance();

				// Create the message
				$messageForUser = Swift_Message::newInstance();
				$messageForUser->setTo(array(
					$recipient => "Registration"
				));
				$messageForUser->setBcc(array(
					'vesna@programming.kr.ua' => "Vesna"
				));
				$messageForUser->setContentType("text/html");
				$messageForUser->setSubject($theme);
				$messageForUser->setBody($message);
				$messageForUser->setFrom("vesna@programming.kr.ua", "Vesna-Soft");
				
				// Send the email
				$mailer = Swift_Mailer::newInstance($transportForUser);
				$mailer->send($messageForUser);

				echo "<div id='result' class='table-bordered'>";
				echo "<u>Відправлене повідомлення на адресу</u>: $recipient<br>";
				echo "<u>Тема</u>: $theme<br>";
				echo "<u>Повідомлення</u>:<br> $message";
				echo "</div>";

				file_put_contents('mail.log', date('d.m.Y H:i')." SENDTO: $recipient, THEME: $theme, TEXT: $message".PHP_EOL, FILE_APPEND);
			}
		?>
		<form action="" method="POST">
			<table>
				<tr>
					<th>
						<label for="recipient">E-mail отримувача</label>
					</th>
					<td>
						<input id="recipient" name="recipient" type="email" required />
					</td>
				</tr>
				<tr>
					<th>
						<label for="theme">Тема повідомлення</label>
					</th>
					<td>
						<input id="theme" name="theme" type="text" required />
					</td>
				</tr>
				<tr>
					<th>
						<label for="message">Текст повідомлення</label>
					</th>
					<td>
						<textarea id="message" name="message" required></textarea>
					</td>
				</tr>
				<tr>
					<th>
						Замітки
					</th>
					<td>
						1. Повідомлення буде відправлене з адреси vesna@programming.kr.ua <br>
						2. Повідомлення повинно бути написано у html форматі.<br>
						3. До повідомлення буде доданий текст <br>
						<div class="add">
							З повагою,<br>
							Команда Vesna-Soft 2015<br>
							-----------------------<br>
							<a href='http://vesnasoft.org'>vesnasoft.org</a> | <a href='https://vk.com/vesna_soft'>vk</a>
						</div>
					</td>
				</tr>
			</table>
			<button class="btn btn-success">Відправити повідомлення</button>
		</form>
		
		<pre>
			!!! ПРИКЛАД !!!

			Доброго дня!
			&lt;br&gt;&lt;br&gt;

			Дякуємо за реєстрацію на Vesna-Soft 2015!&lt;br&gt;
			Чекаємо на тебе 23 травня 2015 року о 10:00 за адресою пров. Василівський, 10, 5й поверх.
			&lt;br&gt;&lt;br&gt;

 			Найближчим часом ми зв’яжемось з тобою аби узгодити всі деталі презентації твого проекту.
 		

			!!!ЭТО ДОПИШЕТЬСЯ АВТОМАТИЧЕСКИ (просто для примера как ссылки написать)

			&lt;br&gt;&lt;br&gt;
			З повагою,&lt;br&gt;
			Команда Vesna-Soft 2015&lt;br&gt;
			---------------------&lt;br&gt;
			&lt;a href='http://vesnasoft.org'&gt;vesnasoft.org&lt;/a&gt; | &lt;a href='https://vk.com/vesna_soft'&gt;vk&lt;/a&gt;
		</pre>
	</body>
</html>





<!--
 $mailForUser = [
 	"hakaton" => "
 		Доброго дня!
 		<br><br>

 		Дякуємо за реєстрацію на Vesna-Soft 2015!<br>
 		Чекаємо на тебе 23 травня 2015 року о 10:00 за адресою пров. Василівський, 10, 5й поверх.
 		<br><br>

 		З повагою,<br>
 		Команда Vesna-Soft 2015<br>
 		---------------------<br>
 		<a href='http://vesnasoft.org'>vesnasoft.org</a> | <a href='https://vk.com/vesna_soft'>vk</a>
 	",
 	"project" => "
 		Доброго дня! 
 		<br><br>

 		Дякуємо за реєстрацію на Vesna-Soft 2015!<br>
 		Ми зв’яжемось з тобою найближчим часом аби узгодити всі деталі презентації твого проекту.
 		<br><br>

 		З повагою,<br>
 		Команда Vesna-Soft 2015<br>
 		---------------------<br>
 		<a href='http://vesnasoft.org'>vesnasoft.org</a> | <a href='https://vk.com/vesna_soft'>vk</a>
 	",
 	"hak_proj" =>"
 		Доброго дня!
 		<br><br>

 		Дякуємо за реєстрацію на Vesna-Soft 2015!<br>
 		Чекаємо на тебе 23 травня 2015 року о 10:00 за адресою пров. Василівський, 10, 5й поверх.
 		<br><br>

 		Найближчим часом ми зв’яжемось з тобою аби узгодити всі деталі презентації твого проекту.
 		<br><br>

 		З повагою,<br>
 		Команда Vesna-Soft 2015<br>
 		---------------------<br>
 		<a href='http://vesnasoft.org'>vesnasoft.org</a> | <a href='https://vk.com/vesna_soft'>vk</a>
 	"
 ];
-->
