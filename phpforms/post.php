<?php
require_once '../phputil/util.php';
$oldguess = isset($_POST['guess']) ? $_POST['guess'] : '';
$oldguess2 = isset($_POST['guess2']) ? $_POST['guess2'] : '';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>POST Form</title>
    </head>
    <body>
        <h1>PHP Forms using POST</h1>
        <h3>Many field types</h3>
        <p>The <strong><em>name</em></strong> attribute of the <strong><em>input</em></strong> tag is passed as the identifier of the corresponding input tag</p>
        <p>The <strong><em>value</em></strong> attribute of the <strong><em>input</em></strong> tag is <u>usually but not always</u> passed as the initialized value of the corresponding input tag</p>
        <p>You can use the browser's <strong>developer tools</strong> to see this under the <strong>network</strong> tab</p>
        <form method="post" action="post.php">
            <p>Guessing game...</p>
            <p>
                <label for='guess'>Input Guess (server side code will <strong>not</strong> sanitize the input)</label>
                <input type='text' name='guess' id='guess' value="<?= $oldguess ?>"/>
            </p> 
            <p>
                <label for='guess2'>Input Guess (server side code will sanitize the input)</label>
                <input type='text' name='guess2' id='guess2' value="<?= htmlentities($oldguess2) ?>"/>
            </p> 
            <hr/>    
            The above is an example of how server side php code persists form input data after the submit button is clicked. 
            <br/>We need to be careful not to directly use the user input. We must sanitize input data as needed. 
            <br/>Try the following input in the above two fields:
            <br/><strong>">DIE DIE</strong>
            <br/>It will end up being part of the html of the page for the first input.
            <br/>Whereas for the second input it will be persisted in the input field as expected.
            <br/>This is achieved by using the php function <strong><em>htmlentities()</em></strong>. 
            <br/>Research how user input is usually sanitized/filtered to remove security concerns
            <br/>Check out the following PHP docs:
            <ul>
                <li><a href="https://www.php.net/manual/en/function.filter-var.php" target="_blank">filter_var()</a></li> 
                <li><a href="https://www.php.net/manual/en/function.htmlentities" target="_blank">htmlentities()</a></li> 
                <li><a href="https://www.php.net/manual/en/function.strlen" target="_blank">strlen($var) > 0</a><br/>strlen can be used in the server side as needed</li>
                <li><a href="https://www.php.net/manual/en/function.is-numeric" target="_blank">is_numeric($var)</a><br/>is_numeric can be used in the server side as needed</li> 
            </ul>
            (Research more as needed)
            <hr/>           
            <p>
                <label for="account">Account</label>
                <input type="text" name="account" id="account" size="40">
            </p>
            <p>
                <label for="password">Password</label>
                <input type="password" name="password" id="password" size="40">
            </p>
            <p>
                <label for="nickname">Nick Name</label>
                <input type="text" name="nickname" id="nickname" size="40">
            </p>   
            <p>
                Preferred Time: <br/>
                <input type="radio" name="when" id="am" value="am"/><label for="am">AM</label>
                <br/>
                <input type="radio" name="when" id="pm" value="pm" checked/><label for="pm">PM</label>
                <br/>
                <input type="radio" name="when" id="none"/><label for="none">None (example of what happens when the 'value' attribute is not set. a value of 'on' is sent to server)</label>
            </p>
            <p>
                Classes taken:<br/>
                <input type="checkbox" name="class1" value="cse5385" checked/>CSE 5385 - Neural Networks <br/>
                <input type="checkbox" name="class2" value="cse6363" />CSE 6363 - Machine Learning <br/>
                <input type="checkbox" name="class3" value="cse6324" />CSE 6324 - Adv Topics in Soft Engr <br/>
                (all inputs without a label tag. label tag is useful for css)
            </p>
            <p>
                <label for="soda">Which Soda:</label>
                <select name="soda" id="soda">
                    <option value="0">-- Please Select --</option>
                    <option value="1">Coke</option>
                    <option value="2">Pepsi</option>
                    <option value="3">Mountain Dew</option> 
                    <option value="4">Orange Juice</option>
                    <option value="5">Lemonade</option>
                </select>
            </p>
            <p>
                <label for="snack">Which Snack:</label>
                <select name="snack" id="snack">
                    <option value="0">-- Please Select --</option>
                    <option value="1">Chips</option>
                    <option value="2" selected>Peanuts</option>
                    <option value="3">Cookie</option> 
                </select>
                <br/>
                (The 'value' attribute of 'option' tag can be any string, but numbers are used quite often)
            </p> 
            <p>
                <label for="bio">Tell us about yourself:</label> <br/> 
                <textarea rows="10" cols="40" id="bio" name="bio">I love building web sites using PHP and MySQL</textarea>
                <br/>
                (if you use a 'pre' tag to echo the contents of the textarea after the form submission you can print/display the inputs in the textarea with the original format preserving the newline chars)
            </p>
            <!-- The submit buttons name/value is passed to the server as key value pairs with the request -->
            <input type="submit" name='mysubmit'/>
            <!-- If there was no name attribute this would not have been passed along with the request -->
            <!--<input type="submit"/>-->
            <input type="reset" name='myreset' value='Reset Form Controls'/>
            <?php ln(a("post.php", 'Reset this page')); ?>
        </form>
        <?php
        ln();
        l('$_POST = ');
        d($_POST);

        if (isset($_POST['bio'])) {
            echo "<pre>";
            echo $_POST['bio'];
            echo "</pre>";
        }
        ?>
        <ul>
            <li><a href="index.php">GET Form</a></li>
            <li><a href="post.php">POST Form</a></li>
            <li><a href="mvc.php">MVC</a></li>
        </ul>     
    </body>
</html>


