<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="language" content="en">
    <title>
        Hex color -> sass variable
    </title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Modern favicons: http://thenewcode.com/28/Making-And-Deploying-SVG-Favicons -->
    <!-- Use http://realfavicongenerator.net/ for all other root icons -->
    <link rel="icon" type="image/png" href="favicon.png">
    <link rel="icon" type="image/svg+xml" href="favicon.svg">

    <style>

        @font-face {
            font-family: 'freightsans_medium';
            src: url('fonts/freight_sans_medium-webfont.woff2') format('woff2'),
                 url('fonts/freight_sans_medium-webfont.woff') format('woff');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'norwesterregular';
            src: url('fonts/norwester-webfont.woff2') format('woff2'),
                 url('fonts/norwester-webfont.woff') format('woff');
            font-weight: normal;
            font-style: normal;
        }

        *  {
            margin: 0;
            padding: 0;
            font-family: 'freightsans_medium', sans-serif;
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
            font-size: 32px;
            font-weight: 400;
            font-family: norwesterregular;
            text-transform: uppercase;
            color: #9d4165;
        }

        form {
            width: 90%;
            max-width: 500px;
            margin: 0 auto;
        }

        .color-input {
            width: 100%;
            padding: 8px 16px;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 2px;
            font-size: 1.5rem;
            background: white;
            transition: all .25s ease;
        }

        .color-input:focus {
            border-color: #222;
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

        .found .color-output {
            transform: rotateX(0);
        }

        .dark .color-output {
            color: white;
        }

        .color-output {
            width: 100%;
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
            transform: rotateX(90deg);
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
            font-weight: 300;
            color: #aaa;
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

        hr {
            margin: 24px 0;
            border: 0;
            border-top: 1px solid #ddd;
        }

    </style>

</head>

<body>

<form method="POST" id="js-hexform">

    <h1>
        Hex color to sass variable
    </h1>

    <input class="color-input" type="search" class="input" name="input" placeholder="Add a hex" autofocus maxlength="7" autocomplete="off">

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

    <footer>
        Thanks <a href="http://chir.ag/projects/ntc" rel="external">Chirag Mehta</a> & <a href="https://github.com/functioneelwit/fiftyshades" rel="external">Functioneel Wit</a>.
        <hr>
        More info about colors in design systems is <a href="https://medium.com/eightshapes-llc/color-in-design-systems-a1c80f65fa3#.w5cudfcy9">here</a>. An example of how to use these variables in sass is <a href="https://github.com/Eworm/template/blob/master/sass/theme/_colors.scss">here</a>.
    </footer>

</form>

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

                var n_match  = ntc.name(hex);
                console.log(hex);
                var name = n_match[1];

                if (luminosity[2] <= 0.5)
                {
                    $('.color-output-container').addClass('dark');
                }
                else
                {
                    $('.color-output-container').removeClass('dark');
                }

                hex = hex.replace(/#/g, '');
                name = name.replace(" / ", "-");
                name = name.replace(/ /g, '-');
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
            setTimeout(function()
            {
                if ($('.color-input').val())
                {
                    $('.color-input').select();
                }
                else
                {
                    $('.color-output-container').removeClass('found');
                }
            }, 10);
        })

        $('.color-input').select();

    });

</script>

</body>
</html>
