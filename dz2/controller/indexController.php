<?php 
//require_once __DIR__ .'/../model/user.class.php';
class IndexController extends BaseController
{
	public function index() 
	{

		$this->registry->template->title = 'Log in';
		$this->registry->template->show( 'index_login');

	}
	
	public function logout()
	{
		session_destroy();
		header( 'Location: ' . __SITE_URL . '/index.php');
	}

	public function login()
	{
		if( !isset( $_POST['username']) || !isset( $_POST['password'] ) || !isset( $_POST['log_in'] ) 
			/*|| preg_match()*/)	//	username ili pasword pogrešno uneseni ++++	dodati pregmatch da izbazi ako je maliciozan unos
		{	//	ispisat grešku pri login-u
			$this->registry->template->errorFlag = True;
			$this->registry->template->errorMsg = 'Poreška pri unosu!';
			$this->index();
			return;
		}
		elseif( !userExsists($_POST['username']) )
		{
			$this->registry->template->errorFlag = True;
			$this->registry->template->errorMsg = 'Korisnik ne postoji!';
			$this->index();
			return;
		}
		elseif( !emailConfirmed( $_POST['username']) ){
			$this->registry->template->errorFlag = True;
			$this->registry->template->errorMsg = 'Registration not confirmed!';
			$this->index();
		}
		else{

			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT * FROM dz2_users WHERE username=:user');
			$st->execute(['user'=>$_POST['username']]);

			if( $st->rowCount() !== 1)	// korisnik ne postoji ili ih je više -- ispisat grrešku
			{
				$this->registry->template->errorFlag = True;
				$this->registry->template->errorMsg = 'Taj korisnik ne postoji!';
				$this->index();
				return;
			}

			$row = $st->fetch();
			$password_hash = $row['password_hash'];
			
			if( password_verify( $_POST['password'], $password_hash) )
			{
				
				$_SESSION['user'] = new User($row['id'], $row['username'], ' ',$row['email'], $row['registration_sequence'], $row['has_registered'] );

				$_SESSION['tab'] = 'My Channels';
				header( 'Location: ' . __SITE_URL . '/index.php?rt=channel' );
			}
			else{
				$this->registry->template->errorFlag = True;
				$this->registry->template->errorMsg = 'Krivi password!';
				$this->index();

			}
		}
	}

	public function registerForward()
	{
		$this->registry->template->title = 'Register';
		$this->registry->template->show( 'register');
	}

	public function register()
	{
		if( !isset( $_POST['username']) || !isset( $_POST['password'] ) || !isset( $_POST['email'] )  )	//nisu postavljeni
		{
			$this->registry->template->errorFlag = True;
			$this->registry->template->errorMsg = 'Krivi password!';
			$this->registerForward();
		}
		elseif( userExsists($_POST['username']) )
		{
			$this->registry->template->errorFlag = True;
			$this->registry->template->errorMsg = 'Korisnik već postoji!';
			$this->registerForward();
		}
		else
		{
			$reg_seq = '';
			for( $i = 0; $i < 20; ++$i )
				$reg_seq .= chr( rand(0, 25) + ord( 'a' ) );

			$db = DB::getConnection();
			$st = $db->prepare( 'INSERT INTO dz2_users (username, password_hash, email, registration_sequence, has_registered) VALUES (:val1,:val2,:val3,:val4,:val5)');
			$st->execute(['val1'=> $_POST['username'],'val2'=> password_hash( $_POST['password'], PASSWORD_DEFAULT ), 
						'val3'=> $_POST['email'],'val4'=> $reg_seq,'val5'=> 0]);

			$to       = $_POST['email'];
			$subject  = 'Registracijski mail';
			$message  = 'Poštovani ' . $_POST['username'] . "!\nZa dovršetak registracije kliknite na sljedeći link: ";
			$message .= 'http://' . $_SERVER['SERVER_NAME'] . htmlentities( dirname( $_SERVER['PHP_SELF'] ) ) . '/register.php?niz=' . $reg_seq . "\n";
			$headers  = 'From: rp2@studenti.math.hr' . "\r\n" .
						'Reply-To: rp2@studenti.math.hr' . "\r\n" .
						'X-Mailer: PHP/' . phpversion();
	
			$isOK = mail($to, $subject, $message, $headers);
	
			if( !$isOK )
				exit( 'Greška: ne mogu poslati mail. (Pokrenite na rp2 serveru.)' );
			
			$this->login();
		}
	}
}; 




// ------
function userExsists( $username )
{
	$db = DB::getConnection();
	$st = $db->prepare( 'SELECT * FROM dz2_users WHERE username=:user');
	$st->execute(['user'=>$username]);
	if( $st->rowCount() !== 0)
		return True;
	else
		return False;
}

function emailConfirmed( $username )
{
	$db = DB::getConnection();
	$st = $db->prepare( 'SELECT has_registered FROM dz2_users WHERE username=:user');
	$st->execute(['user'=>$username]);
	//if($st->rowCount() === 0)
	//	return False;
	$st = $st->fetch();
	if( $st[0] )
		return True;
	else
		return False;
}


function debug()
{
	echo '<pre>$_POST=';
	print_r( $_POST );
	if (session_status() !== PHP_SESSION_NONE) {
		
	echo '$_SESSION=';
	print_r( $_SESSION );
}
	echo '</pre>';
}

?>
