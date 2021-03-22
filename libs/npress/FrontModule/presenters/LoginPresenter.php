<?php
/**
 * nPress - opensource cms
 *
 * @copyright  (c) 2012 Pavel Zbytovský (pavel@zby.cz)
 * @link       http://npress.zby.cz/
 * @package    nPress
 */

namespace FrontModule;

use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;

/** Login presenter
 */
class LoginPresenter extends BasePresenter
{
  public function actionDefault()
  {
    if ($this->user->isLoggedIn()) {
      $this->redirect(":Admin:Admin:");
    }
  }

  public function createComponentLoginForm()
  {
    $form = new Form();
    $form
      ->addText('username', 'Korisničko ime:')
      ->addRule(Form::FILLED, 'Molimo popunite korisničko ime.');
    $form
      ->addPassword('password', 'Šifra:')
      ->addRule(Form::FILLED, 'Molimo popunite šifru..');
    $form->addCheckbox('remember', 'Trajna prijava na ovom računaru');
    $form->addSubmit('login', 'Prijava');
    $form->onSuccess[] = callback($this, 'loginFormSubmitted');

    return $form;
  }
  public function loginFormSubmitted(Form $form)
  {
    try {
      $values = $form->values;
      //if ($values['remember']) {
      $this->user->setExpiration('+ 1 month', false); //also in config.neon#session
      //} else {
      //	$this->user->setExpiration(0, TRUE);
      //}
      //TODO expiration(0) breaks uploadify

      $this->user->login($values['username'], $values['password']);

      if (isset($values['backlink'])) {
        $this->application->restoreRequest($values['backlink']);
      }
      $this->redirect(":Admin:Admin:");
    } catch (AuthenticationException $e) {
      $form->addError($e->getMessage());
    }
  }

  /*/ -----------------------------------------   LOST PASS  ---------------------
	public function createComponentLostPassForm(){
		$usersModel = new UsersModel();

		$form = new AppForm;
		$form->addText('email', 'E-mail kontakt osobe:')
			->addRule(Form::FILLED, 'Molimo popunite e-mail kontakt osobe.')
			->addRule(array($usersModel, 'isExistingEmail'), 'Ovaj e-mail nemamo u bazi podataka. Ukoliko niste sigurni, molimo da nas kontaktirate..');

		$form->addSubmit('send', 'Pošalji');

		$form->onSuccess[] = callback($this, 'lostPassFormSubmitted');
		return $form;
	}

	public function lostPassFormSubmitted(AppForm $form){
		$auth = md5(uniqid(rand()));
		dibi::query('UPDATE [::users] SET auth_lost_pass = %s',$auth,' WHERE email= %s',$form['email']->value);

		//pošleme mail
		$template = $this->createTemplate();
		$template->registerFilter(new LatteFilter);
		$template->auth  = $auth;
		$template->setFile(APP_DIR.'/templates/Login/lostPass_email.phtml'); // -> newPass

		//posíláme
		$mail = new Mail;
		$mail->setEncoding(Mail::ENCODING_QUOTED_PRINTABLE);
		$mail->setFrom(Environment::getVariable('registerRobotEmail'), Environment::getVariable('serverName'));
		$mail->addTo($form['email']->getValue());
		$mail->setHtmlBody($template);

		if(!Environment::isProduction())
			$mail->setMailer(new MyMailer);

    try {
            $mail->send();
    } catch (InvalidStateException $e) {
            throw new IOException('Slanje e-maila nije uspjelo. Molimo pokušajte kasnije.');
    }

		$this->flashMessage('Molimo provjerite svoj e-mail i postupite prema instrukcijama.');
		$this->redirect('Login:form');
	}

	// -----------------------------------------   NEW PASS  ---------------------
	public function actionNewPass($auth){
		$data = dibi::fetch('SELECT  * FROM [::users] WHERE auth_lost_pass= %s',$auth);
		if(!$data){
			$this->flashMessage('Nažalost, link za reset šifre je pogrešan. Pokušajte da ga kopirate pravilno ili generišite novi.');
			$this->redirect('Login:lostPass');
		}

		$this->template->data = $data;
		$this['newPassForm']['auth']->setValue($auth);
	}

	public function createComponentNewPassForm(){
		$usersModel = new UsersModel();

		$form = new AppForm($this, 'newPassForm');
		$form->addHidden('auth');
		$form->addPassword('pass', 'Nova šifra')
			->addRule(Form::FILLED, 'Izaberite svoju šifru')
			->addRule(Form::MIN_LENGTH, 'Šifra je prekratka, mora biti najmanje %d znakova', 5);
		$form->addPassword('pass2', 'Potvrda šifre')
			->addConditionOn($form['pass'], Form::VALID)
				->addRule(Form::FILLED, 'Unesite šifru još jednom kako bi potvrdili')
				->addRule(Form::EQUAL, 'Šifre nisu iste, unesite ponovo', $form['pass']);

		$form->addSubmit('send', 'Sačuvaj');

		$form->onSubmit[] = callback($this, 'newPassFormSubmitted');
		return $form;
	}

	public function newPassFormSubmitted(AppForm $form){
		$pass = sha1($form['pass']->value);
		dibi::query('UPDATE [::users] SET pass = %s',$pass,', auth_lost_pass=\'\' WHERE auth_lost_pass= %s',$form['auth']->value);

		$this->flashMessage('Nova šifra je podešena, možete se prijaviti.');
		$this->redirect('Login:form');

	}

*/
}
