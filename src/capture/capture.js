let pokeballs = [
    {
        "name" : "pokeball",
        "rateModifier" : 1,
    },
    {
        "name" : "greatball",
        "rateModifier" : 1.5,
    },
    {
        "name" : "ultraball",
        "rateModifier" : 2,
    },
    {
        "name" : "repeatball",
        "rateModifier" : 1,
    },
    {
        "name" : "quickball",
        "rateModifier" : 1,
    }
]

    

    function calcCatchRate(currHP,maxHP,ballRate){
        // calculation based off of data from bulbapedia
        var numerator = (1 + ((maxHP * 3)-(currHP * 2)) * ballRate);
        var denominator = (maxHP * 3);
        return numerator / denominator;
    }

    function catchMon(){
        
    }