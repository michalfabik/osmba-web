<?php

use Nette\Application\UI\Form;
use Nette\Utils\Json;

/**
 * nPress - opensource cms
 *
 * @copyright  (c) 2012 Pavel Zbytovský (pavel@zby.cz)
 * @link       http://npress.zby.cz/
 * @package    nPress
 */
class CommunityFormPlugin extends Form
{
  static $events = array();

  public $tags;
  public $projects;

  public function __construct()
  {
    parent::__construct();

    $this->tags = array();
    $tags = PagesModel::getPageById(27)->getMeta('tag_options');
    foreach (explode("\n", $tags) as $t) {
      $t = trim($t);
      $this->tags[$t] = $t;
    }

    $this->projects = PagesModel::getPagesByMeta("project_tag")->getPairs('');

    $this->addText('username', 'OSM.org username')->setDisabled();

    $this->addText('fullname', 'Ime i prezime')
      ->setOption('description', 'Da se možemo prepoznati.')
      ->addRule(Form::FILLED, '%label nije popunjeno.')
      ->addRule(Form::MIN_LENGTH, '%label mora imati najmanje 5 znakova.', 5);

    $this->addText('email', 'Talk-ba')
      ->setOption(
        'description',
        'Email koji se koristi za brojanje postova u talk-ba (nije za javnost)'
      )
      ->addRule(Form::FILLED, '%label nije popunjen.')
      ->addRule(Form::EMAIL, '%label nije validan.');

    $this->addText('contact', 'Javni e-mail')
      ->setOption('description', '(nije obavezno)')
      ->addCondition(Form::FILLED)
      ->addRule(Form::EMAIL, '%label nije validan.');

    $this->addText('twitter', 'Twitter')->setOption(
      'description',
      '(nije obavezno) Korisničko ime bez @'
    );
    $this->addText('github', 'Github')->setOption(
      'description',
      '(nije obavezno) Korisničko ime'
    );

    $this->addText('places', 'Mjesto')->setOption(
      'description',
      '(nije obavezno) Gdje me možete naći - u kom gradu/gradovima.'
    );
    $this['places']->getControlPrototype()->placeholder = 'odvojite zarezom';
    $this['places']->getControlPrototype()->style = 'width: 40%';

    $this->addText('tags', 'Područja interesa')->setOption(
      'description',
      '(nije obavezno)'
    );
    $this['tags']->getControlPrototype()->placeholder = 'ovojite zarezom';
    $this['tags']->getControlPrototype()->style = 'width: 60%';
    $this['tags']->getControlPrototype()->{'data-options'} = Json::encode(
      array_values($this->tags)
    );

    $this->addMultiSelect('projects', 'Projekti', $this->projects)
      ->setOption(
        'description',
        '(nije obavezno) Stranicu projekta je moguće dodati u administraciji, ili možete javiti na dev@openstreetmap.ba'
      )
      ->getControlPrototype()->style = 'height:150px;width:40%';

    $this->addCheckbox('public', 'Objaviti podatke na openstreetmap.ba');

    $this->addSubmit('submit', 'Sačuvati podatke');
    $this->onSuccess[] = callback($this, 'submitted');

    $renderer = $this->getRenderer();
    $renderer->wrappers['controls']['container'] =
      'table class="table form-inline"';
    $renderer->wrappers['error']['container'] = 'ul class="bg-danger"';
    $renderer->wrappers['control']['.text'] = 'form-control';
    $renderer->wrappers['control']['.email'] = 'form-control';
    $renderer->wrappers['control']['.submit'] = 'btn btn-primary';
  }

  protected function attached($presenter)
  {
    parent::attached($presenter);

    if (!$this->isSubmitted()) {
      $row = dibi::fetch(
        "SELECT * FROM users WHERE username = %s",
        $presenter->user->id
      );
      $row['tags'] = $row['tags'];
      $row['projects'] = array_map(function ($a) {
        return trim($a, '()');
      }, explode(",", $row['projects']));
      if ($row) {
        $this->setValues($row);
      }
    }
  }

  public function submitted()
  {
    if ($this->presenter->user->id) {
      $values = $this->values;
      $values['projects'] = join(
        ",",
        array_map(function ($a) {
          return "($a)";
        }, $values['projects'])
      );
      dibi::query(
        "UPDATE users SET",
        $values,
        "WHERE username = %s",
        $this->presenter->user->id
      ); //our id is username
      $this->presenter->flashMessage(
        'Profil upraven pro ' . $this->presenter->user->id
      );
    }

    $this->presenter->redirect('this');
  }
}
