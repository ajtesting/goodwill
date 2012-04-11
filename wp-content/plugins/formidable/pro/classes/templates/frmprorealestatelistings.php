<?php

$values['name'] = 'Real Estate Listings';
$values['description'] = '';

if ($form){
    $form_id = $form->id;
    $frm_form->update($form_id, $values );
    $form_fields = $frm_field->getAll(array('fi.form_id' => $form_id));
    if (!empty($form_fields)){
        foreach ($form_fields as $field)
            $frm_field->destroy($field->id);
    }
}else
    $form_id = $frm_form->create( $values );


$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['name'] = 'MLS ID';
$field_values['required'] = 1;
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['name'] = 'Street Address';
$field_values['description'] = 'e.g., "123 Main St"';
$field_values['required'] = 1;
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['name'] = 'City';
$field_values['description'] = 'e.g., "Anytown"';
$field_values['required'] = 1;
$field_values['field_options']['size'] = 27;
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('select', $form_id));
$field_values['name'] = 'State';
$field_values['required'] = 1;
$field_values['options'] = serialize(array('', 'AL', 'AK', 'AS', 'AZ', 'CA', 'CO', 'CT', 'DE', 'DC', 'FL', 'GA', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MH', 'MD', 'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ', 'NM', 'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'WV', 'WI', 'WY'));
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['name'] = 'Zip Code';
$field_values['required'] = 1;
$field_values['field_options']['size'] = $field_values['field_options']['max'] = 10;
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('checkbox', $form_id));
$field_values['name'] = 'Featured';
$field_values['options'] = serialize(array('Featured'));
$field_values['field_options']['label'] = 'none';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('select', $form_id));
$field_values['name'] = 'Type';
$field_values['options'] = serialize(array('', 'Single Family Home', 'Condo/Townhome/Row Home/Co-Op', 'Multi-Family Home', 'Mfd/Mobile Home', 'Farms/Ranches', 'Land'));
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('select', $form_id));
$field_values['name'] = 'Property Status';
$field_values['required'] = 1;
$field_values['options'] = serialize(array('Active', 'Sale Pending', 'Sold', 'Lease Pending', 'Rented' ));
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['name'] = 'List Price';
$field_values['required'] = 1;
$field_values['field_options']['size'] = 12;
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('date', $form_id));
$field_values['name'] = 'List Date';
$field_values['default_value'] = '[date]';
$field_values['field_options']['size'] = 10;
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['name'] = 'Sale Price';
$field_values['field_options']['size'] = 12;
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('date', $form_id));
$field_values['name'] = 'Sale Date';
$field_values['field_options']['blank'] = '';
$field_values['field_options']['size'] = 10;
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['name'] = 'Brief Blurb';
$field_values['description'] = 'e.g., "Nice 4BR home west of Lantana"';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('textarea', $form_id));
$field_values['name'] = 'Description';
$field_values['description'] = 'A more detailed description';
$field_values['required'] = 1;
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['name'] = 'Bedrooms';
$field_values['field_options']['size'] = 5;
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['name'] = 'Full Baths';
$field_values['field_options']['size'] = 5;
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['name'] = 'Garage Spaces';
$field_values['field_options']['size'] = 5;
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['name'] = 'Sqft (Living)';
$field_values['field_options']['size'] = 5;
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['name'] = 'Sqft (Total)';
$field_values['field_options']['size'] = 5;
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['name'] = 'Acres';
$field_values['field_options']['size'] = 5;
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['name'] = 'Year Built';
$field_values['field_options']['size'] = 5;
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('image', $form_id));
$field_values['name'] = 'Main Photo URL';
$field_values['description'] = 'If using a photo that is already online, you can insert the URL here.';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('file', $form_id));
$field_values['name'] = 'Main Photo Upload';
$field_values['description'] = 'Or if you would like to upload the photo, this would be a good spot.';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['name'] = 'NextGen Gallery ID';
$field_values['description'] = 'If you would like to post a Photo Gallery, insert the NextGen gallery ID for this home here.';
$field_values['field_options']['size'] = 5;
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('divider', $form_id));
$field_values['name'] = 'Property Features';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('checkbox', $form_id));
$field_values['name'] = 'General Features';
$field_values['options'] = serialize(array('Balcony', 'BBQ', 'Courtyard', 'Horse Facilities', 'Greenhouse', 'Lease Option', 'Pets Allowed', 'RV/Boat Parking', 'Spa/Hot Tub', 'Tennis Court(s)'));
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('checkbox', $form_id));
$field_values['name'] = 'Interior';
$field_values['options'] = serialize(array('Ceiling Fans', 'Custom Window Covering', 'Disability Features', 'Energy Efficient Home', 'Hardwood Floors', 'Home Warranty', 'Intercom', 'Pool', 'Skylight', 'Window Blinds', 'Window Coverings', 'Window Drapes/Curtains', 'Window Shutters', 'Vaulted Ceiling'));
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('checkbox', $form_id));
$field_values['name'] = 'Rooms';
$field_values['options'] = serialize(array('Dining Room', 'Family Room', 'Den/Office', 'Basement', 'Laundry Room', 'Game Room'));
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('checkbox', $form_id));
$field_values['name'] = 'Air Conditioning';
$field_values['options'] = serialize(array('Central Air', 'Forced Air'));
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('checkbox', $form_id));
$field_values['name'] = 'Heat';
$field_values['options'] = serialize(array('Central', 'Electric', 'Multiple Units', 'Natural Gas', 'Solar', 'Wall Furnace', 'Wood', 'None'));
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('checkbox', $form_id));
$field_values['name'] = 'Fireplace';
$field_values['options'] = serialize(array('Freestanding', 'Gas Burning', 'Two-way', 'Wood Burning'));
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('checkbox', $form_id));
$field_values['name'] = 'Lot Features';
$field_values['options'] = serialize(array('Corner Lot', 'Cul-de-Sac', 'Golf Course Lot/Frontage', 'Golf Course View', 'Waterfront', 'City View', 'Lake View', 'Hill/Mountain View', 'Ocean View', 'Park View', 'River View', 'Water View', 'View'));
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('checkbox', $form_id));
$field_values['name'] = 'Community Features';
$field_values['options'] = serialize(array('Recreation Facilities', 'Community Security Features', 'Community Swimming Pool(s)', 'Community Boat Facilities', 'Community Clubhouse(s)', 'Community Horse Facilities', 'Community Tennis Court(s)', 'Community Park(s)', 'Community Golf', 'Senior Community', 'Community Spa/Hot Tub(s)'));
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('captcha', $form_id));
$field_values['field_key'] = 'captcha';
$field_values['name'] = 'Captcha';
$field_values['field_options']['label'] = 'none';
$frm_field->create( $field_values );
unset($field_values);
  
?>