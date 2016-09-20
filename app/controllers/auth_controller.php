<?php
class AuthController extends Controller
{
	/*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users,updating of login credential and 
    | log out functions
    |
    */

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */

	public function __construct()
	{
		// load the models that this controller will make use of
		$this->models = ['User'];
		$this->loadModels();
	}

    /**
     * Shows the login page to the user 
     * It also process form validation messages and shows appropriate messages 
     * to the user
     * 
     * @return void
     */

	public function login()
	{   
        // messages to be displayed at top of form
        $infoMessages = [];
        $errorMessages = [];

        // check whether a password has just been updated by checking whether 
        // 'password_updated' is set in the sessions array
		if(notificationExists('password_updated')){
			$infoMessages[] = "<p>Password updated.Login to continue</p>";
			removeNotification("password_updated");
		}

		// check to see if there is a notification of an invalid password
		if(notificationExists('invalid_password')){
			$errorMessages[] = "<p>Username/Password is invalid.</p>";
			removeNotification("invalid_password");
		}

		// check to see if there is a notification of empty username
		if(notificationExists('username_empty')){
			$errorMessages[] = "<p>Please enter username</p>";
			removeNotification('username_empty');
		}

		// check to see if there is a notification of empty password
		if(notificationExists('password_empty')){
			$errorMessages[] = "<p>Please enter password</p>";
			removeNotification('password_empty');
		}

		// we can only have one set of errors at a time
		// alertType is the Bootstrap alert type that we want to use
        if(count($infoMessages)){
        	$messages = $infoMessages;
        	$alertType = 'info';
        }
        else{
        	$messages = $errorMessages;
        	$alertType = 'danger';
        }


		// display view associated with this method
		$this->makeView('auth/login',compact('messages' , 'alertType'));
	}
 
    /**
     * Processes login for a user
     *
     * @return void
     */

	public function process_login()
	{
		if(isset($_POST['login'])){
			$errors= [];
			$notifications = [];

			if(empty($_POST['username'])){
				$errors[] = 'username_empty';
			}
			else{
				$username = $_POST['username'];
			}

			if(empty($_POST['password'])){
				$errors[]='password_empty';
			}
			else{
				$password = $_POST['password'];
			}

			if(empty($errors)){
				if(!User::isValidLogin($username , $password)){
					$errors[] = 'invalid_password';
				}
			}

			if(empty($errors)){
				$user = User::findByUsername($username);

				session_regenerate_id();
			
				$_SESSION['gong_user_id'] = $user->getId();

		        session_write_close();

		        redirect('/thegong/news/create');
			}
			else{
				logNotifications($errors);
				redirect('/thegong/auth/login');
			}
		} 
	}

    /**
     * Shows the password update page to the user 
     * It also processes form validation messages and shows appropriate messages 
     * to the user
     * 
     * @return void
     */

	public function update_password()
	{
		// we only have one type messages here,that is error messages
        $messages = [];

        // check if there is a notification that the current password form field
        // was empty and add appropriate error message into messages to be displayed
		if(notificationExists('current_password_empty')){
            $messages[] = "<p>Please enter current password</p>";
            removeNotification('current_password_empty');
		}

        // check if there is a notification that the new password form field was empty
        // and add appropriate error message into messages to be displayed
        if(notificationExists('new_password_empty')){
        	$messages[] = "<p>Please enter new password</p>";
        	removeNotification('new_password_empty');
        }

        // check if there is a notification that the password entered in the current 
        // password form field didn't match that of the currently logged in user
        if(notificationExists('invalid_password')){
        	$messages[] = "<p>Invalid Current Password</p>";
        	removeNotification('invalid_password');
        }

        // bootstrap alert type to use for messages
        $alertType = 'danger';

		$this->makeView('auth/update_password',compact('messages','alertType'));
	}

    
    /**
     * Handles password update form submissions
     * 
     * @return void
     */
	public function process_password_update()
	{
		if(isset($_POST['update_password'])){
			$user_id = $_SESSION['user_id'];
	    	$user = User::find($user_id);

	    	$errors = [];         // stores error notification strings
	    	$notifications = [];  // stores general notification strings
            
            if(empty($_POST['current_password'])){
            	$errors[] = "current_password_empty";
            }

            if(empty($_POST['new_password'])){
            	$errors[] = "new_password_empty";
            }
            
            if(empty($errors)){
            	$current_password = $_POST['current_password'];
            	$username = $user->getUsername();

            	// check if the user login credentials are valid      
		    	if(!User::isValidLogin($username,$current_password)){
		    		$errors[] = "invalid_password";
		    	}
		    }


	    	if(empty($errors)){
	    		// everything is ok so we proceed to update password

	    	    $newPassword = sanitizeInput($_POST['new_password']);

	    	    $user->setPassword(password_hash($newPassword,PASSWORD_DEFAULT));

	    	    // commit changes 
	    	    $user->save();

	    	    // add a notification of successful change
	    	    $notifications[] = 'password_updated';

                // logout user and redirect to login page
                logoutUser();

                logNotifications($notifications);

	    	    redirect('/thegong/auth/login');
	    	}
            else{
            	// we had problems with how the form was filled so we pass
            	// so we go back
            	logNotifications($errors);

            	redirect('/thegong/auth/update_password');
            }
		}
	}

    /**
     * Log out the user
     * 
     * @return void
     */
	public function logout()
	{
		logoutUser();
		redirect('/thegong/');
	}
}