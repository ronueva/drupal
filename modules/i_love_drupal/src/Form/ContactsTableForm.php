<?php

/**
 * Created by PhpStorm.
 * User: Ron
 * Date: 28/04/2018
 * Time: 8:40M
 */

namespace Drupal\i_love_drupal\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\i_love_drupal\Controller\ILoveDrupalController;

/**
 * Implements an example form.
 */
class ContactsTableForm extends FormBase
{

    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'contacts_table_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {

        $header_table = array(
            'id' => t('ID'),
            'first_name' => t('First Name'),
            'last_name' => t('Last Name'),
            'company' => t('Company'),
            'mobile_number' => t('Mobile Number'),
            'email_address' => t('Email'),
        );

        $query = \Drupal::database()->select('contacts', 'c');
        $query->fields('c', ['id', 'first_name', 'last_name', 'company', 'mobile_number', 'email_address']);

        $results = $query->execute()->fetchAll();

        $rows = array();

        foreach ($results as $result) {
            $rows[$result->id] = array(
                'id' => $result->id,
                'first_name' => $result->first_name,
                'last_name' => $result->last_name,
                'company' => $result->company,
                'mobile_number' => $result->mobile_number,
                'email_address' => $result->email_address,
            );
        }

        $form['actions']['addNewContactBtn'] = array(
            '#type' => 'submit',
            '#value' => t('Add Contact'),
            '#name' => 'addNewContactBtn',
        );

        if (sizeof($rows) > 0) {

            $form['actions']['editContactBtn'] = array(
                '#type' => 'submit',
                '#value' => t('Edit Contact'),
                '#name' => 'editContactBtn',
            );

            $form['actions']['deleteContactBtn'] = array(
                '#type' => 'submit',
                '#value' => t('Delete Contact'),
                '#attributes' => array(
                    'style' => 'float:right'
                ),
                '#name' => 'deleteContactBtn',
            );

        }



        $form['contactsTable'] = array(
            '#type' => 'tableselect',
            '#header' => $header_table,
            '#options' => $rows,
            '#empty' => t('No Contacts Yet'),
            '#multiple' => FALSE,
            '#js_select' => TRUE
        );


        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {

        switch ($form_state->getTriggeringElement()['#name']) {
            case 'addNewContactBtn':

                $url = \Drupal\Core\Url::fromRoute('i_love_drupal.addContactForm');

                $form_state->setRedirectUrl($url);

                break;
            case 'editContactBtn':

                $id = $form_state->getValue('contactsTable');

                if ($id != "") {
                    $url = \Drupal\Core\Url::fromRoute('i_love_drupal.editContactForm')
                        ->setRouteParameters(array('id' => $id));

                    $form_state->setRedirectUrl($url);
                }

                break;
            case 'deleteContactBtn':

                $id = $form_state->getValue('contactsTable');

                if ($id != "") {
                    $url = \Drupal\Core\Url::fromRoute('i_love_drupal.deleteContactForm')
                        ->setRouteParameters(array('id' => $id));

                    $form_state->setRedirectUrl($url);
                }

                break;

        }


    }

}