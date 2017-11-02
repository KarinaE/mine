<?php
// не существует прямого доступа к фалу
defined('_ACCESS') or die;

class controllers_accountController extends controllers_BaseController
{
    protected $model;
    protected $postData;
    protected $getData;
    protected $data;
    protected $contactData;
    protected $salt;
    protected $tpl = array(
        'registerAction'        => 'register.phtml',
        'confirmAction'         => 'message.phtml',
        'regLinkRefreshAction'  => 'message.phtml',
        'socialButtons'         => 'index.phtml',
        'accountAction'         => 'account.phtml',
        'forgot_passwordAction' => 'forgotpass.phtml',
        'passwordChangeAction'  => 'passwordChange.phtml',
        'pass_resetAction'      => 'passreset.phtml',
        'deleteAction'          => 'message.phtml',
    );

    public function __construct()
    {
        parent::__construct();
        //initializing model
        $this->viewer->model = $this->model = new models_accountModel();
        $this->datafilter();
        //getting action name to set template
        $action= Request::instance()->getActionName();
        $this->viewer->setTemplate($this->control_name.'/'.$this->tpl[$action]);
        $this->salt = '10F5S3cB';
    }

    //checking incoming data against security risks
    private function datafilter()
    {
        $this->viewer->postData = $this->postData = filterHelper::checkData($_POST);
        $this->viewer->getData = $this->getData = filterHelper::checkData($_GET);
    }

    public function indexAction()
    {
        parent::indexAction();
        $this->socialButtons();
    }

    public function registerAction()
    {   //getting external links to be able to log in with soc.acc.
        $this->socialButtons();

        if(!empty($this->postData))
        {
            if ($this->postData['password'] == $this->postData['password_check'])
            {
                //checking if user enters email that has not been submitted yet
                if ($this->model->checkUser($this->postData['email']))
                    $this->viewer->Msg = $this->viewer->moduleLanguage['email_exist'];
                else {
                    //getting user data
                    $this->postData['password'] = crypt($this->postData['password'], $this->salt);
                    $this->postData['birth_date'] = $this->postData['b_year'] . '-' . $this->postData['b_month'] . '-' . $this->postData['b_date'];
                    unset ($this->postData['b_year'], $this->postData['b_month'], $this->postData['b_date']);
                    //meaning adding user with link to confirm his email against in his activation status
                    $new_user = $this->model->register($this->postData);
                    if ($new_user)
                    {
                        $this->viewer->Msg_sheet = $this->viewer->moduleLanguage['registration_success'];
                        $this->viewer->setTemplate($this->control_name.'/message.phtml');

                    }
                    //preparing data to send email for registration confirmation link
                    $sendDetails = array(
                        'email'      => $this->postData['email'],
                        'actionName' => 'confirm',
                        'hash'       => md5($this->postData['email'] . time()),
                        'file'       => file_get_contents(CORE_ROOT . 'content/views/account/regReplyLetter'.MAIN_LANG.'.html'),
                        'name'       => $this->postData['first_name'],
                        'subject'    => $this->viewer->moduleLanguage['reg_email_subject']
                    );
                    $this->sendMail($sendDetails);
                }
            } else
                $this->viewer->Msg = $this->viewer->moduleLanguage['pass_nomatch']; //passwords entered don't match
        }
    }

    public function regLinkRefreshAction()
    {
        //getting user data with activation link
        $user = $this->model->regLinkRefresh($this->getData['link']);
        //preparing data to REsend email for registration confirmation link
        $sendDetails = array(
            'email'      => $user[0]['email'],
            'actionName' => 'confirm',
            'hash'       => $this->getData['link'],
            'file'       => file_get_contents(CORE_ROOT . 'content/views/account/regReplyLetter'.MAIN_LANG.'.html'),
            'name'       => $user[0]['first_name'],
            'subject'    => $this->viewer->moduleLanguage['reg_email_subject']
        );
        $this->sendMail($sendDetails);
        $this->viewer->Msg_sheet = $this->viewer->moduleLanguage['confirm_registration'];
    }

    private function sendMail ($sendDetails)
    {
        $email = $sendDetails['email'];
        //creating confirmation link
        $link = "http://" . $_SERVER['HTTP_HOST'] . "/account/" . $sendDetails['actionName'] . "/?mailconfirmation=" . $sendDetails['hash'] . "&at=" . time();
        //getting values instead template tags
        $body = str_replace('-name-', $sendDetails['name'], $sendDetails['file']);
        $body = str_replace('-link-', $link, $body);
        // initializing lib
        require_once(CORE_ROOT.'lib/phpmailer/class.phpmailer.php');
        $mail = new PHPMailer();
        $mail->IsSendmail();
        $mail->CharSet="UTF-8";
        //template for mail
        $mail->MsgHTML($body);
        $mail->SetFrom("support@dotintag.com");
        $mail->Subject= $sendDetails['subject'];
        $mail->AddAddress("$email");
        $mail->Send();
    }

    public function confirmAction()
    {
        $link = filterHelper::checkData($this->getData['mailconfirmation']);
        //checking if the link was sent within 48 hours
        $activation_time_check=$this->model->atCheck($link);
        //changing user activation status to 1 using link in email
        if (!empty($activation_time_check) and time()-$activation_time_check[0]['at']<=172800)
            $user = $this->model->emailconf ($link);
        if (isset($user))
        {
            Session::instance()->set('name',$user[0]['first_name']);
            Session::instance()->set('id',$user[0]['id']);
            $this->viewer->Msg_sheet = $this->viewer->moduleLanguage['confirmation_complete'];

        } else
            $this->viewer->Msg_sheet = $this->viewer->moduleLanguage['wrong_link'];
    }

    public function signinAction()
    {
        $email = $this->postData['email'];
        $password = crypt($this->postData['password'], $this->salt);
        //checking if user exists
        $user_data = $this->model->signIn($email);

        if ($user_data and models_helpers_Access::hash_equals($user_data[0]['password'],$password))
        {
            //if user has not confirmed his registration via email, account will not appear
            if ($user_data[0]['activation'] !== '1')
            {
                //getting link to resend to user if he needs it
                $this->viewer->linkRefresh = $user_data[0]['activation'];
                //asking to confirm regisrtation with link in email
                $this->viewer->Msg_sheet = $this->viewer->moduleLanguage['confirm_registration'];
                $this->viewer->setTemplate($this->control_name.'/message.phtml');
            } else {
                //if user confirmed reg  - redirecting to his acc
                Session::instance()->set('name', $user_data[0]['first_name']);
                Session::instance()->set('id', $user_data[0]['id']);
                Viewer::instance()->redirect('/account/account');
            }
        } else {
            $this->viewer->setTemplate($this->control_name.'/index.phtml');
            //user typed in wrong data
            $this->viewer->Msg = $this->viewer->moduleLanguage['incorrect_log_pass'];
        }
    }

    public function signinFacebookAction()
    {   //getting user's data from soc acc
        $user= socialHelper::instance()->facebook();
        //checking if user exists
        $this->socialCheck($user);
    }

    public function signinGoogleAction()
    {   //getting user's data from soc acc
        $user= socialHelper::instance()->google();
        //checking if user exists
        $this->socialCheck($user);
    }

    public function accountAction()
    {
        if (isset($_SESSION['default']['name']))
        {
            $id = Session::instance()->get('id');
            //getting user info
            $this->viewer->data = $this->model->userData($id);
            $this->viewer->contactData = $this->model->userContactData($id);
            $this->viewer->orderData = $this->model->userOrderData($id);
            //checking if user has set password (there is no pass when entered by soc_id)
            $passCheck = $this->model->passCheck($id);
            $this->viewer->pass = $passCheck[0]['password'];
        } else {
            $this->viewer->setTemplate($this->control_name.'/index.phtml');
            $this->socialButtons();
        }
    }

    private function socialButtons()
    {
        //forming external links to log in with soc.acc.
        $this->viewer->link_fb = socialHelper::instance()->facebooklink();
        $this->viewer->link_google = socialHelper::instance()->googlelink();
    }

    private function socialCheck($raw_user)
    {
        //$raw_user format differs depending on social network (object or array)
        $user=(array)$raw_user;
        //checking if user exists in db with given social id
        $user_exist_soc = $this->model->socialCheck($user['id']);
        if ($user_exist_soc)
        {
            //getting user data to set name and id in session
            //contactdata and order data will be taken from account() having SESSION['name']
            $this->viewer->data = $this->model->userData($user_exist_soc[0]['id_client']);
            Session::instance()->set('name',  $this->viewer->data[0]['first_name']);
            Session::instance()->set('id',  $this->viewer->data[0]['id']);
        } else {
            ////email is not always in fb profile
            if (isset($user['email']))
            {
                //checking if user with same email already exists in db
                $user_exists = $this->model->signIn($user['email']);
                if ($user_exists)
                    $user['id_client'] = $user_exists[0]['id'];
            }
            //sending new user data to add to db depending on soc network
            if ($user['network']=='facebook')
                $this->facebookUser($user);
            elseif ($user['network']=='google')
                $this->googleUser($user);
        }
    }

    private function facebookUser($user)
    {
        $gender = ($user['gender']=='m') ? 1 : 2;
        $facebook_data = array(
            'first_name' => $user['first_name'],
            'last_name'  => $user['last_name'],
            'gender'     => $gender,
            'network'    => $user['network'],
            'avatar'     => $user['picture']['url'],
            'soc_id'     => $user['id']
        );
        //email is not always in fb profile
        if (isset($user['email']))
            $facebook_data['email'] = $user['email'];
        //id_client comes form social check(if user with submitted email already exists in db) - if he heas it we update his data adding soc_id
        if (isset($user['id_client']))
        {
            $facebook_data['id_client'] = $user['id_client'];
            //if user has not confirmed his registration via email before - activation status will be changed automatically to 1
            if ($this->model-> socialDataToUser($facebook_data))
                $id = $user['id_client'];
        } else
            $id = $this->model->addSocialUser($facebook_data); //if user with submitted email does not exist - we add whole profile

        //getting user data to set name and id in session
        //contactdata and order data will be taken from account() having SESSION['name']
        $user_data = $this->model->userData($id);

        Session::instance()->set('name', $user_data[0]['first_name']);
        Session::instance()->set('id', $user_data[0]['id']);
        header('Location:' . HEADPATH_ROOT . 'account/account');
    }

    private function googleUser($user)
    {
        $google_data = array(
            'first_name' => $user['givenName'],
            'last_name'  => $user['familyName'],
            'gender'     => $user['gender'],
            'email'      => $user['email'],
            'avatar'     => $user['picture'],
            'network'    => $user['network'],
            'soc_id'     => $user['id']
        );
        //id_client comes form social check(if user with submitted email already exists in db) - if he heas it we update his data adding soc_id
        if (isset($user['id_client']))
        {
            $google_data['id_client'] = $user['id_client'];
            //if user has not confirmed his registration via email before - activation status will be changed automatically to 1
            if ($this->model->socialDataToUser($google_data))
                $id = $user['id_client'];
        } else
            $id = $this->model->addSocialUser($google_data); //if user with submitted email does not exist - we add whole profile

        //getting user data to set name and id in session
        //contactdata and order data will be taken from account() having SESSION['name']
        $user_data = $this->model->userData($id);
        Session::instance()->set('name', $user_data[0]['first_name']);
        Session::instance()->set('id', $user_data[0]['id']);
        header('Location:' . HEADPATH_ROOT . 'account/account');
    }

    public function passwordChangeAction()
    {
        $id= Session::instance()->get('id');
        //getting user's pass (if not empty; can be empty when entered with soc. acc.)
        $passCheck = $this->model->passCheck($id);
        $this->pass = $passCheck[0]['password'];
        if ($this->postData)
        {
            if (!empty ($this->pass))
            {
                // if user has password we have to check it against what he entered
                $password = crypt($this->postData['existing_password'], $this->salt);

                if (models_helpers_Access::hash_equals($this->pass, $password)) //if pasword entered matches one in db
                {
                    if ($this->postData['new_password']==$this->postData['password_check']) //if new pass entered and confirmed correctly
                    {
                        $new_password= crypt($this->postData['new_password'],$this->salt);
                        if ($this->model-> passwordChange($new_password,$id)) //changing pass
                        {
                            $this->viewer->Msg_sheet = $this->viewer->moduleLanguage['pass_changed'];
                            $this->viewer->setTemplate($this->control_name.'/message.phtml');
                        } else
                            $this->viewer->Msg = $this->viewer->moduleLanguage['p_error'];
                    } else
                        $this->viewer->Msg = $this->viewer->moduleLanguage['pass_nomatch'];
                } else
                    $this->viewer->Msg = $this->viewer->moduleLanguage['not_your_pass'];
            } else
                $this->setPassword();
        }
    }

    private function setPassword()
    {
        $id= Session::instance()->get('id');
        //getting user's pass (if not empty; can be empty when entered with soc. acc.)
        $passCheck = $this->model->passCheck($id);
        $this->pass = $passCheck[0]['password'];
        if ($this->postData['new_password']==$this->postData['password_check']) //if new pass entered and confirmed correctly
        {
            $new_password= crypt($this->postData['new_password'], $this->salt);
            if ($this->model-> passwordChange($new_password,$id)) //setting pass
            {
                $this->viewer->Msg_sheet=$this->viewer->moduleLanguage['pass_changed'];
                $this->viewer->setTemplate($this->control_name.'/message.phtml');
            } else
                $this->viewer->Msg=$this->viewer->moduleLanguage['p_error'];
        } else
            $this->viewer->Msg = $this->viewer->moduleLanguage['pass_nomatch'];
    }

    public function forgot_passwordAction()
    {
        if ($this->postData)
        {
            $email = $this->postData['email'];
            //checking if email user entered exists
            $user = $this->model->checkUser($email);
            if (!empty($user))
            {
                //creating new "activation' hash
                $hash = md5($this->postData['email'] . time());
                //resetting activation form 1 to hash
                $this->model->activationReset($email, $hash);
                //preparing data to send email to reset pass
                $sendDetails = array(
                    'email'      => $email,
                    'actionName' => 'pass_reset',
                    'hash'       => $hash,
                    'file'       => file_get_contents(CORE_ROOT . 'content/views/account/passReplyLetter'.MAIN_LANG.'.html'),
                    'name'       => $user['first_name'],
                    'subject'    => $this->viewer->moduleLanguage['passreset_email_subject']
                );
                $this->sendMail($sendDetails);
                $this->viewer->Msg_sheet = $this->viewer->moduleLanguage['reset_link_sent'];
                $this->viewer->setTemplate($this->control_name.'/message.phtml');
            } else
                $this->viewer->Msg = $this->viewer->moduleLanguage['unknown_email'];
        }
    }

    public function pass_resetAction ()
    {
        if ($this->postData)
        {
            $id= Session::instance()->get('id');
            $password = crypt($this->postData['new_password'], $this->salt);
            if (isset ($id))
                if ($this->model->passwordChange($password, $id))
                {
                    $this->viewer->Msg_sheet = $this->viewer->moduleLanguage['pass_changed'];
                    $this->viewer->setTemplate($this->control_name.'/message.phtml');
                }
        } else {
            $this->link=filterHelper::checkData($this->getData['mailconfirmation']);
            //checking if the link exists and was sent within 1 hour
            $activation_time_check = $this->model->atCheck($this->link);
            if (!empty($activation_time_check) and time() - $activation_time_check[0]['at'] <= 3600)
            {
                // changing user activation status to 1 using link in email
                $user = $this->model->emailconf($this->link);
                if ($user)
                    Session::instance()->set('id',$user[0]['id']);
            }else{
                $this->viewer->Msg = $this->viewer->moduleLanguage['wrong_link'];
                $this->viewer->setTemplate($this->control_name.'/message.phtml');
            }

        }
    }

    public function editInfoAction()
    {
        //getting all user's data to enable tab-panels out of ?action=editInfo
        $this->accountAction();
        $id= Session::instance()->get('id');
        if ($this->postData) //if user changed some info
        {
            $this->postData['birth_date'] = $this->postData['b_year'] . '-' . $this->postData['b_month'] . '-' . $this->postData['b_date'];
            unset ($this->postData['b_year'], $this->postData['b_month'], $this->postData['b_date']);
            $updateArr['id'] = $id;
            foreach ($this->postData as $key => $value)
                $updateArr[$key] = $value; //forming new data array
            $edited=$this->model-> editInfo($updateArr);
        }
        if (isset($edited))
            header('Location:' . HEADPATH_ROOT . 'account/account');
    }

    public function editContactAction()
    {
        //getting all user's data to enable tab-panels out of ?action=editContact
        $this->accountAction();
        if(!empty($this->postData))
        {
            $data = array(
                'id'         => Session::instance()->get('id'),
                'first_name' => Session::instance()->get('name'),
                'id_tel'     => $this->getData['prec'],
                'phone'      => $this->postData['phone'],
                'id_addr'    => $this->getData['arec'],
                'address'    => $this->postData['address'],
                'is_main'    => $this->getData['m']
            );
            if ($this->model->editContactData($data))
                header('Location:' . HEADPATH_ROOT . 'account/account');
        }
    }

    public function changeDefaultAction()
    {
        $data = array(
            'curr_main_address' => $this->getData['marec'],
            'new_def_address'   => $this->getData['arec'],
            'curr_main_tel'     => $this->getData['mprec'],
            'new_def_tel'       => $this->getData['prec'],

        );
        $this->model->changeDefaultAddr($data);
        header('Location:' . HEADPATH_ROOT . 'account/account');
    }

    public function addContactAction()
    {
        //getting all user's data to enable tab-panels out of ?action=addContact
        $this->accountAction();
        if(!empty($this->postData))
        {
            $this->postData['id'] = Session::instance()->get('id');
            $this->postData['first_name'] = Session::instance()->get('name');
            if ($this->model->addContactData($this->postData))
                header('Location:' . HEADPATH_ROOT . 'account/account');
        }
    }

    public function deleteContactAction()
    {
        $this->postData['id_addr'] =  $this->getData['arec'];
        $this->postData['id_tel'] = $this->getData['prec'];
        $this->viewer->data = $this->model->deleteContactData($this->postData);
        header('Location:' . HEADPATH_ROOT . 'account/account');

    }

    public function deleteAction()
    {
        if(!isset($this->postData['delete'])) //setting warning message that account and all data will be deleted
            $this->viewer->Msg_sheet = $this->viewer->moduleLanguage['warning_account_deleted'];
        else {
            $id = Session::instance()->get('id');
            $this->viewer->data = $this->model->deleteUser($id);
            Session::instance()->delete('id');
            Session::instance()->delete('name');
            $this->viewer->Msg_sheet = $this->viewer->moduleLanguage['account_deleted'];
        }
    }

    public function logoutAction()
    {
        Session::instance()->delete('id');
        Session::instance()->delete('name');
        header('Location:' . HEADPATH_ROOT . 'account/');
    }
}
