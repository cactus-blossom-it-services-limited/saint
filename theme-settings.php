<?php
/**
 * @file
 * theme-settings.php
 *
 * Provides theme settings for Saint based themes when admin theme is not.
 *
 * @see ./includes/settings.inc
 */

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Implements hook_form_FORM_ID_alter().
 */
function saint_form_system_theme_settings_alter(&$form, FormStateInterface $form_state, $form_id = NULL) {

  // General "alters" use a form id. Settings should not be set here. The only
  // thing useful about this is if you need to alter the form for the running
  // theme and *not* the theme setting.
  // @see http://drupal.org/node/943212
  if (isset($form_id)) {
    return;
  }

  //Change collapsible fieldsets (now details) to default #open => FALSE.
  $form['theme_settings']['#open'] = FALSE;
  $form['logo']['#open'] = FALSE;
  $form['favicon']['#open'] = FALSE;

  // Library settings
  if (\Drupal::moduleHandler()->moduleExists('bootstrap_library')) {
    $form['saint_library'] = array(
      '#type' => 'select',
      '#title' => t('Load library'),
      '#description' => t('Select how to load the Bootstrap Library.'),
      '#default_value' => theme_get_setting('saint_library'),
      '#options' => array(
        'cdn' => t('CDN'),
        'development' => t('Local non minimized (development)'),
        'production' => t('Local minimized (production)'),
      ),
      '#empty_option' => t('None'),
      '#description' => t('If none is selected you should load the library via Bootstrap Library or manually. If CDN is selected, the library version must be configured on @boostrap_library_link',  array('@bootstrap_library_link' => Drupal::l('Bootstrap Library Settings' , Url::fromRoute('bootstrap_library.admin')))),
    );
  }

  // Vertical tabs
  $form['bootstrap'] = array(
    '#type' => 'vertical_tabs',
    '#prefix' => '<h2><small>' . t('Bootstrap Settings') . '</small></h2>',
    '#weight' => -10,
  );

  // Layout.
  $form['layout'] = array(
    '#type' => 'details',
    '#title' => t('Layout'),
    '#group' => 'bootstrap',
  );

  //Container
  $form['layout']['container'] = array(
    '#type' => 'details',
    '#title' => t('Container'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['layout']['container']['saint_fluid_container'] = array(
    '#type' => 'checkbox',
    '#title' => t('Fluid container'),
    '#default_value' => theme_get_setting('saint_fluid_container'),
    '#description' => t('Use <code>.container-fluid</code> class. See : @saint_link', array(
      '@saint_link' => Drupal::l('Fluid container' , Url::fromUri('http://getbootstrap.com/css/' , ['absolute' => TRUE , 'fragment' => 'grid-example-fluid'])),
    )),
  );

  // List of regions
  $theme = \Drupal::theme()->getActiveTheme()->getName();
  $region_list = system_region_list($theme, $show = REGIONS_ALL);
  // Only for initial setup if not defined on install
  $nowrap = [
    'breadcrumb',
    'highlighted',
    'content',
    'primary_menu',
    'header',
    'sidebar_first',
    'sidebar_second',
  ];

  //Region
  $form['layout']['region'] = array(
    '#type' => 'details',
    '#title' => t('Region'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  foreach ($region_list as $name => $description) {
    if ( theme_get_setting('saint_region_clean_' . $name) !== NULL) {
      $region_clean = theme_get_setting('saint_region_clean_' . $name);
    }
    else {
      $region_clean = in_array($name, $nowrap);
    }
    if ( theme_get_setting('saint_region_class_' . $name) !== NULL) {
      $region_class = theme_get_setting('saint_region_class_' . $name);
    }
    else {
      $region_class = $region_clean ? NULL : 'row';
    }

    $form['layout']['region'][$name] = array(
      '#type' => 'details',
      '#title' => $description,
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    );
    $form['layout']['region'][$name]['saint_region_clean_' . $name] = array(
      '#type' => 'checkbox',
      '#title' => t('Clean wrapper for @description region', array('@description' => $description)),
      '#default_value' => $region_clean,
    );
    $form['layout']['region'][$name]['saint_region_class_' . $name] = array(
      '#type' => 'textfield',
      '#title' => t('Classes for @description region', array('@description' => $description)),
      '#default_value' => $region_class,
      '#size' => 40,
      '#maxlength' => 40,
    );
  }

  // Sidebar Position
  $form['layout']['sidebar_position'] = array(
    '#type' => 'details',
    '#title' => t('Sidebar Position'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['layout']['sidebar_position']['saint_sidebar_position'] = array(
    '#type' => 'select',
    '#title' => t('Sidebars Position'),
    '#default_value' => theme_get_setting('saint_sidebar_position'),
    '#options' => array(
      'left' => t('Left'),
      'both' => t('Both sides'),
      'right' => t('Right'),
    ),
  );
  $form['layout']['sidebar_position']['saint_content_offset'] = array(
    '#type' => 'select',
    '#title' => t('Content Offset'),
    '#default_value' => theme_get_setting('saint_content_offset'),
    '#options' => array(
      0 => t('None'),
      1 => t('1 Cols'),
      2 => t('2 Cols'),
    ),
  );

  // Sidebar First
  $form['layout']['sidebar_first'] = array(
    '#type' => 'details',
    '#title' => t('Sidebar First Layout'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['layout']['sidebar_first']['saint_sidebar_collapse'] = array(
    '#type' => 'checkbox',
    '#title' => t('Sidebar collapse'),
    '#default_value' => theme_get_setting('saint_sidebar_collapse'),
  );
  $form['layout']['sidebar_first']['saint_sidebar_first_width'] = array(
    '#type' => 'select',
    '#title' => t('Sidebar First Width'),
    '#default_value' => theme_get_setting('saint_sidebar_first_width'),
    '#options' => array(
      2 => t('2 Cols'),
      3 => t('3 Cols'),
      4 => t('4 Cols'),
    ),
  );
  $form['layout']['sidebar_first']['saint_sidebar_first_offset'] = array(
    '#type' => 'select',
    '#title' => t('Sidebar First Offset'),
    '#default_value' => theme_get_setting('saint_sidebar_first_offset'),
    '#options' => array(
      0 => t('None'),
      1 => t('1 Cols'),
      2 => t('2 Cols'),
    ),
  );

  // Sidebar Second
  $form['layout']['sidebar_second'] = array(
    '#type' => 'details',
    '#title' => t('Sidebar Second Layout'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['layout']['sidebar_second']['saint_sidebar_second_width'] = array(
    '#type' => 'select',
    '#title' => t('Sidebar Second Width'),
    '#default_value' => theme_get_setting('saint_sidebar_second_width'),
    '#options' => array(
      2 => t('2 Cols'),
      3 => t('3 Cols'),
      4 => t('4 Cols'),
    ),
  );
  $form['layout']['sidebar_second']['saint_sidebar_second_offset'] = array(
    '#type' => 'select',
    '#title' => t('Sidebar Second Offset'),
    '#default_value' => theme_get_setting('saint_sidebar_second_offset'),
    '#options' => array(
      0 => t('None'),
      1 => t('1 Cols'),
      2 => t('2 Cols'),
    ),
  );

  // General.
  $form['components'] = array(
    '#type' => 'details',
    '#title' => t('Components'),
    '#group' => 'bootstrap',
  );

  // Buttons.
  $form['components']['buttons'] = array(
    '#type' => 'details',
    '#title' => t('Buttons'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['components']['buttons']['saint_button'] = array(
    '#type' => 'checkbox',
    '#title' => t('Convert input submit to button element'),
    '#default_value' => theme_get_setting('saint_button'),
    '#description' => t('Convert all input submit to button element. There is a known issue with Ajax Exposed Filters, which do not work as the ajax commands expects an input element, no other issues have been detected at the time.'),
  );
  $form['components']['buttons']['saint_button_size'] = array(
    '#type' => 'select',
    '#title' => t('Default button size'),
    '#default_value' => theme_get_setting('saint_button_size'),
    '#empty_option' => t('Normal'),
    '#options' => array(
      'btn-sm' => t('Small'),
      'btn-lg' => t('Large'),
  ),
  $form['components']['buttons']['saint_button_outline'] = array(
    '#type' => 'checkbox',
    '#title' => t('Button with outline format'),
    '#default_value' => theme_get_setting('saint_button_outline'),
    '#description' => t('Use <code>.btn-default-outline</code> class. See : @saint_link', array(
      '@saint_link' => Drupal::l('Outline Buttons' , Url::fromUri('http://getbootstrap.com/css/' , ['absolute' => TRUE , 'fragment' => 'grid-example-fluid'])),
    )),)
  );

  // Navbar.
  $form['components']['navbar'] = array(
    '#type' => 'details',
    '#title' => t('Navbar'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['components']['navbar']['saint_navbar_container'] = array(
    '#type' => 'checkbox',
    '#title' => t('Navbar Width Container'),
    '#description' => t('Check if Navbar width will be inside container or fluid width.'),
    '#default_value' => theme_get_setting('saint_navbar_container'),
  );
  $form['components']['navbar']['saint_navbar_toggle'] = array(
    '#type' => 'select',
    '#title' => t('Navbar toggle size'),
    '#description' => t('Select size for navbar to collapse.'),
    '#default_value' => theme_get_setting('saint_navbar_toggle'),
    '#options' => array(
      'navbar-toggleable-lg' => t('Large'),
      'navbar-toggleable-md' => t('Medium'),
      'navbar-toggleable-sm' => t('Small'),
      'navbar-toggleable-xs' => t('Extra Small'),
    ),
  );
  $form['components']['navbar']['saint_navbar_top_navbar'] = array(
    '#type' => 'checkbox',
    '#title' => t('Navbar Top is Navbar'),
    '#description' => t('Check if Navbar Top .navbar class should be added.'),
    '#default_value' => theme_get_setting('saint_navbar_top_navbar'),
  );
  $form['components']['navbar']['saint_navbar_top_position'] = array(
    '#type' => 'select',
    '#title' => t('Navbar top position'),
    '#description' => t('Select your navbar top position.'),
    '#default_value' => theme_get_setting('saint_navbar_top_position'),
    '#options' => array(
      'fixed-top' => t('Fixed Top'),
      'fixed-bottom' => t('Fixed Bottom'),
      'sticky-top' => t('Sticky Top'),
    ),
    '#empty_option' => t('Normal'),
  );
  $form['components']['navbar']['saint_navbar_top_color'] = array(
    '#type' => 'select',
    '#title' => t('Navbar top color'),
    '#description' => t('Select a color for links in navbar top.'),
    '#default_value' => theme_get_setting('saint_navbar_top_color'),
    '#options' => array(
      'navbar-light' => t('Light'),
      'navbar-dark' => t('Dark'),
    ),
    '#empty_option' => t('Default'),
  );
  $form['components']['navbar']['saint_navbar_top_background'] = array(
    '#type' => 'select',
    '#title' => t('Navbar top background'),
    '#description' => t('Select a color for background in navbar top.'),
    '#default_value' => theme_get_setting('saint_navbar_top_background'),
    '#options' => array(
      'bg-primary' => t('Primary'),
      'bg-light' => t('Light'),
      'bg-dark' => t('Dark'),
    ),
    '#empty_option' => t('Default'),
  );
  $form['components']['navbar']['saint_navbar_position'] = array(
    '#type' => 'select',
    '#title' => t('Navbar Position'),
    '#description' => t('Select your Navbar position.'),
    '#default_value' => theme_get_setting('saint_navbar_position'),
    '#options' => array(
      'fixed-top' => t('Fixed Top'),
      'fixed-bottom' => t('Fixed Bottom'),
      'sticky-top' => t('Sticky Top'),
    ),
    '#empty_option' => t('Normal'),
  );
  $form['components']['navbar']['saint_navbar_color'] = array(
    '#type' => 'select',
    '#title' => t('Navbar link color'),
    '#description' => t('Select a color for links in navbar style.'),
    '#default_value' => theme_get_setting('saint_navbar_color'),
    '#options' => array(
      'navbar-light' => t('Light'),
      'navbar-dark' => t('Dark'),
    ),
    '#empty_option' => t('Default'),
  );
  $form['components']['navbar']['saint_navbar_background'] = array(
    '#type' => 'select',
    '#title' => t('Navbar background'),
    '#description' => t('Select a color for background in navbar.'),
    '#default_value' => theme_get_setting('saint_navbar_background'),
    '#options' => array(
      'bg-primary' => t('Primary'),
      'bg-light' => t('Light'),
      'bg-dark' => t('Dark'),
    ),
    '#empty_option' => t('Default'),
  );

  // Affix
  $form['affix'] = array(
    '#type' => 'details',
    '#title' => t('Affix'),
    '#group' => 'bootstrap',
  );
  $form['affix']['navbar_top'] = array(
    '#type' => 'details',
    '#title' => t('Affix Navbar Top'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['affix']['navbar_top']['saint_navbar_top_affix'] = array(
    '#type' => 'checkbox',
    '#title' => t('Affix navbar top'),
    '#description' => t('Apply affix effect to the top navbar of the site.'),
    '#default_value' => theme_get_setting('saint_navbar_top_affix'),
  );
/*  $form['affix']['navbar_top']['saint_navbar_top_affix_top'] = array(
    '#type' => 'textfield',
    '#title' => t('Affix top'),
    '#default_value' => theme_get_setting('saint_navbar_top_affix_top'),
    '#prefix' => '<div id="navbar-top-affix">',
    '#size' => 6,
    '#maxlength' => 3,
    '#states' => [
      'invisible' => [
        'input[name="saint_navbar_top_affix"]' => ['checked' => FALSE],
      ],
    ],
  );
  $form['affix']['navbar_top']['saint_navbar_top_affix_bottom'] = array(
    '#type' => 'textfield',
    '#title' => t('Affix bottom'),
    '#default_value' => theme_get_setting('saint_navbar_top_affix_bottom'),
    '#suffix' => '</div>',
    '#size' => 6,
    '#maxlength' => 3,
    '#states' => [
      'invisible' => [
        'input[name="saint_navbar_top_affix"]' => ['checked' => FALSE],
      ],
    ],
  ); */
  $form['affix']['navbar'] = array(
    '#type' => 'details',
    '#title' => t('Affix Navbar'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['affix']['navbar']['saint_navbar_affix'] = array(
    '#type' => 'checkbox',
    '#title' => t('Affix navbar'),
    '#description' => t('Apply affix effect to the navbar of the site.'),
    '#default_value' => theme_get_setting('saint_navbar_affix'),
  );
/*  $form['affix']['navbar']['saint_navbar_affix_top'] = array(
    '#type' => 'textfield',
    '#title' => t('Affix top'),
    '#default_value' => theme_get_setting('saint_navbar_affix_top'),
    '#prefix' => '<div id="navbar-affix">',
    '#size' => 6,
    '#maxlength' => 3,
    '#states' => [
      'invisible' => [
        'input[name="saint_navbar_affix"]' => ['checked' => FALSE],
      ],
    ],
  );
  $form['affix']['navbar']['saint_navbar_affix_bottom'] = array(
    '#type' => 'textfield',
    '#title' => t('Affix bottom'),
    '#default_value' => theme_get_setting('saint_navbar_affix_bottom'),
    '#suffix' => '</div>',
    '#size' => 6,
    '#maxlength' => 3,
    '#states' => [
      'invisible' => [
        'input[name="saint_navbar_affix"]' => ['checked' => FALSE],
      ],
    ],
  ); */
  $form['affix']['sidebar_first'] = array(
    '#type' => 'details',
    '#title' => t('Affix sidebar first'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['affix']['sidebar_first']['saint_sidebar_first_affix'] = array(
    '#type' => 'checkbox',
    '#title' => t('Affix sidebar first'),
    '#description' => t('Apply affix effect to the sidebar first of the site.'),
    '#default_value' => theme_get_setting('saint_sidebar_first_affix'),
  );
/*  $form['affix']['sidebar_first']['saint_sidebar_first_affix_top'] = array(
    '#type' => 'textfield',
    '#title' => t('Affix top'),
    '#default_value' => theme_get_setting('saint_sidebar_first_affix_top'),
    '#prefix' => '<div id="sidebar-first-affix">',
    '#size' => 6,
    '#maxlength' => 3,
    '#states' => [
      'invisible' => [
        'input[name="saint_sidebar_first_affix"]' => ['checked' => FALSE],
      ],
    ],
  );
  $form['affix']['sidebar_first']['saint_sidebar_first_affix_bottom'] = array(
    '#type' => 'textfield',
    '#title' => t('Affix bottom'),
    '#default_value' => theme_get_setting('saint_sidebar_first_affix_bottom'),
    '#suffix' => '</div>',
    '#size' => 6,
    '#maxlength' => 3,
    '#states' => [
      'invisible' => [
        'input[name="saint_sidebar_first_affix"]' => ['checked' => FALSE],
      ],
    ],
  ); */
  $form['affix']['sidebar_second'] = array(
    '#type' => 'details',
    '#title' => t('Affix sidebar second'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['affix']['sidebar_second']['saint_sidebar_second_affix'] = array(
    '#type' => 'checkbox',
    '#title' => t('Affix sidebar second'),
    '#description' => t('Apply affix effect to the sidebar second of the site.'),
    '#default_value' => theme_get_setting('saint_sidebar_second_affix'),
  );
/*  $form['affix']['sidebar_second']['saint_sidebar_second_affix_top'] = array(
    '#type' => 'textfield',
    '#title' => t('Affix top'),
    '#default_value' => theme_get_setting('saint_sidebar_second_affix_top'),
    '#prefix' => '<div id="sidebar-second-affix">',
    '#size' => 6,
    '#maxlength' => 3,
    '#states' => [
      'invisible' => [
        'input[name="saint_sidebar_second_affix"]' => ['checked' => FALSE],
      ],
    ],
  );
  $form['affix']['sidebar_second']['saint_sidebar_second_affix_bottom'] = array(
    '#type' => 'textfield',
    '#title' => t('Affix bottom'),
    '#default_value' => theme_get_setting('saint_sidebar_second_affix_bottom'),
    '#suffix' => '</div>',
    '#size' => 6,
    '#maxlength' => 3,
    '#states' => [
      'invisible' => [
        'input[name="saint_sidebar_second_affix"]' => ['checked' => FALSE],
      ],
    ],
  ); */

  // Scroll Spy.
  $form['scroll_spy'] = array(
    '#type' => 'details',
    '#title' => t('Scroll Spy'),
    '#group' => 'bootstrap',
  );
  $form['scroll_spy']['saint_scroll_spy'] = array(
    '#type' => 'textfield',
    '#title' => t('Element for Scroll Spy'),
    '#description' => t('A valid jquery Id for the element containing .nav that will behave as scroll spy.'),
    '#default_value' => theme_get_setting('saint_scroll_spy'),
    '#size' => 40,
    '#maxlength' => 40,
  );

  // Fonts.
  // General.
  $form['fonts'] = array(
    '#type' => 'details',
    '#title' => t('Fonts'),
    '#group' => 'bootstrap',
  );

  $form['fonts']['fonts'] = array(
    '#type' => 'details',
    '#title' => t('Fonts'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );
  $form['fonts']['fonts']['saint_google_fonts'] = array(
    '#type' => 'select',
    '#title' => t('Google Fonts Combination'),
    '#default_value' => theme_get_setting('saint_google_fonts'),
    '#empty_option' => t('None'),
    '#options' => array(
      'roboto' => 'Roboto Condensed, Roboto',
      'monserrat_lato' => 'Monserrat, Lato',
      'alegreya_roboto' => 'Alegreya, Roboto Condensed, Roboto',
      'dancing_garamond' => 'Dancing Script, EB Garamond',
      'amatic_josefin' => 'Amatic SC, Josefin Sans',
      'oswald_droid' => 'Oswald, Droid Serif',
      'playfair_alice' => 'Playfair Display, Alice',
      'dosis_opensans' => 'Dosis, Open Sans',
      'lato_hotel' => 'Lato, Grand Hotel',
      'medula_abel' => 'Medula One, Abel',
      'fjalla_cantarell' => 'Fjalla One, Cantarell',
      'coustard_leckerli' => 'Coustard Ultra, Leckerli One',
      'philosopher_muli' => ' Philosopher, Muli ',
      'vollkorn_exo' => 'Vollkorn, Exo',
    ),
  );
  $form['fonts']['icons'] = array(
    '#type' => 'details',
    '#title' => t('Icons'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );
  $form['fonts']['icons']['saint_icons'] = array(
    '#type' => 'select',
    '#title' => t('Icon set'),
    '#default_value' => theme_get_setting('saint_icons'),
    '#empty_option' => t('None'),
    '#options' => array(
      'material_design_icons' => 'Material Design Icons',
      'fontawesome' => 'Font Awesome',
    ),
  );

  // General.
  $form['colors'] = array(
    '#type' => 'details',
    '#title' => t('Colors'),
    '#group' => 'bootstrap',
  );

  // Alerts.
  $form['colors']['alerts'] = array(
    '#type' => 'details',
    '#title' => t('Colors'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );
  $form['colors']['alerts']['saint_system_messages'] = array(
    '#type' => 'select',
    '#title' => t('System Messages Color Scheme'),
    '#default_value' => theme_get_setting('saint_system_messages'),
    '#empty_option' => t('Default'),
    '#options' => array(
      'messages_light' => t('Light'),
      'messages_dark' => t('Dark'),
    ),
    '#description' => t('Replace standard color scheme for the system mantainance alerts with Google Material Design color scheme'),
  );
  $form['colors']['tables'] = array(
    '#type' => 'details',
    '#title' => t('Tables'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );
  $form['colors']['tables']['saint_table_style'] = array(
    '#type' => 'select',
    '#title' => t('Table cell style'),
    '#default_value' => theme_get_setting('saint_table_style'),
    '#empty_option' => t('Default'),
    '#options' => array(
      'table-striped' => t('Striped'),
      'table-bordered' => t('Bordered'),
    ),
  );
  $form['colors']['tables']['saint_table_hover'] = array(
    '#type' => 'checkbox',
    '#title' => t('Hover efect over table cells'),
    '#description' => t('Apply Bootstrap table hover effect.'),
    '#default_value' => theme_get_setting('saint_table_hover'),
  );
  $form['colors']['tables']['saint_table_head'] = array(
    '#type' => 'select',
    '#title' => t('Table Header Color Scheme'),
    '#default_value' => theme_get_setting('saint_table_head'),
    '#empty_option' => t('Default'),
    '#options' => array(
      'thead-light' => t('Light'),
      'thead-dark' => t('Dark'),
    ),
    '#description' => t('Select the table head color scheme'),
  );
}
