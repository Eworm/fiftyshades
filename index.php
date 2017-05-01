<?php

include('functions.php');

if($_POST['input'])
{
    $input_hex = $_POST['input'];

    if(validate_hex($input_hex))
    {
        header('location: ' . $input_hex);
        exit;
    }
    else
    {
        $error = '#' . $input_hex . ' is no valid hex color value.. better yourself!';
    }

}


if( $_GET['q'] )
{
    $hex = strtoupper($_GET['q']);

    if(strlen($hex) === 3)
    {
        $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
    }

    $rgb = hex2rgb($hex);

    if( $rgb['r'] === $rgb['g'] && $rgb['g'] === $rgb['b'] )
    {

        $code = 100 - round((($rgb['r'] / 255) * 100));

        if( $code < 10 )
        {
            $code = '0' . $code;
        }

        $class = '$color--gray-' . $code. ': #' . minify_hex($hex) . ';';

    }
    else
    {

        $find_by_js = true;

    }

    $input = '<button class="color-output" type="button" id="code" data-clipboard-text="' . @$class . '"><span class="color-output-color">' . @$class . '</span><span class="color-output-copied">Copied!</span></button>';

}

?>

<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <meta name="language" content="en">
    <title>
        Hex color -> sass variable
    </title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Fira+Sans:300,400,500">

    <style>

        *  {
            margin: 0;
            padding: 0;
            font-family: 'Fira Sans';
            text-align: center;
            line-height: 1;
            outline: 0;
        }

        body {
            padding: 100px 0;
        }

        h1 {
            margin: 0 0 40px;
            font-size: 24px;
            font-weight: 400;
        }

        .color-input {
            margin: 0 auto;
            padding: 8px 16px;
            display: block;
            border: 1px solid #ccc;
            border-radius: 36px;
            font-size: 24px;
            background: white;
            transition: all .25s ease;
        }

        .color-input:focus {
            border-color: #000;
        }

        .color-output {
            margin: 16px auto;
            padding: 16px 48px;
            display: block;
            position: relative;
            border: 0;
            border-radius: 36px;
            font-size: 24px;
            background: #<?php echo $hex ? $hex : 'ddd' ?>;
            cursor: pointer;
        }

        .color-output-color {
            opacity: 1;
            transition: opacity .15s ease-in;
        }

        .copied .color-output-color {
            opacity: 0;
        }

        .color-output-copied {
            opacity: 0;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1;
            transition: opacity .15s ease-in;
        }

        .copied .color-output-copied {
            opacity: 1;
        }
        
        .exp {
            display: block;
            color: #aaa;
            font-weight: 300;
        }

        footer {
            margin-top: 48px;
            display: inline-block;
            font-size: .75rem;
            font-weight: 300;
        }

        footer a {
            text-decoration: none;
        }

    </style>

</head>
<body>

<?php

    if(isset($error))
    {

?>

    <span class="error"><?php echo $error ?></span>

<?php

    }

?>

    <h1>
        Hex color -> sass variable
    </h1>

    <form method="POST" id="js-hexform">

        <input class="color-input" type="text" class="input" name="input" value="<?php echo ( $input_hex ) ? $input_hex : $hex ?>" placeholder="Add a hex color" autofocus maxlength="7">

    </form>

    <?php echo $input; ?>
    
    <small class="exp">
        Click the variable to copy
    </small>

    <footer>
        Thanks <a href="http://chir.ag/projects/ntc" rel="external">Chirag Mehta</a> & <a href="https://github.com/functioneelwit/fiftyshades" rel="external">Functioneel Wit</a>
    </footer>

<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="ntc.js"></script>
<script src="clipboard.js-master/dist/clipboard.min.js"></script>

<?php if(isset($find_by_js)) { ?>

    <span class="compare" id="compare"></span>

    <script>

        var n_match  = ntc.name("#<?php echo $hex; ?>");
        var name = n_match[1];

        name = name.replace(" / ", "-");
        name = name.replace(" ", "-");
        name = name.toLowerCase();

        $('.color-output-color').html('$color--' + name + ': #<?php echo minify_hex($hex) ?>;');
        $('#code').attr('data-clipboard-text', '$color--' + name + ': #<?php echo minify_hex($hex) ?>;');

    </script>

<?php } ?>

<script>
    
    $(document).ready(function()
    {

        var clipboard = new Clipboard('#code');
    
        clipboard.on('success', function(e)
        {
            e.clearSelection();
        });
    
        $('#code').on('click', function()
        {
            me = $(this);
            me.addClass('copied');
    
            setTimeout(function()
            {
                me.removeClass('copied');
            }, 1250);
        })
        
        $('.color-input').on('click', function()
        {
            $(this).select();    
        })
        
        $('.color-input').select();
        
    });

</script>

</body>
</html>
