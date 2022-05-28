<?php include_once '../phpUtil/util.php'; ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>PHP Arrays</title>
    </head>
    <body>
        <?php
        l(h(1, "PHP Arrays"));

        l(h(2, "Integer Indices"));
        $stuff = array("Hi", "There");
        ln($stuff[1]);

        l(h(2, "Assosiative Arrays"));
        $stuff = array("name" => "Sudipta",
            "course" => "wa4e");
        ln($stuff["course"]);
        d($stuff);

        l(h(2, "var_dump vs print_r"));
        $thing = false;
        ln("one");
        d($thing);
        ln('after one print_r was used to inspect the variable $thing');
        ln('but nothing got printed');
        ln();
        ln("two");
        d($thing, true);
        l(p('var_dump displays false, and the datatype of values while print_r does not'));

        l(h(2, "Building Up an Array"));
        $va = array();
        $va[] = "Hello";
        $va[] = "World";
        d($va);

        $za = array();
        $za["name"] = "Sudipta Sharif";
        $za["course"] = "Neural Networks";
        d($za);

        l(h(2, "Looping Through an Array"));
        foreach ($za as $k => $v) {
            //echo "key=", $k, " val=", $v, "<br>";
            ln("key=$k val=$v");
        }

        $epl = array();
        $epl[] = "liverpool";
        $epl[] = "man city";
        $epl[] = "man utd";
        $epl[] = "chelsea";

        l(h(3, "Counted Loop"));
        for ($idx = 0; $idx < count($epl); $idx++) {
            ln("I=$idx Val=$epl[$idx]");
        }

        l(p('Counted loops only work for compact arrays like the following:'));
        d($epl);
        ln("This works because the idex of counted loops starts with 0, and there");
        ln("are no gaps in the sequence of the index.");
        l(p('However, counted loops (for-loops) does not work for non-compact array like the following:'));
        $randarr = array();
        $randarr[0] = "zero";
        $randarr[4] = "four";
        d($randarr);
        for ($idx = 0; $idx < count($randarr); $idx++) {
            ln("I=$idx Val=$randarr[$idx]");
        }
        ln("Nothing breaks! but you will get a Notice!!");

        l(h(2, "Arrays of Arrays"));
        $products = array(
            'paper' => array(
                'copier' => 'Copier & Multipurpose',
                'inkjet' => 'Inkjet Printer',
                'laser' => 'Laser Printer',
                'photo' => 'Photographic Paper'
            ),
            'pens' => array(
                'ball' => 'Ball Point',
                'hilite' => 'Highlighters',
                'marker' => 'Markers'
            ),
            'misc' => array(
                'tape' => 'Sticky Tape',
                'glue' => 'Adhesives',
                'clips' => 'Paperclips'
            )
        );
        d($products);
        l('$products[\'pens\'][\'marker\'] = ');
        ln($products['pens']['marker']);

        l(h(2, "Array Functions"));

        $epl = array();
        $epl["mohammand"] = "liverpool";
        $epl["mane"] = "liverpool";
        $epl["messi"] = "psg";
        $epl["ronaldo"] = "man utd";
        $epl["kevin"] = "man city";
        $epl["nevile"] = "man utd";
        $epl["gerrad"] = "liverpool";
        $epl["kane"] = "tottenham";
        $epl["vardy"] = "leicester";

        printPlayerInfo("sudipta", $epl);
        printPlayerInfo("mane", $epl);
        printPlayerInfo("kane", $epl);
        printPlayerInfo("john", $epl);
       
        l(h(2, "isset() vs array_key_exists()"));
        function printPlayerInfo($player, $epl) {
            if (array_key_exists($player, $epl)) {
                ln("[Player:] $player [Team:] $epl[$player]");
            } else {
                ln("[Player:] $player [Team:] N/A");
            }
        }
        $player = "sudipta";
        echo isset($epl[$player]) ? "$player is a player in epl": "$player does not play for epl";
        echo "<br>";
        $player = "gerrad";
        echo isset($epl[$player]) ? "$player is a player in epl": "$player does not play for epl";
        
        l(h(2, "Array Sorting"));
        ln('$epl = ');
        d($epl);
        
        $epl_sort = $epl;
        ln('$epl_sort = ');
        d($epl_sort);    
        
        sort($epl_sort);
        ln('$epl_sort');
        d($epl_sort);
        
        ln('$epl');
        d($epl);
        
        $epl_ksort = $epl;
        ksort($epl_ksort);
        ln('$epl_ksort (sort by key)');
        d($epl_ksort);
                
        $epl_asort = $epl;
        ln('$epl_asort (sort by value)');
        asort($epl_asort);
        d($epl_asort);
        
        ln('$epl');
        d($epl); 
        
        l(h(2, "Exploding Arrays"));
        $msg = "This is a sentence with seven words";
        ln('$msg = '."$msg");
        $msgTokens = explode(" ", $msg);
        d($msgTokens);
        
        l(h(2, "PHP Super Global Variables"));
        l(h(3, '$_GET'));
        d($_GET, true);
        ln('nothing in the $_GET super global variable, its empty');
        
        ln('this looks very much linke an empty array');
        $arr = array();
        d($arr, true);
        
        echo isset($_GET) ? '$_GET is set (meaning $_GET is declared, and its not null)' : '$_GET is not set';
        ln();
        echo isset($arr) ? '$arr is set (meaning $arr is declared, and its not null)' : '$arr is not set';
        ln();
        echo isset($arr2) ? '$arr2 is set (meaning $arr is declared, and its not null)' : '$arr is not set';
        
        
        ?>
    </body>
</html>
