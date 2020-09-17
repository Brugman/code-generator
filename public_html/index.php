<?php

/**
 * Config.
 */

include '../config.php';

/**
 * Functions.
 */

function maximum_number()
{
    global $config;

    $number_chars = strlen( $config['chars'] ) - 1;

    $length = $config['length'] - strlen( $config['prefix'] ) - strlen( $config['postfix'] );

    $maximum_number_codes = $number_chars;
    for ( $i = 2; $i <= $length; $i++ )
        $maximum_number_codes *= $number_chars;

    $existing_list_codes = existing_list_codes();

    if ( $existing_list_codes )
        $maximum_number_codes = $maximum_number_codes - count( $existing_list_codes );

    return $maximum_number_codes;
}

function check_impossible()
{
    global $config;

    if ( !$config['unique'] )
        return;

    if ( maximum_number() < $config['number'] )
        exit('Dieded. Your config is impossible.');
}

function existing_list_codes()
{
    global $config;

    if ( !$config['unique'] || !$config['autel'] )
        return false;

    if ( !file_exists( '../'.$config['autel_file'] ) )
        exit('Dieded. Existing list not found.');

    $el_codes = file_get_contents( '../'.$config['autel_file'] );

    $filename_parts = explode( '.', $config['autel_file'] );
    $ext = end( $filename_parts );

    if ( $ext == 'txt' )
    {
        $el_codes = explode( "\n", $el_codes );
    }
    else if ( $ext == 'json' )
    {
        $el_codes = json_decode( $el_codes );
    }
    else
    {
        die( 'Dieded. Existing list extension not detected.' );
    }

    return $el_codes;
}

function build_codes()
{
    global $config;

    $existing_list_codes = existing_list_codes();

    $new_codes = [];

    while ( count( $new_codes ) +1 <= $config['number'] )
    {
        $code = create_code();

        if ( $config['unique'] )
            if ( in_array( $code, $new_codes ) )
                continue;

        if ( $existing_list_codes )
            if ( in_array( $code, $existing_list_codes ) )
                continue;

        $new_codes[] = $code;
    }

    return $new_codes;
}

function create_code()
{
    global $config;

    $code = '';

    $length = $config['length'] - strlen( $config['prefix'] ) - strlen( $config['postfix'] );

    for ( $i = 1; $i <= $length; $i++ )
    {
        $number_chars = strlen( $config['chars'] ) - 1;
        $random_position = mt_rand( 0, $number_chars );

        $code .= substr( $config['chars'], $random_position, 1 );
    }

    return $config['prefix'].$code.$config['postfix'];
}

function display_codes( $new_codes )
{
    $new_codes_txt = '';
    foreach ( $new_codes as $new_code )
        $new_codes_txt .= $new_code.'<br>';

    $new_codes_json = json_encode( $new_codes );
?>
<link rel="stylesheet" href="/assets/theme.css">
<div class="wrapper">
    <div class="info">Generated <?=count( $new_codes );?> codes out of the possible <?=maximum_number();?>.</div>
    <div class="code-wrapper txt">
        <div class="codes"><?=$new_codes_txt;?></div>
        <button class="js-clipboard" data-clipboard-target=".txt .codes">Copy</button>
    </div>
    <div class="code-wrapper json">
        <div class="codes"><?=$new_codes_json;?></div>
        <button class="js-clipboard" data-clipboard-target=".json .codes">Copy</button>
    </div>
</div>
<script src="/assets/clipboard.min.js"></script>
<script>
new ClipboardJS('.js-clipboard');
</script>
<?php
}

/**
 * Runtime.
 */

check_impossible();

$new_codes = build_codes();

display_codes( $new_codes );

