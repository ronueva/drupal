<?php
/**
 * Created by PhpStorm.
 * User: Ron
 * Date: 28/04/2018
 * Time: 8:37 AM
 */

namespace Drupal\i_love_drupal\Controller;

use Drupal\Core\Controller\ControllerBase;

class ILoveDrupalController extends ControllerBase
{

    public function index()
    {

        $form['contactstable'] = \Drupal::formBuilder()->getForm('Drupal\i_love_drupal\Form\ContactsTableForm');

        return $form;
    }

    public function addContact()
    {
        $form['input']['backBtn'] = array(
            '#type' => 'submit',
            '#value' => t('Back'),
            '#name' => 'backBtn',
        );

        $form['addContactForm'] = \Drupal::formBuilder()->getForm('Drupal\i_love_drupal\Form\AddContactForm');

        $form['#attached']['library'][] = 'i_love_drupal/ilovedrupal';

        return $form;

    }

    public function editContact($id)
    {
        $form['input']['backBtn'] = array(
            '#type' => 'submit',
            '#value' => t('Back'),
            '#name' => 'backBtn',
        );

        $form['editContactForm'] = \Drupal::formBuilder()->getForm('Drupal\i_love_drupal\Form\EditContactForm',$id);

        $form['#attached']['library'][] = 'i_love_drupal/ilovedrupal';

        return $form;

    }

    public function deleteContact($id) {

        $form['deleteContactForm'] = \Drupal::formBuilder()->getForm('Drupal\i_love_drupal\Form\DeleteContactForm',$id);

        drupal_set_message(t('Are you sure you want to delete Contact #'.$id.' ?'),'error');

        return $form;


    }

}