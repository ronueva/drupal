<?php

/**
 * Created by PhpStorm.
 * User: Ron
 * Date: 28/04/2018
 * Time: 8:40M
 */

namespace Drupal\i_love_drupal\Form;

use MongoDB\Driver\Exception\Exception;
use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Implements an example form.
 */
class EditContactForm extends FormBase
{
    private $id = null;

    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'edit_contact_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state, $arg = NULL)
    {
        $this->id = $arg;

        $query = \Drupal::database()->select('contacts', 'c');
        $query->fields('c', ['id', 'first_name', 'last_name', 'company', 'mobile_number', 'email_address']);
        $query->condition('id', $this->id, '=');

        $results = $query->execute()->fetchAll();

        $form['input']['firstName'] = array(
            '#type' => 'textfield',
            '#title' => t('First Name'),
            '#required' => true,
            '#value' => $results[0]->first_name,
        );
        $form['input']['lastName'] = array(
            '#type' => 'textfield',
            '#title' => t('Last Name'),
            '#required' => true,
            '#value' => $results[0]->last_name,
        );
        $form['input']['company'] = array(
            '#type' => 'textfield',
            '#title' => t('Company'),
            '#required' => true,
            '#value' => $results[0]->company,
        );
        $form['input']['mobileNumber'] = array(
            '#type' => 'textfield',
            '#title' => t('Mobile Number'),
            '#required' => true,
            '#value' => $results[0]->mobile_number,
            '#maxlength' => 13
        );
        $form['input']['email'] = array(
            '#type' => 'textfield',
            '#title' => t('Email'),
            '#required' => true,
            '#value' => $results[0]->email_address,
        );

        $form['input']['editContactBtn'] = array(
            '#type' => 'submit',
            '#value' => t('Save'),
            '#name' => 'editContactBtn',
        );

        $form['input']['resetBtn'] = array(
            '#type' => 'submit',
            '#value' => t('Reset'),
            '#name' => 'resetBtn',
        );


        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        parent::validateForm($form, $form_state);

        $values = $form_state->getValues();

        $con = Database::getConnection();

        $validateEmail = $con->query("SELECT 
                        COUNT(*) as 'count'
                        FROM
                          contacts
                        WHERE 
                        if( '" . $form_state->getUserInput()['email'] . "'='" . $form_state->getValue('email') . "' ,1=0,
                        email_address = '" . $form_state->getUserInput()['email'] . "')");

        if (preg_match("/[a-z]/i", $values['mobileNumber'])) {

            $form_state->setErrorByName('mobileNumber', $this->t('Please input numbers only on Mobile Number field.'));

        } else if ($validateEmail->fetchAll()['0']->count > 0) {

            $form_state->setErrorByName('email', $this->t('Email <b>' . $form_state->getUserInput()['email'] . '</b> already exists.'));


        }
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        switch ($form_state->getTriggeringElement()['#name']) {
            case 'editContactBtn':

                $con = Database::getConnection();

                $query = $con->update('contacts')
                    ->fields(array(
                        'first_name' => $form_state->getUserInput()['firstName'],
                        'last_name' => $form_state->getUserInput()['lastName'],
                        'company' => $form_state->getUserInput()['company'],
                        'mobile_number' => $form_state->getUserInput()['mobileNumber'],
                        'email_address' => $form_state->getUserInput()['email'],
                    ))
                    ->condition('id', $this->id, '=');

                $query->execute();

                drupal_set_message($this->t('Contact Edited!'));

                break;
            case 'resetBtn':

                break;
        }


        return true;


    }

}