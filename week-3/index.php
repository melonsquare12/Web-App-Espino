<?php 
    function displayOrderSummary(){
        if($_SERVER["REQUEST_METHOD"] === "POST") {
            echo "<h2>Order Summary</h2>";
            
            $coffee_prices = [
                "espresso" => 500,
                "latte" => 600,
                "cappucino" => 700,
                "americano" => 800,
                "mocha" => 900,
            ];
            $size_prices = [
                "small" => 0.00,
                "medium" => 50.00,
                "large" => 80.00,
            ];
    
            $extras_prices = [
                "sugar" => 5.00,
                "cream" => 10.00,
            ];

            $name = $_POST["name"];
            $coffee_type = $_POST["coffee"];
            $size = $_POST["size"];
            $instructions = $_POST["instructions"];
    
    
            if(isset($_POST["extras"])) {
                $extras = $_POST["extras"];
            } else {
                $extras = [];
            }
    
            $total_price = $coffee_prices[$coffee_type] + $size_prices[$size];
            $total_price = calculatePrice($coffee_prices,$size_prices, $size, $extras_prices, $coffee_type, $extras,);
            echo $name;
            echo "<br />";
            echo $instructions;
            echo "<br />";
            echo $total_price;

            $receiptContent = generateReceipt($name, $coffee_type, $size, $total_price, $instructions);

            saveReceipt($receiptContent);
    
            foreach($extras as $extra) {
               $total_price = $total_price + $extras_prices[$extra];
            }
    
            echo $total_price;
            echo "<br/>";
    
            if($coffee_type !== "espresso") {
                echo "Hey, " , htmlspecialchars($_POST["name"]) . "!";
                echo "<p>Here's a joke for you: Why did the coffee file
                a police report? It got Mugged!</p>";
            }
    
            if ($total_price > 450 && $total_price < 850) {
                echo"<p>Password for the CR: coffee123</p>";
            } elseif ($total_price >=900) {
                echo "<p>Password for Wi-Fi: mocha123</p>";
            }

    }
    
    }

    function calculatePrice($coffee_prices,$size_prices, $size, $extras_prices, $coffee_type, $extras,){
        $total_price = $coffee_prices[$coffee_type] + $size_prices[$size];

        foreach($extras as $extra) {
            $total_price = $total_price + $extras_prices[$extra];
        }

        return $total_price;
    }

    function generateReceipt($name, $coffee_type, $size, $total_price, $instructions){
        $receiptContent ="Order Summary\n";
        $receiptContent .= " - - - - - - - - - - - -\n";

        $receiptContent .= "Name: " . $name . "\n";
        $receiptContent .= "Coffee Type: " . $coffee_type . "\n";
        $receiptContent .= "Size: " . $size . "\n";
        $receiptContent .= "Total Price: " . $total_price . "\n";
        $receiptContent .= "Instructions: " . $instructions . "\n";
        $receiptContent .= "Thank you for ordering!";

        return $receiptContent;

    }

    function saveReceipt($receiptContent){
        $file = fopen("Hey your receipt is here.txt", "w") or die("Unable to open file");
        fwrite($file, $receiptContent);
        fclose($file);

        echo "Receipt saved";
    }

    displayOrderSummary();

?>
