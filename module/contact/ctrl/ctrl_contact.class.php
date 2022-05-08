<?php
 if (isset($_SESSION["tiempo"])) {  
    $_SESSION["tiempo"] = time();
}

class ctrl_contact
{
	function view()
	{
		common::load_view('top_page_contact.php', VIEW_PATH_CONTACT . 'contact_list.html');
	}

	function send_email_contact()
	{
		$message = [
			'type' => 'contact',
			'inputName' => $_POST['name'],
			'fromEmail' => $_POST['email'],
			'inputMatter' => $_POST['matter'],
			'inputMessage' => $_POST['message']
		];
		 $email = json_decode(mail::send_email($message), true);
	
		if (!empty($email)) {
			$email = json_decode(true, true);
			echo json_encode($email);
		}else {
			echo json_encode('Error!');
		}

	}
}
