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
            line-height: 1.8;
            outline: 0;
        }

        a {
            color: #ce5169;
        }

        body {
            padding: 100px 0;
        }

        h1 {
            margin: 0 0 24px;
            font-size: 24px;
            font-weight: 400;
        }

        .color-input {
            margin: 0 auto;
            padding: 8px 16px;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 2px;
            font-size: 1.5rem;
            background: white;
            transition: all .25s ease;
        }

        .color-input:focus {
            border-color: #f6d282;
        }

        .color-output-container {
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transition: all .15s ease-in;
        }

        .found {
            max-height: none;
            opacity: 1;
        }

        .dark .color-output {
            color: white;
        }

        .color-output {
            margin: 16px auto;
            padding: 16px 48px;
            display: block;
            position: relative;
            border: 0;
            border-radius: 36px;
            font-size: 1.5rem;
            background: #<?php echo $hex ? $hex : 'ddd' ?>;
            cursor: pointer;
            font-size: 1.25rem;
            transition: all .25s ease;
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
            margin-top: 8px;
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

        footer a:hover, footer a:focus {
            text-decoration: underline;
        }

        .error {
            margin-bottom: 40px;
            display: none;
            color: red;
        }

    </style>

</head>

<body>

<h1>
    Hex color -> sass variable
</h1>

<form method="POST" id="js-hexform">

    <input class="color-input" type="search" class="input" name="input" placeholder="Add a hex color" autofocus maxlength="7" autocomplete="off">

    <div class="color-output-container">

        <button class="color-output" type="button" id="code" data-clipboard-text="">
            <span class="color-output-color"></span>
            <span class="color-output-copied">Copied!</span>
        </button>

        <small class="exp">
            Click variable to copy to clipboard
        </small>

    </div>

    <span class="error"></span>

</form>

<footer>
    Thanks <a href="http://chir.ag/projects/ntc" rel="external">Chirag Mehta</a> & <a href="https://github.com/functioneelwit/fiftyshades" rel="external">Functioneel Wit</a>
    <br>
    More info about colors in design systems is <a href="https://medium.com/eightshapes-llc/color-in-design-systems-a1c80f65fa3#.w5cudfcy9">here</a>
</footer>

<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="ntc.js"></script>
<script src="color-conversion-algorithms.js"></script>
<script src="clipboard.js-master/dist/clipboard.min.js"></script>

<span class="compare" id="compare"></span>

<script>

    $(document).ready(function()
    {

        $('#js-hexform').on('submit', function(e)
        {
            e.preventDefault();
            hexToRgb($('.color-input').val());
            $('.color-input').focus();
        })

        function hexToRgb(hex)
        {
            // Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF")
            var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
            hex = hex.replace(shorthandRegex, function(m, r, g, b) {
                return r + r + g + g + b + b;
            });

            var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
            var resultColor = [];
            resultColor.push (
                parseInt(result[1], 16),
                parseInt(result[2], 16),
                parseInt(result[3], 16)
            );
            var luminosity = rgbToHsl(parseInt(result[1], 16), parseInt(result[2], 16), parseInt(result[3], 16));

            if (!!resultColor.reduce(function(a, b){ return (a === b) ? a : NaN; }) === true)
            {

                console.log('A grey');

                if (luminosity[2] < 0.5)
                {
                    $('.color-output-container').addClass('dark');
                }
                else
                {
                    $('.color-output-container').removeClass('dark');
                }

                var greyPercentage = Math.floor(100- (100/255) * resultColor[0]);
                $('.color-output-container').addClass('found');
                $('.color-output-color').html('$color--' + greyPercentage + ': #' + hex + ';');
                $('#code').attr('data-clipboard-text', '$color--' + greyPercentage + ': #' + hex + ';').css('background', '#' + hex);

            }
            else
            {

                console.log('A color');

                var n_match  = ntc.name('#' + hex);
                var name = n_match[1];
                
                console.log(hex);
                console.log(luminosity);
                if (luminosity[2] <= 0.5)
                {
                    $('.color-output-container').addClass('dark');
                }
                else
                {
                    $('.color-output-container').removeClass('dark');
                }

                name = name.replace(" / ", "-");
                name = name.replace(" ", "-");
                name = name.toLowerCase();

                $('.color-output-container').addClass('found');
                $('.color-output-color').html('$color--' + name + ': #' + hex + ';');
                $('#code').attr('data-clipboard-text', '$color--' + name + ': #' + hex + ';').css('background', '#' + hex);

            }
        }

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
