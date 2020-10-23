<?php

namespace FrontModule;


use dibi;
use Nette\Application\UI\Form;

/**
 * For workshop attendants
 */
class LivePresenter extends BasePresenter
{
  private $liveuser;

  public function startup()
  {
    parent::startup();
    $this->liveuser = $this->getSession('liveuser');

    if ($this->liveuser->data) {
      barDump($this->liveuser);
      dibi::query(
        'UPDATE live_users SET online=NOW() WHERE user_id=%i',
        $this->liveuser->id
      );
    }
  }

  public function actionDefault()
  {
    if (!$this->liveuser->id) {
      $this->redirect("login");
    }

    $this['userForm']->setDefaults(
      dibi::fetch(
        'SELECT * FROM live_users WHERE user_id=%i',
        $this->liveuser->id
      )
    );
  }

  public function handleRefresh()
  {
    $this->invalidateControl('userlist');
    $this->invalidateControl('posts');
  }

  public function createComponentPostForm()
  {
    $form = new Form();
    $form->elementPrototype->class('ajax');
    $text = $form->addTextarea('text', 'Poruka:')->controlPrototype;
    $form->addSubmit('send', 'Pošalji');
    $form->onSuccess[] = callback($this, 'postFormSubmitted');
    $form->renderer->wrappers["error"]["container"] = null;
    $form->renderer->wrappers["error"]["item"] =
      "div class='alert alert-error'";

    $text->title = "Link se završava razmakom";
    $text->style['width'] = "690px";
    $text->style['height'] = "30px";
    return $form;
  }

  public function postFormSubmitted(Form $form)
  {
    dibi::query("INSERT INTO live_posts", array(
      'name' => $this->liveuser->data->name,
      'text' => $form['text']->value
    ));
    $this->invalidateControl('posts');
    $this->invalidateControl('postform');
    $this['postForm-text']->value = "";
    if (!$this->isAjax()) {
      $this->redirect('default');
    }
  }

  public function createComponentUserForm()
  {
    $form = new Form();
    $form->addText('osm', 'OSM nalog:');
    $form
      ->addText('mail', 'E-mail:') //->setRequired()
      //->addRule(Form::EMAIL)
      ->setOption('description', '(nije za javnost)');
    $form->addText('gc', 'GC nadimak:');
    $form->addText('fullname', 'Poruka ostalima:');
    $form
      ->addText('poznamka', 'Feedback:')
      ->setOption('description', '(nije za javnost)');

    $form->addSubmit('login', 'Spasi');
    $form->onSuccess[] = callback($this, 'userFormSubmitted');

    $form->renderer->wrappers["error"]["container"] = null;
    $form->renderer->wrappers["error"]["item"] =
      "div class='alert alert-error'";
    return $form;
  }

  public function userFormSubmitted(Form $form)
  {
    dibi::update('live_users', $form->values)
      ->where('user_id=%i', $this->liveuser->id)
      ->execute();
    $this->flashMessage("Spašeno");
    $this->redirect("default");
  }

  public function actionLogout()
  {
    $this->liveuser->data = false;
    $this->flashMessage("Uspješno ste odjavljeni");
    $this->redirect("login");
  }

  public function createComponentLoginForm()
  {
    $form = new Form();
    $form
      ->addText('nick', 'Nadimak: ')
      ->addRule(Form::FILLED, 'Molimo da popunite korisničko ime.');
    $form->addSubmit('login', 'Prijavi se')->getControlPrototype()->class =
      'btn btn-primary margin-top-10 space-2em';
    $form->onSuccess[] = callback($this, 'loginFormSubmitted');

    $form->renderer->wrappers["error"]["container"] = null;
    $form->renderer->wrappers["error"]["item"] =
      "div class='alert alert-error'";
    return $form;
  }
  public function loginFormSubmitted($form)
  {
    $nick = $form['nick']->value;
    $user = dibi::fetch(
      'SELECT * FROM live_users_online WHERE name = %s',
      $nick
    );

    if (!$user) {
      dibi::query('INSERT INTO live_users', array(
        'name' => $nick,
        'registred%sql' => 'CURDATE()'
      ));
      $user = dibi::fetch('SELECT * FROM live_users WHERE name = %s', $nick);
    } elseif ($user->isonline) {
      $form->addError(
        'Ovo korisničko ime je već zauzeto, izaberite drugo. Ako ste greškom zatvorili prozor, sačekajte 30 sekundi.'
      );
      return;
    }

    // $this->user->login(new \Nette\Security\Identity($user->user_id, 'user', $user));
    $this->liveuser->id = $user['user_id'];
    $this->liveuser->data = $user;
    $this->redirect('default');
  }

  /**
   * Reload stats for current workshop day or all
   *
   * @param bool $all
   */
  public function actionOsmedit($all = false)
  {
    @set_time_limit(3000);

    $time = microtime(true);
    $query = dibi::select('*')
      ->from('live_users')
      ->where('osm != ""');
    if (!$all) {
      $query->where('registred = CURDATE() OR DATE(online) = CURDATE()');
    }
    foreach ($query as $r) {
      echo "$r->osm, ";
      $o = rawurlencode($r->osm);
      if (!$o) {
        continue;
      }

      $ww = @file_get_contents("http://www.openstreetmap.org/user/$o");

      if (!$ww) {
        dibi::query(
          "UPDATE live_users SET osmedit = -404 WHERE user_id=%i",
          $r->user_id
        );
      } elseif (
        preg_match_all(
          '~/history.*<span class=\'count-number\'>([,0-9]+)</span>~isU',
          $ww,
          $m
        )
      ) {
        dibi::query(
          "UPDATE live_users SET osmedit = %s",
          str_replace(",", "", $m[1][0]),
          " WHERE user_id = %i",
          $r->user_id
        );
      }
    }
    echo microtime(true) - $time . " sec";

    echo "<table border=1 style=border-collapse:collapse>";
    foreach (
      dibi::query(
        'SELECT * FROM live_users_online ORDER BY registred DESC, name'
      )
      as $r
    ) {
      echo "<tr><td>";
      echo implode('<td>', (array) $r);
    }
    echo "</table>";

    echo "<script>setTimeout(function(){window.location.reload()}, 60*1000)</script>";
    $this->terminate();
  }
}
