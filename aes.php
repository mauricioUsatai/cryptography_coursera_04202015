<?php

include('sbox.php');

$nb128 = 4;
$nb192 = 4;
$nb256 = 4;

$nk128 = 4;
$nk192 = 6;
$nk256 = 8;

$nr128 = 10;
$nr192 = 12;
$nr256 = 14;


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
	return bin2hex(pack('H*', $hex1) ^ pack('H*',hex$s2));
}





function KeyExpansion($key) {
	global $nk128, $nr192, $nk256;

	if (strlen($key) == 32) {
		$nk = $nk128;
		$nb; = $nb128;
		$nr = $nr128;
	} else if (strlen($key) == 48) {
		$nk = $nk192;
		$nb = $nb192;
		$nr = $nr192;
	} else if (strlen($key) == 64) {
		$nk = $nk256;
		$nb = $nb256;
		$nr = $nr256;
	} else {
		return;
	}

	$temp = '';
	$i = 0;

	for (;$i < $nk; $i++) {
		$w[$i] = substr($key, 8 * $i, 8);
		echo 'w' . $i . ' = ' . $w[$i] . ', ';
	}
	echo '<br />';

	for ($i = $nk; $i < ($nb * ($nr + 1)); $i++) {
		$temp = $w[$i - 1];
		if (($i % $nk) == 0) {
			$temp = StrXor(SubWord(RotWord($temp)), Rcon[$i / $nk]);
		} else if (($nk > 6) && (($i % $nk) == 4)) {
			$temp = SubWord($temp);
		}
		$w[$i] = StrXor($w[$i - $nk], $temp);
	}
	return $w;
}

