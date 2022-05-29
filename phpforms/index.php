<?php require_once '../phputil/util.php'; ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>GET Form</title>
    </head>
    <body>
        <h1>PHP Forms using GET</h1>
        <form>
            <p>
                <label for="guess">Input Guess</label>
                <input type="text" name="guess" id="guess">
            </p>
            <p>
                <label for="pass">Input Password</label>
                <input type="password" name="pass" id="pass">
            </p>

            <fieldset> 
                <legend>(Checkbox) Choose your monster's features:</legend>
                <div>
                    <input type="checkbox" id="scales" name="scales">
                    <label for="scales">Scales</label>
                </div>      
                <div>
                    <input type="checkbox" id="horns" name="horns">
                    <label for="horns">Horns</label>
                </div>                   
            </fieldset>
            <fieldset>
                <legend>(Radio Buttons) Choose your favorite color:</legend>  
                <div>
                    <input type="radio" id="red" name="favcolor" value="red">
                    <label for="red">Red</label>
                </div>
                <div>
                    <input type="radio" id="blue" name="favcolor" value="blue">
                    <label for="blue">Blue</label>
                </div>
                <div>
                    <input type="radio" id="green" name="favcolor" value="green">
                    <label for="green">Green</label>
                </div>                
            </fieldset>
            <fieldset>
                <legend>(input type='color') Choose your monster's colors:</legend>
                <div>
                    <input type="color" name="monshead" id="monshead" value="#e66465">
                    <label for="monshead">Head</label>
                </div>
                <div>
                    <input type="color" name="monsbody" id="monsbody" value="#f6b73c">
                    <label for="monsbody">Body</label>
                </div>  
                <p>Note: this input type is not supported by many browsers. <br/> <a href="https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/color" target='_blank'>Check</a> before using.</p>
            </fieldset>

            <fieldset>
                <legend>(input type='range') Audio settings:</legend>
                <div>
                    <input type="range" name="volume" id="volume" min="0" max="10">
                    <label for="volume">Volume</label>
                </div>
                <div>
                    <input type="range" name="cowbell" id="cowbell" min="0" max="100" step="5" value="25">
                    <label for="cowbell">Cowbell</label>
                </div>
            </fieldset>

            <p>
                <input type="submit"/>
                <input type="reset" />
                <?php ln(a("index.php", 'Reset this page')); ?>
            </p>
        </form>
        <h3>Important diff between checkbox and radio buttons are:</h3>
        <ul>
            <li>Checkbox: <em>name</em> attribute can have <strong>any</strong> value, and it <strong>must</strong> be set.</li>
            <li>Radio Button: <em>name</em> attribute must have the <strong>same</strong> value</li>
            <li>Checkbox: <em>value</em> attribute does not have to be set,and you should not set it. The value of checkbox sent to the server is <u>'on'</u> if selected otherwise an unselected checkbox is not sent to the server.</li>
            <li>Radio Button: <em>value</em> attribute <strong>must</strong>be set. The value of checkbox sent to the server is <u>'on'</u> if selected when <u>'value'</u> attribute is not set</li>
        </ul>
        <?php
        ln();
        l('$_GET');
        d($_GET);
        ?>
        <ul>
            <li><a href="post.php">POST Form</a></li>
        </ul>        
    </body>
</html>
