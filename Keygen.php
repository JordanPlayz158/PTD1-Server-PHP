<?php

use JetBrains\PhpStorm\Pure;

#[Pure] function generateProfileId(int $currentSave, int $trainerId): string {
	$currentSaveInt = currentSaveToInt($currentSave);

	// CurrentSave must be 14 characters long, trainerId must be between 333 and 99999, and currentSaveInt must not be 0.
	if (strlen($currentSave) != 14 || $trainerId < 333 || $trainerId > 99999 || $currentSaveInt == 0) {
		//This used to call a function which outputted a bunch of garbage for the profileId,
		//but now it gives you a helpful, clear message to let you know that the currentSave or trainerId was invalid
		return 'invalidCurrentSaveOrTrainerId';
	}

	$profileId = '';
	$_loc5 = strval(($trainerId * $currentSaveInt) * 14);

	for($i = 0; $i < strlen($_loc5); $i++)
		$profileId .= numToChar((int)$_loc5[$i] + $_loc5[0]);

	return $profileId;
}

#[Pure] function currentSaveToInt(int $currentSave): int {
	$num = 0;
	$currentSaveString = "$currentSave";

	for ($i = 0; $i < strlen($currentSaveString); $i++)
		$num += charToInt($currentSaveString[$i]);

	return $num;
}

/**
 * This function converts a char to its ascii decimal value,
 * unless the char is 0 (returns 0 manually due to intval's invalid number return number being 0)
 * or if the char is a number.
 * ex. '0' = 0, '1' = 1, '2' = 2, 'a' = 1, 'b' = 2
 */
function charToInt(string $char) : int {
	return intval($char) || (ord($char) - 96) * (intval($char) === 0 && $char !== '0');
}

/**
 * Converts a number into a letter in the alphabet
 * ex. 0 = a
 *     1 = b
 *     2 = c, etc.
 */
function numToChar(int $num) : string {
	return chr($num + 97);
}