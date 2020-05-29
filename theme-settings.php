<?php

/**
 * @file
 * UMD Terp (umd_terp), add custom theme settings options here.
 */

use Drupal\Core\Form\FormStateInterface;

/**
 * Implements hook_form_system_theme_settings_alter().
 */
function umd_terp_form_system_theme_settings_alter(&$form, FormStateInterface $form_state) {

  // Header.
  $form['umd_terp_header_settings'] = [
    '#type' => 'details',
    '#title' => t('UMD Terp Header Settings'),
  ];
  $form['umd_terp_header_settings']['umd_terp_header_light'] = [
    '#type' => 'checkbox',
    '#title' => t('Light header style'),
    '#description' => t('Display light header version.'),
    '#default_value' => theme_get_setting('umd_terp_header_light'),
  ];

  // Footer.
  $form['umd_terp_footer_settings'] = [
    '#type' => 'details',
    '#title' => t('UMD Terp Footer Settings'),
  ];
  $form['umd_terp_footer_settings']['umd_terp_footer_logo_path'] = array(
    '#type' => 'textfield',
    '#title' => t('Path to custom footer logo'),
    '#default_value' => theme_get_setting('umd_terp_footer_logo_path'),
  );
  $form['umd_terp_footer_settings']['umd_terp_footer_logo_upload'] = array(
    '#type' => 'file',
    '#title' => t('Upload footer logo image'),
    '#maxlength' => 40,
    '#description' => t('Please upload the footer-specific logo. This should be a "dark" version of the logo that features black typography.'),
  );
  $form['umd_terp_footer_settings']['umd_terp_address'] = [
    '#type' => 'textfield',
    '#title' => t('Address'),
    '#default_value' => theme_get_setting('umd_terp_address'),
    '#description' => t('Please add the address you wish to display.'),
  ];
  $form['umd_terp_footer_settings']['umd_terp_phone'] = [
    '#type' => 'textfield',
    '#title' => t('Phone Number'),
    '#default_value' => theme_get_setting('umd_terp_phone'),
    '#description' => t('Please add the phone number you wish to display.'),
  ];
  $form['umd_terp_footer_settings']['umd_terp_email'] = [
    '#type' => 'textfield',
    '#title' => t('Email'),
    '#default_value' => theme_get_setting('umd_terp_email'),
    '#description' => t('Please add the email address you wish to display.'),
  ];

  // Social media.
  $form['umd_terp_social_settings'] = [
    '#type' => 'details',
    '#title' => t('UMD Terp Social Media Accounts'),
  ];
  $form['umd_terp_social_settings']['umd_terp_twitter_link'] = [
    '#type' => 'textfield',
    '#title' => t('Twitter Link'),
    '#default_value' => theme_get_setting('umd_terp_twitter_link'),
    '#description' => t('Add the URL to your twitter profile.'),
  ];
  $form['umd_terp_social_settings']['umd_terp_facebook_link'] = [
    '#type' => 'textfield',
    '#title' => t('Facebook Link'),
    '#default_value' => theme_get_setting('umd_terp_facebook_link'),
    '#description' => t('Add the URL to your facebook profile.'),
  ];
  $form['umd_terp_social_settings']['umd_terp_youtube_link'] = [
    '#type' => 'textfield',
    '#title' => t('Youtube Link'),
    '#default_value' => theme_get_setting('umd_terp_youtube_link'),
    '#description' => t('Add the URL to your youtube profile.'),
  ];
  $form['umd_terp_social_settings']['umd_terp_instagram_link'] = [
    '#type' => 'textfield',
    '#title' => t('Instagram Link'),
    '#default_value' => theme_get_setting('umd_terp_instagram_link'),
    '#description' => t('Add the URL to your instagram profile.'),
  ];

  // Directory.
  $form['directory'] = [
    '#type' => 'details',
    '#title' => t('Directory Settings'),
    '#collapsible' => TRUE,
  ];

  $help_markup = "<p>These settings are for if you wish to change the default directory URL from '/directory' to something else. Be sure you also change the corresponding pathauto pattern for People.</p>";
  $form['directory']['directory_help'] = [
    '#type' => 'markup',
    '#markup' => $help_markup,
  ];

  $form['directory']['umd_terp_directory_path'] = [
    '#type' => 'textfield',
    '#title' => t('Directory path'),
    '#description' => t('Provides a site wide {{ umd_terp_directory_path }} variable for profile templates. Ex: /directory. Defaults to "/directory".'),
    '#default_value' => theme_get_setting('umd_terp_directory_path'),
  ];

  // Other.
  $form['other'] = [
    '#type' => 'details',
    '#title' => t('UMD Terp Admin Settings'),
    '#collapsible' => TRUE,
  ];

  $form['other']['umd_terp_assets_path'] = [
    '#type' => 'textfield',
    '#title' => t('Assets path'),
    '#description' => t('Provides a site wide {{ assets_path }} variable for the builds assets path relative to the theme root. Usable in twig templates. Ex: /static/build'),
    '#default_value' => theme_get_setting('umd_terp_assets_path'),
  ];
  $form['#validate'][] = 'umd_terp_form_system_theme_settings_validate_test';
  $form['#submit'][] = 'umd_terp_form_system_theme_settings_submit';
}

function umd_terp_form_system_theme_settings_validate_test($form, FormStateInterface &$form_state) {
  // Handle file uploads.
  $validators = array(
    'file_validate_is_image' => array(),
  );
  // Check for a new uploaded logo.
  $file = file_save_upload('umd_terp_footer_logo_upload', $validators, FALSE, 0);
  if (isset($file)) {

    // File upload was attempted.
    if ($file) {
      // Put the temporary file in form_values so we can save it on submit.
      $form_state->setValue('umd_terp_footer_logo_upload', $file);
    }
    else {
      // File upload failed.
      $form_state->setErrorByName('umd_terp_footer_logo_upload', t('The footer logo could not be uploaded.'));
    }
  }
}

function umd_terp_form_system_theme_settings_submit(&$form, \Drupal\Core\Form\FormStateInterface $form_state) {
    $values = $form_state->getValues();
    if (!empty($values['umd_terp_footer_logo_upload'])) {
      $filename = file_unmanaged_copy($values['umd_terp_footer_logo_upload']->getFileUri());
      $values['umd_terp_footer_logo_path'] = $filename;
      $form_state->setValue(['umd_terp_footer_logo_path'], $filename);
      // kint($values);
      $form_state->unsetValue('umd_terp_footer_logo_upload');
    }
}
