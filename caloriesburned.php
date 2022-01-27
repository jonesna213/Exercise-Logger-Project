<?php
    /*
        Purpose: To calculate the calories burned by an exercise. which is 
            different depending on the gender.
        
        Description: Takes in the gender, heart rate, weight, age and the 
            time length of the exercise and uses them in an equation to
            calculate the calories burned by the exercise. Returns "Error" if 
            something went wrong otherwise returns the calories burned.
        
        @param $gender the gender of the person
        @param $heartRate the average heart rate during the exercise
        @param $weight the weight of the person
        @param $age the age of the person in years
        @param $timeLength the duration of the exercise in minutes
        
        @return $caloriesBurned either returns a number containing the 
            calories burned or "Error" if something went wrong
    */
    function calculateCaloriesBurned($gender, $heartRate, $weight, $age
            , $timeLength) {
        
        //Assume failure
        $caloriesBurned = "Error";
        
        //Equation for Males
        if ($gender == "Male") {
        
            $caloriesBurned = ((-55.0969 + (0.6309 * $heartRate) + (0.090174 * $weight) + (0.2017 * $age)) / 4.184) * $timeLength;
            
        }
        //Equation for Females
        if ($gender == "Female") {
            
            $caloriesBurned = ((-20.4022 + (0.4472 * $heartRate) - (0.057288 * $weight) + (0.074 * $age)) / 4.184) * $timeLength;
        }
        //Equation for Non binary
        if ($gender == "Non Binary") {
            $caloriesBurned = ((-37.7495 + (0.5391 * $heartRate) + (0.01644 * $weight) + (0.01379 * $age)) / 4.184) * $timeLength;
        }
        
        //returning a rounded integer
        return intval(round($caloriesBurned));
        
    
    }
    
    /*
        Purpose: To calculate how old somebody is in years
        
        Description: Calculates the age in years of somebody by 
        
        @param $birthdate when the person was born
        
        @return $age the age in years the person is 
    */
    function calculateAgeInYears($age, $date) {
        
        $age = substr($age, 6);
        $date = substr($date, 6);
        
        $age = intval($age);
        $date = intval($date);
        
        $age = $date - $age;
        
        return $age;
    }
?>













