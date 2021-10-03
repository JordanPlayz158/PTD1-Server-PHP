<?php
    /**
     * Note: As this code was made in a hurry,
     *  it is not a code made to be readable much less maintainable.
     *  Plz Don't waste your time or your sanity trying to understand what I've done here.
     */

    class Keygen
    {
        public static function genprofileid($currentSave, $trainerId)
        {
            $_loc8 = 0;
            $_loc3 = Keygen::method_150($currentSave);

            if (strlen($currentSave) != 14) {
                return Keygen::method_118();
            }
            if ($trainerId < 333 or $trainerId > 99999) {
                return Keygen::method_118();
            }

            if ($_loc3 == 0)
            {
                return Keygen::method_118();
            }
            $_loc4 = ((int) $trainerId * $_loc3) * 14;
            $_loc5 = "$_loc4";
            $_loc6 = $_loc5[0];
            $_loc7 = "";
            $_loc10 = 0;
            while ($_loc10 < strlen($_loc5)) {
                $_loc8 = (int) $_loc5[$_loc10] + $_loc6;
                $_loc9 = Keygen::num2char("$_loc8");
                $_loc7 = $_loc7.$_loc9;
                $_loc10++;
            }
            return $_loc7;
        }

        private static function method_150($currentSave)
        {
            $_loc2 = 0;
            $index = 0;
            while ($index < strlen("$currentSave")) {
                $_loc2 += Keygen::char2int("$currentSave"[$index]);
                $index += 1;
            }
            return $_loc2;
        }

        private static function char2int($char)
        {
            $num = 0;
            if ($char == "a" or $char == "1") {
                $num = 1;
            }
            else if ($char == "b" or $char == "2") {
                $num = 2;
            }
            else if ($char == "c" or $char == "3") {
                $num = 3;
            }
            else if ($char == "d" or $char == "4") {
                $num = 4;
            }
            else if ($char == "e" or $char == "5") {
                $num = 5;
            }
            else if ($char == "f" or $char == "6") {
                $num = 6;
            }
            else if ($char == "g" or $char == "7") {
                $num = 7;
            }
            else if ($char == "h" or $char == "8") {
                $num = 8;
            }
            else if ($char == "i" or $char == "9") {
                $num = 9;
            }
            else if ($char == "j") {
                $num = 10;
            }
            else if ($char == "k") {
                $num = 11;
            }
            else if ($char == "l") {
                $num = 12;
            }
            else if ($char == "m") {
                $num = 13;
            }
            else if ($char == "n") {
                $num = 14;
            }
            else if ($char == "o") {
                $num = 15;
            }
            else if ($char == "p") {
                $num = 16;
            }
            else if ($char == "q") {
                $num = 17;
            }
            else if ($char == "r") {
                $num = 18;
            }
            else if ($char == "s") {
                $num = 19;
            }
            else if ($char == "t") {
                $num = 20;
            }
            else if ($char == "u") {
                $num = 21;
            }
            else if ($char == "v") {
                $num = 22;
            }
            else if ($char == "w") {
                $num = 23;
            }
            else if ($char == "x") {
                $num = 24;
            }
            else if ($char == "y") {
                $num = 25;
            }
            else if ($char == "z") {
                $num = 26;
            }
            return $num;
        }

        private static function num2char($num)
        {
            # Originaly named of method_68
            if($num == "0")
            {
                $_loc2_ = "a";
            }
            else if($num == "1")
            {
                $_loc2_ = "b";
            }
            else if($num == "2")
            {
                $_loc2_ = "c";
            }
            else if($num == "3")
            {
                $_loc2_ = "d";
            }
            else if($num == "4")
            {
                $_loc2_ = "e";
            }
            else if($num == "5")
            {
                $_loc2_ = "f";
            }
            else if($num == "6")
            {
                $_loc2_ = "g";
            }
            else if($num == "7")
            {
                $_loc2_ = "h";
            }
            else if($num == "8")
            {
                $_loc2_ = "i";
            }
            else if($num == "9")
            {
                $_loc2_ = "j";
            }
            else if($num == "10")
            {
                $_loc2_ = "k";
            }
            else if($num == "11")
            {
                $_loc2_ = "l";
            }
            else if($num == "12")
            {
                $_loc2_ = "m";
            }
            else if($num == "13")
            {
                $_loc2_ = "n";
            }
            else if($num == "14")
            {
                $_loc2_ = "o";
            }
            else if($num == "15")
            {
                $_loc2_ = "p";
            }
            else if($num == "16")
            {
                $_loc2_ = "q";
            }
            else if($num == "17")
            {
                $_loc2_ = "r";
            }
            else if($num == "18")
            {
                $_loc2_ = "s";
            }
            else if($num == "19")
            {
                $_loc2_ = "t";
            }
            else if($num == "20")
            {
                $_loc2_ = "u";
            }
            else if($num == "21")
            {
                $_loc2_ = "v";
            }
            else if($num == "22")
            {
                $_loc2_ = "w";
            }
            else if($num == "23")
            {
                $_loc2_ = "x";
            }
            else if($num == "24")
            {
                $_loc2_ = "y";
            }
            else if($num == "25")
            {
                $_loc2_ = "z";
            }
            return $_loc2_;
        }
        private static function method_118()
        {
            $_loc1 = 0;
            $_loc2 = null;
            $_loc3 = "";
            $_loc4 = 0;
            while ($_loc4 <= 7)
            {
                $_loc1 = mt_rand(1, 26);
                $_loc2 = Keygen::method_68("$_loc1");
                $_loc3 += $_loc2;
                $_loc3 += (int) mt_rand(1, 10);
                $_loc4++;
            }
            return $_loc3;
        }

    }
?>
