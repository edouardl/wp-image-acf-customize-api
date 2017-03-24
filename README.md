# ACF - WP Customization API image
As you certainly know if you're using Advanced Custom Fields and the WordPress Customization API, there's no way to use an image declared with ACF option in the WordPress Customization API. The issue is that ACF stores the image id (no matter the return value selected) and the Customization API stores the image url.

This developper plugin provide a simple solution tp this problem, maybe is'nt the best (let me knwo how you proceed instead), but it works.

## What the purpose ?
Because ACF and the API store two different data type for images in the options table, this plugin purposes to let you create two options, one with ACF and one with the Customization API.
But it will synchronize the options :
- when you save an image in the WP admin with ACF, the customization API option will be updated with the url of the image
- when you save an image in the customization interface, the id of the image will be retrieve by the plugin and saved in the ACF option

## How it works ?
The association between the ACF option and the Customization API options must be declared in the functions.php of your theme or in a function during the hook "init".
That's all you need to do.

    add_action( 'init', 'theme_synchronize_image_customize_acf' );
    function theme_synchronize_image_customize_acf() {
        // Init the synchronization, specify the two options names (whithout the acf prefix)
        wiaca_synchronize_image_customize_acf( 'acf_banner_image', 'customize_banner_image' );
    }


The plugin act in the action hooks "updated_option" to detect if one of the two options has been updated to update the associated option.

## Go further
This plugin include a WP Customizer Image class to automaticaly get the label and the description from acf options to the customizer option :
In your control declarations, replace the WP_Customize_Image_Control class by the Customize_Image_Control_Acf_Sync class, an you can remove the 'label' and 'description'

    $wp_customize->add_control( new Customize_Image_Control_Acf_Sync( $wp_customize, 'customize_banner_image', array(
        'acf_option' => 'acf_banner_image', // Key : name of the acf option (without the acf prefix)
        //'label'    => __("Banner Image", 'mytheme'), // inherited from acf option declaration, can be remove
        //'description' => __('Lorem ipsum dolor sit amet', 'mytheme'), // inherited from acf option declaration, can be remove
        'section'  => 'banner_scheme',
        'settings' => 'customize_banner_image'
    )));

## Known issue(s)
- Works only with unserialized options values

## Changelog
Version 1.0
- Initial
