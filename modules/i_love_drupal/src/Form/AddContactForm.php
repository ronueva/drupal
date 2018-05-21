<?php

/**
 * Created by PhpStorm.
 * User: Ron
 * Date: 28/04/2018
 * Time: 8:40M
 */

namespace Drupal\i_love_drupal\Form;


use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use MongoDB\Driver\Exception\Exception;

/**
 * Implements an example form.
 */
class AddContactForm extends FormBase
{
    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'add_contact_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state, $arg = NULL)
    {

        $form['input']['firstName'] = array(
            '#type' => 'textfield',
            '#title' => t('First Name'),
            '#name' => 'firstName',
            '#required' => true,
        );
        $form['input']['lastName'] = array(
            '#type' => 'textfield',
            '#title' => t('Last Name'),
            '#name' => 'lastName',
            '#required' => true,
        );
        $form['input']['company'] = array(
            '#type' => 'textfield',
            '#title' => t('Company'),
            '#name' => 'company',
            '#required' => true,
        );
        $form['input']['mobileNumber'] = array(
            '#type' => 'textfield',
            '#title' => t('Mobile Number'),
            '#required' => true,
            '#name' => 'mobileNumber',
            '#maxlength' => 13
        );
        $form['input']['email'] = array(
            '#type' => 'textfield',
            '#title' => t('Email'),
            '#required' => true,
            '#name' => 'email',
        );

        $form['input']['addNewContactBtn'] = array(
            '#type' => 'submit',
            '#value' => t('Save'),
            '#name' => 'addNewContactBtn',
        );

        $form['input']['resetBtn'] = array(
            '#type' => 'button',
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

        if ($form_state->getTriggeringElement()['#name'] == 'addNewContactBtn') {

            parent::validateForm($form, $form_state);

            $values = $form_state->getValues();

            $con = Database::getConnection();

            $validateEmail = $con->query("SELECT 
                        COUNT(*) as 'count'
                        FROM
                          contacts
                        WHERE 
                        email_address = '" . $form_state->getValue('email') . "'");

            if (preg_match("/[a-z]/i", $values['mobileNumber'])) {

                $form_state->setErrorByName('mobileNumber', $this->t('Please input numbers only on Mobile Number field.'));

            } else if ($validateEmail->fetchAll()['0']->count > 0) {

                $form_state->setErrorByName('email', $this->t('Email <b>' . $form_state->getUserInput()['email'] . '</b> already exists.'));

            }
        }

    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {

        switch ($form_state->getTriggeringElement()['#name']) {
            case 'addNewContactBtn':
                $con = Database::getConnection();

                $query = $con->insert('contacts')
                    ->fields(array(
                        'first_name' => $form_state->getValue('firstName'),
                        'last_name' => $form_state->getValue('lastName'),
                        'company' => $form_state->getValue('company'),
                        'mobile_number' => $this->convertMobileNumber($form_state->getValue('mobileNumber')),
                        'email_address' => $form_state->getValue('email'),
                    ));

                $query->execute();

                drupal_set_message($this->t('Contact Saved!'));

                break;

            case 'resetBtn':

                break;
        }

        return true;


    }

    private function convertMobileNumber($mobileNumber)
    {

        if (strlen($mobileNumber) == 11) {

            return substr_replace(substr_replace($mobileNumber, '-', 4, 0), '-', 8, 0);

        } else if (strlen($mobileNumber) == 13) {

            return substr_replace(substr_replace($mobileNumber, '-', 6, 0), '-', 10, 0);

        }

        return $mobileNumber;

    }

}