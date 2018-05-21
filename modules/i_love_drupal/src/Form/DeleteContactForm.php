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
class DeleteContactForm extends FormBase
{
    private $id = null;

    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'delete_contact_form';
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
            '#attributes' => array(
                'disabled' => 'true'
            ),
            '#required' => true,
            '#value' => $results[0]->first_name,
        );
        $form['input']['lastName'] = array(
            '#type' => 'textfield',
            '#title' => t('Last Name'),
            '#attributes' => array(
                'disabled' => 'true'
            ),
            '#required' => true,
            '#value' => $results[0]->last_name,
        );
        $form['input']['company'] = array(
            '#type' => 'textfield',
            '#title' => t('Company'),
            '#attributes' => array(
                'disabled' => 'true'
            ),
            '#required' => true,
            '#value' => $results[0]->company,
        );
        $form['input']['mobileNumber'] = array(
            '#type' => 'textfield',
            '#title' => t('Mobile Number'),
            '#attributes' => array(
                'disabled' => 'true'
            ),
            '#required' => true,
            '#value' => $results[0]->mobile_number,
            '#maxlength' => 13
        );
        $form['input']['email'] = array(
            '#type' => 'textfield',
            '#attributes' => array(
                'disabled' => 'true'
            ),
            '#title' => t('Email'),
            '#required' => true,
            '#value' => $results[0]->email_address,
        );

        $form['input']['deleteContactBtn'] = array(
            '#type' => 'submit',
            '#value' => t('Delete'),
            '#name' => 'deleteContactBtn',
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

        $con = Database::getConnection();

        $query = $con->delete('contacts')
            ->condition('id', $this->id, '=');

        $query->execute();

        $url = \Drupal\Core\Url::fromRoute('i_love_drupal');

        $form_state->setRedirectUrl($url);


    }

}