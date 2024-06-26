<?php
     $servername = "localhost";
     $username = "root";
     $password = "";
     $dbname = "proj3";
 
     try {
         // the program uses new PDO and setAttribute to 
         // connect php files to the localhostAdmin database
         $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
         // set the PDO error mode to exception
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     } catch(PDOException $e) {
         echo "Connection failed: " . $e->getMessage();
         die();
     }

    class pokeball {
        // properties
        public $name;
        public $rateModifier;
        // functions (setters and getters)
        function setName($name){
            $this->name = $name;
        }
        function getName(){
            return $this->name;
        }
        function setRateModifier($rateModifier){
            $this->rateModifier = $rateModifier;
        }
        function getRateModifier(){
            return $this->rateModifier;
        }
    }

    $pokeBall = new pokeball();
    $pokeBall->setName("Pokeball");
    $pokeBall->setRateModifier(1);

    $greatBall = new pokeball();
    $greatBall->setName("Greatball");
    $greatBall->setRateModifier(1.5);

    $ultraBall = new pokeball();
    $ultraBall->setName("Ultraball");
    $ultraBall->setRateModifier(2);

    

    // created using copilot
    $pokeballs = [
        $pokeBall,
        $greatBall,
        $ultraBall
    ];

    function calcCatchRate($currHP,$maxHP,$ballRate){
        // calculation based off of data from bulbapedia
        $numerator = (1 + (($maxHP * 3)-($currHP * 2)) * $ballRate);
        $denominator = ($maxHP * 3);
        return $numerator / $denominator;
    }
    
    


?>