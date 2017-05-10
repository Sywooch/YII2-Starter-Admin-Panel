<?php

use yii\db\Migration;

class m130524_201442_init extends Migration {
    public function up() {

        $pathOfSQL = dirname( dirname(__FILE__))."/data/default_table.sql";
        if(file_exists($pathOfSQL)) {
            //$tempsql = file_get_contents($pathOfSQL);
            $templine = '';
            // Read in entire file
            $lines = file($pathOfSQL);
            // Loop through each line
            foreach ($lines as $line)
            {
                // Skip it if it's a comment
                if (substr($line, 0, 2) == '--' || $line == '')
                    continue;

                // Add this line to the current segment
                $templine .= $line;
                // If it has a semicolon at the end, it's the end of the query
                if (substr(trim($line), -1, 1) == ';')
                {
                    // Perform the query
                    //mysql_query($templine) or print('Error performing query \'<strong>' . $templine . '\': ' . mysql_error() . '<br /><br />');
                    $this->execute($templine);
                    // Reset temp variable to empty
                    $templine = '';
                }
            }
            echo "Tables imported successfully";
            return true;
        }
        echo $pathOfSQL;
        return false;
        die;
    }

    public function down() {

        echo "m151128_130445_boobardbschema cannot be reverted.\n";

        return false;
    }
}
