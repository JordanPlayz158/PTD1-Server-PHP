<?php
class Keygen {
    public static function generateProfileId(int $currentSave, int $trainerId) {
		$currentSaveInt = Keygen::currentSaveToInt($currentSave);

		// CurrentSave must be 14 characters long, trainerId must be between 333 and 99999, and currentSaveInt must not be 0.
        if (strlen($currentSave) != 14 || $trainerId < 333 || $trainerId > 99999 || $currentSaveInt == 0) {
			/**
	 		 * This used to call a function which outputed a bunch of garbage for the profileId
	 		 * but now it gives you a helpful, clear message to let you know that the currentSave or trainerId was invalid
	 		 */
            return 'invalidCurrentSaveOrTrainerId';
        }

		$_loc5 = strval(((int) $trainerId * $currentSaveInt) * 14);
		$_loc6 = $_loc5[0];
        $profileId = '';
		$_loc8 = 0;

		for($i = 0; $i < strlen($_loc5); $i++) {
			$_loc8 = (int) $_loc5[$i] + $_loc6;
            $_loc9 = Keygen::numToChar("$_loc8");
            $profileId .= $_loc9;
		}
		echo $profileId;
        return $profileId;
    }

    private static function currentSaveToInt(int $currentSave) {
		$num = 0;
		$currentSaveString = "$currentSave";
		for($i = 0; $i < strlen($currentSaveString); $i++) {
			$num += Keygen::charToInt($currentSaveString[$i]);
		}
        return $num;
    }

	/**
	 * This function converts a char to it's ascii decimal value,
	 * unless the char is 0 (returns 0 manually due to intval's invalid number return number being 0)
	 * or if the char is a number.
	 * ex. '0' = 0, '1' = 1, '2' = 2, 'a' = 1, 'b' = 2
	 */
    private static function charToInt(string $char) : int {
		if($char === '0') {
			return 0;
		}

		$num = intval($char);

		if($num === 0) {
			$num = ord($char) - 96;
		}

		return $num;
    }

	/**
	 * Converts a number into a letter in the alphabet
	 * ex. 0 = a, 1 = b, 2 = c, etc.
	 */
    private static function numToChar(int $num) : string {
        return chr($num + 97);
	}
}
?>