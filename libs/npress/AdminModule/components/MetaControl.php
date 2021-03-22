<?php

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\InvalidStateException;

/**
 * nPress - opensource cms
 *
 * @copyright  (c) 2012 Pavel Zbytovský (pavel@zby.cz)
 * @link       http://npress.zby.cz/
 * @package    nPress
 */

class MetaControl extends Control
{
  private $page;

  public function __construct()
  {
    parent::__construct();
    $this->monitor('Nette\Application\UI\Presenter');
  }

  protected function attached($presenter)
  {
    parent::attached($presenter);
    if (!($presenter instanceof /*Nette\Application\UI\*/ Presenter)) {
      return;
    }

    //připojen presenter
    if (!isset($presenter->page)) {
      throw new InvalidStateException(
        'MetaControl attached to uncompatible Presenter'
      );
    }
    $this->page = $presenter->page;
  }

  public function render()
  {
    $template = $this->getTemplate();
    $template->setFile(dirname(__FILE__) . '/MetaControl.latte');
    $template->page = $this->page;
    $template->render();
  }

  //handle delete by key
  public function handleDelete($key)
  {
    $val = $this->page->meta[$key];
    $this->page->deleteMeta($key);

    $this->presenter->flashMessage("Postavka je izbrisana");
    //$this->invalidateControl('editpage_metalist');

    $this->invalidateControl();
    if (!$this->presenter->isAjax()) {
      $this->redirect('this#toc-meta');
    }
  }

  public function handleSaveEditForm()
  {
    $post = $this->parent->request->post;
    $this->page->addMeta($post['key'], $post['value']);
    $this->presenter->flashMessage('Postavka je promijenjena');

    $this->invalidateControl();
    if (!$this->presenter->isAjax()) {
      $this->redirect('this#toc-meta');
    }
  }

  //metaAdd
  protected function createComponentMetaAddForm()
  {
    $form = new Form();
    $form->getElementPrototype()->class('ajax');

    $form->addText('key', 'ključ')->getControlPrototype()->style = 'width:90px';
    $form->addText('value', 'vrijednost');
    $form->addSubmit('submit1', 'Dodaj');
    $form->onSuccess[] = callback($this, 'metaAddFormSubmitted');

    return $form;
  }
  public function metaAddFormSubmitted(Form $form)
  {
    $this->page->addMeta($form->values['key'], $form->values['value']);

    $this->presenter->flashMessage('Postavka je dodata');
    //$this->invalidateControl('editpage_metalist');
    $form->setValues(array(), true);

    $this->invalidateControl();
    if (!$this->presenter->isAjax()) {
      $this->redirect('this#toc-meta');
    }
  }
}
