<?php

include('sbox.php');

$nb = 4; 

function SubBytes($byte) {
	global $sbox;
	return isset($sbox[$byte]) ? $sbox[$byte] : $byte;	
}

function SubWords($word) {
	$retval = '';
	for ($i = 0, $length = strlen($word); $i < $length; $i += 2) {
		$retval .= SubBytes(substr($word, $i, 2));
	}
	return $retval;
}

function StrXor($hex1, $hex2) {
	return bin2hex(pack('H*', $hex1) ^ pack('H*',$hex2));
}

function RotWord($word) {
	return substr($word, 2, 6) . substr($word, 0, 2);
}

function Rcon($index) {
	global $rcon;
	return $rcon[$index] . '000000';
}

function KeyExpansion($key) {
	global $nb;

	$nk = (4*strlen($key))/32;
	$nr = 10 + $nk - $nb;

	$temp = '';
	$i = 0;

	echo "nk = {$nk}, nr = {$nr}\n";

	for (;$i < $nk; $i++) {
		$w[$i] = substr($key, 8 * $i, 8);
		echo 'w' . $i . ' = ' . $w[$i] . ', ';
	}
	echo "\n";

	for ($i = $nk; $i < ($nb * ($nr + 1)); $i++) {
		$temp = $w[$i - 1];
		if (($i % $nk) == 0) {
			$temp = StrXor(SubWords(RotWord($temp)), Rcon($i / $nk));
		} else if (($nk > 6) && (($i % $nk) == 4)) {
			$temp = SubWords($temp);
		}
		$w[$i] = StrXor($w[$i - $nk], $temp);
	}
	return $w;
}
